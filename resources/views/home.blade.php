@include('inc.header')

<section class="hero-slider position-relative">
    @php
        $slides = glob(public_path('hero-slides/*'));
    @endphp

    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4500">

        <!-- Indicators -->
        <div class="carousel-indicators">
            @foreach ($slides as $key => $slide)
                <button type="button" data-bs-target="#heroCarousel" 
                        data-bs-slide-to="{{ $key }}" 
                        class="{{ $key == 0 ? 'active' : '' }}"></button>
            @endforeach
        </div>

        <!-- Slides -->
        <div class="carousel-inner">

            @foreach ($slides as $key => $slide)
                @php
                    $fileName = basename($slide);
                    $fileUrl = asset('hero-slides/' . $fileName);
                    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $isVideo = in_array($ext, ['mp4', 'webm', 'mov']);
                @endphp

                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    @if ($isVideo)
                        <video class="d-block w-100" autoplay muted loop playsinline>
                            <source src="{{ $fileUrl }}" type="video/{{ $ext }}">
                        </video>
                    @else
                        <img src="{{ $fileUrl }}" class="d-block w-100" alt="Slide {{ $key + 1 }}">
                    @endif
                </div>
            @endforeach

        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

<!-- ================= TENTANG KAMI ================= -->
<section class="section-about py-5">
    <div class="container py-4">
        <div class="row align-items-center g-5 flex-column-reverse flex-lg-row">

            <!-- TEKS -->
            <div class="col-lg-6 about-textbox text-center text-lg-start" data-aos="fade-right"
                data-aos-duration="1000">
                <h2 class="about-title fw-bold mb-4">Tentang Kami</h2>
                <p class="about-paragraph">
                    Sejak awal berdiri, <strong>Batik Wistara</strong> berkomitmen menjaga warisan batik Nusantara
                    melalui desain yang autentik dan kualitas premium.
                    Setiap helai kain Batik Wistara menghadirkan harmoni antara tradisi dan inovasi — melestarikan
                    budaya dengan sentuhan modern.
                </p>
                <a href="{{ url('/tentang') }}" class="about-button mt-3">Selengkapnya</a>
            </div>

            <!-- GAMBAR -->
            <div class="col-lg-6 text-center" data-aos="fade-left" data-aos-duration="1000">
                <img src="{{ asset('img/about.jpg') }}" alt="Tentang Batik Wistara"
                    class="about-image rounded-4 shadow-lg img-fluid">
            </div>

        </div>
    </div>
</section>

