<?php

namespace App\Http\Controllers;

use App\Services\GroqService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WhatsAppWebhookController extends Controller
{
    protected $groq;

    public function __construct(GroqService $groq)
    {
        $this->groq = $groq;
    }

    public function webhook(Request $request)
    {
        Log::info('📥 WEBHOOK RECEIVED', $request->all());

        try {
            $from = $request->input('from') ?? $request->input('sender') ?? 'unknown';
            $message = $request->input('message') ?? $request->input('text') ?? '';
            $name = $request->input('name') ?? $request->input('pushname') ?? 'Customer';

            if (empty($message)) {
                return response()->json(['status' => 'no_message']);
            }

            $message = trim($message);

            // Cek menu command
            if (in_array(strtolower($message), ['0', '1', '2', '3', '4'])) {
                Log::info('🔹 MENU command: ' . $message);
                $response = $this->handleMenu($message);
            } else {
                Log::info('🤖 AI Processing...');
                $response = $this->handleAI($message, $name, $from);
            }

            Log::info('📤 Sending response');
            $this->sendWhatsApp($from, $response);

            return response()->json(['status' => 'success', 'ai_used' => !in_array($message, ['0','1','2','3','4'])]);

        } catch (\Exception $e) {
            Log::error('❌ ERROR: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    protected function handleMenu($cmd)
    {
        $menus = [
            '0' => "✨ *Menu Utama Batik Wistara*\n\n1️⃣ Katalog Produk\n2️⃣ Berita\n3️⃣ Alamat & Jam Buka\n4️⃣ Hubungi Admin\n\n💡 Atau tanya apa saja ke AI!",
            '1' => "🛍️ *Katalog Produk*\n\nKoleksi kami:\n• Batik Tulis Premium\n• Batik Cap\n• Batik Modern\n\nLihat: " . url('/produk') . "\n\n💬 Tanya AI: 'Ada batik untuk acara formal?'",
            '2' => "📰 *Berita Terbaru*\n\nCek: " . url('/berita'),
            '3' => "📍 *Alamat & Jam Buka*\n\n🏪 Batik Wistara\n📍 [Alamat Toko]\n⏰ Sen-Sab: 09:00-17:00",
            '4' => "💬 *Hubungi Admin*\n\nSilakan hubungi admin.",
        ];
        return $menus[$cmd] ?? $menus['0'];
    }

    protected function handleAI($message, $name, $from)
    {
        $history = Cache::get("wa_ai_{$from}", []);

        $systemPrompt = "Anda adalah customer service Batik Wistara yang ramah.\n\nNama: {$name}\n\nINFO:\n- Produk: Batik tulis, batik cap, kemeja, dress\n- Harga: Rp 150k - 2jt\n- Jam: Sen-Sab 09:00-17:00\n\nJawab dengan ramah, pakai emoji 😊, max 100 kata.\n\nJangan buat-buat harga pasti!";

        $result = $this->groq->chatWithSystemPrompt($message, $systemPrompt, $history);

        if ($result['success']) {
            Log::info('✅ AI SUCCESS - Tokens: ' . ($result['usage']['total_tokens'] ?? 0));

            $history[] = ['role' => 'user', 'content' => $message];
            $history[] = ['role' => 'assistant', 'content' => $result['response']];

            if (count($history) > 8) {
                $history = array_slice($history, -8);
            }

            Cache::put("wa_ai_{$from}", $history, now()->addHours(2));

            return $result['response'] . "\n\n─────\n💡 Ketik *0* untuk menu";
        }

        Log::error('❌ AI FAILED: ' . ($result['error'] ?? 'unknown'));
        return "Maaf {$name}, AI sedang sibuk 😅\nKetik *4* untuk hubungi admin.";
    }

    protected function sendWhatsApp($to, $message)
    {
        $provider = config('services.whatsapp.provider');
        $apiUrl = config('services.whatsapp.api_url');
        $token = config('services.whatsapp.api_token');

        if (empty($token)) {
            Log::error('WhatsApp token missing!');
            return false;
        }

        try {
            if ($provider === 'fonnte') {
                $response = Http::withHeaders(['Authorization' => $token])
                    ->post($apiUrl . '/send', [
                        'target' => $to,
                        'message' => $message,
                    ]);
            } else {
                $response = Http::withHeaders(['Authorization' => $token])
                    ->post($apiUrl . '/api/send-message', [
                        'phone' => $to,
                        'message' => $message,
                    ]);
            }

            Log::info('📱 WhatsApp sent: ' . $response->status());
            return $response->successful();

        } catch (\Exception $e) {
            Log::error('📱 Send error: ' . $e->getMessage());
            return false;
        }
    }
}
