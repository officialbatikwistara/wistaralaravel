@include('inc.header')
<!-- ========================= HERO PRODUK ========================= -->
<section class="produk-detail pt-5 mt-4">
    <div class="container py-4">

        <div class="row g-4">

            <!-- ==================== FOTO PRODUK ==================== -->
            <div class="col-lg-6">

                <div class="main-image shadow-sm rounded-4 overflow-hidden mb-3">
                    <img id="mainPreview" 
                         src="{{ asset($product->gambar) }}" 
                         class="img-fluid w-100" 
                         style="object-fit: cover; height: 420px;">
                </div>

                <!-- Thumbnail -->
                <div class="d-flex gap-2">
                    <img src="{{ asset($product->gambar) }}" 
                         class="thumb-img rounded shadow-sm" 
                         onclick="changeImage(this)">
                </div>

            </div>

            <!-- ==================== INFO PRODUK ==================== -->
            <div class="col-lg-6">

                <!-- Kategori -->
                <span class="badge bg-dark mb-2">{{ $product->kategori->nama_kategori }}</span>

                <h2 class="fw-bold">{{ $product->nama_produk }}</h2>

                <!-- Rating -->
                @php
                    $avgRating = round($product->average_rating, 1);
                    $reviewCount = $product->review_count;
                @endphp

                <div class="rating-stars mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($avgRating))
                            <i class="fa-solid fa-star text-warning"></i>
                        @elseif($i == ceil($avgRating) && $avgRating - floor($avgRating) >= 0.5)
                            <i class="fa-solid fa-star-half-stroke text-warning"></i>
                        @else
                            <i class="fa-regular fa-star text-secondary"></i>
                        @endif
                    @endfor
                    <span class="text-muted ms-1 small">({{ $reviewCount }} ulasan)</span>
                </div>

                <!-- Harga -->
                <h3 class="fw-bold text-warning mb-3">
                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                </h3>

                <!-- Deskripsi -->
                <p class="text-muted" style="line-height: 1.7;">
                    {!! nl2br(e($product->deskripsi)) !!}
                </p>

                <!-- TOMBOL AKSI -->
                <div class="d-flex gap-3 mt-4">

                    <!-- Beli Sekarang -->
                    <a href="{{ route('checkout.direct', $product->id_produk) }}" 
                       class="btn btn-warning px-4 py-2 fw-bold text-dark shadow-sm">
                        🛍️ Beli Sekarang
                    </a>

                    <!-- Tambah Keranjang -->
                    @auth
                        <form action="{{ route('cart.add', $product->id_produk) }}" method="POST">
                            @csrf
                            <input type="hidden" name="qty" value="1">
                            <button class="btn btn-outline-dark px-4 py-2 fw-bold">
                                <i class="fa-solid fa-cart-plus"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" 
                           class="btn btn-outline-dark px-4 py-2 fw-bold">
                           <i class="fa-solid fa-cart-plus"></i>
                        </a>
                    @endauth

                </div>

                <!-- Marketplace -->
                <div class="mt-4">
                    <p class="fw-semibold mb-2">Atau beli melalui:</p>

                    <div class="d-flex gap-2 flex-wrap">
                        <a href="https://wa.me/62895381110035?text={{ urlencode('Halo admin, saya tertarik dengan produk ' . $product->nama_produk) }}"
                           class="btn btn-success btn-sm d-flex align-items-center gap-2">
                            <i class="fa-brands fa-whatsapp"></i> WhatsApp
                        </a>

                        @if ($product->link_shopee)
                            <a href="{{ $product->link_shopee }}" target="_blank" 
                               class="btn btn-warning btn-sm text-dark d-flex align-items-center gap-2">
                                <i class="fa-solid fa-bag-shopping"></i> Shopee
                            </a>
                        @endif

                        @if ($product->link_tiktok)
                            <a href="{{ $product->link_tiktok }}" target="_blank" 
                               class="btn btn-dark btn-sm d-flex align-items-center gap-2">
                                <i class="fa-brands fa-tiktok"></i> TikTok Shop
                            </a>
                        @endif
                    </div>
                </div>

            </div>

        </div>

        <!-- ========================= REVIEW USER ========================= -->
        <hr class="my-5">

        <h4 class="fw-bold mb-3">Ulasan Produk</h4>

        @forelse ($product->approvedReviews as $review)
            <div class="card border-0 shadow-sm p-3 mb-3">

                <div class="d-flex justify-content-between">
                    <strong>{{ $review->user->name ?? 'User' }}</strong>
                    <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                </div>

                <!-- Rating -->
                <div class="rating-stars my-1">
                    @for ($i = 1; $i <= 5; $i++)
                        @if($i <= $review->rating)
                            <i class="fa-solid fa-star text-warning"></i>
                        @else
                            <i class="fa-regular fa-star text-secondary"></i>
                        @endif
                    @endfor
                </div>

                <p class="mb-2">{{ $review->comment }}</p>

                @if($review->photos)
                    <div class="d-flex gap-2 mt-2">
                        @foreach($review->photos as $photo)
                            <img src="{{ asset('storage/'.$photo) }}" class="rounded" width="90">
                        @endforeach
                    </div>
                @endif

                @if($review->video)
                    <video class="mt-3 rounded" width="200" controls>
                        <source src="{{ asset('storage/'.$review->video) }}">
                    </video>
                @endif

            </div>
        @empty
            <p class="text-muted">Belum ada ulasan untuk produk ini.</p>
        @endforelse

    </div>
</section>

<script>
    function changeImage(el) {
        document.getElementById('mainPreview').src = el.src;
    }
</script>
@include('inc.footer')