<!-- ================= KATALOG ================= -->
<section class="section-katalog-slider py-5">
    <div class="container">

        <!-- Judul -->
        <div class="text-center mb-5" data-aos="fade-up" data-aos-duration="800">
            <h2 class="fw-bold text-gold">Katalog Wistara</h2>
            <hr class="mx-auto mt-3" style="width:100px;height:3px;background:#fff">
        </div>

        <div class="row g-4 align-items-start">

            <!-- ================= KIRI: VIDEO ================= -->
            <div class="col-lg-5 d-flex justify-content-center mb-4 mb-lg-0" data-aos="fade-right" data-aos-duration="800">
                <div class="katalog-media overflow-hidden rounded-4 shadow">
                    <video autoplay muted loop playsinline>
                        <source src="{{ asset('img/vidbatik.mp4') }}" type="video/mp4">
                    </video>
                </div>
            </div>

            <!-- ================= KANAN: PRODUK ================= -->
            <div class="col-lg-7" data-aos="fade-left" data-aos-duration="800">

                <!-- ======= MOBILE MODE: 2 CARD SCROLL HORIZONTAL ======= -->
                <div class="mobile-scroll d-lg-none">
                    <div class="scroll-inner">

                        @foreach ($produk as $p)
                            @php
                                $gambarUrl = file_exists(public_path($p->gambar)) ? asset($p->gambar) : asset('img/no-image.jpg');
                                $avgRating = round($p->average_rating, 1);
                                $reviewCount = $p->review_count;
                            @endphp

                            <a href="{{ route('produk.show', $p->slug) }}" class="scroll-card text-decoration-none">
                                <div class="card produk-card border-0 shadow-sm">

                                    <div class="produk-img-wrapper">
                                        <img src="{{ $gambarUrl }}" class="produk-img" alt="">
                                        <span class="kategori-badge badge bg-light text-gold position-absolute top-0 start-0 m-2">
                                            {{ $p->nama_kategori }}
                                        </span>
                                    </div>

                                    <div class="card-body">
                                        <h6 class="fw-bold text-dark text-truncate">{{ $p->nama_produk }}</h6>

                                        <div class="rating-stars mb-1">
                                            @for($i=1;$i<=5;$i++)
                                                @if($i <= floor($avgRating))
                                                    <i class="fa-solid fa-star text-warning"></i>
                                                @elseif($i == ceil($avgRating) && ($avgRating - floor($avgRating)) >= 0.5)
                                                    <i class="fa-solid fa-star-half-stroke text-warning"></i>
                                                @else
                                                    <i class="fa-regular fa-star text-secondary"></i>
                                                @endif
                                            @endfor
                                            <span class="small text-muted">({{ $reviewCount }})</span>
                                        </div>

                                        <p class="harga-produk fw-bold text-warning mb-1">
                                            Rp {{ number_format($p->harga, 0, ',', '.') }}
                                        </p>

                                        <p class="text-muted small mb-0">
                                            {{ Str::limit(strip_tags($p->deskripsi), 50) }}
                                        </p>
                                    </div>

                                </div>
                            </a>
                        @endforeach

                    </div>
                </div>

                <!-- ======= TOMBOL MOBILE ======= -->
                <div class="text-center mt-4 d-lg-none">
                    <a href="{{ url('/katalog') }}" class="about-button mt-3">
                        Lihat Semua Katalog
                    </a>
                </div>

                <!-- ======= DESKTOP: CAROUSEL 3 ITEM ======= -->
                <div id="carouselKatalog" class="carousel slide d-none d-lg-block" data-bs-interval="0">
                    <div class="carousel-inner">

                        @foreach ($produk->chunk(3) as $chunkIndex => $chunk)
                            <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                                <div class="row g-4">

                                    @foreach ($chunk as $p)
                                        @php
                                            $gambarUrl = file_exists(public_path($p->gambar)) ? asset($p->gambar) : asset('img/no-image.jpg');
                                            $avgRating = round($p->average_rating, 1);
                                            $reviewCount = $p->review_count;
                                        @endphp

                                        <div class="col-md-4">
                                            <a href="{{ route('produk.show', $p->slug) }}" class="text-decoration-none">
                                                <div class="card produk-card h-100 border-0 shadow-sm">

                                                    <div class="produk-img-wrapper">
                                                        <img src="{{ $gambarUrl }}" class="produk-img">
                                                        <span class="kategori-badge badge bg-light text-gold position-absolute top-0 start-0 m-2">{{ $p->nama_kategori }}</span>
                                                    </div>

                                                    <div class="card-body">
                                                        <h6 class="fw-bold text-dark text-truncate">{{ $p->nama_produk }}</h6>

                                                        <div class="rating-stars mb-1">
                                                            @for($i=1;$i<=5;$i++)
                                                                @if($i <= floor($avgRating))
                                                                    <i class="fa-solid fa-star text-warning"></i>
                                                                @elseif($i == ceil($avgRating) && ($avgRating-floor($avgRating)) >= 0.5)
                                                                    <i class="fa-solid fa-star-half-stroke text-warning"></i>
                                                                @else
                                                                    <i class="fa-regular fa-star text-secondary"></i>
                                                                @endif
                                                            @endfor
                                                            <span class="small text-muted">({{ $reviewCount }})</span>
                                                        </div>

                                                        <p class="fw-bold text-warning mb-1">
                                                            Rp {{ number_format($p->harga, 0, ',', '.') }}
                                                        </p>

                                                        <p class="text-muted small">
                                                            {{ Str::limit(strip_tags($p->deskripsi), 60) }}
                                                        </p>
                                                    </div>

                                                </div>
                                            </a>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        @endforeach

                    </div>

                    <!-- Kontrol -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselKatalog" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-dark rounded-circle p-2"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselKatalog" data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-dark rounded-circle p-2"></span>
                    </button>

                </div>

                <!-- ======= TOMBOL DESKTOP ======= -->
                <div class="text-center mt-4 d-none d-lg-block">
                    <a href="{{ url('/katalog') }}" class="about-button mt-3">
                        Lihat Semua Katalog
                    </a>
                </div>

            </div>

        </div>

    </div>
</section>


<!-- ================= BERITA TERKINI ================= -->
<section class="section-berita py-5">
    <div class="container-berita">
        <!-- Judul -->
        <div class="text-center mb-4" data-aos="fade-up" data-aos-duration="1000">
            <h2 class="text-gold">Berita Terkini</h2>
            <hr class="berita-divider mx-auto">
        </div>

        <!-- Grid Berita -->
        <div class="berita-grid">
            @foreach ($berita as $index => $b)
                <div class="berita-card" data-aos="fade-up" data-aos-duration="1000"
                    data-aos-delay="{{ $index * 150 }}">

                    <!-- Gambar + Sumber Overlay -->
                    <div class="berita-img-wrapper position-relative">
                        @if (filter_var($b->gambar, FILTER_VALIDATE_URL))
                            <img src="{{ $b->gambar }}" alt="{{ $b->judul }}">
                        @else
                            <img src="{{ asset($b->gambar) }}" alt="{{ $b->judul }}">
                        @endif

                        @if (!empty($b->sumber))
                            <div class="berita-sumber-overlay">
                                Sumber:
                                @if (!empty($b->tautan_sumber))
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
                    @if (!empty($b->tautan_sumber))
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

        <!-- Tombol Lihat Semua -->
        <div class="berita-footer mt-4 text-center" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ url('/berita') }}" class="btn-lihat-semua">Lihat Semua Berita</a>
        </div>
    </div>
</section>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const video = document.querySelector(".background-video");
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        video.play();
        observer.unobserve(video);
      }
    });
  });
  observer.observe(video);
});
</script>

@include('inc.footer')
