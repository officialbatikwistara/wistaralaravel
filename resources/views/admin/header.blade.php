<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Batik Wistara Admin</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;700&family=Libre+Caslon+Text:wght@400;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/header.css') }}">
</head>

<body>
    <!-- ================= ADMIN NAVBAR ================= -->
    <nav id="navbarAdmin" class="navbar navbar-dark navbar-expand-lg position-fixed top-0 start-0 w-100" style="z-index: 1000;">
        <div class="container">

            <!-- Logo -->
            <a class="navbar-brand me-auto" href="{{ url('/admin/dashboard') }}">
                <img src="{{ asset('img/logoputih.png') }}" alt="Batik Wistara" class="logo-putih" height="50">
                <img src="{{ asset('img/logowarna.png') }}" alt="Batik Wistara" class="logo-warna" height="50">
            </a>

            <!-- Toggler -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar"
                aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu Tengah -->
            <div class="collapse navbar-collapse justify-content-center" id="adminNavbar">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="{{ url('/admin/dashboard') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/kategori*') ? 'active' : '' }}" href="{{ url('/admin/kategori') }}">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/produk*') ? 'active' : '' }}" href="{{ url('/admin/produk') }}">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/berita*') ? 'active' : '' }}" href="{{ url('/admin/berita') }}">Berita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/reviews*') ? 'active' : '' }}" href="{{ url('/admin/reviews') }}">Review</a>
                    </li>
                </ul>
            </div>

            <!-- Bagian Kanan -->
            <div class="d-flex align-items-center gap-3" id="adminRightNavbar">

                <!-- Orders/Cart -->
                <a href="{{ url('/admin/pesanan') }}" class="nav-link text-white position-relative p-0 d-inline-flex align-items-center">
                    <i class="fa-solid fa-cart-shopping fa-lg"></i>
                    @if(isset($jumlahPesanan) && $jumlahPesanan > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            style="font-size: 0.7rem; min-width: 20px; padding: 4px 6px;">
                            {{ $jumlahPesanan }}
                            <span class="visually-hidden">orders</span>
                        </span>
                    @endif
                </a>

                <!-- Admin Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center px-3 py-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user me-2"></i>
                        {{ Str::limit(session('admin_name') ?? 'Super Admin', 12) }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li>
                            <a class="dropdown-item text-danger d-flex align-items-center" href="{{ route('admin.logout') }}">
                                <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </nav>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const navbar = document.getElementById("navbarAdmin");
            const navbarNav = document.getElementById("adminNavbar");
            const rightIcons = document.getElementById("adminRightNavbar");

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
</body>

</html>
