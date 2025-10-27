<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
    body {
      background: url("https://batikwistara.com/assets/img/background-batik.jpg") center/cover no-repeat;
      font-family: 'Poppins', sans-serif;
      color: #333;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .overlay {
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.4);
      z-index: 1;
    }

    .login-card {
      position: relative;
      z-index: 2;
      width: 400px;
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
      padding: 40px 35px;
    }

    .login-card h3 {
      font-weight: 700;
      color: #222;
      text-align: center;
      margin-bottom: 25px;
    }

    .form-control {
      border-radius: 10px;
      padding: 10px;
      border: 1px solid #ccc;
      background-color: #f5f7ff;
    }

    .form-control:focus {
      border-color: #000;
      box-shadow: none;
    }

    .btn-login {
      background-color: #000;
      color: #fff;
      font-weight: 600;
      border-radius: 10px;
      transition: 0.3s;
      width: 100%;
    }

    .btn-login:hover {
      background-color: #333;
    }
  </style>
</head>
<body>

  <div class="overlay"></div>

  <div class="login-card">
    <h3><i class="fa-solid fa-user-shield me-2"></i>Login Admin</h3>

    @if(session('error'))
      <div class="alert alert-danger py-2">{{ session('error') }}</div>
    @endif
    @if(session('success'))
      <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" placeholder="admin@gmail.com" required>
      </div>
      <div class="mb-4 position-relative">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
      </div>
      <button class="btn btn-login py-2">
        <i class="fa-solid fa-right-to-bracket me-1"></i> Sign In
      </button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
