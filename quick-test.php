<?php

echo "🧪 QUICK TEST - Groq AI Multi-Channel\n";
echo str_repeat('═', 50) . "\n\n";

$base = 'http://localhost:8000';
$tests = [
    ['name' => 'Groq Config', 'url' => '/api/chatbot/test', 'method' => 'GET'],
    ['name' => 'Web Chatbot UI', 'url' => '/chatbot', 'method' => 'GET'],
    ['name' => 'Web AI Chat', 'url' => '/api/chatbot/chat', 'method' => 'POST', 'data' => ['message' => 'hai']],
    ['name' => 'WhatsApp Webhook', 'url' => '/api/webhook/whatsapp', 'method' => 'POST', 'data' => ['from' => '6281234', 'message' => 'test', 'name' => 'User']],
];

foreach ($tests as $i => $test) {
    echo ($i + 1) . ". " . $test['name'] . "... ";

    $ch = curl_init($base . $test['url']);

    if ($test['method'] == 'POST') {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($test['data'] ?? []));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $result = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code == 200) {
        echo "✅ PASS ($code)\n";
    } else {
        echo "❌ FAIL ($code)\n";
    }
}

echo "\n" . str_repeat('═', 50) . "\n";
echo "✅ Test selesai!\n";
echo "📝 Cek log: Get-Content storage\\logs\\laravel.log -Tail 50\n";
