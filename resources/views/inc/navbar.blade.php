<!-- ================= NAVBAR ================= -->
<nav id="navbarWistara" class="navbar navbar-dark navbar-expand-lg position-fixed top-0 start-0 w-100" style="z-index: 1000;">
  <div class="container">

    <!-- Logo -->
    <a class="navbar-brand me-auto" href="{{ url('/') }}">
      <img src="{{ asset('img/logoputih.png') }}" alt="Batik Wistara" class="logo-putih" height="60">
      <img src="{{ asset('img/logowarna.png') }}" alt="Batik Wistara" class="logo-warna" height="60">
    </a>

    <!-- Toggler -->
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu Tengah -->
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item">
          <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Beranda</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('tentang') ? 'active' : '' }}" href="{{ url('/tentang') }}">Tentang</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('berita') ? 'active' : '' }}" href="{{ url('/berita') }}">Berita</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('katalog') ? 'active' : '' }}" href="{{ url('/katalog') }}">Katalog</a>
        </li>
      </ul>
    </div>

    <!-- Ikon Keranjang & Admin -->
    <div class="d-flex align-items-center gap-3">
      <!-- Keranjang -->
      <a href="{{ url('/cart') }}" class="position-relative text-white nav-link p-0">
        <i class="fa-solid fa-cart-shopping fa-lg"></i>
        @if(session('cart_count') && session('cart_count') > 0)
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ session('cart_count') }}
          </span>
        @endif
      </a>

      <!-- Sign In -->
      <a class="btn btn-outline-light ms-2 px-3 py-1 btn-signin" href="{{ url('/login') }}" title="Sign In">
        Sign In
      </a>

  </div>
</nav>

<!-- JS SCROLL NAVBAR -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const navbar = document.getElementById("navbarWistara");
    if (!navbar) return;

    window.addEventListener("scroll", function () {
      navbar.classList.toggle("scrolled", window.scrollY > 50);
    });
  });
</script>
