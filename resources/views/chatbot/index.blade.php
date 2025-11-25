<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Chatbot - Batik Wistara</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f0f2f5; }
        .chat-container { max-width: 800px; margin: 20px auto; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); height: 90vh; display: flex; flex-direction: column; }
        .chat-header { padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px 12px 0 0; }
        .chat-header h2 { margin-bottom: 5px; }
        .chat-header p { opacity: 0.9; font-size: 14px; }
        .status { padding: 10px 20px; background: #f8f9fa; border-bottom: 1px solid #e9ecef; font-size: 13px; color: #666; }
        .status.connected { background: #d4edda; color: #155724; }
        .status.error { background: #f8d7da; color: #721c24; }
        .chat-messages { flex: 1; padding: 20px; overflow-y: auto; background: #fafafa; }
        .message { margin-bottom: 16px; display: flex; animation: slideIn 0.3s ease; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .message.user { justify-content: flex-end; }
        .message-content { max-width: 70%; padding: 12px 16px; border-radius: 18px; line-height: 1.4; word-wrap: break-word; }
        .message.user .message-content { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-bottom-right-radius: 4px; }
        .message.bot .message-content { background: white; color: #333; box-shadow: 0 1px 2px rgba(0,0,0,0.1); border-bottom-left-radius: 4px; }
        .chat-input { padding: 20px; border-top: 1px solid #e9ecef; display: flex; gap: 10px; background: white; border-radius: 0 0 12px 12px; }
        .chat-input input { flex: 1; padding: 12px 16px; border: 2px solid #e9ecef; border-radius: 24px; font-size: 15px; outline: none; }
        .chat-input input:focus { border-color: #667eea; }
        .chat-input button { padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 24px; cursor: pointer; font-weight: 600; }
        .chat-input button:hover { transform: scale(1.05); }
        .chat-input button:disabled { background: #ccc; cursor: not-allowed; }
        .loading { text-align: center; padding: 12px; color: #666; font-size: 14px; }
    </style>
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
                    👋 Selamat datang di Batik Wistara! Saya adalah AI assistant yang siap membantu Anda. Ada yang bisa saya bantu?
                </div>
            </div>
        </div>

        <div class="chat-input">
            <input type="text" id="messageInput" placeholder="Ketik pesan Anda..." autocomplete="off">
            <button onclick="sendMessage()" id="sendButton">Kirim</button>
        </div>
    </div>

    <script>
        const messagesDiv = document.getElementById('chatMessages');
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');
        const statusDiv = document.getElementById('status');

        checkConnection();

        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') sendMessage();
        });

        async function checkConnection() {
            try {
                const response = await fetch('/chatbot/test');
                const data = await response.json();

                if (data.success) {
                    statusDiv.className = 'status connected';
                    statusDiv.textContent = '✅ Terhubung ke Groq AI';
                } else {
                    statusDiv.className = 'status error';
                    statusDiv.textContent = '❌ Error: ' + data.error;
                }
            } catch (error) {
                statusDiv.className = 'status error';
                statusDiv.textContent = '❌ Gagal terhubung: ' + error.message;
            }
        }

        function addMessage(content, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user' : 'bot'}`;
            messageDiv.innerHTML = `<div class="message-content">${content}</div>`;
            messagesDiv.appendChild(messageDiv);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        function showLoading() {
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'loading';
            loadingDiv.id = 'loading';
            loadingDiv.textContent = '⏳ AI sedang berpikir...';
            messagesDiv.appendChild(loadingDiv);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        function hideLoading() {
            const loading = document.getElementById('loading');
            if (loading) loading.remove();
        }

        async function sendMessage() {
            const message = messageInput.value.trim();
            if (!message) return;

            messageInput.disabled = true;
            sendButton.disabled = true;

            addMessage(message, true);
            messageInput.value = '';
            showLoading();

            try {
                const response = await fetch('/chatbot/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message })
                });

                const data = await response.json();
                hideLoading();

                if (data.success) {
                    addMessage(data.response);
                } else {
                    addMessage('❌ Error: ' + (data.error || 'Terjadi kesalahan'));
                }
            } catch (error) {
                hideLoading();
                addMessage('❌ Gagal menghubungi server: ' + error.message);
            }

            messageInput.disabled = false;
            sendButton.disabled = false;
            messageInput.focus();
        }
    </script>
</body>
</html>
