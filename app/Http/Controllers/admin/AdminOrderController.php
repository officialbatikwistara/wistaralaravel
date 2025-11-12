<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderStatusNotification;
use Illuminate\Support\Facades\Log;

class AdminOrderController extends Controller
{
    /**
     * 📝 Tampilkan semua pesanan
     */
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->filled('start')) {
            $query->whereDate('created_at', '>=', $request->start);
        }

        if ($request->filled('end')) {
            $query->whereDate('created_at', '<=', $request->end);
        }

        if ($request->filled('keyword')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', "%{$request->keyword}%")
                  ->orWhere('id', $request->keyword);
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->get();
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
        $statusPembayaranChanged = false;

        // Simpan status pembayaran
        if ($request->filled('status_pembayaran')) {
            $oldStatus = $order->status_pembayaran;
            $order->status_pembayaran = $request->status_pembayaran;
            $statusPembayaranChanged = ($oldStatus !== $request->status_pembayaran);
        }

        // Simpan status pesanan
        if ($request->filled('status')) {
            $order->status = $request->status;
        }

        $order->save();

        // 📩 Kirim notifikasi ke user HANYA jika status pembayaran berubah
        if ($statusPembayaranChanged) {
            $user = User::find($order->user_id);

            if ($user) {
                try {
                    if ($order->status_pembayaran === 'lunas') {
                        $message = "✅ Pembayaran untuk pesanan #{$order->id} telah dikonfirmasi. Terima kasih 🙏";

                        $user->notify(new OrderStatusNotification($order, $message));
                        $this->sendWhatsapp($user->phone, $message);

                    } elseif ($order->status_pembayaran === 'gagal') {
                        $message = "❌ Pembayaran untuk pesanan #{$order->id} gagal diverifikasi. Silakan hubungi admin.";

                        $user->notify(new OrderStatusNotification($order, $message));
                        $this->sendWhatsapp($user->phone, $message);
                    }
                } catch (\Exception $e) {
                    Log::error('Error sending notification: ' . $e->getMessage());
                    // Tetap lanjut meskipun notifikasi gagal
                }
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
        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();

        // Kirim notifikasi HANYA jika status berubah
        if ($oldStatus !== $request->status) {
            $user = User::find($order->user_id);

            if ($user) {
                try {
                    $messages = [
                        'proses' => "🛍️ Pesanan #{$order->id} sedang diproses ✅",
                        'selesai' => "🎉 Pesanan #{$order->id} telah selesai dan siap diambil 🧾",
                        'batal' => "❌ Pesanan #{$order->id} telah dibatalkan oleh admin."
                    ];

                    if (isset($messages[$request->status])) {
                        $message = $messages[$request->status];
                        $user->notify(new OrderStatusNotification($order, $message));
                        $this->sendWhatsapp($user->phone, $message);
                    }
                } catch (\Exception $e) {
                    Log::error('Error sending notification: ' . $e->getMessage());
                }
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
