<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\OrderStatusNotification;
use App\Models\User;

class CancelStaleOrders extends Command
{
    protected $signature = 'orders:cancel-stale {--dry-run : Tampilkan ringkasan tanpa mengubah data}';
    protected $description = 'Batalkan pesanan yang lewat 24 jam tanpa pembayaran, kembalikan stok, dan kirim notifikasi.';

    public function handle()
    {
        $cutoff = Carbon::now()->subHours(24);

        $query = Order::with(['items.produk'])
            ->where('status', 'pending')
            ->whereIn('status_pembayaran', ['belum_bayar', 'menunggu_verifikasi'])
            ->whereIn('metode_pembayaran', ['bank_transfer', 'qris'])
            ->where('created_at', '<=', $cutoff);

        $total = (clone $query)->count();

        if ($this->option('dry-run')) {
            $this->info("DRY RUN: {$total} pesanan akan dibatalkan.");
            return Command::SUCCESS;
        }

        $batalkan = 0;

        $query->chunkById(100, function ($orders) use (&$batalkan) {
            foreach ($orders as $order) {
                DB::beginTransaction();
                try {
                    // 🛍️ Kembalikan stok
                    foreach ($order->items as $item) {
                        if ($item->produk) {
                            $item->produk->increment('stok', $item->qty);
                        }
                    }

                    // 📝 Update status pesanan
                    $order->status = 'batal';
                    $order->status_pembayaran = 'gagal';
                    $order->save();

                    // 📩 Kirim notifikasi Email & WhatsApp
                    $user = User::find($order->user_id);
                    if ($user) {
                        $pesan = "Pesanan Anda #{$order->id} telah dibatalkan otomatis karena tidak ada pembayaran dalam 24 jam.";
                        $user->notify(new OrderStatusNotification($order, $pesan));
                        $this->sendWhatsapp($user->phone, $pesan);
                    }

                    DB::commit();
                    $batalkan++;
                } catch (\Throwable $e) {
                    DB::rollBack();
                    $this->error("Gagal batalkan #{$order->id}: " . $e->getMessage());
                }
            }
        });

        $this->info("✅ Selesai. {$batalkan} pesanan dibatalkan otomatis.");
        return Command::SUCCESS;
    }

    /**
     * 📲 Kirim notifikasi WhatsApp via Fonnte
     */
    protected function sendWhatsapp($phone, $message)
    {
        if (!$phone) return;

        $token = env('FONNTE_TOKEN'); // Token Fonnte kamu di .env
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
