@include('admin.header')

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
    <h2 class="fw-bold text-dark border-bottom pb-2"> Kelola Pesanan</h2>
  </div>

  <!-- 🔍 Filter Form -->
  <div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body bg-light rounded-4">
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
      <ul class="nav nav-tabs card-header-tabs justify-content-center border-0" id="orderTabs" role="tablist">
        <li class="nav-item">
          <button class="nav-link active fw-semibold" data-bs-toggle="tab" data-bs-target="#Semua" type="button">📋 Semua</button>
        </li>
        <li class="nav-item">
          <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#pending" type="button">🕒 Pending</button>
        </li>
        <li class="nav-item">
          <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#proses" type="button">⏳ Proses</button>
        </li>
        <li class="nav-item">
          <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#selesai" type="button">✅ Selesai</button>
        </li>
        <li class="nav-item">
          <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#batal" type="button">❌ Batal</button>
        </li>
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
                    <select name="status" class="form-select form-select-sm border-0 shadow-sm text-center" onchange="this.form.submit()">
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
                    <select name="status_pembayaran" class="form-select form-select-sm border-0 shadow-sm text-center" onchange="this.form.submit()">
                      <option value="belum_bayar" {{ $order->status_pembayaran == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                      <option value="menunggu_verifikasi" {{ $order->status_pembayaran == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu</option>
                      <option value="lunas" {{ $order->status_pembayaran == 'lunas' ? 'selected' : '' }}>Lunas</option>
                      <option value="gagal" {{ $order->status_pembayaran == 'gagal' ? 'selected' : '' }}>Gagal</option>
                    </select>
                  </form>
                </td>

                <!-- 🧾 Bukti -->
                <td>
                  @if($order->bukti_pembayaran)
                    <button class="btn btn-sm text-white rounded-pill" style="background-color: #001f3f;" data-bs-toggle="modal" data-bs-target="#buktiModal{{ $order->id }}">
                      <i class="fa-solid fa-image me-1"></i> Lihat
                    </button>

                    <!-- Modal Bukti Pembayaran -->
                    <div class="modal fade" id="buktiModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 rounded-4 shadow-lg">
                          <div class="modal-header text-white" style="background-color: #001f3f;">
                            <h5 class="modal-title">🧾 Bukti Pembayaran #{{ $order->id }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body text-center">
                            <img src="{{ asset('uploads/bukti/'.$order->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="img-fluid rounded shadow-sm">
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
                  <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm text-white rounded-pill px-3" style="background-color: #001f3f;">
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

@include('admin.footer')

<!-- 🌈 STYLE -->
<style>
  /* NAV TABS */
  .nav-tabs .nav-link {
    color: white;
    background-color: #001f3f;
    border: none;
    border-radius: 10px 10px 0 0;
    padding: 8px 20px;
    margin: 0 5px;
    transition: all 0.3s ease;
  }

  .nav-tabs .nav-link.active {
    background-color: #ffffff;
    color: #001f3f;
  }

  .nav-tabs .nav-link:hover {
    opacity: 0.85;
  }

  /* 🔵 HEADER TABEL NAVY PUTIH */
  .table-header-navy {
    background-color: #001f3f !important;
    color: #ffffff !important;
  }

  .table-header-navy th {
    font-weight: 600;
    text-transform: capitalize;
    padding: 12px;
    vertical-align: middle;
    border-bottom: 2px solid #ffffff44;
  }

  .table thead th {
    border: none !important;
  }
</style>
