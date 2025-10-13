@include('inc.header')

<div class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
  <div class="bg-white shadow rounded p-4 p-md-5" style="max-width: 420px; width: 100%;">
    <h2 class="text-center fw-bold mb-4" style="font-family: 'Libre Caslon Text', serif;">Buat Password Baru</h2>

    <form method="POST" action="{{ route('password.update') }}">
      @csrf

      <input type="hidden" name="token" value="{{ $token }}">

      <div class="mb-3">
        <label for="email" class="form-label fw-semibold">Email</label>
        <input type="email" name="email" id="email" value="{{ request('email') }}" class="form-control @error('email') is-invalid @enderror" readonly>
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="password" class="form-label fw-semibold">Password Baru</label>
        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" required>
        @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password" required>
      </div>

      <div class="d-grid mb-3">
        <button type="submit" class="btn btn-dark py-2 fw-semibold" style="font-family: 'Libre Caslon Text', serif;">
          Simpan Password
        </button>
      </div>
    </form>
  </div>
</div>

@include('inc.footer')
