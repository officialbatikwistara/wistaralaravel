@include('admin.header')

<style>
/* 🌄 Background fullscreen */
body {
  font-family: 'Poppins', sans-serif;
  color: #0b1841;
  margin: 0;
  min-height: 100vh;
  background: url('{{ asset('img/background1.svg') }}') no-repeat center center fixed;
  background-size: cover;
}

/* 🏷️ Judul halaman */
h2 {
  font-weight: 700;
  color: #0b1841;
}

/* 🌸 Card umum */
.card {
  background: rgba(255, 255, 255, 0.97);
  border-radius: 18px !important;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08) !important;
  backdrop-filter: blur(6px);
  border: none !important;
}

/* 🔵 Tabs */
.nav-tabs {
  border: none !important;
}

.nav-tabs .nav-link {
  color: #ffffff !important;
  background-color: #001f3f !important;
  border: none !important;
  border-radius: 10px 10px 0 0 !important;
  padding: 10px 20px !important;
  margin: 0 5px !important;
  transition: all 0.3s ease;
}

.nav-tabs .nav-link.active {
  background-color: #ffffff !important;
  color: #001f3f !important;
  font-weight: 600;
}

.nav-tabs .nav-link:hover {
  opacity: 0.9;
}

/* 🧾 Header tabel */
.table-header-navy {
  background-color: #001f3f !important;
  color: #ffffff !important;
}

.table-header-navy th {
  font-weight: 600;
  text-transform: capitalize;
  padding: 12px;
  vertical-align: middle;
  border: none !important;
}

/* 🧭 Table layout */
.table {
  border-collapse: separate !important;
  border-spacing: 0;
  border-radius: 18px !important;
  overflow: hidden !important;
}

.table tbody tr:hover {
  background-color: #f8fafc !important;
  transition: 0.2s ease;
}

/* 🔘 Select style */
select.form-select {
  background-color: #f8fafc;
  border-radius: 8px;
  cursor: pointer;
}

/* 🧿 Tombol utama */
.btn-primary-navy {
  background-color: #001f3f !important;
  color: white !important;
  border: none !important;
  border-radius: 8px !important;
  font-weight: 500 !important;
  transition: 0.3s ease;
}

.btn-primary-navy:hover {
  background-color: #102b6d !important;
}

/* Modal */
.modal-content {
  border-radius: 18px !important;
  overflow: hidden !important;
}

