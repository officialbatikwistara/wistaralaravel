<?php

namespace App\Http\Controllers;

use App\Http\Controllers\admin\NotifikasiAdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Produk;

class CheckoutController extends Controller
{
    public function index($id_produk = null)
    {
        if ($id_produk) {
            $produk = Produk::findOrFail($id_produk);
            $cartItems = collect([
                (object)[
                    'id_produk' => $produk->id_produk,
                    'qty' => 1,
                    'produk' => $produk
                ]
            ]);
        } else {
            $cartItems = Cart::where('user_id', Auth::id())
                ->with('produk.kategori')
                ->get();
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        return view('checkout.index', compact('cartItems'));
    }

    public function process(Request $request)
    {
        $this->validateCheckout($request);

        $user = Auth::user();
        $userId = $user->id;

        // Ambil atau kirim?
        $alamatFinal = $request->tipe_order === 'ambil'
            ? 'Ambil di toko Batik Wistara - Jl. Tambak Medokan Ayu VI C No.56B, Medokan Ayu, Rungkut, Jawa Timur'
            : ($request->alamat ?? 'Alamat tidak tersedia');

        // Hitung total
        if ($request->filled('id_produk')) {
            $produk = Produk::findOrFail($request->id_produk);
            $total = $produk->harga;
            $items = [[
                'id_produk' => $produk->id_produk,
                'qty' => 1,
                'harga' => $produk->harga
            ]];
        } else {
            $cartItems = Cart::where('user_id', $userId)->with('produk')->get();
            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Tidak ada item untuk diproses.');
            }

            $total = $cartItems->sum(fn($item) => $item->qty * $item->produk->harga);
            $items = $cartItems->map(function ($item) {
                return [
                    'id_produk' => $item->id_produk,
                    'qty' => $item->qty,
                    'harga' => $item->produk->harga
                ];
            })->toArray();
        }

        // Buat order
        $order = Order::create(
            $this->prepareOrderData($request, $userId, $total, $alamatFinal)
        );

        // Simpan detail item
        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'id_produk' => $item['id_produk'],
                'qty' => $item['qty'],
                'harga' => $item['harga'],
                'subtotal' => $item['qty'] * $item['harga'],
            ]);

            Produk::where('id_produk', $item['id_produk'])
                ->decrement('stok', $item['qty']);
        }

        // Hapus keranjang jika checkout dari cart
        if (!$request->filled('id_produk')) {
            Cart::where('user_id', $userId)->delete();
        }

        // Kupon
        if ($request->coupon_id) {
            \App\Models\Coupon::where('id', $request->coupon_id)->increment('used_count');
        }

        // Kirim notifikasi admin ✨
        $this->kirimnotifikasi($order, $user);

        // Redirect pembayaran
        return $this->redirectPembayaran($request, $order);
    }

    private function validateCheckout(Request $request)
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'metode_pembayaran' => 'required|string',
            'tipe_order' => 'required|string|in:ambil,dikirim',
        ];

        if ($request->tipe_order === 'ambil') {
            $rules['tanggal_ambil'] = 'required|date';
        }

        if ($request->tipe_order === 'dikirim') {
            $rules['alamat'] = 'required|string';
        }

        $request->validate($rules);
    }

    protected function kirimnotifikasi($order, $user)
    {
        $admin = \App\Models\Admin::first();
        if (!$admin) return;

        $message =
            "🆕 PESANAN BARU!\n" .
            "ID: #{$order->id}\n" .
            "Customer: {$user->name}\n" .
            "Total: Rp " . number_format($order->final_total ?? $order->total, 0, ',', '.') . "\n\n" .
            "Segera cek dashboard untuk detail lengkap.";

        // Email / Laravel notification
        $admin->notify(new \App\Notifications\NewOrderNotification($order, $user));

        // Whatsapp
        (new NotifikasiAdminController())->sendWhatsapp(
            $admin->phone ?? env('ADMIN_PHONE'),
            $message
        );

        // TELEGRAM
        (new NotifikasiAdminController())->sendTelegram($message);

    }

    private function redirectPembayaran(Request $request, $order)
    {
        return match ($request->metode_pembayaran) {
            'bank_transfer' => redirect()->route('checkout.bank', $order->id),
            'qris' => redirect()->route('checkout.qris', $order->id),
            default => redirect('/user/dashboard')->with('success', 'Pesanan berhasil dibuat!'),
        };
    }

    protected function prepareOrderData(Request $request, $userId, $baseTotal, $alamatFinal): array
    {
        $data = [
            'user_id' => $userId,
            'nama' => $request->nama,
            'telepon' => $request->telepon,
            'total' => $baseTotal,
            'status' => 'pending',
            'status_pembayaran' => 'belum_bayar',
            'metode_pembayaran' => $request->metode_pembayaran,
            'tanggal_ambil' => $request->tanggal_ambil,
            'tipe_order' => $request->tipe_order,
            'alamat' => $alamatFinal,
            'catatan' => $request->catatan,
        ];

        // Kolom opsional
        foreach (['coupon_id', 'discount_amount', 'final_total', 'bukti_pembayaran'] as $col) {
            if (Schema::hasColumn('orders', $col)) {
                $data[$col] = $request->$col ?? ($col === 'coupon_id' ? null : 0);
            }
        }

        return $data;
    }
}
