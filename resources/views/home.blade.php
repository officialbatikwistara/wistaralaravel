@include('inc.header')

<!-- ================= HERO SECTION ================= -->
<section class="hero-section d-flex justify-content-center align-items-center text-center text-light">
  <!-- Background Video -->
  <video autoplay muted loop playsinline class="background-video">
    <source src="{{ asset('img/vidbatik.mp4') }}" type="video/mp4">
    Your browser does not support HTML5 video.
  </video>

  <!-- Overlay -->
  <div class="overlay"></div>

  <!-- Content -->
  <div class="hero-content" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="200">
    <img src="{{ asset('img/logoputih.png') }}" alt="Batik Wistara" class="hero-logo mb-4">
    <h1 class="animate-title">Selamat Datang di <span class="text-warning">Batik Wistara</span></h1>
    <p class="animate-subtitle">Batik Wistara menghadirkan karya batik berkualitas tinggi, memadukan keindahan budaya dan sentuhan modern.</p>
    <a href="{{ url('/katalog') }}" class="btn btn-hero mt-3 px-4" data-aos="zoom-in" data-aos-delay="400">Lihat Katalog</a>
  </div>
</section>

<!-- ================= TENTANG KAMI ================= -->
<section class="section-about py-5">
  <div class="container py-4">
    <div class="row align-items-center g-5 flex-column-reverse flex-lg-row">
      
      <!-- TEKS -->
      <div class="col-lg-6 about-textbox text-center text-lg-start" data-aos="fade-right" data-aos-duration="1000">
        <h2 class="about-title fw-bold mb-4">Tentang Kami</h2>
        <p class="about-paragraph">
          Sejak awal berdiri, <strong>Batik Wistara</strong> berkomitmen menjaga warisan batik Nusantara melalui desain yang autentik dan kualitas premium. 
          Setiap helai kain Batik Wistara menghadirkan harmoni antara tradisi dan inovasi — melestarikan budaya dengan sentuhan modern.
        </p>
        <a href="{{ url('/tentang') }}" class="about-button mt-3">Selengkapnya</a>
      </div>

      <!-- GAMBAR -->
      <div class="col-lg-6 text-center" data-aos="fade-left" data-aos-duration="1000">
        <img 
          src="{{ asset('img/about.jpg') }}" 
          alt="Tentang Batik Wistara" 
          class="about-image rounded-4 shadow-lg img-fluid">
      </div>

    </div>
  </div>
</section>

<!-- ================= KATALOG PRODUK SLIDER ================= -->
<section class="section-katalog-slider py-5">
  <div class="container">

    <!-- Judul -->
    <div class="text-center mb-5" data-aos="fade-up" data-aos-duration="1000">
      <h2 class="fw-bold text-gold">Katalog Wistara</h2>
      <hr class="mx-auto mt-3" style="width: 100px; height: 3px; background-color: #ffffffff;">
    </div>

    <div class="row g-4 align-items-center">
      
      <!-- KIRI: Video -->
      <div class="col-lg-5 d-flex justify-content-center" data-aos="fade-right" data-aos-duration="1000">
        <div class="katalog-media overflow-hidden rounded-4 shadow">
          <video autoplay muted loop playsinline>
            <source src="{{ asset('img/vidbatik.mp4') }}" type="video/mp4">
            Browser Anda tidak mendukung video.
          </video>
        </div>
      </div>

      <!-- KANAN: Carousel Produk -->
      <div class="col-lg-7" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
        <div id="carouselKatalog" class="carousel slide mb-4" data-bs-ride="carousel" data-bs-interval="0">
          <div class="carousel-inner">
            @foreach($produk->chunk(3) as $chunkIndex => $chunk)
              <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                <div class="row g-4 justify-content-center">
                  @foreach($chunk as $p)
                    <div class="col-12 col-sm-6 col-md-4" data-aos="zoom-in" data-aos-duration="800">
                      <div class="card produk-card h-100 border-0 shadow-sm">
                        
                        <!-- Gambar Produk -->
                        <div class="produk-img-wrapper position-relative">
                          <img src="{{ asset('uploads/produk/'.$p->gambar) }}" 
                              alt="{{ $p->nama_produk }}" 
                              class="card-img-top produk-img">
                          <!-- Badge kategori di atas gambar -->
                          <span class="kategori-badge badge bg-dark position-absolute top-0 start-0 m-2">
                            {{ $p->nama_kategori }}
                          </span>
                        </div>

                        <!-- Body Produk -->
                        <div class="card-body d-flex flex-column">
                          <h6 class="fw-bold mb-1 text-dark text-truncate">{{ $p->nama_produk }}</h6>

                          <!-- Harga Produk -->
                          <p class="harga-produk mb-2 fw-bold text-warning">
                            Rp {{ number_format($p->harga, 0, ',', '.') }}
                          </p>

                          <p class="text-muted small flex-grow-1 mb-3">
                            {{ Str::limit(strip_tags($p->deskripsi), 60) }}
                          </p>

                          <button 
                            type="button"
                            class="btn btn-outline-dark btn-sm mt-auto w-100 fw-semibold"
                            data-bs-toggle="modal"
                            data-bs-target="#produkModal{{ $p->id_produk }}">
                            Detail Produk
                          </button>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endforeach
          </div>

          <!-- Navigasi Carousel -->
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselKatalog" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-dark rounded-circle p-2"></span>
            <span class="visually-hidden">Sebelumnya</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselKatalog" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-dark rounded-circle p-2"></span>
            <span class="visually-hidden">Selanjutnya</span>
          </button>
        </div>

        <!-- Tombol ke Katalog -->
        <div class="text-center mt-3" data-aos="fade-up" data-aos-delay="300">
          <a href="{{ url('/katalog') }}" class="btn btn-hero px-4 py-2">
            Lihat Semua Produk
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
      <h2 class="berita-title">Berita Terkini</h2>
      <hr class="berita-divider mx-auto">
    </div>

    <!-- Grid Berita -->
    <div class="berita-grid">
      @foreach($berita as $index => $b)
        <div class="berita-card" 
             data-aos="fade-up" 
             data-aos-duration="1000" 
             data-aos-delay="{{ $index * 150 }}">
             
          <!-- Gambar + sumber overlay -->
          <div class="berita-img-wrapper position-relative">
            @if(filter_var($b->gambar, FILTER_VALIDATE_URL))
              <img src="{{ $b->gambar }}" alt="{{ $b->judul }}">
            @else
              <img src="{{ asset('uploads/berita/'.$b->gambar) }}" alt="{{ $b->judul }}">
            @endif

            @if(!empty($b->sumber))
              <div class="berita-sumber-overlay">
                Sumber: {{ $b->sumber }}
              </div>
            @endif
          </div>

          <!-- Judul & Deskripsi -->
          <h3 class="berita-judul mt-3">{{ $b->judul }}</h3>
          <p class="berita-deskripsi">{{ Str::limit(strip_tags($b->deskripsi), 150) }}</p>

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

    <!-- Tombol lihat semua -->
    <div class="berita-footer mt-4 text-center" data-aos="fade-up" data-aos-delay="200">
      <a href="{{ url('/berita') }}" class="btn-lihat-semua">Lihat Semua Berita</a>
    </div>
  </div>
