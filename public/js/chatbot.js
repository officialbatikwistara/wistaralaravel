const messagesDiv = document.getElementById("chatMessages");
const messageInput = document.getElementById("messageInput");
const sendButton = document.getElementById("sendButton");
const statusDiv = document.getElementById("status");

checkConnection();

messageInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") sendMessage();
});

async function checkConnection() {
    try {
        const response = await fetch("/api/chatbot/test");
        const data = await response.json();

        if (data.success) {
            statusDiv.className = "status connected";
            statusDiv.textContent = "✅ Terhubung ke Groq AI";
        } else {
            statusDiv.className = "status error";
            statusDiv.textContent = "❌ Error: " + data.error;
        }
    } catch (error) {
        statusDiv.className = "status error";
        statusDiv.textContent = "❌ Gagal terhubung: " + error.message;
    }
}

function addMessage(content, isUser = false) {
    const messageDiv = document.createElement("div");
    messageDiv.className = `message ${isUser ? "user" : "bot"}`;
    messageDiv.innerHTML = `<div class="message-content">${content}</div>`;
    messagesDiv.appendChild(messageDiv);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

function showLoading() {
    const loadingDiv = document.createElement("div");
    loadingDiv.className = "loading";
    loadingDiv.id = "loading";
    loadingDiv.textContent = "⏳ AI sedang berpikir...";
    messagesDiv.appendChild(loadingDiv);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

function hideLoading() {
    const loading = document.getElementById("loading");
    if (loading) loading.remove();
}

async function sendMessage() {
    const message = messageInput.value.trim();
    if (!message) return;

    messageInput.disabled = true;
    sendButton.disabled = true;

    addMessage(message, true);
    messageInput.value = "";
    showLoading();

    try {
        const response = await fetch("/api/chatbot/chat", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ message }),
        });

        const data = await response.json();
        hideLoading();

        if (data.success) {
            addMessage(data.response);
        } else {
            addMessage("❌ Error: " + (data.error || "Terjadi kesalahan"));
        }
    } catch (error) {
        hideLoading();
        addMessage("❌ Gagal menghubungi server: " + error.message);
    }

    messageInput.disabled = false;
    sendButton.disabled = false;
    messageInput.focus();
}