.modal-header {
  background-color: #001f3f !important;
  color: #ffffff !important;
}
</style>

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
    <h2 class="fw-bold text-dark border-bottom pb-2">Kelola Pesanan</h2>
  </div>

  <!-- 🔍 Filter Form -->
  <div class="card mb-4">
    <div class="card-body">
      <form id="filterForm" class="row g-3 align-items-center" method="GET" action="{{ route('admin.orders.index') }}">
        <div class="col-md-auto">
          <label class="form-label mb-1 fw-semibold text-secondary">Dari</label>
          <input type="date" name="start" value="{{ request('start') }}" class="form-control border-0 shadow-sm" onchange="this.form.submit()">
        </div>
        <div class="col-md-auto">
          <label class="form-label mb-1 fw-semibold text-secondary">Sampai</label>
          <input type="date" name="end" value="{{ request('end') }}" class="form-control border-0 shadow-sm" onchange="this.form.submit()">
        </div>
        <div class="col-md">
          <label class="form-label mb-1 fw-semibold text-secondary">Kata Kunci</label>
          <input type="text" name="keyword" placeholder="Cari nama atau ID pesanan..." value="{{ request('keyword') }}" class="form-control border-0 shadow-sm" onkeypress="if(event.key === 'Enter'){ this.form.submit(); }">
        </div>
      </form>
    </div>
  </div>

  <!-- 🗂️ Tabs -->
  <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
    <div class="card-header py-3" style="background-color: #001f3f;">
      <ul class="nav nav-tabs card-header-tabs justify-content-center" id="orderTabs" role="tablist">
        <li class="nav-item"><button class="nav-link active fw-semibold" data-bs-toggle="tab" data-bs-target="#Semua" type="button">📋 Semua</button></li>
        <li class="nav-item"><button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#pending" type="button">🕒 Pending</button></li>
        <li class="nav-item"><button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#proses" type="button">⏳ Proses</button></li>
        <li class="nav-item"><button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#selesai" type="button">✅ Selesai</button></li>
        <li class="nav-item"><button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#batal" type="button">❌ Batal</button></li>
      </ul>
    </div>

    <div class="tab-content p-4 bg-white">
      @php
        $statusList = ['Semua' => null, 'pending' => 'pending', 'proses' => 'proses', 'selesai' => 'selesai', 'batal' => 'batal'];
      @endphp

      @foreach ($statusList as $key => $status)
      <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $key }}">
        <div class="table-responsive">
          <table class="table table-striped align-middle text-center m-0 shadow-sm border">
            <thead class="table-header-navy">
              <tr>
                <th>No</th>
                <th>ID</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status Pesanan</th>
                <th>Status Pembayaran</th>
                <th>Bukti</th>
                <th>Tipe</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @php $no = 1; @endphp
              @foreach($orders->where('status', $status)->when($status == null, fn($q) => $orders) as $order)
              <tr>
                <td>{{ $no++ }}</td>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->nama }}</td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>

                <!-- 🔵 STATUS PESANAN -->
                <td>
                  <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select form-select-sm text-center shadow-sm" onchange="this.form.submit()">
                      <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                      <option value="proses" {{ $order->status == 'proses' ? 'selected' : '' }}>Proses</option>
                      <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                      <option value="batal" {{ $order->status == 'batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                  </form>
                </td>

                <!-- 💰 STATUS PEMBAYARAN -->
                <td>
                  <form action="{{ route('admin.orders.updatePayment', $order->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <select name="status_pembayaran" class="form-select form-select-sm text-center shadow-sm" onchange="this.form.submit()">
                      <option value="belum_bayar" {{ $order->status_pembayaran == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                      <option value="menunggu_verifikasi" {{ $order->status_pembayaran == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu</option>
                      <option value="lunas" {{ $order->status_pembayaran == 'lunas' ? 'selected' : '' }}>Lunas</option>
                      <option value="gagal" {{ $order->status_pembayaran == 'gagal' ? 'selected' : '' }}>Gagal</option>
                    </select>
                  </form>
                </td>

                <!-- 🧾 Bukti -->
                <td>
                  @if($order->bukti_pembayaran && file_exists(public_path('uploads/bukti/'.$order->bukti_pembayaran)))
                    <button class="btn btn-sm btn-primary-navy rounded-pill" 
                            data-bs-toggle="modal" 
                            data-bs-target="#buktiModal{{ $order->id }}">
                      <i class="fa-solid fa-image me-1"></i> Lihat
                    </button>

                    <!-- Modal Bukti Pembayaran TANPA OVERLAY -->
                    <div class="modal fade" 
                        id="buktiModal{{ $order->id }}" 
                        tabindex="-1" 
                        aria-hidden="true"
                        data-bs-backdrop="false"     {{-- ❌ overlay dimatikan --}}
                        data-bs-keyboard="true">    {{-- ✅ ESC bisa menutup modal --}}
                      <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content shadow-lg border-0">
                          <div class="modal-header">
                            <h5 class="modal-title">Bukti Pembayaran #{{ $order->id }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body text-center">
                            <img src="{{ asset('uploads/bukti/'.$order->bukti_pembayaran) }}" 
                                alt="Bukti Pembayaran {{ $order->id }}" 
                                class="img-fluid rounded shadow-sm" 
                                style="max-height: 80vh; object-fit: contain;">
                          </div>
                        </div>
                      </div>
                    </div>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>

                <td>{{ ucfirst($order->tipe_order) }}</td>

                <!-- 🧭 Aksi -->
                <td>
                  <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary-navy rounded-pill px-3">
                    <i class="fa-solid fa-eye me-1"></i> Detail
                  </a>
                </td>
              </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <td colspan="10" class="text-center py-2 text-muted">
                  Total Pesanan: {{ $orders->where('status', $status)->when($status == null, fn($q) => $orders)->count() }}
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>
<script>
document.addEventListener('click', function (e) {
  const modal = document.querySelector('.modal.show');
  if (modal && !modal.querySelector('.modal-content').contains(e.target)) {
    const modalInstance = bootstrap.Modal.getInstance(modal);
    modalInstance.hide();
  }
});
</script>

@include('admin.footer')
