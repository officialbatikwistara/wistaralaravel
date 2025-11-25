<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 GROQ QUICK TEST\n";
echo "==================\n\n";

$apiKey = env('GROQ_API_KEY');
$apiUrl = env('GROQ_API_URL', 'https://api.groq.com/openai/v1');
$model = env('GROQ_MODEL', 'llama-3.3-70b-versatile');

echo "📋 Configuration:\n";
echo "   API Key: " . substr($apiKey, 0, 15) . "...\n";
echo "   Model: $model\n";
echo "   URL: $apiUrl\n\n";

echo "🚀 Sending test message...\n";

try {
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiKey,
        'Content-Type' => 'application/json',
    ])->timeout(30)->post($apiUrl . '/chat/completions', [
        'model' => $model,
        'messages' => [
            ['role' => 'user', 'content' => 'Halo! Jawab dengan singkat: siapa kamu?']
        ],
        'max_tokens' => 100,
    ]);

    if ($response->successful()) {
        $data = $response->json();
        echo "\n✅ SUCCESS!\n\n";
        echo "📨 Response:\n";
        echo "   " . $data['choices'][0]['message']['content'] . "\n\n";
        echo "📊 Token Usage:\n";
        echo "   Prompt: " . $data['usage']['prompt_tokens'] . "\n";
        echo "   Completion: " . $data['usage']['completion_tokens'] . "\n";
        echo "   Total: " . $data['usage']['total_tokens'] . "\n\n";
        echo "🎉 Groq AI is working perfectly!\n";
        echo "\n💡 Now check: https://console.groq.com/usage\n";
        echo "   Your API calls should be > 0\n";
    } else {
        echo "\n❌ FAILED!\n\n";
        echo "Status: " . $response->status() . "\n";
        echo "Error: " . $response->body() . "\n";
    }
} catch (\Exception $e) {
    echo "\n❌ ERROR!\n\n";
    echo $e->getMessage() . "\n";
}
