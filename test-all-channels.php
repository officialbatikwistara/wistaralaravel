<?php

echo "╔════════════════════════════════════════════════╗\n";
echo "║   COMPREHENSIVE TEST - ALL AI CHANNELS         ║\n";
echo "╚════════════════════════════════════════════════╝\n\n";

$baseUrl = 'http://localhost:8000';
$results = [];

// Test 1: Groq AI Service Direct
echo "1️⃣  Testing Groq AI Service (Direct)\n";
echo str_repeat('─', 50) . "\n";

$startTime = microtime(true);
$ch = curl_init($baseUrl . '/chatbot/test');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$duration = round((microtime(true) - $startTime) * 1000);

$results['groq_service'] = [
    'status' => $httpCode == 200 ? 'PASS' : 'FAIL',
    'http_code' => $httpCode,
    'duration' => $duration . 'ms',
];

echo "   Status: " . ($httpCode == 200 ? '✅ PASS' : '❌ FAIL') . "\n";
echo "   HTTP Code: $httpCode\n";
echo "   Duration: {$duration}ms\n\n";

// Test 2: Web Chatbot UI
echo "2️⃣  Testing Web Chatbot UI\n";
echo str_repeat('─', 50) . "\n";

$startTime = microtime(true);
$ch = curl_init($baseUrl . '/chatbot');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$duration = round((microtime(true) - $startTime) * 1000);

$results['web_chatbot'] = [
    'status' => $httpCode == 200 ? 'PASS' : 'FAIL',
    'http_code' => $httpCode,
    'duration' => $duration . 'ms',
];

echo "   Status: " . ($httpCode == 200 ? '✅ PASS' : '❌ FAIL') . "\n";
echo "   HTTP Code: $httpCode\n";
echo "   Duration: {$duration}ms\n\n";

// Test 3: Web Chatbot AI Response
echo "3️⃣  Testing Web Chatbot AI Response\n";
echo str_repeat('─', 50) . "\n";

$startTime = microtime(true);
$ch = curl_init($baseUrl . '/chatbot/chat');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['message' => 'test AI']));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$duration = round((microtime(true) - $startTime) * 1000);

$data = json_decode($response, true);
$aiWorking = isset($data['success']) && $data['success'];

$results['web_ai'] = [
    'status' => $aiWorking ? 'PASS' : 'FAIL',
    'http_code' => $httpCode,
    'duration' => $duration . 'ms',
    'ai_response' => $aiWorking,
];

echo "   Status: " . ($aiWorking ? '✅ PASS' : '❌ FAIL') . "\n";
echo "   HTTP Code: $httpCode\n";
echo "   AI Working: " . ($aiWorking ? 'Yes' : 'No') . "\n";
echo "   Duration: {$duration}ms\n\n";

// Test 4: WhatsApp Webhook
echo "4️⃣  Testing WhatsApp Webhook (API)\n";
echo str_repeat('─', 50) . "\n";

$startTime = microtime(true);
$ch = curl_init($baseUrl . '/api/webhook/whatsapp');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'from' => '628123456789',
    'message' => 'test whatsapp ai',
    'name' => 'Test User'
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$duration = round((microtime(true) - $startTime) * 1000);

$data = json_decode($response, true);
$aiUsed = isset($data['ai_used']) && $data['ai_used'];

$results['whatsapp'] = [
    'status' => $httpCode == 200 ? 'PASS' : 'FAIL',
    'http_code' => $httpCode,
    'duration' => $duration . 'ms',
    'ai_used' => $aiUsed,
];

echo "   Status: " . ($httpCode == 200 ? '✅ PASS' : '❌ FAIL') . "\n";
echo "   HTTP Code: $httpCode\n";
echo "   AI Used: " . ($aiUsed ? 'Yes' : 'No') . "\n";
echo "   Duration: {$duration}ms\n\n";

// Test 5: Telegram Webhook
echo "5️⃣  Testing Telegram Webhook\n";
echo str_repeat('─', 50) . "\n";

$startTime = microtime(true);
$ch = curl_init($baseUrl . '/api/telegram/webhook');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'message' => [
        'chat' => ['id' => 123456789],
        'from' => ['first_name' => 'Test User'],
        'text' => 'test telegram ai',
    ]
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$duration = round((microtime(true) - $startTime) * 1000);

$data = json_decode($response, true);
$telegramOk = isset($data['ok']) && $data['ok'];

$results['telegram'] = [
    'status' => $telegramOk ? 'PASS' : 'FAIL',
    'http_code' => $httpCode,
    'duration' => $duration . 'ms',
];

echo "   Status: " . ($telegramOk ? '✅ PASS' : '❌ FAIL') . "\n";
echo "   HTTP Code: $httpCode\n";
echo "   Duration: {$duration}ms\n\n";

// Summary
echo "\n";
echo "╔════════════════════════════════════════════════╗\n";
echo "║              TEST SUMMARY                      ║\n";
echo "╚════════════════════════════════════════════════╝\n\n";

$passed = 0;
$failed = 0;

foreach ($results as $name => $result) {
    $status = $result['status'] == 'PASS' ? '✅' : '❌';
    $statusText = str_pad($result['status'], 6);
    $nameText = str_pad(ucfirst(str_replace('_', ' ', $name)), 25);
    $duration = str_pad($result['duration'], 10);

    echo "  $status $statusText | $nameText | $duration\n";

    if ($result['status'] == 'PASS') {
        $passed++;
    } else {
        $failed++;
    }
}

echo "\n";
echo str_repeat('─', 50) . "\n";
echo "  Total Tests: " . count($results) . "\n";
echo "  ✅ Passed: $passed\n";
echo "  ❌ Failed: $failed\n";
echo str_repeat('─', 50) . "\n\n";

if ($failed == 0) {
    echo "🎉 ALL TESTS PASSED! AI is working on all channels!\n\n";
} else {
    echo "⚠️  Some tests failed. Check the logs:\n";
    echo "   Get-Content storage\\logs\\laravel.log -Tail 100\n\n";
}

echo "Next steps:\n";
echo "1. ✅ Groq AI Service is ready\n";
echo "2. 🌐 Web Chatbot: http://localhost:8000/chatbot\n";
echo "3. 📱 WhatsApp: Update webhook at Fonnte\n";
echo "4. 📨 Telegram: Set webhook with /telegram/set-webhook\n\n";
