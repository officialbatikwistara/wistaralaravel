@include('inc.header')


<div class="login-page d-flex align-items-center justify-content-center min-vh-100 bg-light">
  <div class="login-box bg-white shadow rounded p-4 p-md-5" style="max-width: 420px; width: 100%;">
    <h2 class="text-center fw-bold mb-4" style="font-family: 'Libre Caslon Text', serif;">Sign In</h2>

    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('user.login.post') }}">
      @csrf
      <!-- Email / No Telp -->
      <div class="mb-3">
        <label for="email" class="form-label fw-semibold">Email atau No. Telepon</label>
        <input type="text" name="email" id="email" class="form-control" placeholder="Masukkan email atau nomor telepon" required>
      </div>

      <!-- Password -->
      <div class="mb-3">
        <label for="password" class="form-label fw-semibold">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
      </div>

      <!-- Tombol Login -->
      <div class="d-grid mb-3">
        <button type="submit" class="btn btn-dark py-2 fw-semibold" style="font-family: 'Libre Caslon Text', serif;">
          Sign In
        </button>
      </div>

      <!-- Link Bawah -->
      <div class="text-center">
        <small>Belum punya akun?
          <a href="{{ url('/register') }}" class="text-decoration-none fw-semibold">Daftar di sini</a>
        </small>
      </div>
    </form>
  </div>
</div>

@include('inc.footer')
