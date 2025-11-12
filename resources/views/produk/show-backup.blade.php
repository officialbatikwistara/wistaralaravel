@include('inc.header')

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('katalog') }}">Katalog</a></li>
            <li class="breadcrumb-item active">{{ $product->nama_produk }}</li>
        </ol>
    </nav>

    <!-- Product Info -->
    <div class="row mb-5">
        <div class="col-md-6">
            @php
                $fileName = basename($product->gambar ?? '');
                $gambarPath = public_path('uploads/produk/'.$fileName);
                $gambarUrl = (file_exists($gambarPath) && $fileName)
                    ? asset('uploads/produk/'.$fileName)
                    : asset('img/no-image.jpg');
            @endphp
            <img src="{{ $gambarUrl }}" alt="{{ $product->nama_produk }}" class="img-fluid rounded">
        </div>
        <div class="col-md-6">
            <h1 class="mb-3">{{ $product->nama_produk }}</h1>
            <h3 class="text-warning mb-3">Rp {{ number_format($product->harga, 0, ',', '.') }}</h3>
            <p class="mb-3">
                @if($product->kategori)
                    <span class="badge bg-secondary">{{ $product->kategori->nama_kategori }}</span>
                @endif
            </p>
            <p class="mb-4">
                @if($product->stok > 0)
                    <span class="badge bg-success">Stok: {{ $product->stok }}</span>
                @else
                    <span class="badge bg-danger">Stok Habis</span>
                @endif
            </p>

            @auth
                @if($product->stok > 0)
                    <div class="d-flex gap-2">
                        <form action="{{ route('cart.add', $product->id_produk) }}" method="POST" class="flex-fill">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">🛒 Tambah ke Keranjang</button>
                        </form>
                        <a href="{{ route('checkout.direct', $product->id_produk) }}" class="btn btn-success flex-fill">⚡ Beli Sekarang</a>
                    </div>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-primary w-100">Login untuk Membeli</a>
            @endauth
        </div>
    </div>

    <!-- Description -->
    <div class="mb-5">
        <h3>Deskripsi Produk</h3>
        <p>{!! nl2br(e($product->deskripsi)) !!}</p>
    </div>

    <!-- Reviews Section -->
    <div>
        <h2 class="mb-4">Ulasan Produk</h2>
        @include('components.product-review', ['product' => $product])
    </div>
</div>

@include('inc.footer')

