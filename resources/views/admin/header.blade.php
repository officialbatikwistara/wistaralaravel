<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel - @yield('title')</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
    body {
      background-color: #f5f7fa;
      font-family: 'Poppins', sans-serif;
    }

    /* Navbar Styling */
    .navbar-admin {
      background: linear-gradient(90deg, #1c1f24, #343a40);
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
      padding: 0.75rem 1.5rem;
    }

    .navbar-admin .navbar-brand {
      color: #f8f9fa;
      font-weight: 700;
      font-size: 1.3rem;
      letter-spacing: 0.5px;
      transition: color 0.3s ease;
    }

    .navbar-admin .navbar-brand:hover {
      color: #ffc107;
    }

    .navbar-admin .nav-link {
      color: #e9ecef;
      font-weight: 500;
      transition: color 0.3s ease, transform 0.2s ease;
    }

    .navbar-admin .nav-link:hover {
      color: #ffc107;
      transform: translateY(-2px);
    }

    .navbar-admin .dropdown-toggle::after {
      display: none;
    }

    .navbar-admin .dropdown-menu {
      border-radius: 10px;
      border: none;
      box-shadow: 0 5px 15px rgba(0,0,0,0.15);
      padding: 0.5rem;
    }

    .navbar-admin .dropdown-item {
      border-radius: 8px;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .navbar-admin .dropdown-item:hover {
      background-color: #212529;
      color: #ffc107;
    }

    .navbar-admin .btn-logout {
      background-color: #dc3545;
      border: none;
      border-radius: 10px;
      padding: 0.4rem 1rem;
      color: white;
      font-weight: 500;
      transition: background 0.3s ease;
    }

    .navbar-admin .btn-logout:hover {
      background-color: #bb2d3b;
    }

    /* Branding Icon */
    .navbar-brand i {
      color: #ffc107;
      font-size: 1.5rem;
      margin-right: 0.4rem;
    }

    /* Mobile Toggler */
    .navbar-toggler {
      border: none;
    }
    .navbar-toggler:focus {
      box-shadow: none;
    }

  </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-admin">
  <div class="container-fluid">

    <!-- Brand -->
    <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
      <i class="fa-solid fa-chart-line"></i> Admin Panel
    </a>

    <!-- Toggle for mobile -->
    <button class="navbar-toggler text-light" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
      <i class="fa-solid fa-bars"></i>
    </button>

    <!-- Menu -->
    <div class="collapse navbar-collapse justify-content-end" id="adminNavbar">
      <ul class="navbar-nav align-items-center gap-2">
        <li class="nav-item">
          <a class="nav-link" href="{{ url('/admin/produk') }}">
            <i class="fa-solid fa-shirt me-1"></i> Produk
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ url('/admin/pesanan') }}">
            <i class="fa-solid fa-box me-1"></i> Pesanan
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ url('/admin/berita') }}">
            <i class="fa-solid fa-newspaper me-1"></i> Berita
          </a>
        </li>

        <!-- Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
            <i class="fa-solid fa-user-shield me-1"></i> {{ session('admin_name') ?? 'Super Admin' }}
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}">
                <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>

  </div>
</nav>
