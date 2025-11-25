<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Perbandingan: Chatbot Biasa vs AI</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1400px; margin: 0 auto; }
        h1 { text-align: center; margin-bottom: 30px; color: #333; }
        .comparison { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .chatbot { background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
        .header { padding: 20px; color: white; font-weight: bold; }
        .header.basic { background: #dc3545; }
        .header.ai { background: #28a745; }
        .messages { height: 400px; padding: 20px; overflow-y: auto; background: #fafafa; }
        .message { margin-bottom: 15px; padding: 10px 15px; border-radius: 10px; max-width: 80%; }
        .message.user { background: #e3f2fd; margin-left: auto; text-align: right; }
        .message.bot { background: white; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .input-area { padding: 20px; border-top: 1px solid #ddd; }
        .input-group { display: flex; gap: 10px; }
        input { flex: 1; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 15px; }
        button { padding: 12px 24px; background: #007bff; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; }
        button:hover { background: #0056b3; }
        .legend { text-align: center; margin-bottom: 20px; padding: 15px; background: white; border-radius: 8px; }
        .legend span { display: inline-block; margin: 0 15px; }
        .legend .basic-label { color: #dc3545; font-weight: bold; }
        .legend .ai-label { color: #28a745; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🆚 Perbandingan: Chatbot Biasa vs AI-Powered (Groq)</h1>

        <div class="legend">
            <span class="basic-label">🔴 TANPA AI (Rule-based)</span>
            <span>—</span>
            <span class="ai-label">🟢 DENGAN AI (Groq Llama 3.3)</span>
        </div>

        <div class="comparison">
            <!-- Chatbot Biasa -->
            <div class="chatbot">
                <div class="header basic">
                    🔴 Chatbot Biasa (TANPA AI)
                </div>
                <div class="messages" id="basicMessages">
                    <div class="message bot">
                        ❌ Saya hanya bisa jawab pertanyaan yang sudah di-program:<br>
                        halo, hai, apa kabar, siapa kamu, terima kasih, bye
                    </div>
                </div>
            </div>

            <!-- Chatbot AI -->
            <div class="chatbot">
                <div class="header ai">
                    🟢 Chatbot AI (DENGAN Groq)
                </div>
                <div class="messages" id="aiMessages">
                    <div class="message bot">
                        ✅ Saya bisa jawab APAPUN! Tanya apa saja, saya akan coba bantu.
                    </div>
                </div>
            </div>
        </div>

        <div class="input-area">
            <div class="input-group">
                <input type="text" id="messageInput" placeholder="Coba tanya apapun... (contoh: 'Jelaskan Laravel', 'Berapa 5+3?', 'Siapa presiden Indonesia?')" autocomplete="off">
                <button onclick="sendMessage()">Kirim ke KEDUA Chatbot</button>
            </div>
        </div>
    </div>

    <script>
        const basicMessagesDiv = document.getElementById('basicMessages');
        const aiMessagesDiv = document.getElementById('aiMessages');
        const messageInput = document.getElementById('messageInput');

        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') sendMessage();
        });

        function addMessage(container, content, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user' : 'bot'}`;
            messageDiv.textContent = content;
            container.appendChild(messageDiv);
            container.scrollTop = container.scrollHeight;
        }

        async function sendMessage() {
            const message = messageInput.value.trim();
            if (!message) return;

            // Add user message to both
            addMessage(basicMessagesDiv, message, true);
            addMessage(aiMessagesDiv, message, true);
            messageInput.value = '';

            // Add loading
            const basicLoading = document.createElement('div');
            basicLoading.className = 'message bot';
            basicLoading.textContent = '⏳ Berpikir...';
            basicLoading.id = 'basicLoading';
            basicMessagesDiv.appendChild(basicLoading);

            const aiLoading = document.createElement('div');
            aiLoading.className = 'message bot';
            aiLoading.textContent = '⏳ AI sedang berpikir...';
            aiLoading.id = 'aiLoading';
            aiMessagesDiv.appendChild(aiLoading);

            try {
                const response = await fetch('/comparison/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message })
                });

                const data = await response.json();

                // Remove loading
                document.getElementById('basicLoading').remove();
                document.getElementById('aiLoading').remove();

                // Add responses
                addMessage(basicMessagesDiv, '❌ ' + data.basic);
                addMessage(aiMessagesDiv, '✅ ' + data.ai);

            } catch (error) {
                document.getElementById('basicLoading').remove();
                document.getElementById('aiLoading').remove();
                addMessage(basicMessagesDiv, '❌ Error: ' + error.message);
                addMessage(aiMessagesDiv, '❌ Error: ' + error.message);
            }

            messageInput.focus();
        }

        // Auto-suggest questions
        const suggestions = [
            "Halo, apa kabar?",
            "Jelaskan tentang Laravel",
            "Berapa 5 + 3?",
            "Siapa presiden Indonesia?",
            "Buatkan kode PHP untuk upload file",
            "Apa perbedaan MySQL dan PostgreSQL?",
            "Bagaimana cara membuat API di Laravel?",
        ];

        console.log('💡 Coba tanya:', suggestions);
    </script>
</body>
</html>
