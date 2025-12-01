<?php

namespace App\Http\Controllers;

use App\Services\GroqService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TelegramBotController extends Controller
{
    protected $groq;
    protected $botToken;

    public function __construct(GroqService $groq)
    {
        $this->groq = $groq;
        $this->botToken = config('services.telegram.bot_token');
    }

    public function webhook(Request $request)
    {
        Log::info('📱 TELEGRAM Webhook', $request->all());

        try {
            $update = $request->all();

            if (!isset($update['message'])) {
                return response()->json(['ok' => true]);
            }

            $message = $update['message'];
            $chatId = $message['chat']['id'];
            $text = $message['text'] ?? '';
            $name = $message['from']['first_name'] ?? 'User';

            if (empty($text)) {
                return response()->json(['ok' => true]);
            }

            Log::info('📥 Telegram message', ['chat_id' => $chatId, 'text' => $text]);

            // Check menu command
            if ($this->isCommand($text)) {
                $response = $this->handleCommand($text);
            } else {
                $response = $this->handleAI($text, $name, $chatId);
            }

            $this->sendMessage($chatId, $response);

            return response()->json(['ok' => true]);

        } catch (\Exception $e) {
            Log::error('❌ Telegram Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    protected function isCommand($text)
    {
        return in_array($text, ['/start', '/menu', '/help']) ||
               in_array(strtolower(trim($text)), ['0', '1', '2', '3', '4']);
    }

    protected function handleCommand($cmd)
    {
        $commands = [
            '/start' => "👋 *Selamat datang di Batik Wistara Bot!*\n\nSaya adalah AI assistant yang siap membantu Anda.\n\n📋 *Menu:*\n1️⃣ Katalog Produk\n2️⃣ Berita\n3️⃣ Alamat & Jam Buka\n4️⃣ Hubungi Admin\n\n💡 Atau tanya apa saja tentang batik!",

            '/menu' => "📋 *Menu Utama*\n\n1️⃣ Katalog Produk\n2️⃣ Berita Terbaru\n3️⃣ Alamat & Jam Buka\n4️⃣ Hubungi Admin\n\n💬 Ketik angka atau tanya langsung!",

            '/help' => "ℹ️ *Cara Menggunakan Bot*\n\n1. Ketik /menu untuk melihat menu\n2. Tanya apa saja tentang batik\n3. Contoh: 'Ada batik untuk pernikahan?'\n\nBot ini menggunakan AI dan bisa menjawab berbagai pertanyaan! 🤖",

            '0' => "✨ *Menu Utama*\n\n1️⃣ Katalog Produk\n2️⃣ Berita\n3️⃣ Alamat\n4️⃣ Admin",

            '1' => "🛍️ *Katalog Produk*\n\nKoleksi Batik Wistara:\n• Batik Tulis Premium\n• Batik Cap\n• Batik Modern\n• Kemeja & Dress Batik\n\n🌐 Lihat lengkap: " . url('/produk') . "\n\n💬 Atau tanya: 'Ada batik formal?'",

            '2' => "📰 *Berita Terbaru*\n\nCek update terbaru:\n" . url('/berita'),

            '3' => "📍 *Alamat & Jam Buka*\n\n🏪 Batik Wistara\n📍 [Alamat Lengkap]\n⏰ Senin-Sabtu: 09:00-17:00\n📞 [Telepon]\n🌐 " . url('/'),

            '4' => "💬 *Hubungi Admin*\n\nUntuk bantuan lebih lanjut, hubungi admin kami.",
        ];

        return $commands[$cmd] ?? $commands['/start'];
    }

    protected function handleAI($text, $name, $chatId)
    {
        $history = Cache::get("tg_ai_{$chatId}", []);

        $systemPrompt = <<<PROMPT
Anda adalah customer service Batik Wistara di Telegram.

Nama customer: {$name}

INFORMASI TOKO:
- Nama: Batik Wistara
- Produk: Batik tulis premium, batik cap, kemeja batik, dress batik
- Harga: Rp 150.000 - 2.000.000
- Jam Operasional: Senin-Sabtu 09:00-17:00
- Website: {url('/')}
- Lokasi: [Isi alamat toko Anda]

CARA MENJAWAB:
1. Gunakan bahasa Indonesia yang ramah dan sopan
2. Gunakan emoji yang sesuai 😊
3. Format dengan Markdown (*bold*, _italic_) jika perlu
4. Berikan info yang jelas tapi ringkas (max 150 kata)
5. Jika ditanya harga, beri range umum
6. Akhiri dengan tawaran bantuan

PANTANGAN:
❌ Jangan buat-buat harga pasti
❌ Jangan terlalu panjang
❌ Jangan gunakan bahasa formal/kaku

Contoh jawaban bagus:
"Halo {$name}! 😊

Batik tulis adalah batik premium yang dibuat manual menggunakan canting. Prosesnya memakan waktu 1-3 bulan dan setiap motifnya unik!

*Kelebihan:*
✨ Motif detail & eksklusif
✨ Nilai seni tinggi
✨ Harga: Rp 250k - 2jt

Kami punya koleksi batik tulis dengan berbagai motif klasik. Mau lihat katalognya? 🎨"
PROMPT;

        $result = $this->groq->chatWithSystemPrompt($text, $systemPrompt, $history);

        if ($result['success']) {
            Log::info('✅ Telegram AI SUCCESS - Tokens: ' . ($result['usage']['total_tokens'] ?? 0));

            $history[] = ['role' => 'user', 'content' => $text];
            $history[] = ['role' => 'assistant', 'content' => $result['response']];

            if (count($history) > 8) {
                $history = array_slice($history, -8);
            }

            Cache::put("tg_ai_{$chatId}", $history, now()->addHours(2));

            return $result['response'] . "\n\n─────\n💡 Ketik /menu untuk pilihan lain";
        }

        Log::error('❌ Telegram AI FAILED: ' . ($result['error'] ?? 'unknown'));
        return "Maaf {$name}, AI sedang sibuk 😅\nKetik /help untuk bantuan.";
    }

    protected function sendMessage($chatId, $text)
    {
        try {
            $response = Http::post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'Markdown',
            ]);

            Log::info('📤 Telegram sent: ' . $response->status());
            return $response->successful();

        } catch (\Exception $e) {
            Log::error('📤 Telegram send error: ' . $e->getMessage());
            return false;
        }
    }

    public function setWebhook()
    {
        $webhookUrl = url('/telegram/webhook');

        $response = Http::post("https://api.telegram.org/bot{$this->botToken}/setWebhook", [
            'url' => $webhookUrl,
        ]);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'message' => 'Webhook set successfully',
                'webhook_url' => $webhookUrl,
                'data' => $response->json(),
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $response->json(),
        ], 400);
    }

    public function getWebhookInfo()
    {
        $response = Http::get("https://api.telegram.org/bot{$this->botToken}/getWebhookInfo");

        return response()->json($response->json());
    }
}
