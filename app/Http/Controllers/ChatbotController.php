<?php

namespace App\Http\Controllers;

use App\Services\GroqService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    protected $groq;

    public function __construct(GroqService $groq)
    {
        $this->groq = $groq;
    }

    public function index()
    {
        return view('chatbot.index');
    }

    public function chat(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:1000',
            ]);

            $message = $request->input('message');
            $history = Session::get('chatbot_history', []);

            $systemPrompt = "Anda adalah asisten virtual Batik Wistara yang ramah dan membantu. Berikan jawaban yang informatif dalam bahasa Indonesia.";

            $result = $this->groq->chatWithSystemPrompt($message, $systemPrompt, $history);

            if ($result['success']) {
                $history[] = ['role' => 'user', 'content' => $message];
                $history[] = ['role' => 'assistant', 'content' => $result['response']];

                if (count($history) > 10) {
                    $history = array_slice($history, -10);
                }

                Session::put('chatbot_history', $history);

                return response()->json([
                    'success' => true,
                    'response' => $result['response'],
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => $result['error'],
            ], 500);

        } catch (\Exception $e) {
            Log::error('Chatbot Exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function clearHistory()
    {
        Session::forget('chatbot_history');
        return response()->json(['success' => true]);
    }

    public function test()
    {
        try {
            $apiKey = config('services.groq.api_key');

            if (empty($apiKey)) {
                return response()->json([
                    'success' => false,
                    'error' => 'GROQ_API_KEY tidak ditemukan'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Konfigurasi OK',
                'config' => [
                    'api_key_exists' => !empty($apiKey),
                    'api_key_preview' => substr($apiKey, 0, 10) . '...',
                    'model' => config('services.groq.model'),
                    'api_url' => config('services.groq.api_url')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
