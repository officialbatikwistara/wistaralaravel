<?php

echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║        COMPREHENSIVE WEBSITE TEST - BATIK WISTARA        ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

$baseUrl = 'http://localhost:8000';
$results = [];
$totalTests = 0;
$passed = 0;
$failed = 0;

function testEndpoint($name, $url, $method = 'GET', $data = null, $expectedCode = 200) {
    global $baseUrl, $results, $totalTests, $passed, $failed;

    $totalTests++;
    echo str_pad("Testing: $name", 60, '.') . " ";

    $startTime = microtime(true);
    $ch = curl_init($baseUrl . $url);

    if ($method == 'POST') {
        curl_setopt($ch, CURLOPT_POST, 1);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    $duration = round((microtime(true) - $startTime) * 1000);
    $status = ($httpCode == $expectedCode) ? 'PASS' : 'FAIL';

    if ($status == 'PASS') {
        echo "✅ PASS";
        $passed++;
    } else {
        echo "❌ FAIL";
        $failed++;
    }

    echo " ($httpCode) {$duration}ms\n";

    $results[] = [
        'name' => $name,
        'status' => $status,
        'http_code' => $httpCode,
        'expected' => $expectedCode,
        'duration' => $duration,
        'error' => $error
    ];

    return $status == 'PASS';
}

echo "═══════════════════════════════════════════════════════════\n";
echo "SECTION 1: CORE PAGES\n";
echo "═══════════════════════════════════════════════════════════\n";

testEndpoint('Homepage', '/');
testEndpoint('About Page', '/tentang');
testEndpoint('News/Berita Page', '/berita');
testEndpoint('Products Page', '/produk');
testEndpoint('Contact Page', '/kontak');

echo "\n═══════════════════════════════════════════════════════════\n";
echo "SECTION 2: GROQ AI SERVICE\n";
echo "═══════════════════════════════════════════════════════════\n";

testEndpoint('Groq Config Test', '/api/chatbot/test');
testEndpoint('Groq AI Direct Chat', '/api/chatbot/chat', 'POST', ['message' => 'test ai']);

echo "\n═══════════════════════════════════════════════════════════\n";
echo "SECTION 3: CHATBOT (WEB UI)\n";
echo "═══════════════════════════════════════════════════════════\n";

testEndpoint('Chatbot UI Page', '/chatbot');
testEndpoint('Chatbot AI Response', '/api/chatbot/chat', 'POST', ['message' => 'halo']);
testEndpoint('Chatbot Clear History', '/api/chatbot/clear', 'POST');

echo "\n═══════════════════════════════════════════════════════════\n";
echo "SECTION 4: WHATSAPP WEBHOOK\n";
echo "═══════════════════════════════════════════════════════════\n";

testEndpoint('WhatsApp Webhook (Menu)', '/api/webhook/whatsapp', 'POST', [
    'from' => '628123456789',
    'message' => '0',
    'name' => 'Test User'
]);

testEndpoint('WhatsApp Webhook (AI)', '/api/webhook/whatsapp', 'POST', [
    'from' => '628123456789',
    'message' => 'jelaskan batik',
    'name' => 'Test User'
]);

echo "\n═══════════════════════════════════════════════════════════\n";
echo "SECTION 5: TELEGRAM BOT (if configured)\n";
echo "═══════════════════════════════════════════════════════════\n";

testEndpoint('Telegram Webhook', '/api/telegram/webhook', 'POST', [
    'message' => [
        'chat' => ['id' => 123456789],
        'from' => ['first_name' => 'Test'],
        'text' => '/start'
    ]
]);

testEndpoint('Telegram Webhook Info', '/api/telegram/webhook-info');

echo "\n═══════════════════════════════════════════════════════════\n";
echo "SECTION 6: API ENDPOINTS (if exist)\n";
echo "═══════════════════════════════════════════════════════════\n";

testEndpoint('API Health Check', '/api/health', 'GET', null, 200);

echo "\n═══════════════════════════════════════════════════════════\n";
echo "SECTION 7: ASSETS & STATIC FILES\n";
echo "═══════════════════════════════════════════════════════════\n";

testEndpoint('CSS Assets', '/css/app.css', 'GET', null, 200);
testEndpoint('JS Assets', '/js/app.js', 'GET', null, 200);

echo "\n╔═══════════════════════════════════════════════════════════╗\n";
echo "║                      TEST SUMMARY                         ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

// Group by status
$passedTests = array_filter($results, fn($r) => $r['status'] == 'PASS');
$failedTests = array_filter($results, fn($r) => $r['status'] == 'FAIL');

echo "Total Tests: $totalTests\n";
echo "✅ Passed: $passed (" . round(($passed/$totalTests)*100) . "%)\n";
echo "❌ Failed: $failed (" . round(($failed/$totalTests)*100) . "%)\n\n";

if ($failed > 0) {
    echo "═══════════════════════════════════════════════════════════\n";
    echo "FAILED TESTS DETAIL:\n";
    echo "═══════════════════════════════════════════════════════════\n";
    foreach ($failedTests as $test) {
        echo "❌ {$test['name']}\n";
        echo "   Expected: {$test['expected']}, Got: {$test['http_code']}\n";
        if (!empty($test['error'])) {
            echo "   Error: {$test['error']}\n";
        }
        echo "\n";
    }
}

echo "═══════════════════════════════════════════════════════════\n";
echo "PERFORMANCE METRICS:\n";
echo "═══════════════════════════════════════════════════════════\n";

$durations = array_column($results, 'duration');
$avgDuration = array_sum($durations) / count($durations);
$maxDuration = max($durations);
$minDuration = min($durations);

echo "Average Response Time: " . round($avgDuration) . "ms\n";
echo "Fastest Response: " . $minDuration . "ms\n";
echo "Slowest Response: " . $maxDuration . "ms\n\n";

// Find slowest endpoints
arsort($durations);
echo "Top 5 Slowest Endpoints:\n";
$slowest = array_slice($durations, 0, 5, true);
foreach ($slowest as $index => $duration) {
    echo "  " . ($index + 1) . ". {$results[$index]['name']} - {$duration}ms\n";
}

echo "\n═══════════════════════════════════════════════════════════\n";
echo "RECOMMENDATIONS:\n";
echo "═══════════════════════════════════════════════════════════\n";

if ($failed == 0) {
    echo "🎉 PERFECT! All tests passed!\n";
    echo "✅ Website is fully functional\n";
    echo "✅ Groq AI is working on all channels\n";
    echo "✅ All pages are accessible\n\n";
} else {
    echo "⚠️  Some tests failed. Please check:\n";
    echo "1. Server is running (php artisan serve)\n";
    echo "2. Database connection is working\n";
    echo "3. .env file is configured correctly\n";
    echo "4. Cache is cleared (php artisan optimize:clear)\n\n";
}

if ($avgDuration > 2000) {
    echo "⚠️  Average response time > 2s. Consider:\n";
    echo "- Enable caching\n";
    echo "- Optimize database queries\n";
    echo "- Use CDN for assets\n\n";
}

echo "═══════════════════════════════════════════════════════════\n";
echo "NEXT STEPS:\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "1. 🌐 Open in browser: http://localhost:8000\n";
echo "2. 💬 Test chatbot: http://localhost:8000/chatbot\n";
echo "3. 📱 Test WhatsApp bot via Fonnte\n";
echo "4. 📨 Test Telegram bot (if configured)\n";
echo "5. 📊 Check Groq usage: https://console.groq.com/usage\n";
echo "6. 📝 Check logs: Get-Content storage\\logs\\laravel.log -Tail 100\n\n";

echo "╔═══════════════════════════════════════════════════════════╗\n";
if ($failed == 0) {
    echo "║              ✨ ALL SYSTEMS OPERATIONAL ✨               ║\n";
} else {
    echo "║           ⚠️  SOME ISSUES NEED ATTENTION ⚠️            ║\n";
}
echo "╚═══════════════════════════════════════════════════════════╝\n";
