<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ $title ?? 'Batik Wistara' }}</title>

  <!-- Favicon -->
  <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;700&family=Libre+Caslon+Text:wght@400;700&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome & Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<!-- Trigger otomatis modal saat halaman dimuat -->
<div class="container">
    <div class="d-none">
        <button type="button" id="openVerifyModal" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#verifyModal">
            Buka Modal
        </button>
    </div>
</div>

<!-- Modal -->
<div class="modal fade show" id="verifyModal" tabindex="-1" aria-labelledby="verifyModalLabel" aria-modal="true" role="dialog" style="display: block; background-color: rgba(0,0,0,0.5);">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 shadow-lg border-0">

      {{-- ✅ Jika email sudah terverifikasi --}}
      @if (Auth::user() && Auth::user()->hasVerifiedEmail())
        <div class="modal-body text-center p-4">
          <div class="bg-success bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center mx-auto mb-4" style="width: 90px; height: 90px;">
            <i class="fa-solid fa-circle-check text-success fa-3x"></i>
          </div>
          <h4 class="fw-bold text-success mb-2">Email Berhasil Diverifikasi!</h4>
          <p class="text-muted mb-3">Anda akan diarahkan ke halaman login dalam <span id="countdown">5</span> detik...</p>
          <div class="spinner-border text-success mb-2" role="status"></div>
        </div>

        <script>
          document.addEventListener("DOMContentLoaded", () => {
            let seconds = 5;
            const countdown = document.getElementById('countdown');
            const interval = setInterval(() => {
              seconds--;
              countdown.textContent = seconds;
              if (seconds <= 0) {
                clearInterval(interval);
                window.location.href = "{{ url('/login') }}";
              }
            }, 1000);
          });
        </script>

      {{-- 📩 Jika email belum terverifikasi --}}
      @else
        <div class="modal-body text-center p-4">
          <div class="bg-warning bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center mx-auto mb-4" style="width: 90px; height: 90px;">
            <i class="fa-solid fa-envelope-circle-check text-warning fa-3x"></i>
          </div>
          <h4 class="fw-bold text-dark mb-2">Verifikasi Email Anda</h4>
          <p class="text-muted mb-3">
            Kami telah mengirimkan link verifikasi ke:<br>
            <strong>{{ Auth::user()->email }}</strong><br>
            Silakan cek inbox atau folder spam Anda.
          </p>

          @if (session('message'))
              <div class="alert alert-success small py-2 mb-3">
                  {{ session('message') }}
              </div>
          @endif

          <form method="POST" action="{{ route('verification.send') }}">
              @csrf
              <button type="submit" class="btn btn-dark w-100 py-2 mb-2">
                  Kirim Ulang Email Verifikasi
              </button>
          </form>
          <a href="{{ url('/logout-user') }}" class="text-decoration-none small text-secondary">
            Keluar & Ganti Akun
          </a>
        </div>
      @endif

    </div>
  </div>
</div>

<!-- Script untuk langsung tampil modal -->
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const modal = new bootstrap.Modal(document.getElementById('verifyModal'), {
      backdrop: 'static', // tidak bisa klik luar modal
      keyboard: false
    });
    modal.show();
  });
</script>