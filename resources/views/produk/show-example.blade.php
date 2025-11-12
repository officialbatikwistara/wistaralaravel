<!-- Contoh Halaman Detail Produk dengan Review Component -->
@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 2rem 1rem;">
    <!-- Breadcrumb -->
    <div style="margin-bottom: 2rem; color: #666; font-size: 0.875rem;">
        <a href="{{ route('home') }}" style="color: #007bff; text-decoration: none;">Home</a>
        <span> / </span>
        <a href="{{ route('katalog') }}" style="color: #007bff; text-decoration: none;">Katalog</a>
        <span> / </span>
        <span>{{ $product->nama_produk }}</span>
    </div>

    <!-- Product Details Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 3rem;">
        <!-- Product Image -->
        <div>
            <div style="background: #f8f9fa; border-radius: 0.5rem; overflow: hidden; aspect-ratio: 1;">
                @if ($product->gambar)
                    <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama_produk }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #999;">
                        Tidak ada gambar
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div>
            <h1 style="margin-top: 0; margin-bottom: 1rem; font-size: 2rem; color: #333;">{{ $product->nama_produk }}</h1>

            <!-- Rating Summary (dari Review Component) -->
            <div style="background: #f8f9fa; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="text-align: center;">
                        <div style="font-size: 2rem; font-weight: bold; color: #ffc107;">
                            {{ number_format($product->average_rating, 1) }}
                        </div>
                        <div style="color: #666; font-size: 0.875rem;">⭐ dari 5</div>
                    </div>
                    <div style="border-left: 1px solid #ddd; padding-left: 1rem;">
                        <div style="color: #333; font-weight: 600;">{{ $product->review_count }} ulasan</div>
                        <div style="color: #666; font-size: 0.875rem;">dari {{ $product->reviews()->count() }} total</div>
                    </div>
                </div>
            </div>

            <!-- Price -->
            <div style="margin-bottom: 1.5rem;">
                <div style="color: #666; font-size: 0.875rem; margin-bottom: 0.5rem;">Harga</div>
                <div style="font-size: 1.75rem; font-weight: bold; color: #28a745;">
                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                </div>
            </div>

            <!-- Stock -->
            <div style="margin-bottom: 1.5rem;">
                <div style="color: #666; font-size: 0.875rem; margin-bottom: 0.5rem;">Stok</div>
                <div style="font-size: 1rem; color: {{ $product->stok > 0 ? '#28a745' : '#dc3545' }}; font-weight: 600;">
                    {{ $product->stok > 0 ? $product->stok . ' tersedia' : 'Habis' }}
                </div>
            </div>

            <!-- Category -->
            @if ($product->kategori)
                <div style="margin-bottom: 1.5rem;">
                    <div style="color: #666; font-size: 0.875rem; margin-bottom: 0.5rem;">Kategori</div>
                    <div style="display: inline-block; background: #e7f3ff; color: #0066cc; padding: 0.5rem 1rem; border-radius: 0.25rem; font-size: 0.875rem;">
                        {{ $product->kategori->nama_kategori }}
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                @if ($product->stok > 0)
                    <form action="{{ route('cart.add', $product->id_produk) }}" method="POST" style="flex: 1;">
                        @csrf
                        <button type="submit" style="width: 100%; padding: 0.75rem 1.5rem; background-color: #007bff; color: white; border: none; border-radius: 0.25rem; cursor: pointer; font-weight: 600; font-size: 1rem;">
                            🛒 Tambah ke Keranjang
                        </button>
                    </form>

                    <a href="{{ route('checkout.direct', $product->id_produk) }}" style="flex: 1; padding: 0.75rem 1.5rem; background-color: #28a745; color: white; border: none; border-radius: 0.25rem; cursor: pointer; font-weight: 600; font-size: 1rem; text-align: center; text-decoration: none;">
                        💳 Beli Sekarang
                    </a>
                @else
                    <button disabled style="flex: 1; padding: 0.75rem 1.5rem; background-color: #ccc; color: #666; border: none; border-radius: 0.25rem; cursor: not-allowed; font-weight: 600; font-size: 1rem;">
                        Stok Habis
                    </button>
                @endif
            </div>

            <!-- Social Links -->
            @if ($product->link_shopee || $product->link_tiktok)
                <div style="display: flex; gap: 1rem;">
                    @if ($product->link_shopee)
                        <a href="{{ $product->link_shopee }}" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #ee4d2d; color: white; border-radius: 0.25rem; text-decoration: none; font-size: 0.875rem;">
                            🛍️ Shopee
                        </a>
                    @endif
                    @if ($product->link_tiktok)
                        <a href="{{ $product->link_tiktok }}" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #000; color: white; border-radius: 0.25rem; text-decoration: none; font-size: 0.875rem;">
                            🎵 TikTok
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Product Description -->
    <div style="background: #f8f9fa; padding: 2rem; border-radius: 0.5rem; margin-bottom: 3rem;">
        <h2 style="margin-top: 0; margin-bottom: 1rem; font-size: 1.5rem; color: #333;">Deskripsi Produk</h2>
        <div style="color: #555; line-height: 1.6;">
            {!! nl2br(e($product->deskripsi)) !!}
        </div>
    </div>

    <!-- Reviews Section (Component) -->
    <div>
        @include('components.product-review', ['product' => $product])
    </div>
</div>

<!-- Responsive Design -->
<style>
    @media (max-width: 768px) {
        div[style*="grid-template-columns: 1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }

        div[style*="display: flex"][style*="gap: 1rem"] {
            flex-direction: column;
        }

        div[style*="display: flex"][style*="gap: 1rem"] > * {
            flex: 1 !important;
        }
    }
</style>
@endsection
