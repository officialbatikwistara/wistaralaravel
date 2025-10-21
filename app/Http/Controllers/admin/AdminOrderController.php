<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderStatusNotification;

class AdminOrderController extends Controller
{
    /**
     * 📝 Tampilkan semua pesanan
     */
    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();
        return view('admin.pesanan.index', compact('orders'));
    }

    /**
     * 📄 Detail pesanan
     */
    public function show($id)
    {
        $order = Order::with(['items.produk'])->findOrFail($id);
        return view('admin.pesanan.show', compact('order'));
    }

    /**
     * 💳 Update status pembayaran + status pesanan
     */
    public function updatePayment(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => 'nullable|in:belum_bayar,menunggu_verifikasi,lunas,gagal',
            'status' => 'nullable|in:pending,proses,selesai,batal'
        ]);

        $order = Order::findOrFail($id);

        // Simpan status pembayaran
        if ($request->filled('status_pembayaran')) {
            $order->status_pembayaran = $request->status_pembayaran;
        }

        // Simpan status pesanan
        if ($request->filled('status')) {
            $order->status = $request->status;
        }

        $order->save();

        // 📩 Kirim notifikasi ke user
        $user = User::find($order->user_id);
        if ($user && $request->filled('status_pembayaran')) {
            if ($order->status_pembayaran === 'lunas') {
                $user->notify(new OrderStatusNotification(
                    $order,
                    "✅ Pembayaran untuk pesanan #{$order->id} telah dikonfirmasi. Terima kasih 🙏"
                ));
                $this->sendWhatsapp($user->phone, "✅ Pembayaran untuk pesanan #{$order->id} telah dikonfirmasi. Terima kasih 🙏");
            } elseif ($order->status_pembayaran === 'gagal') {
                $user->notify(new OrderStatusNotification(
                    $order,
                    "❌ Pembayaran untuk pesanan #{$order->id} gagal diverifikasi. Silakan hubungi admin."
                ));
                $this->sendWhatsapp($user->phone, "❌ Pembayaran untuk pesanan #{$order->id} gagal diverifikasi. Silakan hubungi admin.");
            }
        }

        return back()->with('success', '✅ Status pembayaran dan pesanan berhasil diperbarui.');
    }

    /**
     * 🔄 Update status pesanan (Pending / Proses / Selesai / Batal)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,proses,selesai,batal'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        $user = User::find($order->user_id);
        if ($user) {
            if ($request->status === 'proses') {
                $user->notify(new OrderStatusNotification(
                    $order,
                    "🛍️ Pesanan Anda sedang diproses ✅"
                ));
                $this->sendWhatsapp($user->phone, "🛍️ Pesanan #{$order->id} sedang diproses ✅");
            } elseif ($request->status === 'selesai') {
                $user->notify(new OrderStatusNotification(
                    $order,
                    "🎉 Pesanan Anda telah selesai dan siap diambil 🧾"
                ));
                $this->sendWhatsapp($user->phone, "🎉 Pesanan #{$order->id} telah selesai dan siap diambil 🧾");
            } elseif ($request->status === 'batal') {
                $user->notify(new OrderStatusNotification(
                    $order,
                    "❌ Pesanan Anda #{$order->id} telah dibatalkan oleh admin."
                ));
                $this->sendWhatsapp($user->phone, "❌ Pesanan #{$order->id} telah dibatalkan oleh admin.");
            }
        }

        return back()->with('success', '✅ Status pesanan berhasil diperbarui.');
    }

    /**
     * 📲 Helper kirim pesan WhatsApp via Fonnte API
     */
    protected function sendWhatsapp($phone, $message)
    {
        if (!$phone) return;

        $token = env('FONNTE_TOKEN');
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.fonnte.com/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'target' => $phone,
                'message' => $message,
            ],
            CURLOPT_HTTPHEADER => [
                "Authorization: $token"
            ],
        ]);
        curl_exec($curl);
        curl_close($curl);
    }
}
