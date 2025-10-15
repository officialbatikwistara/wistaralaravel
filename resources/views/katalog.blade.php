{{-- Header dan Navbar --}}
@include('inc.header')

<!-- ===== Page Header / Banner Section ===== -->
<section class="page-header d-flex align-items-center justify-content-center">
  <div class="overlay"></div>
  <div class="container position-relative text-center">
    <h1 class="fw-bold page-title">Katalog Produk</h1>
  </div>
</section>

<!-- ===== Section Katalog Produk ===== -->
<section class="section-katalog py-5 bg-light">
  <div class="container">
    <h2 class="text-center fw-bold mb-4">Temukan Produk Batik Terbaikmu di Wistara</h2>
    <hr class="mb-4" style="width: 60px; height: 3px; background-color: #CDA349; margin: 0 auto;">

    <!-- Filter Kategori -->
    <div class="text-center mb-5">
      <a href="{{ route('katalog', ['kategori' => 'all']) }}"
         class="btn btn-outline-dark m-1 {{ $filter === 'all' ? 'active' : '' }}">
         Semua
      </a>
      @foreach($kategori as $k)
        <a href="{{ route('katalog', ['kategori' => $k->id_kategori]) }}"
           class="btn btn-outline-dark m-1 {{ (string)$filter === (string)$k->id_kategori ? 'active' : '' }}">
           {{ $k->nama_kategori }}
        </a>
      @endforeach
    </div>

    <!-- Daftar Produk -->
    <div class="row row-cols-1 row-cols-md-3 g-4">
      @foreach($produk as $p)
        <div class="col">
          <div class="card h-100 shadow-sm">
            <img src="{{ asset('uploads/produk/'.$p->gambar) }}" class="card-img-top" alt="{{ $p->nama_produk }}">
            <div class="card-body">
              <h5 class="card-title fw-bold">{{ $p->nama_produk }}</h5>
              <p class="badge bg-secondary">{{ $p->nama_kategori }}</p>
              <p class="card-text text-muted">{{ Str::limit($p->deskripsi, 80) }}</p>
              <p class="text-muted"><small>Diunggah: {{ \Carbon\Carbon::parse($p->tanggal_upload)->format('d M Y') }}</small></p>
              <button type="button" class="btn btn-outline-dark w-100" data-bs-toggle="modal" data-bs-target="#produkModal{{ $p->id_produk }}">
                Beli Sekarang
              </button>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== Modal Detail Produk ===== -->
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
              <!-- WhatsApp -->
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

{{-- Footer --}}
@include('inc.footer')
