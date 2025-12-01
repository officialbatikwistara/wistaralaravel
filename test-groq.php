<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$apiKey = config('services.groq.api_key');
$apiUrl = config('services.groq.api_url');
$model = config('services.groq.model');

echo "Testing Groq Connection...\n";
echo "API Key: " . substr($apiKey, 0, 15) . "...\n";
echo "Model: $model\n\n";

$response = \Illuminate\Support\Facades\Http::withHeaders([
    'Authorization' => 'Bearer ' . $apiKey,
    'Content-Type' => 'application/json',
])->timeout(30)->post($apiUrl . '/chat/completions', [
    'model' => $model,
    'messages' => [
        ['role' => 'user', 'content' => 'Halo, siapa kamu?']
    ],
    'max_tokens' => 100,
]);

if ($response->successful()) {
    $data = $response->json();
    echo "✅ SUCCESS!\n\n";
    echo "Response: " . $data['choices'][0]['message']['content'] . "\n\n";
    echo "Tokens used: " . $data['usage']['total_tokens'] . "\n";
} else {
    echo "❌ FAILED!\n";
    echo "Status: " . $response->status() . "\n";
    echo "Error: " . $response->body() . "\n";
}
