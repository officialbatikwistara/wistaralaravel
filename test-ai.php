<?php

// Ubah port jika perlu
$webhookUrl = 'http://localhost:8000/api/webhook/whatsapp'; // atau 8001

echo "=== TESTING GROQ AI WEBHOOK ===\n";
echo "Testing URL: $webhookUrl\n\n";

// Test connection first
echo "1. Testing server connection...\n";
$ch = curl_init('http://localhost:8000');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 0) {
    echo "❌ SERVER NOT RUNNING!\n";
    echo "Please run: php artisan serve\n";
    exit(1);
}

echo "✅ Server is running (HTTP: $httpCode)\n\n";

// Run tests
$tests = [
    ['message' => '0', 'expect' => 'Menu (manual)'],
    ['message' => 'jelaskan batik tulis', 'expect' => 'AI Response'],
];

foreach ($tests as $i => $test) {
    echo "Test " . ($i + 1) . ": " . $test['message'] . "\n";

    $ch = curl_init($webhookUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'from' => '628123456789',
        'message' => $test['message'],
        'name' => 'Test User'
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($httpCode == 0) {
        echo "❌ Connection failed: $error\n";
    } else {
        echo "HTTP: $httpCode\n";
        echo "Response: $response\n";

        if ($httpCode == 200) {
            echo "✅ Success\n";
        } else {
            echo "❌ Failed\n";
        }
    }

    echo str_repeat('-', 50) . "\n\n";
    sleep(1);
}

echo "Done! Check logs: Get-Content storage\\logs\\laravel.log -Tail 50\n";
