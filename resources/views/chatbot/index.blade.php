<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Chatbot - Batik Wistara</title>
    <link rel="stylesheet" href="{{ asset('js/checkout.js') }}">
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
</head>

<body>
    <div class="chat-container">
        <div class="chat-header">
            <h2>🤖 AI Assistant Batik Wistara</h2>
            <p>Powered by Groq AI</p>
        </div>

        <div class="status" id="status">🔄 Mengecek koneksi...</div>

        <div class="chat-messages" id="chatMessages">
            <div class="message bot">
                <div class="message-content">
                    👋 Selamat datang di Batik Wistara! Saya adalah AI assistant yang siap membantu Anda. Ada yang bisa
                    saya bantu?
                </div>
            </div>
        </div>

        <div class="chat-input">
            <input type="text" id="messageInput" placeholder="Ketik pesan Anda..." autocomplete="off">
            <button onclick="sendMessage()" id="sendButton">Kirim</button>
        </div>
    </div>

    <script src="{{ asset('js/chatbot.js') }}"></script>
</body>

</html>
