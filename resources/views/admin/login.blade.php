<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
    body {
      background: linear-gradient(135deg, #1f1c2c, #928dab);
      font-family: 'Poppins', sans-serif;
      color: #fff;
    }

    .login-card {
      width: 380px;
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 20px;
      backdrop-filter: blur(15px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    .login-card h4 {
      color: #fff;
      font-weight: 700;
    }

    .form-control {
      background: rgba(255,255,255,0.15);
      border: none;
      color: #fff;
      border-radius: 10px;
    }

    .form-control::placeholder {
      color: rgba(255,255,255,0.7);
    }

    .form-control:focus {
      background: rgba(255,255,255,0.25);
      color: #fff;
      box-shadow: 0 0 0 2px #ffc107;
    }

    .btn-login {
      background: #ffc107;
      border: none;
      border-radius: 10px;
      font-weight: 600;
      transition: 0.3s;
      color: #000;
    }

    .btn-login:hover {
      background: #ffcf3c;
      transform: translateY(-2px);
    }

    .alert {
      font-size: 0.9rem;
      border-radius: 10px;
    }
  </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

  <div class="login-card p-4 text-center">
    <h4 class="mb-4"><i class="fa-solid fa-user-shield me-2 text-warning"></i>Login Admin</h4>

    @if(session('error'))
      <div class="alert alert-danger py-2">{{ session('error') }}</div>
    @endif
    @if(session('success'))
      <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login') }}">
      @csrf
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>
      <button class="btn btn-login w-100 py-2">
        <i class="fa-solid fa-right-to-bracket me-1"></i> Login
      </button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
