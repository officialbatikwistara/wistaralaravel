<div class="card produk-card shadow-sm border-0 h-100">

    <!-- GAMBAR -->
    <div class="produk-img-wrapper position-relative">
        <img src="{{ $gambarUrl }}" class="produk-img">

        <span class="badge-produk">
            {{ $p->kategori->nama_kategori ?? 'Kategori' }}
        </span>
    </div>

    <div class="card-body d-flex flex-column">

        <h6 class="fw-bold text-dark text-truncate mb-1">{{ $p->nama_produk }}</h6>

        <!-- Rating -->
        <div class="rating-stars mb-1">
            @for ($i = 1; $i <= 5; $i++)
                @if($i <= floor($avg))
                    <i class="fa-solid fa-star text-warning"></i>
                @elseif($i == ceil($avg) && ($avg - floor($avg)) >= 0.5)
                    <i class="fa-solid fa-star-half-stroke text-warning"></i>
                @else
                    <i class="fa-regular fa-star text-secondary"></i>
                @endif
            @endfor
            <span class="text-muted small ms-1">({{ $count }})</span>
        </div>

        <p class="fw-bold text-warning mb-2">
            Rp {{ number_format($p->harga, 0, ',', '.') }}
        </p>

        <p class="text-muted small flex-grow-1">
            {{ Str::limit(strip_tags($p->deskripsi), 60) }}
        </p>

    </div>

</div>
