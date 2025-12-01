<?php

// Simulate WhatsApp webhook request
$webhookUrl = 'http://localhost:8000/api/webhook/whatsapp'; // Pakai /api/

$data = [
    'from' => '628123456789',
    'message' => 'jelaskan batik tulis',
    'name' => 'Test User',
    'pushname' => 'Test User'
];

$ch = curl_init($webhookUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

echo "Testing webhook with AI message...\n\n";
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

if ($httpCode == 200) {
    echo "✅ Webhook working!\n";
    echo "Now update Fonnte/Wablas webhook to:\n";
    echo "https://your-domain.com/api/webhook/whatsapp\n";
} else {
    echo "❌ Webhook failed!\n";
}
