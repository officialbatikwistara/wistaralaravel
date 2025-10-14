@include('inc.header')

<section class="cart-section position-relative" 
         style="background: url('{{ asset('img/bghero.svg') }}') center/cover no-repeat; min-height: 100vh; margin-top: -80px; padding-bottom: 60px;">

  <!-- Overlay hitam transparan -->
  <div class="position-absolute top-0 start-0 w-100 h-100" 
       style="background: rgba(0, 0, 0, 0.63); backdrop-filter: blur(2px); z-index: 1;"></div>

  <!-- Kontainer konten -->
  <div class="container position-relative" style="z-index: 2; padding-top: 160px;">
    <h2 class="fw-bold mb-4 text-center text-white">
      Keranjang Belanja Anda
    </h2>

    {{-- Notifikasi --}}
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    {{-- Jika ada produk --}}
    @if($cartItems->count() > 0)
      <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead class="table-dark">
              <tr>
                <th scope="col" style="width: 35%">Produk</th>
                <th scope="col">Harga</th>
                <th scope="col" class="text-center">Jumlah</th>
                <th scope="col">Total</th>
                <th scope="col" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($cartItems as $item)
                <tr>
                  <td class="d-flex align-items-center gap-3">
                    <img src="{{ asset('uploads/produk/' . ($item->produk->gambar ?? 'default.jpg')) }}"
                         alt="{{ $item->produk->nama_produk ?? 'Produk' }}"
                         class="rounded shadow-sm"
                         style="width: 70px; height: 70px; object-fit: cover;">
                    <div>
                      <h6 class="mb-1 fw-bold">{{ $item->produk->nama_produk ?? 'Produk tidak tersedia' }}</h6>
                      <small class="text-muted">{{ $item->produk->nama_kategori ?? '-' }}</small>
                    </div>
                  </td>
                  <td>Rp {{ number_format(optional($item->produk)->harga ?? 0, 0, ',', '.') }}</td>
                  <td class="text-center">{{ $item->jumlah }}</td>
                  <td>Rp {{ number_format($item->jumlah * (optional($item->produk)->harga ?? 0), 0, ',', '.') }}</td>
                  <td class="text-center">
                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Hapus produk dari keranjang?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                        <i class="fa-solid fa-trash-can me-1"></i> Hapus
                      </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      {{-- Total & Checkout --}}
      @php
        $totalHarga = $cartItems->sum(fn($item) => $item->jumlah * (optional($item->produk)->harga ?? 0));
      @endphp
      <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2 text-white">
        <h5 class="fw-bold mb-0">Total:
          <span>Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
        </h5>
        <a href="#" class="btn btn-warning btn-lg rounded-pill px-4">
          <i class="fa-solid fa-cash-register me-2"></i> Checkout
        </a>
      </div>

    {{-- Jika kosong --}}
    @else
      <div class="text-center py-5 text-white">
        <i class="fa-solid fa-cart-shopping fa-3x mb-3"></i>
        <h5 class="fw-semibold mb-2">Keranjang Anda kosong</h5>
        <p class="opacity-75 mb-4">Yuk, mulai belanja produk Batik Wistara!</p>
        <a href="{{ url('/katalog') }}" class="btn btn-warning rounded-pill px-4">
          <i class="fa-solid fa-store me-2"></i> Lihat Katalog
        </a>
      </div>
    @endif
  </div>
</section>

@include('inc.footer')
