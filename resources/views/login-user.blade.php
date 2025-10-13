@include('inc.header')

<div class="login-page d-flex align-items-center justify-content-center min-vh-100 bg-light">
  <div class="login-box bg-white shadow rounded p-4 p-md-5" style="max-width: 420px; width: 100%;">
    <h2 class="text-center fw-bold mb-4" style="font-family: 'Libre Caslon Text', serif;">Sign In</h2>

    {{-- ✅ Alert Error / Success --}}
    @if (session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-exclamation me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <form method="POST" action="{{ route('user.login.post') }}">
      @csrf

      <!-- Email / No Telp -->
      <div class="mb-3">
        <label for="email" class="form-label fw-semibold">Email atau No. Telepon</label>
        <input 
          type="text" 
          name="email" 
          id="email" 
          class="form-control @error('email') is-invalid @enderror" 
          placeholder="Masukkan email atau nomor telepon" 
          value="{{ old('email') }}"
          required
        >
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Password -->
      <div class="mb-3 position-relative">
        <label for="password" class="form-label fw-semibold">Password</label>
        <div class="input-group">
          <input 
            type="password" 
            name="password" 
            id="password" 
            class="form-control @error('password') is-invalid @enderror" 
            placeholder="Masukkan password" 
            required
          >
          <button class="btn btn-outline-secondary toggle-password" type="button">
            <i class="fa fa-eye"></i>
          </button>
        </div>
        @error('password')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
      </div>

      <div class="text-center mb-3">
        <a href="{{ route('password.request') }}" class="text-decoration-none fw-semibold text-secondary">
          Lupa Password?
        </a>
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

<script>
  // 👁️ Toggle Password Visibility
  document.querySelector('.toggle-password').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');

    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
      passwordInput.type = 'password';
      icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
  });
</script>

@include('inc.footer')
