@include('admin.header')

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h2 class="fw-bold text-dark border-bottom pb-2">📦 Kelola Pesanan</h2>
    </div>

    <!-- Filter Form -->
    <form class="row g-2 mb-3" method="GET" action="{{ route('admin.orders.index') }}">
        <div class="col-md-auto">
            <input type="date" class="form-control" name="start" value="{{ request('start') }}">
        </div>
        <div class="col-md-auto">
            <input type="date" class="form-control" name="end" value="{{ request('end') }}">
        </div>
        <div class="col-md">
            <input type="text" class="form-control" name="keyword" placeholder="Cari order..."
                value="{{ request('keyword') }}">
        </div>
        <div class="col-md-auto">
            <button type="submit" class="btn btn-dark w-100">Cari</button>
        </div>
    </form>

    <!-- Tabs -->
    <div class="card shadow border-0 rounded-3 overflow-hidden">
        <ul class="nav nav-tabs mb-3" id="orderTabs" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#Semua"
                    type="button">📋 Semua</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pending"
                    type="button">🕒 Pending</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#proses" type="button">⏳
                    Proses</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#selesai"
                    type="button">✅ Selesai</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#batal" type="button">❌
                    Batal</button></li>
        </ul>

        <div class="tab-content p-3">
            @php
                $statusList = [
                    'Semua' => null,
                    'pending' => 'pending',
                    'proses' => 'proses',
                    'selesai' => 'selesai',
                    'batal' => 'batal',
                ];
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
                                <th>Status</th>
                                <th>Tipe Order</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($orders->where('status', $status)->when($status == null, fn($q) => $orders) as $order)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->nama }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($order->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($order->status == 'proses')
                                            <span class="badge bg-primary">Proses</span>
                                        @elseif($order->status == 'selesai')
                                            <span class="badge bg-success">Selesai</span>
                                        @else
                                            <span class="badge bg-danger">Batal</span>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($order->tipe_order) }}</td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="fa-solid fa-eye me-1"></i> Lihat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="8" class="text-center py-2">
                                    Total Pesanan:
                                    {{ $orders->where('status', $status)->when($status == null, fn($q) => $orders)->count() }}
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
