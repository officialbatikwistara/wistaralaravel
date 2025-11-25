# 🆓 AI Providers Gratis - Setup Guide

## 1️⃣ GROQ (RECOMMENDED - Paling Mudah & Cepat!)

### ✅ Kelebihan:
- **100% GRATIS** untuk personal use
- **Super cepat** (respon < 1 detik)
- **Generous free tier**: 30 req/min, 14,400 req/day
- Model bagus: Llama 3.3 70B, Mixtral 8x7B
- API compatible dengan OpenAI

### 📝 Cara Setup:
1. Daftar di: https://console.groq.com
2. Buat API Key (gratis, tanpa CC)
3. Copy API key

```bash
AI_AGENT_PROVIDER=groq
AI_AGENT_API_KEY=gsk_xxxxxxxxxxxxxxxxxxxxxxxx
AI_AGENT_MODEL=llama-3.3-70b-versatile
```

### 🎯 Model Available:
- `llama-3.3-70b-versatile` - Terbaru & terbaik! (recommended)
- `llama-3.1-70b-versatile` - Sangat bagus
- `mixtral-8x7b-32768` - Context panjang
- `gemma2-9b-it` - Ringan & cepat

---

## 2️⃣ GOOGLE GEMINI (Bagus untuk Bahasa Indonesia)

### ✅ Kelebihan:
- **Gratis** dengan quota besar
- **Excellent** untuk bahasa Indonesia
- 60 requests/minute (free tier)
- Multimodal (bisa gambar juga)

### 📝 Cara Setup:
1. Buka: https://aistudio.google.com/apikey
2. Login dengan Google Account
3. Klik "Get API Key" → Create API Key
4. Copy API key

```bash
AI_AGENT_PROVIDER=gemini
AI_AGENT_API_KEY=AIzaSyxxxxxxxxxxxxxxxxxxxxxxxxx
AI_AGENT_MODEL=gemini-2.0-flash-exp
```

### 🎯 Model Available:
- `gemini-2.0-flash-exp` - Terbaru, experimental (gratis)
- `gemini-1.5-flash` - Cepat & efisien
- `gemini-1.5-pro` - Paling pintar (limit lebih kecil)

---

## 3️⃣ OPENROUTER (Banyak Pilihan Model)

### ✅ Kelebihan:
- Akses ke **puluhan model AI**
- Beberapa model **gratis**
- Free tier: $0.10 credit per hari
- Bisa coba berbagai model

### 📝 Cara Setup:
1. Daftar di: https://openrouter.ai/keys
2. Dapatkan $1 free credit
3. Pilih model dengan tag "free"

```bash
AI_AGENT_PROVIDER=openrouter
AI_AGENT_API_KEY=sk-or-v1-xxxxxxxxxxxxxxxx
AI_AGENT_MODEL=meta-llama/llama-3.2-3b-instruct:free
```

### 🎯 Model Gratis:
- `meta-llama/llama-3.2-3b-instruct:free`
- `google/gemini-flash-1.5:free`
- `microsoft/phi-3-mini-128k-instruct:free`

---

## 4️⃣ OLLAMA (100% Gratis - Running Lokal)

### ✅ Kelebihan:
- **Sepenuhnya gratis** tanpa batas
- **Privacy terjaga** (data tidak keluar)
- **Offline** - tidak perlu internet
- Banyak pilihan model open source

### ⚠️ Kekurangan:
- Butuh komputer yang cukup kuat
- RAM minimal 8GB (16GB recommended)
- Storage ~4GB per model

### 📝 Cara Setup:

**Windows:**
1. Download: https://ollama.com/download/windows
2. Install Ollama
3. Buka CMD/PowerShell:
```bash
ollama run llama3.2
# Tunggu download model (~2GB)
# Ketik /bye untuk keluar
```

**Mac/Linux:**
```bash
curl -fsSL https://ollama.com/install.sh | sh
ollama run llama3.2
```

**Konfigurasi Laravel:**
```bash
AI_AGENT_PROVIDER=ollama
AI_AGENT_API_KEY=  # kosongkan
AI_AGENT_API_URL=http://localhost:11434/v1
AI_AGENT_MODEL=llama3.2
```

### 🎯 Model Recommended:
```bash
# Install model (pilih salah satu)
ollama pull llama3.2        # 2GB - Bagus & cepat
ollama pull mistral         # 4GB - Lebih pintar
ollama pull phi3           # 2GB - Ringan
ollama pull qwen2.5        # 4GB - Bagus untuk coding
```

### 🔧 Cek Status Ollama:
```bash
ollama list                 # List installed models
ollama ps                   # Running models
curl http://localhost:11434 # Test connection
```

---

## 📊 Perbandingan Provider

| Provider | Free Tier | Speed | Quality | Bahasa ID | Setup |
|----------|-----------|-------|---------|-----------|-------|
| **Groq** | ⭐⭐⭐⭐⭐ | ⚡⚡⚡⚡⚡ | ⭐⭐⭐⭐ | ⭐⭐⭐⭐ | Mudah |
| **Gemini** | ⭐⭐⭐⭐⭐ | ⚡⚡⚡⚡ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | Mudah |
| **OpenRouter** | ⭐⭐⭐ | ⚡⚡⚡ | ⭐⭐⭐ | ⭐⭐⭐ | Mudah |
| **Ollama** | ⭐⭐⭐⭐⭐ | ⚡⚡⚡ | ⭐⭐⭐⭐ | ⭐⭐⭐ | Sedang |

---

## 🎯 Rekomendasi Saya

### 🥇 TERBAIK: GROQ
**Kenapa?**
- Setup paling mudah (3 menit)
- Gratis tanpa CC
- Super cepat
- Model Llama 3.3 sangat bagus
- Free tier generous

**Cocok untuk:**
- Chatbot customer service
- Q&A system
- Development & testing

### 🥈 ALTERNATIF: GEMINI
**Kenapa?**
- Gratis dari Google
- Excellent untuk bahasa Indonesia
- Bagus untuk content generation

**Cocok untuk:**
- Aplikasi berbahasa Indonesia
- Content creator
- Multimodal (gambar + text)

### 🥉 UNTUK PRIVACY: OLLAMA
**Kenapa?**
- 100% private
- Offline capable
- Tidak ada limit

**Cocok untuk:**
- Data sensitif
- Tidak ada internet
- Budget unlimited

---

## 🚀 Quick Start (GROQ)

1. **Daftar Groq** (2 menit)
   