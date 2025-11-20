<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Produk;

class CheckoutController extends Controller
{
    /**
     * 🧾 Menampilkan halaman checkout
     */
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

    /**
     * 💾 Proses checkout & simpan pesanan
     */
    public function process(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'tanggal_ambil' => 'required|date',
            'metode_pembayaran' => 'required|string',
            'tipe_order' => 'required|string',
            'coupon_id' => 'nullable|exists:coupons,id',
            'discount_amount' => 'nullable|numeric|min:0',
            'final_total' => 'nullable|numeric|min:0'
        ]);

        $userId = Auth::id();

        // 🧠 Alamat otomatis untuk "ambil" di toko
        $alamatFinal = $request->tipe_order === 'ambil'
            ? 'Ambil di toko Batik Wistara - Jl. Tambak Medokan Ayu VI C No.56B, Medokan Ayu, Rungkut, Jawa Timur'
            : ($request->alamat ?? 'Alamat tidak tersedia');

        // ⚡ Checkout langsung satu produk
        if ($request->filled('id_produk')) {
            $produk = Produk::findOrFail($request->id_produk);

            $order = Order::create([
                'user_id' => $userId,
                'coupon_id' => $request->coupon_id,
                'nama' => $request->nama,
                'telepon' => $request->telepon,
                'total' => $produk->harga,
                'discount_amount' => $request->discount_amount ?? 0,
                'final_total' => $request->final_total ?? $produk->harga,
                'status' => 'pending',
                'status_pembayaran' => 'belum_bayar',
                'metode_pembayaran' => $request->metode_pembayaran,
                'tanggal_ambil' => $request->tanggal_ambil,
                'tipe_order' => $request->tipe_order,
                'alamat' => $alamatFinal,
                'catatan' => $request->catatan,
            ]);

            // Notify admin about new order
            $admin = \App\Models\Admin::first();
            $user = Auth::user();
            if ($admin && $user) {
                $admin->notify(new \App\Notifications\NewOrderNotification($order, $user));

                // Send WhatsApp notification
                $this->sendWhatsapp($admin->phone ?? env('ADMIN_PHONE'), "🆕 PESANAN BARU!\n\nID: #{$order->id}\nCustomer: {$user->name}\nTotal: Rp " . number_format($order->total, 0, ',', '.') . "\n\nSegera cek dashboard untuk detail lengkap.");
            }

            OrderItem::create([
                'order_id' => $order->id,
                'id_produk' => $produk->id_produk,
                'qty' => 1,
                'harga' => $produk->harga,
                'subtotal' => $produk->harga,
            ]);

            $produk->decrement('stok', 1);

            // Increment coupon usage if coupon was used
            if ($request->coupon_id) {
                \App\Models\Coupon::where('id', $request->coupon_id)->increment('used_count');
            }
        } else {
            // 🛒 Checkout dari keranjang
            $cartItems = Cart::where('user_id', $userId)->with('produk')->get();

            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Tidak ada item untuk diproses.');
            }

            $total = $cartItems->sum(fn($item) => $item->qty * $item->produk->harga);

            $order = Order::create([
                'user_id' => $userId,
                'coupon_id' => $request->coupon_id ?: null,
                'nama' => $request->nama,
                'telepon' => $request->telepon,
                'total' => $total,
                'discount_amount' => $request->discount_amount ?? 0,
                'final_total' => $request->final_total ?? $total,
                'status' => 'pending',
                'status_pembayaran' => 'belum_bayar',
                'metode_pembayaran' => $request->metode_pembayaran,
                'tanggal_ambil' => $request->tanggal_ambil,
                'tipe_order' => $request->tipe_order,
                'alamat' => $alamatFinal,
                'catatan' => $request->catatan,
            ]);

            // Notify admin about new order
            $admin = \App\Models\Admin::first();
            $user = Auth::user();
            if ($admin && $user) {
                $admin->notify(new \App\Notifications\NewOrderNotification($order, $user));

                // Send WhatsApp notification
                $this->sendWhatsapp($admin->phone ?? env('ADMIN_PHONE'), "🆕 PESANAN BARU!\n\nID: #{$order->id}\nCustomer: {$user->name}\nTotal: Rp " . number_format($order->total, 0, ',', '.') . "\n\nSegera cek dashboard untuk detail lengkap.");
            }

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'id_produk' => $item->id_produk,
                    'qty' => $item->qty,
                    'harga' => $item->produk->harga,
                    'subtotal' => $item->qty * $item->produk->harga,
                ]);

                $item->produk->decrement('stok', $item->qty);
            }

            Cart::where('user_id', $userId)->delete();

            // Increment coupon usage if coupon was used
            if ($request->coupon_id) {
                \App\Models\Coupon::where('id', $request->coupon_id)->increment('used_count');
            }
        }

        // 🔁 Redirect sesuai metode pembayaran
        if ($request->metode_pembayaran === 'bank_transfer') {
            return redirect()->route('checkout.bank', $order->id);
        } elseif ($request->metode_pembayaran === 'qris') {
            return redirect()->route('checkout.qris', $order->id);
        } else {
            return redirect('/user/dashboard')->with('success', 'Pesanan berhasil dibuat!');
        }
    }

    /**
     * 📲 Helper kirim pesan WhatsApp via Fonnte API
     */
    protected function sendWhatsapp($phone, $message)
    {
        if (!$phone) return;

        $token = env('FONNTE_TOKEN');

        if (!$token) {
            Log::warning('FONNTE_TOKEN not configured');
            return;
        }

        try {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.fonnte.com/send",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_POSTFIELDS => [
                    'target' => $phone,
                    'message' => $message,
                ],
                CURLOPT_HTTPHEADER => [
                    "Authorization: $token"
                ],
            ]);

            $response = curl_exec($curl);

            if (curl_errno($curl)) {
                Log::error('WhatsApp send error: ' . curl_error($curl));
            }

            curl_close($curl);
        } catch (\Exception $e) {
            Log::error('WhatsApp exception: ' . $e->getMessage());
        }
    }
}
