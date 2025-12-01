<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Http;

echo "Testing Groq API...\n\n";

$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . config('services.groq.api_key'),
    'Content-Type' => 'application/json',
])->timeout(30)->post(config('services.groq.api_url') . '/chat/completions', [
    'model' => config('services.groq.model'),
    'messages' => [
        ['role' => 'user', 'content' => 'Halo, jawab singkat: siapa kamu?']
    ],
    'max_tokens' => 50
]);

echo "Success: " . ($response->successful() ? 'YES' : 'NO') . "\n";
echo "Status Code: " . $response->status() . "\n\n";

if ($response->successful()) {
    $data = $response->json();
    echo "Response:\n";
    echo $data['choices'][0]['message']['content'] . "\n\n";
    echo "Tokens Used: " . $data['usage']['total_tokens'] . "\n";
} else {
    echo "Error:\n";
    echo $response->body() . "\n";
}
