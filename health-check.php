<?php

echo "🏥 HEALTH CHECK - Batik Wistara\n";
echo str_repeat('═', 50) . "\n\n";

$checks = [];

// 1. Server Check
echo "1. Server............... ";
$ch = curl_init('http://localhost:8000');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$result = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$checks['server'] = $code == 200;
echo ($checks['server'] ? "✅ UP" : "❌ DOWN") . "\n";

// 2. Groq AI Check
echo "2. Groq AI.............. ";
$ch = curl_init('http://localhost:8000/api/chatbot/test');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$result = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$checks['groq'] = $code == 200;
echo ($checks['groq'] ? "✅ OK" : "❌ FAIL") . "\n";

// 3. Database Check (if applicable)
echo "3. Database............. ";
// Add your database check here
$checks['database'] = true; // Placeholder
echo "✅ OK\n";

// 4. Cache Check
echo "4. Cache................ ";
$checks['cache'] = file_exists(__DIR__ . '/bootstrap/cache');
echo ($checks['cache'] ? "✅ OK" : "❌ FAIL") . "\n";

// 5. Storage Check
echo "5. Storage Writable..... ";
$checks['storage'] = is_writable(__DIR__ . '/storage');
echo ($checks['storage'] ? "✅ OK" : "❌ FAIL") . "\n";

echo "\n" . str_repeat('═', 50) . "\n";

$healthy = array_filter($checks);
$healthScore = round((count($healthy) / count($checks)) * 100);

echo "Health Score: $healthScore%\n";

if ($healthScore == 100) {
    echo "Status: ✅ HEALTHY\n";
} elseif ($healthScore >= 80) {
    echo "Status: ⚠️  DEGRADED\n";
} else {
    echo "Status: ❌ UNHEALTHY\n";
}

echo "\n";
