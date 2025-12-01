<?php

namespace App\Http\Controllers;

use App\Services\GroqService;
use Illuminate\Http\Request;

class ComparisonController extends Controller
{
    protected $groq;

    public function __construct(GroqService $groq)
    {
        $this->groq = $groq;
    }

    public function index()
    {
        return view('comparison.index');
    }

    public function chat(Request $request)
    {
        $message = $request->input('message');

        // 1. Chatbot TANPA AI (Rule-based)
        $basicResponse = $this->basicChatbot($message);

        // 2. Chatbot DENGAN Groq AI
        $aiResult = $this->groq->chat($message);
        $aiResponse = $aiResult['success'] ? $aiResult['response'] : 'Error: ' . $aiResult['error'];

        return response()->json([
            'basic' => $basicResponse,
            'ai' => $aiResponse,
        ]);
    }

    private function basicChatbot($message)
    {
        $message = strtolower(trim($message));

        // Rule-based responses (harus di-program satu-satu)
        $responses = [
            'halo' => 'Halo juga!',
            'hai' => 'Hai, ada yang bisa dibantu?',
            'apa kabar' => 'Kabar saya baik, terima kasih!',
            'siapa kamu' => 'Saya adalah chatbot sederhana.',
            'terima kasih' => 'Sama-sama!',
            'bye' => 'Sampai jumpa!',
        ];

        // Cek exact match
        if (isset($responses[$message])) {
            return $responses[$message];
        }

        // Cek partial match
        foreach ($responses as $keyword => $response) {
            if (str_contains($message, $keyword)) {
                return $response;
            }
        }

        // Default response jika tidak mengerti
        return "Maaf, saya tidak mengerti. Saya hanya bisa menjawab: " .
               implode(', ', array_keys($responses));
    }
}
