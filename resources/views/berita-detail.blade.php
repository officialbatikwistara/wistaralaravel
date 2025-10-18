@include('inc.header')

<!-- 🟡 Hero Header -->
<section class="page-header d-flex align-items-center justify-content-center text-center"
         style="background: linear-gradient(rgba(0,0,0,0.45), rgba(0, 0, 0, 0.71)), url('{{ asset('img/bghero.svg') }}') center/cover no-repeat;">
  <div class="container position-relative">
    <h1 class="fw-bold page-title text-white display-5">{{ $berita->judul }}</h1>
  </div>
</section>


<!-- 📰 Konten Berita -->
<section class="py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">

        <!-- Gambar Berita -->
@php
  $fileName = basename($berita->gambar);
  $gambarPath = public_path('uploads/berita/'.$fileName);
  $gambar = (file_exists($gambarPath) && $fileName)
      ? asset('uploads/berita/'.$fileName)
      : asset('img/no-image.jpg');
@endphp


        <div class="mb-4 text-center">
          <img src="{{ $gambar }}" alt="{{ $berita->judul }}" class="img-fluid rounded shadow-lg w-100" style="max-height: 450px; object-fit: cover;">
        </div>

        <!-- Tanggal -->
        <div class="d-flex align-items-center justify-content-center text-muted small mb-4">
          <i class="fa-regular fa-calendar me-2"></i>
          <span>{{ \Carbon\Carbon::parse($berita->tanggal)->translatedFormat('d F Y') }}</span>
        </div>

        <!-- Isi Berita -->
        <div class="berita-isi text-justify fs-5 lh-lg" style="color:#333;">
          {!! nl2br(e($berita->konten)) !!}
        </div>

      </div>
    </div>
  </div>
</section>

@include('inc.footer')
