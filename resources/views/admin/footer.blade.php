<footer class="footer-admin text-center py-4 mt-5">
  <div class="container">
    <div class="row gy-3 align-items-center">
      <!-- Logo -->
      <div class="col-12 col-md-4">
        <img src="{{ asset('img/logoputih.png') }}" alt="Batik Wistara" height="70" class="mb-2">
        <p class="mb-0 text-white-50 small">Admin Batik Wistara</p>
      </div>
 <!-- Navigasi -->
      <div class="d-none d-md-block col-md-3">
        <h5 class="fw-bold mb-3">Navigasi</h5>
        <ul class="list-unstyled">
          <li><a href="{{ route('admin.kategori.index') }}" class="text-white text-decoration-none d-block">Kategori</a></li>
          <li><a href="{{ route('admin.produk.index') }}" class="text-white text-decoration-none d-block">Produk</a></li>
          <li><a href="{{ route('admin.berita.index') }}" class="text-white text-decoration-none d-block">Berita</a></li>
          <li><a href="{{ route('admin.pesanan.index') }}" class="text-white text-decoration-none d-block">Pesanan</a></li>
        </ul>
      </div>

      <!-- Kontak -->
      <div class="col-12 col-md-4">
        <h5 class="fw-bold mb-3">Kontak Kami</h5>
        <ul class="list-unstyled">
          <li class="mb-2">
            <strong>Alamat:</strong><br>
            <a href="https://maps.app.goo.gl/WqHPo5eNBDqHykhM8" target="_blank" class="text-dark text-decoration-none d-block">
              Jl. Tambak Medokan Ayu VI C No.56B, Surabaya, Jawa Timur 60295
            </a>
          </li>
          <li class="mb-2">
            <strong>WhatsApp:</strong><br>
            <a href="https://wa.me/6281234567890" class="text-dark text-decoration-none d-block">
              0812-3456-7890
            </a>
          </li>
          <li>
            <strong>Email:</strong><br>
            <a href="mailto:official.batikwistara@gmail.com" class="text-dark text-decoration-none d-block">
              official.batikwistara@gmail.com
            </a>
          </li>
        </ul>
      </div>

    </div>

    <hr class="border-secondary my-3 opacity-50">

    <p class="mb-1 fw-semibold text-white small">
      © {{ date('Y') }} Admin Batik Wistara
    </p>
    <small class="text-white-50">
      Dibuat dengan <i class="fa-solid fa-heart text-danger"></i> oleh Tim Developer Batik Wistara
    </small>
  </div>
</footer>

<!-- Script Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
  .footer-admin {
    background: linear-gradient(135deg, #071739, #1b2a4a);
    color: #ffffff;
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
    box-shadow: 0 -2px 10px rgb(255, 255, 255);
  }

  .footer-admin a:hover {
    color: #f6b400 !important;
  }

  .footer-admin i {
    animation: pulse 1.5s infinite;
  }

  @keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.8; }
    50% { transform: scale(1.2); opacity: 1; }
  }
</style>
</body>
</html>
