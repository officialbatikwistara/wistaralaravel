<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow p-4" style="width: 350px;">
    <h4 class="text-center mb-3 fw-bold">Login Admin</h4>
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
      <button class="btn btn-dark w-100">Login</button>
    </form>
  </div>
</body>
</html>
