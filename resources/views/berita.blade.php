
@include('inc.header')

<!-- ===== Page Header / Banner Section ===== -->
<section class="page-header d-flex align-items-center justify-content-center">
  <div class="overlay"></div>
  <div class="container position-relative text-center">
    <h1 class="fw-bold page-title">Berita</h1>
  </div>
</section>

<section class="section-berita py-5 bg-light">
  <div class="container">

    <h2 class="text-center text-dark mb-2">Berita Terkini Wistara Batik</h2>
    <p class="text-center text-muted mb-4">Lihat informasi dan kegiatan terbaru dari Batik Wistara</p>
    <hr class="w-25 mx-auto mb-4">

    <!-- Filter Form -->
    <form method="GET" action="{{ route('berita') }}" class="row gy-2 gx-3 align-items-center justify-content-center mb-5">
      <div class="col-12 col-sm-8 col-md-5">
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input type="text" name="search" class="form-control" placeholder="Cari berita..." value="{{ $search }}">
        </div>
      </div>

      <div class="col-6 col-sm-3 col-md-auto">
        <select name="limit" class="form-select" onchange="this.form.submit()">
          <option value="8"  {{ $limit == 8 ? 'selected' : '' }}>8</option>
          <option value="20" {{ $limit == 20 ? 'selected' : '' }}>20</option>
          <option value="50" {{ $limit == 50 ? 'selected' : '' }}>50</option>
        </select>
      </div>

      <div class="col-6 col-sm-3 col-md-auto">
        <button type="submit" class="btn btn-dark w-100 d-flex align-items-center justify-content-center gap-1">
          <i class="bi bi-funnel-fill"></i>
          <span>Cari</span>
        </button>
      </div>
    </form>

    <!-- Grid Berita -->
    <div class="row g-4">
      @forelse($berita as $b)
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="card h-100 shadow-sm border-0 berita-card">
            <div class="position-relative">
              @php
                $gambar = filter_var($b->gambar, FILTER_VALIDATE_URL)
                    ? $b->gambar
                    : asset('uploads/berita/'.$b->gambar);
              @endphp
              <img src="{{ $gambar }}" class="card-img-top" alt="{{ $b->judul }}" style="height: 220px; object-fit: cover;">

              @if(!empty($b->sumber))
                <small class="position-absolute bottom-0 end-0 m-2 bg-white bg-opacity-75 px-2 py-1 rounded text-muted">
                  Sumber: {{ $b->sumber }}
                </small>
              @endif
            </div>

            <div class="card-body d-flex flex-column">
              <h5 class="card-title">{{ $b->judul }}</h5>
              <p class="card-text small text-muted mb-2">{{ \Carbon\Carbon::parse($b->tanggal)->translatedFormat('d F Y') }}</p>
              <p class="card-text flex-grow-1">{{ Str::limit(strip_tags($b->deskripsi), 120) }}</p>

              @if(!empty($b->tautan_sumber))
                <a href="{{ $b->tautan_sumber }}" target="_blank" class="berita-link">Baca Selengkapnya →</a>
              @else
                <a href="{{ route('berita.detail', $b->slug) }}" class="berita-link">Baca Selengkapnya →</a>
              @endif
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center">
          <div class="alert alert-warning">Berita tidak ditemukan.</div>
        </div>
      @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-5 d-flex justify-content-center">
      {{ $berita->links() }}
    </div>

  </div>
</section>
@include('inc.footer')


