{{-- resources/views/berita.blade.php --}}
@include('inc.header')

<!-- ===== Page Header / Banner Section ===== -->
<section class="page-header d-flex align-items-center justify-content-center">
  <div class="overlay"></div>
  <div class="container position-relative text-center">
    <h1 class="fw-bold page-title">Berita</h1>
  </div>
</section>

<!-- ================= HALAMAN BERITA ================= -->
<section class="section-berita py-5 bg-light">
  <div class="container-berita container">
    <!-- Judul -->
    <div class="text-center mb-4" data-aos="fade-up" data-aos-duration="1000">
      <h2 class="berita-title">Berita Terkini Wistara Batik</h2>
      <p class="text-muted mb-3">Lihat informasi dan kegiatan terbaru dari Batik Wistara</p>
      <hr class="berita-divider mx-auto">
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('berita') }}" 
          class="row gy-2 gx-3 align-items-center justify-content-center mb-5" 
          data-aos="fade-up" data-aos-delay="100">

      <div class="col-12 col-sm-8 col-md-5">
        <div class="input-group shadow-sm">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input type="text" name="search" class="form-control" placeholder="Cari berita..." value="{{ $search }}">
        </div>
      </div>

      <div class="col-6 col-sm-3 col-md-auto">
        <select name="limit" class="form-select shadow-sm" onchange="this.form.submit()">
          <option value="8"  {{ $limit == 8 ? 'selected' : '' }}>8</option>
          <option value="20" {{ $limit == 20 ? 'selected' : '' }}>20</option>
          <option value="50" {{ $limit == 50 ? 'selected' : '' }}>50</option>
        </select>
      </div>

      <div class="col-6 col-sm-3 col-md-auto">
        <button type="submit" class="btn btn-dark w-100 d-flex align-items-center justify-content-center gap-1 shadow-sm">
          <i class="bi bi-funnel-fill"></i>
          <span>Cari</span>
        </button>
      </div>
    </form>

    <!-- Grid Berita -->
    <div class="berita-grid">
      @foreach($berita as $index => $b)
        <div class="berita-card"
             data-aos="fade-up"
             data-aos-duration="1000"
             data-aos-delay="{{ $index * 150 }}">
             
          <!-- Gambar + Sumber Overlay -->
          <div class="berita-img-wrapper position-relative">
            @if(filter_var($b->gambar, FILTER_VALIDATE_URL))
              <img src="{{ $b->gambar }}" alt="{{ $b->judul }}">
            @else
              <img src="{{ asset($b->gambar) }}" alt="{{ $b->judul }}">
            @endif

            @if(!empty($b->sumber))
              <div class="berita-sumber-overlay">
                Sumber:
                @if(!empty($b->tautan_sumber))
                  <a href="{{ $b->tautan_sumber }}" target="_blank">{{ $b->sumber }}</a>
                @else
                  {{ $b->sumber }}
                @endif
              </div>
            @endif
          </div>

          <!-- Judul & Konten Singkat -->
          <h3 class="berita-judul mt-3">{{ $b->judul }}</h3>
          <p class="berita-deskripsi">{{ Str::limit(strip_tags($b->konten), 150) }}</p>

          <!-- Tanggal -->
          <p class="berita-tanggal text-muted mb-2">
            <small>{{ \Carbon\Carbon::parse($b->tanggal)->format('d M Y') }}</small>
          </p>

          <!-- Link -->
          @if(!empty($b->tautan_sumber))
            <a href="{{ $b->tautan_sumber }}" target="_blank" class="berita-link">
              Baca Selengkapnya →
            </a>
          @else
            <a href="{{ route('berita.detail', $b->slug) }}" class="berita-link">
              Baca Selengkapnya →
            </a>
          @endif
        </div>
      @endforeach
    </div>

    <!-- Pagination -->
    @if ($berita->hasPages())
      <div class="mt-5 d-flex justify-content-center">
        {{ $berita->links('pagination::bootstrap-5') }}
      </div>
    @endif
  </div>
</section>

@include('inc.footer')
