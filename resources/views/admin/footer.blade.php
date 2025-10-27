<footer class="footer-admin text-center text-md-start py-4 mt-5">
  <div class="container">
    <div class="row gy-3 align-items-start">
      <!-- Logo -->
      <div class="col-12 col-md-4 text-md-start">
        <img src="{{ asset('img/logoputih.png') }}" alt="Batik Wistara" height="70" class="mb-2">
        <p class="mb-0 text-white-50 small">Admin Batik Wistara</p>
      </div>

      <!-- Navigasi -->
      <div class="col-12 col-md-3 text-md-start">
        <h5 class="fw-bold mb-3 text-white">Navigasi</h5>
        <ul class="list-unstyled mb-0">
          <li><a href="{{ route('admin.kategori.index') }}" class="text-white text-decoration-none d-block mb-1">Kategori</a></li>
          <li><a href="{{ route('admin.produk.index') }}" class="text-white text-decoration-none d-block mb-1">Produk</a></li>
          <li><a href="{{ route('admin.berita.index') }}" class="text-white text-decoration-none d-block mb-1">Berita</a></li>
        </ul>
      </div>

      <!-- Kontak -->
      <div class="col-12 col-md-4 text-md-start">
        <h5 class="fw-bold mb-3 text-white">Kontak Kami</h5>
        <ul class="list-unstyled mb-0">
          <li class="mb-2">
            <strong class="text-white">Alamat:</strong><br>
            <a href="https://maps.app.goo.gl/WqHPo5eNBDqHykhM8" target="_blank" class="footer-contact-link d-block">
              Jl. Tambak Medokan Ayu VI C No.56B, Surabaya, Jawa Timur 60295
            </a>
          </li>
          <li class="mb-2">
            <strong class="text-white">WhatsApp:</strong><br>
            <a href="https://wa.me/6281234567890" class="footer-contact-link d-block">
              0812-3456-7890
            </a>
          </li>
          <li>
            <strong class="text-white">Email:</strong><br>
            <a href="mailto:official.batikwistara@gmail.com" class="footer-contact-link d-block">
              official.batikwistara@gmail.com
            </a>
          </li>
        </ul>
      </div>
    </div>

    <hr class="border-secondary my-3 opacity-50">

    <!-- Bagian bawah footer -->
    <div class="text-center small text-white-50 mt-3">
      Dibuat dengan <i class="bi bi-heart-fill text-danger"></i> oleh <strong>Batik Wistara Team</strong>
    </div>
  </div>
</footer>

<!-- Tambahkan link Bootstrap Icons di dalam <head> atau sebelum penutup </body> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
.footer-admin {
  background: linear-gradient(135deg, #071739, #1b2a4a);
  color: #ffffff;
  border-top-left-radius: 20px;
  border-top-right-radius: 20px;
  box-shadow: 0 -2px 10px rgba(255, 255, 255, 0.1);
}

.footer-admin a:hover {
  color: #f6b400 !important; /* Warna emas saat hover */
}

/* 🌙 Bagian Kontak Kami & Navigasi */
.footer-admin h5,
.footer-admin strong,
.footer-admin .footer-contact-link {
  color: #ffffff !important;
  text-decoration: none;
  transition: color 0.3s ease;
}

.footer-admin .footer-contact-link:hover,
.footer-admin .footer-contact-link:focus,
.footer-admin .footer-contact-link:active {
  color: #f6b400 !important; /* Warna emas saat hover */
}

/* Efek animasi hati ❤️ */
.footer-admin i.bi-heart-fill {
  display: inline-block; /* penting agar transform berfungsi */
  animation: pulse 1.5s infinite;
  transform-origin: center;
}

@keyframes pulse {
  0%, 100% {
    transform: scale(1);
    opacity: 0.8;
  }
  50% {
    transform: scale(1.3);
    opacity: 1;
  }
}
</style>
