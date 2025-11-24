<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class WhatsAppWebhookController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Handle incoming WhatsApp messages (webhook dari Fonnte/Wablas)
     * URL: POST /api/whatsapp/webhook
     */
    public function handleIncoming(Request $request)
    {
        Log::info('📥 WhatsApp webhook received', $request->all());

        $message = $request->input('message') ?? $request->input('text') ?? $request->input('body');
        $sender = $request->input('sender') ?? $request->input('phone') ?? $request->input('from');

        if (!$sender || !$message) {
            Log::warning('❌ Invalid webhook data', $request->all());
            return response()->json([
                'status' => 'invalid',
                'error' => 'Missing sender or message'
            ], 400);
        }

        $reply = $this->processMessage($message, $sender);

        if ($reply) {
            if (app()->environment('testing') || !config('services.whatsapp.api_token')) {
                Log::info('🧪 TEST MODE - Would send reply', [
                    'to' => $sender,
                    'message' => substr($reply, 0, 100) . '...'
                ]);
            } else {
                try {
                    $result = $this->whatsappService->sendMessage($sender, $reply);

                    if ($result['success']) {
                        Log::info('✅ Auto-reply sent successfully', [
                            'to' => $sender,
                            'preview' => substr($reply, 0, 50) . '...'
                        ]);
                    } else {
                        Log::error('❌ Failed to send auto-reply', [
                            'to' => $sender,
                            'error' => $result['error'] ?? 'Unknown error'
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('❌ Exception sending auto-reply', [
                        'to' => $sender,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 'ok',
            'received' => true,
            'reply_sent' => !empty($reply),
            'timestamp' => now()->toISOString()
        ], 200);
    }

    /**
     * Process incoming message and generate auto-reply
     */
    protected function processMessage($message, $sender)
    {
        $msg = strtolower(trim($message));

        // Greeting
        if (str_contains($msg, 'halo') || str_contains($msg, 'hai') || str_contains($msg, 'hello')) {
            return "Halo! 👋\n\nSelamat datang di *Wistara*.\nAda yang bisa kami bantu?\n\nKetik *HELP* untuk melihat menu.";
        }

        // Order/Pemesanan
        if (str_contains($msg, 'order') || str_contains($msg, 'pesan') || str_contains($msg, 'beli')) {
            return "📦 *PEMESANAN*\n\n" .
                   "Untuk melakukan pemesanan:\n" .
                   "1. Kunjungi website kami\n" .
                   "2. Pilih produk yang diinginkan\n" .
                   "3. Checkout dan bayar\n\n" .
                   "Atau kirim nama produk yang Anda inginkan.";
        }

        // Status pesanan - format: STATUS 12345
        if (str_contains($msg, 'status')) {
            // Extract order ID from message
            preg_match('/status\s*(\d+)/i', $msg, $matches);

            if (isset($matches[1])) {
                $orderId = $matches[1];
                $order = Order::find($orderId);

                if ($order) {
                    return "📋 *STATUS PESANAN #$orderId*\n\n" .
                           "Nama: {$order->nama}\n" .
                           "Total: Rp " . number_format($order->total, 0, ',', '.') . "\n" .
                           "Status: {$order->status}\n" .
                           "Pembayaran: {$order->status_pembayaran}\n" .
                           "Tanggal: " . $order->created_at->format('d/m/Y H:i');
                } else {
                    return "❌ Pesanan dengan ID *$orderId* tidak ditemukan.\n\n" .
                           "Pastikan nomor pesanan Anda benar.";
                }
            }

            return "ℹ️ Untuk cek status pesanan, kirim:\n" .
                   "*STATUS [NOMOR_ORDER]*\n\n" .
                   "Contoh: STATUS 12345";
        }

        // Produk
        if (str_contains($msg, 'produk') || str_contains($msg, 'katalog')) {
            return "🛍️ *KATALOG PRODUK*\n\n" .
                   "Lihat produk kami di:\n" .
                   "https://wistara.com/produk\n\n" .
                   "Atau hubungi CS untuk rekomendasi produk.";
        }

        // Customer Service
        if (str_contains($msg, 'cs') || str_contains($msg, 'customer service') || str_contains($msg, 'admin')) {
            return "👤 *CUSTOMER SERVICE*\n\n" .
                   "Hubungi CS kami:\n" .
                   "📞 WhatsApp: 0812-3456-7890\n" .
                   "📧 Email: cs@wistara.com\n\n" .
                   "Jam operasional:\n" .
                   "Senin - Jumat: 08:00 - 17:00\n" .
                   "Sabtu: 08:00 - 12:00";
        }

        // Help menu
        if (str_contains($msg, 'help') || str_contains($msg, 'bantuan') || str_contains($msg, 'menu')) {
            return "📋 *MENU BANTUAN*\n\n" .
                   "Ketik keyword berikut:\n\n" .
                   "🛒 *ORDER* - Informasi pemesanan\n" .
                   "📦 *STATUS* - Cek status pesanan\n" .
                   "🛍️ *PRODUK* - Lihat katalog\n" .
                   "👤 *CS* - Hubungi customer service\n" .
                   "❓ *HELP* - Menu bantuan ini\n\n" .
                   "Atau langsung kirim pertanyaan Anda.";
        }

        // Terima kasih
        if (str_contains($msg, 'terima kasih') || str_contains($msg, 'thanks') || str_contains($msg, 'makasih')) {
            return "Sama-sama! 😊\n\nSenang bisa membantu Anda.\nJangan ragu untuk bertanya lagi ya!";
        }

        // Default reply - forward to admin
        Log::info('Unhandled message - forwarding to admin', [
            'from' => $sender,
            'message' => $message
        ]);

        return "Terima kasih atas pesan Anda! 🙏\n\n" .
               "Tim kami akan segera merespons.\n" .
               "Untuk respon lebih cepat, ketik *HELP* untuk melihat menu.";
    }

    /**
     * Handle status updates from WhatsApp provider
     * URL: POST /api/whatsapp/status
     */
    public function handleStatus(Request $request)
    {
        Log::info('📊 WhatsApp status update', $request->all());

        $messageSid = $request->input('MessageSid') ?? $request->input('message_id');
        $status = $request->input('MessageStatus') ?? $request->input('status');

        // Log delivery status
        if ($messageSid && $status) {
            Log::info('📬 Message delivery status', [
                'message_id' => $messageSid,
                'status' => $status,
                'timestamp' => now()->toISOString()
            ]);
        }

        return response()->json([
            'status' => 'ok',
            'received' => true
        ], 200);
    }
}