</section>


@foreach($produk as $p)
<div class="modal fade" id="produkModal{{ $p->id_produk }}" tabindex="-1" aria-labelledby="produkModalLabel{{ $p->id_produk }}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow-lg">

      <!-- HEADER -->
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold" id="produkModalLabel{{ $p->id_produk }}">
          {{ $p->nama_produk }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>

      <!-- BODY -->
      <div class="modal-body">
        <div class="row g-4 align-items-start">

          <!-- Gambar Produk -->
          <div class="col-md-5 text-center">
            <img src="{{ asset('uploads/produk/'.$p->gambar) }}" class="img-fluid rounded shadow-sm" alt="{{ $p->nama_produk }}">
          </div>

          <!-- Info Produk -->
          <div class="col-md-7">
            <span class="badge bg-secondary mb-2">{{ $p->nama_kategori }}</span>
            <h4 class="fw-bold text-warning mb-3">
              Rp {{ number_format($p->harga, 0, ',', '.') }}
            </h4>
            <p class="mb-4" style="line-height: 1.6;">{{ $p->deskripsi }}</p>

            <!-- Tombol Beli & Keranjang -->
            <div class="d-flex gap-2 mb-3">
              <a href="{{ url('checkout/'.$p->id_produk) }}" class="btn btn-dark flex-grow-1 fw-semibold">
                🛍️ Beli Sekarang
              </a>

              @auth
              <form action="{{ route('cart.add', $p->id_produk) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-dark px-3" title="Tambah ke Keranjang">
                  <i class="bi bi-cart-plus fs-5"></i>
                </button>
              </form>
              @else
              <a href="{{ route('login') }}" class="btn btn-outline-dark px-3" title="Login untuk menambahkan ke keranjang">
                <i class="bi bi-cart-plus fs-5"></i>
              </a>
              @endauth
            </div>

            <hr>

            <!-- Tombol Channel Lain -->
            <p class="fw-semibold mb-2">Atau beli melalui:</p>
            <div class="d-flex flex-wrap gap-2">
              <!-- WA -->
              <a href="https://wa.me/62895381110035?text={{ urlencode('Halo admin, saya tertarik dengan produk '.$p->nama_produk) }}"
                 target="_blank"
                 class="btn btn-outline-dark btn-sm border d-flex align-items-center gap-2">
                <i class="fab fa-whatsapp text-success"></i> WA
              </a>

              <!-- Shopee -->
              @if($p->link_shopee)
              <a href="{{ $p->link_shopee }}" target="_blank" 
                 class="btn btn-outline-dark btn-sm border d-flex align-items-center gap-2">
                <i class="fas fa-store text-warning"></i> Shopee
              </a>
              @endif

              <!-- TikTok Shop -->
              @if($p->link_tiktok)
              <a href="{{ $p->link_tiktok }}" target="_blank" 
                 class="btn btn-outline-dark btn-sm border d-flex align-items-center gap-2">
                <i class="fab fa-tiktok"></i> TikTokShop
              </a>
              @endif
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
</div>
@endforeach

@include('inc.footer')
