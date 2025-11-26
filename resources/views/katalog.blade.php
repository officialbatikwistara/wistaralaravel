{{-- ==================== Header & Navbar ==================== --}}
@include('inc.header')

<!-- ================= HERO SECTION ================= -->
<!-- ===== Page Header / Banner Section ===== -->
<section class="page-header d-flex align-items-center justify-content-center">
    <div class="overlay"></div>
    <div class="container position-relative text-center">
        <h1 class="fw-bold page-title">Katalog</h1>
    </div>
</section>

<!-- ================= FILTER KATEGORI ================= -->
<section class="filter-section py-4 bg-white shadow-sm position-relative z-2">
    <div class="container text-center">
        <div class="d-flex justify-content-center flex-wrap gap-2 filter-pills">
            <!-- Semua -->
            <a href="{{ route('katalog', ['kategori' => 'all']) }}"
               class="btn kategori-pill {{ $filter === 'all' ? 'active' : '' }}">
               Semua
            </a>

            <!-- Loop Kategori -->
            @foreach($kategori as $k)
                <a href="{{ route('katalog', ['kategori' => $k->id_kategori]) }}"
                   class="btn kategori-pill {{ (string)$filter === (string)$k->id_kategori ? 'active' : '' }}">
                    {{ $k->nama_kategori }}
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- ================= KATALOG PRODUK ================= -->
<section class="section-katalog py-5 bg-light">
    <div class="container">
        <div class="row g-4">

            @foreach($produk as $p)

                @php
                    // Mapping gambar lama ke file baru
                    $fileName = basename($p->gambar ?? '');
                    $imageMap = [
                        'batik-parang.jpg' => '1760930150_14.jpg',
                        'batik-mega-mendung.jpg' => '1760930168_6.jpg',
                        'batik-sekar-jagad.jpg' => '1760930223_2.jpg',
                    ];

                    $actualFile = $imageMap[$fileName] ?? $fileName;

                    // Cek file
                    $path = public_path('uploads/produk/' . $actualFile);
                    $img = file_exists($path) && $actualFile
                        ? asset('uploads/produk/' . $actualFile)
                        : asset('img/logo.png');

                    // Rating
                    $avg = round($p->average_rating ?? 0, 1);
                    $count = $p->review_count ?? 0;
                @endphp

                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="{{ route('produk.show', $p->slug) }}" class="text-decoration-none">

                        <div class="card produk-card border-0 shadow-sm rounded-4 h-100 overflow-hidden">

                            <!-- Gambar Produk -->
                            <div class="produk-img-wrapper position-relative">
                                <img src="{{ $img }}" class="produk-img" alt="{{ $p->nama_produk }}">
                                <span class="kategori-badge badge bg-light text-gold position-absolute top-0 start-0 m-2">
                                    {{ $p->kategori->nama_kategori ?? 'Kategori' }}
                                </span>
                            </div>

                            <!-- BODY -->
                            <div class="card-body">

                                <h6 class="fw-bold text-dark text-truncate mb-1">{{ $p->nama_produk }}</h6>

                                <!-- RATING -->
                                <div class="rating-stars mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($avg))
                                            <i class="fa-solid fa-star text-warning"></i>
                                        @elseif ($i == ceil($avg) && ($avg - floor($avg)) >= 0.5)
                                            <i class="fa-solid fa-star-half-stroke text-warning"></i>
                                        @else
                                            <i class="fa-regular fa-star text-secondary"></i>
                                        @endif
                                    @endfor
                                    <span class="small text-muted ms-1">({{ $count }})</span>
                                </div>

                                <!-- HARGA -->
                                <p class="fw-bold text-warning mb-2">
                                    Rp {{ number_format($p->harga, 0, ',', '.') }}
                                </p>

                                <!-- DESKRIPSI -->
                                <p class="text-muted small mb-0">
                                    {{ Str::limit(strip_tags($p->deskripsi), 60) }}
                                </p>

                            </div>

                        </div>

                    </a>
                </div>

            @endforeach

        </div>
    </div>
</section>

@include('inc.footer')
