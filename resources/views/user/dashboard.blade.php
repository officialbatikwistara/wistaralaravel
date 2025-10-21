@include('inc.header')

<div class="dashboard-page" style="padding-top: 120px; background: url('{{ asset('img/bghero.svg') }}') center/cover no-repeat;">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-10 col-lg-8">
        <div class="card shadow-lg rounded-4 p-4 text-center border-0 mb-4">
          <i class="fa-solid fa-user-circle fa-4x text-dark mb-3"></i>
          <h2 class="fw-bold mb-2 text-dark">{{ Auth::user()->name }}</h2>
          <p class="text-muted mb-4">
            Selamat datang di Dashboard User — Anda telah berhasil login ✅
          </p>

          <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ url('/katalog') }}" class="btn btn-dark px-4 py-2 rounded-pill">
              <i class="fa-solid fa-store me-2"></i> Lihat Katalog
            </a>

            <a href="{{ url('/cart') }}" class="btn btn-outline-dark px-4 py-2 rounded-pill position-relative">
              <i class="fa-solid fa-cart-shopping me-2"></i> Keranjang Saya
              @php
                $cartCount = \App\Models\Cart::where('user_id', Auth::id())->sum('qty');
              @endphp
              @if($cartCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                  {{ $cartCount }}
                </span>
              @endif
            </a>

            <!-- Tombol Edit Profil -->
            <button class="btn btn-warning px-4 py-2 rounded-pill" data-bs-toggle="modal" data-bs-target="#editProfileModal">
              <i class="fa-solid fa-user-pen me-2"></i> Edit Profil
            </button>

            <a href="{{ route('user.logout') }}" class="btn btn-danger px-4 py-2 rounded-pill">
              <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
            </a>
          </div>
        </div>

        <!-- 🧾 PESANAN SAYA -->
        <div class="card shadow-lg rounded-4 p-4 border-0">
          <h4 class="fw-bold text-start mb-3">
            <i class="fa-solid fa-receipt me-2 text-dark"></i> Pesanan Saya
          </h4>

          @php
            $orders = \App\Models\Order::where('user_id', Auth::id())->orderBy('created_at','desc')->get();
          @endphp

          @if($orders->isEmpty())
            <p class="text-muted text-center py-3 mb-0">
              Belum ada pesanan 😄
            </p>
          @else
            <div class="table-responsive">
              <table class="table align-middle">
                <thead class="bg-dark text-white">
                  <tr>
                    <th>ID</th>
                    <th>Tanggal Ambil</th>
                    <th>Metode</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($orders as $order)
                    <tr>
                      <td>#{{ $order->id }}</td>
                      <td>{{ \Carbon\Carbon::parse($order->tanggal_ambil)->format('d M Y') }}</td>
                      <td>
                        @if($order->metode_pembayaran === 'bank_transfer')
                          🏦 Bank Transfer
                        @elseif($order->metode_pembayaran === 'qris')
                          📱 QRIS
                        @else
                          💵 COD
                        @endif
                      </td>
                      <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                      <td>
                        @if($order->status == 'pending')
                          <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($order->status == 'proses')
                          <span class="badge bg-primary">Diproses</span>
                        @elseif($order->status == 'selesai')
                          <span class="badge bg-success">Selesai</span>
                        @else
                          <span class="badge bg-danger">Batal</span>
                        @endif
                      </td>
                      <td>
                        @if($order->status_pembayaran == 'belum_bayar')
                          <span class="badge bg-secondary">Belum Bayar</span>
                        @elseif($order->status_pembayaran == 'menunggu_verifikasi')
                          <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                        @elseif($order->status_pembayaran == 'lunas')
                          <span class="badge bg-success">Lunas</span>
                        @else
                          <span class="badge bg-danger">Gagal</span>
                        @endif
                      </td>
                      <td>
                        <a href="{{ route('user.order.show', $order->id) }}" class="btn btn-sm btn-outline-dark rounded-pill">
                          <i class="fa-solid fa-eye me-1"></i> Detail
                        </a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<!-- 🟡 MODAL EDIT PROFIL -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow-lg">
      <div class="modal-header bg-dark text-white border-0">
        <h5 class="modal-title fw-bold" id="editProfileModalLabel">
          <i class="fa-solid fa-user-pen me-2"></i> Edit Profil
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form method="POST" action="{{ route('user.update.profile') }}">
        @csrf
        @method('PUT')
        <div class="modal-body text-start">
          <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" readonly>
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label fw-semibold">Nomor Telepon</label>
            <input type="tel" class="form-control" id="phone" name="phone" value="{{ Auth::user()->phone }}">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Password Baru (opsional)</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Biarkan kosong jika tidak ingin mengganti">
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-dark">
            <i class="fa-solid fa-save me-2"></i> Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@include('inc.footer')
