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

    <!-- Bagian Kanan -->
    <div class="d-flex align-items-center gap-3" id="rightNavbarIcons">
      <!-- 🛒 Ikon Keranjang -->
      @php
          $cartCount = 0;
          if (Auth::check()) {
              $cartCount = \App\Models\Cart::where('user_id', Auth::id())->sum('qty');
          }
      @endphp

      <a href="{{ url('/cart') }}" 
        class="nav-link text-white position-relative p-0 d-inline-flex align-items-center">
        <i class="fa-solid fa-cart-shopping fa-lg"></i>

        @if($cartCount > 0)
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                style="font-size: 0.7rem; min-width: 20px; padding: 4px 6px;">
            {{ $cartCount }}
            <span class="visually-hidden">item di keranjang</span>
          </span>
        @endif
      </a>

      <!-- 🔐 Tombol Sign In / Profil -->
      @auth
        <div class="dropdown">
          <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center px-3 py-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-user me-2"></i>
            {{ Str::limit(Auth::user()->name, 12) }}
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow">
            <li>
              <a class="dropdown-item" href="{{ url('/user/dashboard') }}">
                <i class="fa-solid fa-user-circle me-2"></i> Profil
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form action="{{ route('user.logout') }}" method="GET" class="m-0">
                @csrf
                <button type="submit" class="dropdown-item text-danger">
                  <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
              </form>
            </li>
          </ul>
        </div>
      @else
        <a class="btn btn-outline-light ms-2 px-3 py-1 btn-signin" href="{{ url('/login') }}" title="Sign In">
          <i class="fa-solid fa-right-to-bracket me-2"></i> Sign In
        </a>
      @endauth
    </div>
    
  </div>
</nav>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const navbar = document.getElementById("navbarWistara");
    const navbarNav = document.getElementById("navbarNav");
    const rightIcons = document.getElementById("rightNavbarIcons");

    // Efek transparansi saat scroll
    window.addEventListener("scroll", () => {
      navbar.classList.toggle("scrolled", window.scrollY > 50);
    });

    // Saat dropdown dibuka → tambahkan class 'menu-open'
    navbarNav.addEventListener('show.bs.collapse', () => {
      navbar.classList.add("menu-open");
      rightIcons.style.display = 'none';
    });

    // Saat dropdown ditutup → hilangkan class 'menu-open'
    navbarNav.addEventListener('hide.bs.collapse', () => {
      navbar.classList.remove("menu-open");
      rightIcons.style.display = 'flex';
    });
  });
</script>
