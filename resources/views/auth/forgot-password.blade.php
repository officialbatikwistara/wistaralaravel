@include('inc.header')

<div class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
  <div class="bg-white shadow rounded p-4 p-md-5" style="max-width: 420px; width: 100%;">
    <h2 class="text-center fw-bold mb-4" style="font-family: 'Libre Caslon Text', serif;">Reset Password</h2>

    @if (session('status'))
      <div class="alert alert-success alert-dismissible fade show">
        <i class="fa-solid fa-circle-check me-2"></i>
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf

      <div class="mb-3">
        <label for="email" class="form-label fw-semibold">Email Anda</label>
        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email terdaftar" required>
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="d-grid mb-3">
        <button type="submit" class="btn btn-dark py-2 fw-semibold" style="font-family: 'Libre Caslon Text', serif;">
          Kirim Link Reset
        </button>
      </div>

      <div class="text-center">
        <small>
          <a href="{{ url('/login') }}" class="text-decoration-none fw-semibold">Kembali ke Login</a>
        </small>
      </div>
    </form>
  </div>
</div>

@include('inc.footer')
