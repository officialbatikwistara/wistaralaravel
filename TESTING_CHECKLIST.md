# 🧪 Manual Testing Checklist - Batik Wistara

## ✅ Core Pages

- [ ] **Homepage** (http://localhost:8000)
  - [ ] Logo tampil
  - [ ] Menu navigation berfungsi
  - [ ] Hero section tampil
  - [ ] Footer tampil

- [ ] **About Page** (/tentang)
  - [ ] Content tampil lengkap
  - [ ] Gambar load dengan baik

- [ ] **Products** (/produk)
  - [ ] Product list tampil
  - [ ] Filter/search berfungsi (jika ada)
  - [ ] Product detail bisa diklik

- [ ] **News** (/berita)
  - [ ] Berita list tampil
  - [ ] Pagination berfungsi (jika ada)

- [ ] **Contact** (/kontak)
  - [ ] Form contact tampil
  - [ ] Validasi form berfungsi

## ✅ Groq AI Chatbot

- [ ] **Web Chatbot** (http://localhost:8000/chatbot)
  - [ ] UI chatbot muncul
  - [ ] Status "✅ Terhubung ke Groq AI" tampil
  - [ ] Ketik "halo" → AI response muncul
  - [ ] Ketik "jelaskan batik tulis" → AI kasih penjelasan detail
  - [ ] Conversation history tersimpan
  - [ ] Button "Clear" berfungsi

## ✅ WhatsApp Bot (via Fonnte)

- [ ] Kirim "0" → Menu muncul
- [ ] Kirim "1" → Info katalog
- [ ] Kirim "jelaskan batik" → AI response detail
- [ ] Kirim "ada batik untuk pernikahan?" → AI kasih rekomendasi
- [ ] Response time < 3 detik

## ✅ Telegram Bot (Optional)

- [ ] /start → Welcome message
- [ ] /menu → Menu tampil
- [ ] "jelaskan batik cap" → AI response
- [ ] Markdown formatting berfungsi

## ✅ Performance

- [ ] Homepage load < 2 detik
- [ ] AI response < 3 detik
- [ ] No console errors
- [ ] Mobile responsive

## ✅ Groq AI Monitoring

- [ ] Buka https://console.groq.com/usage
- [ ] API calls bertambah setelah test
- [ ] Tokens used tercatat
- [ ] No rate limit errors

## 🐛 Known Issues

List any issues found:
1. 
2. 
3. 

## 📝 Notes

Additional observations:
-
