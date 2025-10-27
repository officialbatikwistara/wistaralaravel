@include('admin.header')

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h2 class="fw-bold text-dark border-bottom pb-2">📦 Kelola Pesanan</h2>
    </div>

    <!-- Filter Form -->
    <form id="filterForm" class="row g-2 mb-3" method="GET" action="{{ route('admin.orders.index') }}">
        <div class="col-md-auto">
            <input 
                type="date" 
                class="form-control" 
                name="start" 
                value="{{ request('start') }}" 
                onchange="document.getElementById('filterForm').submit()">
        </div>
        <div class="col-md-auto">
            <input 
                type="date" 
                class="form-control" 
                name="end" 
                value="{{ request('end') }}" 
                onchange="document.getElementById('filterForm').submit()">
        </div>
        <div class="col-md">
            <input 
                type="text" 
                class="form-control" 
                name="keyword" 
                placeholder="Cari order..." 
                value="{{ request('keyword') }}"
                onkeypress="if(event.key === 'Enter'){ this.form.submit(); }">
        </div>
        <div class="col-md-auto d-none">
            <button type="submit" class="btn btn-dark w-100">Cari</button>
        </div>
</form>

    <!-- Tabs -->
    <div class="card shadow border-0 rounded-3 overflow-hidden">
        <ul class="nav nav-tabs mb-3" id="orderTabs" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#Semua" type="button">📋 Semua</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pending" type="button">🕒 Pending</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#proses" type="button">⏳ Proses</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#selesai" type="button">✅ Selesai</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#batal" type="button">❌ Batal</button></li>
        </ul>

        <div class="tab-content p-3">
            @php
                $statusList = ['Semua' => null, 'pending' => 'pending', 'proses' => 'proses', 'selesai' => 'selesai', 'batal' => 'batal'];
            @endphp

            @foreach ($statusList as $key => $status)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $key }}">
                <table class="table table-striped align-middle text-center m-0">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Tanggal Order</th>
                            <th>Total</th>
                            <th>Status Pesanan</th>
                            <th>Status Pembayaran</th>
                            <th>Bukti</th>
                            <th>Tipe Order</th>
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
                            <!-- 🟡 STATUS PESANAN -->
                            <td>
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    @if($order->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($order->status == 'proses')
                                        <span class="badge bg-primary">Proses</span>
                                    @elseif($order->status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-danger">Batal</span>
                                    @endif

                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="proses" {{ $order->status == 'proses' ? 'selected' : '' }}>Proses</option>
                                            <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="batal" {{ $order->status == 'batal' ? 'selected' : '' }}>Batal</option>
                                        </select>
                                    </form>
                                </div>
                            </td>

                            <!-- 💳 STATUS PEMBAYARAN -->
                            <td>
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    @if($order->status_pembayaran == 'belum_bayar')
                                        <span class="badge bg-secondary">Belum Bayar</span>
                                    @elseif($order->status_pembayaran == 'menunggu_verifikasi')
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @elseif($order->status_pembayaran == 'lunas')
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-danger">Gagal</span>
                                    @endif

                                    <form action="{{ route('admin.orders.updatePayment', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status_pembayaran" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="belum_bayar" {{ $order->status_pembayaran == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                            <option value="menunggu_verifikasi" {{ $order->status_pembayaran == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu</option>
                                            <option value="lunas" {{ $order->status_pembayaran == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                            <option value="gagal" {{ $order->status_pembayaran == 'gagal' ? 'selected' : '' }}>Gagal</option>
                                        </select>
                                    </form>
                                </div>
                            </td>

                            <td>
                                @if($order->bukti_pembayaran)
                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#buktiModal{{ $order->id }}">
                                    <i class="fa-solid fa-image me-1"></i> Lihat
                                </button>

                                <!-- Modal Bukti Pembayaran -->
                                <div class="modal fade" id="buktiModal{{ $order->id }}" tabindex="-1" aria-labelledby="buktiModalLabel{{ $order->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content border-0 rounded-4">
                                            <div class="modal-header bg-dark text-white">
                                                <h5 class="modal-title" id="buktiModalLabel{{ $order->id }}">
                                                    🧾 Bukti Pembayaran #{{ $order->id }}
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ asset('uploads/bukti/'.$order->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="img-fluid rounded shadow">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ ucfirst($order->tipe_order) }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fa-solid fa-eye me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="10" class="text-center py-2">
                                Total Pesanan: {{ $orders->where('status', $status)->when($status == null, fn($q) => $orders)->count() }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endforeach
        </div>
    </div>
</div>

@include('admin.footer')
