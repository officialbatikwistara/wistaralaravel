@include('inc.header')

<div class="register-page d-flex align-items-center justify-content-center">
  <!-- Overlay -->
  <div class="register-overlay"></div>

  <!-- Box Register -->
  <div class="register-box bg-white shadow rounded p-4 p-md-5">
    <h2 class="text-center fw-bold mb-4 font-caslon">Daftar Akun</h2>

    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('user.register.post') }}">
      @csrf
      <!-- Nama -->
      <div class="mb-3">
        <label for="name" class="form-label fw-semibold text-dark">Nama Lengkap</label>
        <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan nama lengkap" required>
      </div>

      <!-- Email -->
      <div class="mb-3">
        <label for="email" class="form-label fw-semibold text-dark">Email</label>
        <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan email" required>
      </div>

      <!-- Nomor Telepon -->
      <div class="mb-3">
        <label for="phone" class="form-label fw-semibold text-dark">Nomor Telepon</label>
        <input type="text" name="phone" id="phone" class="form-control" placeholder="Masukkan nomor telepon" required>
      </div>

      <!-- Password -->
      <div class="mb-3">
        <label for="password" class="form-label fw-semibold text-dark">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
        <small class="text-muted">
          Minimal 8 karakter, harus mengandung huruf besar, huruf kecil, angka, dan simbol.
        </small>
        <div id="passwordError" class="text-danger small mt-1"></div>
      </div>

      <!-- Konfirmasi Password -->
      <div class="mb-3">
        <label for="password_confirmation" class="form-label fw-semibold text-dark">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password" required>
      </div>

      <!-- Tombol -->
      <div class="d-grid mb-3">
        <button type="submit" class="btn btn-dark py-2 fw-semibold font-caslon">
          Daftar Sekarang
        </button>
      </div>

      <!-- Link Login -->
      <div class="text-center">
        <small class="text-dark">Sudah punya akun?
          <a href="{{ url('/login') }}" class="text-decoration-none fw-semibold">Login di sini</a>
        </small>
      </div>
    </form>
  </div>
</div>

<script>
  const passwordInput = document.getElementById('password');
  const passwordError = document.getElementById('passwordError');

  passwordInput.addEventListener('input', function () {
    const val = this.value;
    const valid = /[a-z]/.test(val) &&
                  /[A-Z]/.test(val) &&
                  /[0-9]/.test(val) &&
                  /[@$!%*#?&]/.test(val) &&
                  val.length >= 8;

    if (!valid) {
      passwordError.textContent = "Password belum memenuhi syarat keamanan.";
      this.classList.add('is-invalid');
      this.classList.remove('is-valid');
    } else {
      passwordError.textContent = "";
      this.classList.remove('is-invalid');
      this.classList.add('is-valid');
    }
  });
</script>

@include('inc.footer')
