<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Batik Wistara Admin</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


    <style>
        /* ===========================
       ✨ Batik Wistara Admin Header (Full Width)
       =========================== */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            padding-top: 85px;
            /* Supaya konten gak ketutup navbar */
        }

        .navbar-admin {
            background-color: #071739;
            /* warna biru navy khas wistara */
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 1030;
        }

        .navbar-admin .navbar-brand img {
            height: 55px;
        }

        /* MENU NAVBAR */
        .navbar-admin .nav-link {
            color: white;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: color 0.3s ease;
        }

        .navbar-admin .nav-link:hover,
        .navbar-admin .nav-link.active {
            color: #f6b400;
            /* warna kuning wistara */
        }

        /* IKON TROLI */
        .icon-btn {
            color: white;
            font-size: 1.3rem;
            margin-right: 1.5rem;
            transition: color 0.3s ease;
            position: relative;
        }

        .icon-btn:hover {
            color: #f6b400;
        }

        .icon-btn .badge {
            position: absolute;
            top: -6px;
            right: -10px;
            font-size: 0.7rem;
            background-color: #f6b400;
            color: #071739;
        }

        /* DROPDOWN ADMIN */
        .dropdown-toggle {
            color: #071739;
            background-color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            padding: 8px 14px;
            transition: all 0.3s ease;
        }

        .dropdown-toggle:hover {
            background-color: #f6b400;
            color: #071739;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            padding: 0.5rem;
        }

        .dropdown-item:hover {
            background-color: #f6b400;
            color: #071739;
        }

        /* RESPONSIF */
        @media (max-width: 991px) {
            .navbar-collapse {
                background-color: #071739;
                padding: 1rem;
                border-radius: 10px;
            }

            .dropdown-toggle {
                background-color: #fff;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-admin">
        <div class="container-fluid">

            <!-- LOGO -->
            <a class="navbar-brand" href="{{ url('/admin/dashboard') }}">
                <img src="{{ asset('img/logoputih.png') }}" alt="Batik Wistara Logo">
            </a>

            <!-- TOGGLE MOBILE -->
            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse"
                data-bs-target="#adminNavbar">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- MENU TENGAH -->
            <div class="collapse navbar-collapse justify-content-center" id="adminNavbar">
                <ul class="navbar-nav gap-4">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/kategori') ? 'active' : '' }}"
                            href="{{ url('/admin/kategori') }}">
                            KATEGORI PRODUK
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/produk') ? 'active' : '' }}"
                            href="{{ url('/admin/produk') }}">
                            PRODUK
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/berita') ? 'active' : '' }}"
                            href="{{ url('/admin/berita') }}">
                            BERITA
                        </a>
                    </li>
                </ul>
            </div>

            <!-- KANAN -->
            <div class="d-flex align-items-center">

                <!-- IKON TROLI -->
                <a href="{{ url('/admin/pesanan') }}" class="icon-btn position-relative">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="badge">{{ $jumlahPesanan ?? 0 }}</span>
                </a>

                <!-- DROPDOWN ADMIN -->
                <div class="dropdown">
                    <a class="dropdown-toggle d-flex align-items-center" href="#" role="button"
                        data-bs-toggle="dropdown">
                        <i class="fa-solid fa-user me-2"></i>
                        {{ session('admin_name') ?? 'Super Admin' }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}">
                                <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
