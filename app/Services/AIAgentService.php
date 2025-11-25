<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIAgentService
{
    protected $provider;
    protected $apiKey;
    protected $apiUrl;
    protected $model;
    protected $temperature;
    protected $maxTokens;

    public function __construct()
    {
        $this->provider = config('services.ai_agent.provider', 'groq');
        $this->apiKey = config('services.ai_agent.api_key');
        $this->apiUrl = config('services.ai_agent.api_url');
        $this->model = config('services.ai_agent.model');
        $this->temperature = config('services.ai_agent.temperature', 0.7);
        $this->maxTokens = config('services.ai_agent.max_tokens', 1000);

        // Auto-configure based on provider
        $this->configureProvider();
    }

    protected function configureProvider()
    {
        switch ($this->provider) {
            case 'groq':
                $this->apiUrl = $this->apiUrl ?: 'https://api.groq.com/openai/v1';
                $this->model = $this->model ?: 'llama-3.3-70b-versatile';
                break;
            case 'gemini':
                $this->apiUrl = $this->apiUrl ?: 'https://generativelanguage.googleapis.com/v1beta';
                $this->model = $this->model ?: 'gemini-2.0-flash-exp';
                break;
            case 'openrouter':
                $this->apiUrl = $this->apiUrl ?: 'https://openrouter.ai/api/v1';
                $this->model = $this->model ?: 'meta-llama/llama-3.2-3b-instruct:free';
                break;
            case 'ollama':
                $this->apiUrl = $this->apiUrl ?: 'http://localhost:11434/v1';
                $this->model = $this->model ?: 'llama3.2';
                break;
            case 'openai':
            default:
                $this->apiUrl = $this->apiUrl ?: 'https://api.openai.com/v1';
                $this->model = $this->model ?: 'gpt-4o-mini';
                break;
        }
    }

    public function chat(string $message, array $conversationHistory = [])
    {
        try {
            $messages = array_merge($conversationHistory, [
                ['role' => 'user', 'content' => $message]
            ]);

            // Provider-specific handling
            if ($this->provider === 'gemini') {
                return $this->chatGemini($messages);
            }

            // OpenAI-compatible providers (Groq, OpenRouter, Ollama, OpenAI)
            return $this->chatOpenAICompatible($messages);

        } catch (\Exception $e) {
            Log::error('AI Agent Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    protected function chatOpenAICompatible(array $messages)
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];

        // Add auth headers based on provider
        if ($this->provider !== 'ollama') {
            $headers['Authorization'] = 'Bearer ' . $this->apiKey;
        }

        if ($this->provider === 'openrouter') {
            $headers['HTTP-Referer'] = config('app.url');
        }

        $response = Http::withHeaders($headers)
            ->timeout(60)
            ->post($this->apiUrl . '/chat/completions', [
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
                'provider' => $this->provider,
            ];
        }

        return [
            'success' => false,
            'error' => $response->json()['error']['message'] ?? 'Unknown error',
        ];
    }

    protected function chatGemini(array $messages)
    {
        // Convert OpenAI format to Gemini format
        $contents = [];
        foreach ($messages as $msg) {
            if ($msg['role'] === 'system') continue; // Gemini doesn't support system role

            $contents[] = [
                'role' => $msg['role'] === 'assistant' ? 'model' : 'user',
                'parts' => [['text' => $msg['content']]]
            ];
        }

        $response = Http::timeout(60)
            ->post($this->apiUrl . '/models/' . $this->model . ':generateContent?key=' . $this->apiKey, [
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => $this->temperature,
                    'maxOutputTokens' => $this->maxTokens,
                ]
            ]);

        if ($response->successful()) {
            $data = $response->json();
            return [
                'success' => true,
                'response' => $data['candidates'][0]['content']['parts'][0]['text'] ?? '',
                'provider' => 'gemini',
            ];
        }

        return [
            'success' => false,
            'error' => $response->json()['error']['message'] ?? 'Unknown error',
        ];
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
