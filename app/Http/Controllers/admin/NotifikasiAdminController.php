<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotifikasiAdminController extends Controller
{
    public function sendWhatsapp($phone, $message)
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

    public function sendTelegram($message)
    {
        try {
            $token  = env('TELEGRAM_BOT_TOKEN');
            $chatId = env('TELEGRAM_CHAT_ID');

            Http::post("https://api.telegram.org/bot{$token}/sendMessage",
            [
                'chat_id' => $chatId,
                'text'    => $message
            ]);
        } catch (\Exception $e) {
            Log::error("Telegram Error: " . $e->getMessage());
        }
    }

    public function sendAll($phone, $message)
    {
        $this->sendWhatsapp($phone, $message);
        $this->sendTelegram($message);
    }
}
