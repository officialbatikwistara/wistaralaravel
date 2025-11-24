<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiUrl;
    protected $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url', 'https://api.fonnte.com');
        $this->apiToken = config('services.whatsapp.api_token');
    }

    public function sendMessage($phone, $message, $imageUrl = null)
    {
        try {
            $phone = $this->formatPhoneNumber($phone);

            $payload = [
                'target' => $phone,
                'message' => $message,
            ];

            if ($imageUrl) {
                $payload['url'] = $imageUrl;
            }

            $response = Http::withHeaders([
                'Authorization' => $this->apiToken,
            ])->post($this->apiUrl . '/send', $payload);

            if ($response->successful()) {
                Log::info('✅ WhatsApp sent successfully', [
                    'phone' => $phone,
                    'response' => $response->json()
                ]);

                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            throw new \Exception('Fonnte API error: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('❌ WhatsApp send failed', [
                'error' => $e->getMessage(),
                'phone' => $phone
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function sendBulk(array $recipients, $message)
    {
        $results = [];

        foreach ($recipients as $phone) {
            $results[] = $this->sendMessage($phone, $message);
            usleep(500000); // 0.5 second delay to avoid rate limit
        }

        return $results;
    }

    protected function formatPhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Convert 08xxx to 628xxx (Indonesia)
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        // Add 62 if no country code
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}
