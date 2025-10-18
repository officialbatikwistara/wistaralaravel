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
      background-color: #f8f9fa;
    }
    .navbar-admin {
      background-color: #212529;
    }
    .navbar-admin .nav-link,
    .navbar-admin .navbar-brand {
      color: #fff;
    }
    .navbar-admin .nav-link.active,
    .navbar-admin .nav-link:hover {
      color: #ffc107;
    }
    .dropdown-menu {
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-admin shadow-sm">
  <div class="container-fluid px-4">

    <!-- Logo / Brand -->
    <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('admin.dashboard') }}">
      <i class="fa-solid me-2 text-warning"></i> Admin Panel
    </a>

    <!-- Toggle mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon text-light"></span>
    </button>

    <!-- Menu -->
    <div class="collapse navbar-collapse justify-content-end" id="adminNavbar">
      <ul class="navbar-nav mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-user-shield me-1"></i>
            {{ session('admin_name') ?? 'Admin' }}
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow">
            <li>
              <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}">
                <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
