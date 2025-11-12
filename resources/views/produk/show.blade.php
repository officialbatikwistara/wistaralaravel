@include('inc.header')

<!-- 🟡 Hero Header -->
<section class="page-header d-flex align-items-center justify-content-center text-center"
         style="background: linear-gradient(rgba(0,0,0,0.45), rgba(0, 0, 0, 0.71)), url('{{ asset('img/bghero.svg') }}') center/cover no-repeat; min-height: 200px;">
  <div class="container position-relative">
    <h1 class="fw-bold page-title text-white display-5">{{ $product->nama_produk ?? 'Produk' }}</h1>
  </div>
</section>

<!-- 📦 Detail Produk -->
<section class="py-5">
  <div class="container">
    <!-- Breadcrumb -->
    <div class="mb-4" style="color: #666; font-size: 0.875rem;">
      <a href="{{ route('home') }}" style="color: #007bff; text-decoration: none;">Home</a>
      <span> / </span>
      <a href="{{ route('katalog') }}" style="color: #007bff; text-decoration: none;">Katalog</a>
      <span> / </span>
      <span>{{ $product->nama_produk }}</span>
    </div>

    <!-- Product Details Grid -->
    <div class="row g-4 mb-5">
      <!-- Product Image -->
      <div class="col-md-6">
        <div class="bg-light rounded overflow-hidden" style="aspect-ratio: 1;">
          @php
            $fileName = basename($product->gambar ?? '');
            // Map old database paths to actual filenames
            $imageMap = [
                'batik-parang.jpg' => '1760930150_14.jpg',
                'batik-mega-mendung.jpg' => '1760930168_6.jpg',
                'batik-sekar-jagad.jpg' => '1760930223_2.jpg',
            ];
            $actualFileName = $imageMap[$fileName] ?? $fileName;
            $gambarPath = public_path('uploads/produk/'.$actualFileName);
            $gambarUrl = (file_exists($gambarPath) && $actualFileName)
                ? asset('uploads/produk/'.$actualFileName)
                : asset('img/logo.png');
          @endphp
          <img src="{{ $gambarUrl }}" alt="{{ $product->nama_produk }}" class="w-100 h-100" style="object-fit: cover;">
        </div>
      </div>

      <!-- Product Info -->
      <div class="col-md-6">
        <h1 class="fw-bold mb-3">{{ $product->nama_produk }}</h1>

        <!-- Rating Summary -->
        <div class="bg-light p-3 rounded mb-3">
          <div class="d-flex align-items-center gap-3">
            <div class="text-center">
              <div class="fs-2 fw-bold text-warning">
                {{ number_format($product->average_rating, 1) }}
              </div>
              <div class="text-muted small">⭐ dari 5</div>
            </div>
            <div class="border-start ps-3">
              <div class="fw-semibold">{{ $product->review_count }} ulasan</div>
              <div class="text-muted small">dari {{ $product->reviews()->count() }} total</div>
            </div>
          </div>
        </div>

        <!-- Price -->
        <div class="mb-3">
          <h3 class="text-warning fw-bold">Rp {{ number_format($product->harga, 0, ',', '.') }}</h3>
        </div>

        <!-- Category -->
        <div class="mb-3">
          @if($product->kategori)
            <span class="badge bg-secondary">{{ $product->kategori->nama_kategori }}</span>
          @else
            <span class="badge bg-secondary">Tanpa Kategori</span>
          @endif
        </div>

        <!-- Stock -->
        <div class="mb-4">
          @if ($product->stok > 0)
            <span class="badge bg-success">✅ Stok Tersedia ({{ $product->stok }})</span>
          @else
            <span class="badge bg-danger">❌ Stok Habis</span>
          @endif
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-2 mb-4">
          @auth
            @if ($product->stok > 0)
              <form action="{{ route('cart.add', $product->id_produk) }}" method="POST" class="flex-fill">
                @csrf
                <button type="submit" class="btn btn-primary w-100">
                  🛒 Tambah ke Keranjang
                </button>
              </form>
              <a href="{{ route('checkout.direct', $product->id_produk) }}" class="btn btn-success flex-fill">
                ⚡ Beli Sekarang
              </a>
            @else
              <button class="btn btn-secondary w-100" disabled>Stok Habis</button>
            @endif
          @else
            <a href="{{ route('login') }}" class="btn btn-primary w-100">
              Login untuk Membeli
            </a>
          @endauth
        </div>

        <!-- Marketplace Links -->
        @if ($product->link_shopee || $product->link_tiktok)
          <div class="mb-3">
            <h6 class="fw-semibold mb-2">Beli di Marketplace:</h6>
            <div class="d-flex gap-2">
              @if ($product->link_shopee)
                <a href="{{ $product->link_shopee }}" target="_blank" class="btn btn-sm btn-outline-warning">
                  🛍️ Shopee
                </a>
              @endif
              @if ($product->link_tiktok)
                <a href="{{ $product->link_tiktok }}" target="_blank" class="btn btn-sm btn-outline-dark">
                  🎵 TikTok Shop
                </a>
              @endif
            </div>
          </div>
        @endif
      </div>
    </div>

    <!-- Product Description -->
    <div class="bg-light p-4 rounded mb-5">
      <h2 class="h4 mb-3">Deskripsi Produk</h2>
      <div style="line-height: 1.8; color: #555;">
        {!! nl2br(e($product->deskripsi)) !!}
      </div>
    </div>

    <!-- Reviews Section -->
    <div>
      @include('components.product-review', ['product' => $product])
    </div>
  </div>
</section>

@include('inc.footer')
