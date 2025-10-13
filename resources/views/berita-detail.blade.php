@extends('layouts.app')

@section('content')
<section class="page-header d-flex align-items-center justify-content-center">
  <div class="overlay"></div>
  <div class="container position-relative text-center">
    <h1 class="fw-bold page-title">{{ $berita->judul }}</h1>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        @php
          $gambar = filter_var($berita->gambar, FILTER_VALIDATE_URL)
              ? $berita->gambar
              : asset('uploads/berita/'.$berita->gambar);
        @endphp
        <img src="{{ $gambar }}" alt="{{ $berita->judul }}" class="img-fluid rounded mb-4">
        <p class="text-muted mb-2">{{ \Carbon\Carbon::parse($berita->tanggal)->translatedFormat('d F Y') }}</p>
        <div class="berita-isi">
          {!! nl2br(e($berita->deskripsi)) !!}
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
