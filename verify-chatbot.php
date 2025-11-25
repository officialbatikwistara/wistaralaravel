<?php

echo "=================================\n";
echo "  CHATBOT VERIFICATION SCRIPT   \n";
echo "=================================\n\n";

// Check files
$files = [
    'app/Services/GroqService.php',
    'app/Http/Controllers/ChatbotController.php',
    'resources/views/chatbot/index.blade.php',
];

echo "1. Checking Files...\n";
foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        echo "   ✅ $file\n";
    } else {
        echo "   ❌ MISSING: $file\n";
    }
}

echo "\n2. Checking .env configuration...\n";
$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    $env = file_get_contents($envPath);

    if (strpos($env, 'GROQ_API_KEY') !== false) {
        echo "   ✅ GROQ_API_KEY found\n";
    } else {
        echo "   ❌ GROQ_API_KEY not found in .env\n";
    }

    if (strpos($env, 'GROQ_MODEL') !== false) {
        echo "   ✅ GROQ_MODEL found\n";
    } else {
        echo "   ⚠️  GROQ_MODEL not found (using default)\n";
    }
} else {
    echo "   ❌ .env file not found!\n";
}

echo "\n3. Checking config/services.php...\n";
$servicesPath = __DIR__ . '/config/services.php';
if (file_exists($servicesPath)) {
    $services = file_get_contents($servicesPath);

    if (strpos($services, "'groq'") !== false) {
        echo "   ✅ Groq configuration exists\n";
    } else {
        echo "   ❌ Groq configuration NOT found in services.php\n";
    }
}

echo "\n4. Checking routes/web.php...\n";
$routesPath = __DIR__ . '/routes/web.php';
if (file_exists($routesPath)) {
    $routes = file_get_contents($routesPath);

    if (strpos($routes, 'ChatbotController') !== false) {
        echo "   ✅ Chatbot routes found\n";
    } else {
        echo "   ❌ Chatbot routes NOT found in web.php\n";
    }
}

echo "\n=================================\n";
echo "📋 SUMMARY:\n";
echo "=================================\n";
echo "If all checks are ✅, run:\n";
echo "  php artisan serve\n\n";
echo "Then open:\n";
echo "  http://localhost:8000/chatbot\n\n";
echo "You should see:\n";
echo "  ✅ Terhubung ke Groq AI\n";
echo "=================================\n";
