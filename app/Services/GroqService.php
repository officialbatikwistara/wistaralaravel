<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    protected $apiKey;
    protected $apiUrl;
    protected $model;
    protected $temperature;
    protected $maxTokens;

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key');
        $this->apiUrl = config('services.groq.api_url');
        $this->model = config('services.groq.model');
        $this->temperature = config('services.groq.temperature');
        $this->maxTokens = config('services.groq.max_tokens');
    }

    public function chat(string $message, array $conversationHistory = [])
    {
        try {
            if (empty($this->apiKey)) {
                throw new \Exception('GROQ_API_KEY tidak ditemukan');
            }

            $messages = array_merge($conversationHistory, [
                ['role' => 'user', 'content' => $message]
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->apiUrl . '/chat/completions', [
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => $this->temperature,
                'max_tokens' => $this->maxTokens,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'response' => $data['choices'][0]['message']['content'],
                    'usage' => $data['usage'] ?? null,
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['error']['message'] ?? 'Groq API error',
            ];
        } catch (\Exception $e) {
            Log::error('Groq Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function chatWithSystemPrompt(string $message, string $systemPrompt, array $conversationHistory = [])
    {
        $messages = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];

        $messages = array_merge($messages, $conversationHistory, [
            ['role' => 'user', 'content' => $message]
        ]);

        return $this->chat($message, array_slice($messages, 0, -1));
    }
}
