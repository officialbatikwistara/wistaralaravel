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
        try {
            Log::info('📥 [GROQ] WhatsApp Message Received', $request->all());

            $from = $request->input('from') ?? $request->input('sender') ?? 'unknown';
            $message = $request->input('message') ?? $request->input('text') ?? '';
            $name = $request->input('name') ?? $request->input('pushname') ?? 'Customer';

            if (empty($message)) {
                return response()->json(['status' => 'ignored']);
            }

            $message = trim($message);
            $history = Cache::get("wa_groq_{$from}", []);

            // Check if menu command
            $isMenu = in_array(strtolower($message), ['0', '1', '2', '3', '4', 'menu']);

            if ($isMenu) {
                Log::info('🔹 [MENU] Handling menu: ' . $message);
                $response = $this->getMenuResponse($message);
            } else {
                Log::info('🤖 [GROQ AI] Processing with AI');
                $response = $this->getAIResponse($message, $name, $history);

                // Save to history
                $history[] = ['role' => 'user', 'content' => $message];
                $history[] = ['role' => 'assistant', 'content' => $response];

                if (count($history) > 10) {
                    $history = array_slice($history, -10);
                }

                Cache::put("wa_groq_{$from}", $history, now()->addHours(24));
            }

            Log::info('📤 [SEND] Response: ' . substr($response, 0, 100));

            $this->sendWhatsApp($from, $response);

            return response()->json(['status' => 'success', 'ai_powered' => !$isMenu]);

        } catch (\Exception $e) {
            Log::error('❌ [ERROR] ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    protected function getMenuResponse($command)
    {
        $menus = [
            '0' => "✨ *Menu Utama Batik Wistara*\n\n1️⃣ Katalog Produk\n2️⃣ Berita Terbaru\n3️⃣ Alamat & Jam Buka\n4️⃣ Hubungi Admin\n\n💡 Atau tanya apa saja!\nContoh: 'Ada batik tulis?'",
            '1' => "🛍️ *Katalog Produk*\n\nKoleksi kami:\n• Batik Tulis Premium\n• Batik Cap\n• Kemeja & Dress Batik\n\nLihat: " . url('/produk') . "\n\n💬 Tanya AI: 'Ada batik untuk acara formal?'\n\nKetik 0 = Menu",
            '2' => "📰 *Berita Terbaru*\n\nCek update: " . url('/berita') . "\n\nKetik 0 = Menu",
            '3' => "📍 *Alamat & Jam Buka*\n\n🏪 Batik Wistara\n📍 [Alamat Toko]\n⏰ Sen-Sab: 09:00-17:00\n📞 [Telepon]\n\nKetik 0 = Menu",
            '4' => "💬 *Hubungi Admin*\n\nSilakan hubungi admin untuk bantuan.\n\nKetik 0 = Menu",
            'menu' => "✨ *Menu Utama*\n\n0️⃣ Menu Utama\n1️⃣ Katalog\n2️⃣ Berita\n3️⃣ Alamat\n4️⃣ Admin\n\n💡 Atau tanya apa saja ke AI!",
        ];

        return $menus[strtolower($command)] ?? $menus['0'];
    }

    protected function getAIResponse($message, $name, $history)
    {
        $systemPrompt = "Anda adalah customer service Batik Wistara yang ramah dan profesional.\n\nNama customer: {$name}\n\nINFORMASI:\n- Produk: Batik tulis, batik cap, kemeja batik, dress batik\n- Harga: Rp 150k - 2jt\n- Jam: Senin-Sabtu 09:00-17:00\n- Website: " . url('/') . "\n\nCara menjawab:\n1. Gunakan bahasa Indonesia ramah dengan emoji 😊\n2. Berikan info jelas tapi ringkas\n3. Jika ditanya harga, beri range umum\n4. Akhiri dengan tawaran bantuan\n\nPANTANGAN:\n❌ Jangan buat-buat harga pasti\n❌ Jangan terlalu panjang (max 150 kata)";

        $result = $this->groq->chatWithSystemPrompt($message, $systemPrompt, $history);

        if ($result['success']) {
            Log::info('✅ [AI] Success - Tokens: ' . ($result['usage']['total_tokens'] ?? 0));
            return $result['response'] . "\n\n─────────\n💡 Ketik *0* untuk menu";
        }

        Log::error('❌ [AI] Failed: ' . $result['error']);
        return "Maaf {$name}, AI sedang sibuk 😅\nSilakan ketik *4* untuk hubungi admin.\n\nTerima kasih! 🙏";
    }

    protected function sendWhatsApp($to, $message)
    {
        $provider = config('services.whatsapp.provider');
        $apiUrl = config('services.whatsapp.api_url');
        $token = config('services.whatsapp.api_token');

        if (empty($token)) {
            Log::error('WhatsApp token not configured');
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
            Log::error('📱 Send failed: ' . $e->getMessage());
            return false;
        }
    }
}
