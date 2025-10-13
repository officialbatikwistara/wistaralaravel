{{-- Header dan Navbar --}}
@include('inc.header')

<!-- ===== Page Header / Banner Section ===== -->
<section class="page-header d-flex align-items-center justify-content-center">
  <div class="overlay"></div>
  <div class="container position-relative text-center">
    <h1 class="fw-bold page-title">Katalog Produk</h1>
  </div>
</section>

<section class="section-katalog py-5 bg-light">
  <div class="container">
    <h2 class="text-center fw-bold mb-4">Temukan Produk Batik Terbaikmu Di Wistara</h2>
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
              <button type="button" class="btn btn-outline-dark w-100" data-bs-toggle="modal" data-bs-target="#modalProduk{{ $p->id_produk }}">
                Beli Sekarang
              </button>
            </div>
          </div>
        </div>

        <!-- Modal Produk -->
        <div class="modal fade" id="modalProduk{{ $p->id_produk }}" tabindex="-1" aria-labelledby="modalLabel{{ $p->id_produk }}" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalLabel{{ $p->id_produk }}">Detail Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
              </div>
              <div class="modal-body">
                <div class="row g-4">
                  <div class="col-md-5 text-center">
                    <img src="{{ asset('uploads/produk/'.$p->gambar) }}" class="img-fluid rounded shadow" alt="{{ $p->nama_produk }}">
                  </div>
                  <div class="col-md-7">
                    <h4 class="fw-bold">{{ $p->nama_produk }}</h4>
                    <p class="badge bg-secondary mb-2">{{ $p->nama_kategori }}</p>
                    <p class="text-muted"><small>Diunggah: {{ \Carbon\Carbon::parse($p->tanggal_upload)->format('d M Y') }}</small></p>
                    <p>{{ nl2br(e($p->deskripsi)) }}</p>

                    <hr>
                    <p><strong>Beli melalui:</strong></p>
                    <div class="d-grid gap-2">
                      @php
                        $pesan = "Halo admin Wistara, saya ingin bertanya mengenai produk ".$p->nama_produk;
                        $link_wa = "https://wa.me/62895381110035?text=".urlencode($pesan);
                      @endphp

                      <a href="{{ $link_wa }}" target="_blank" class="btn btn-outline-dark w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="fab fa-whatsapp fa-lg text-success"></i> <span>WhatsApp</span>
                      </a>

                      @if($p->link_shopee)
                        <a href="{{ $p->link_shopee }}" target="_blank" class="btn btn-outline-dark w-100 d-flex align-items-center justify-content-center gap-2">
                          <i class="fas fa-store fa-lg text-warning"></i> <span>Shopee</span>
                        </a>
                      @endif

                      @if($p->link_tiktok)
                        <a href="{{ $p->link_tiktok }}" target="_blank" class="btn btn-outline-dark w-100 d-flex align-items-center justify-content-center gap-2">
                          <i class="fab fa-tiktok fa-lg text-dark"></i> <span>TikTokShop</span>
                        </a>
                      @endif

                      <a href="{{ url('checkout/'.$p->id_produk) }}" class="btn btn-outline-dark w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="fas fa-shopping-cart fa-lg text-primary"></i> <span>Website</span>
                      </a>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

@include('inc.footer')

