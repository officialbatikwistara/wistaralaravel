<?php

// Test dengan URL lama
$webhookUrl = 'http://localhost:8000/webhook/whatsapp';

echo "Testing OLD webhook URL with AI...\n";
echo "URL: $webhookUrl\n\n";

$ch = curl_init($webhookUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'from' => '628123456789',
    'message' => 'jelaskan batik tulis',
    'name' => 'Test User'
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP: $httpCode\n";
echo "Response: $response\n";

if ($httpCode == 200) {
    echo "\n✅ URL lama sudah pakai AI!\n";
    echo "Tidak perlu ganti webhook di Fonnte!\n";
} else {
    echo "\n❌ Failed!\n";
}
