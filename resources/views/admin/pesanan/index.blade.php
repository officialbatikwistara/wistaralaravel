@include('admin.header')

<div class="container py-4">
<<<<<<< HEAD
=======

    <!-- Judul Halaman -->
>>>>>>> 7a49855 (update error)
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h2 class="fw-bold text-dark border-bottom pb-2">📦 Kelola Pesanan</h2>
    </div>

<<<<<<< HEAD
    <!-- Filter Form -->
    <form class="row g-2 mb-3" method="GET" action="{{ route('admin.orders.index') }}">
        <div class="col-md-auto">
            <input type="date" class="form-control" name="start" value="{{ request('start') }}">
        </div>
        <div class="col-md-auto">
            <input type="date" class="form-control" name="end" value="{{ request('end') }}">
        </div>
        <div class="col-md">
            <input type="text" class="form-control" name="keyword" placeholder="Cari order..." value="{{ request('keyword') }}">
=======
    <!-- 🔍 Filter Pencarian -->
    <form class="row g-2 mb-3" method="GET">
        <div class="col-md-auto">
            <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
        </div>
        <div class="col-md-auto">
            <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
        </div>
        <div class="col-md">
            <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                placeholder="Cari order...">
>>>>>>> 7a49855 (update error)
        </div>
        <div class="col-md-auto">
            <button type="submit" class="btn btn-dark w-100">Cari</button>
        </div>
    </form>

    <!-- 🧭 Tabs Kategori Status -->
    <div class="card shadow border-0 rounded-3 overflow-hidden">
        <ul class="nav nav-tabs mb-3" id="orderTabs" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#Semua" type="button">📋 Semua</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pending" type="button">🕒 Pending</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#proses" type="button">⏳ Proses</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#selesai" type="button">✅ Selesai</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#batal" type="button">❌ Batal</button></li>
        </ul>

        <div class="tab-content p-3">
<<<<<<< HEAD
            @php
                $statusList = ['Semua' => null, 'pending' => 'pending', 'proses' => 'proses', 'selesai' => 'selesai', 'batal' => 'batal'];
            @endphp

            @foreach ($statusList as $key => $status)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $key }}">
=======

            <!-- 📋 Tab: Semua Pesanan -->
            <div class="tab-pane fade show active" id="Semua">
>>>>>>> 7a49855 (update error)
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
<<<<<<< HEAD
                        @php $no = 1; @endphp
                        @foreach($orders->where('status', $status)->when($status == null, fn($q) => $orders) as $order)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->nama }}</td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td>
                                @if($order->status == 'pending')
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
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fa-solid fa-eye me-1"></i> Lihat
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="8" class="text-center py-2">
                                Total Pesanan: {{ $orders->where('status', $status)->when($status == null, fn($q) => $orders)->count() }}
                            </td>
=======
                        <!-- Loop semua pesanan -->
                        @forelse ($order as $index => $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>#ORD{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $order->nama }}</td>
                                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td>
                                    <span
                                        class="badge
                                        @if ($order->status == 'Proses') bg-warning text-dark
                                        @elseif($order->status == 'Dikirim') bg-primary
                                        @elseif($order->status == 'Selesai') bg-success
                                        @elseif($order->status == 'Batal') bg-danger @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ ucfirst($order->tipe_order) }}</td>
                                <td>
                                    <!-- Tombol lihat detail (pakai parameter detail_id) -->
                                    <a href="{{ route('admin.pesanan.index', ['detail_id' => $order->id]) }}"
                                        class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada pesanan ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="8" class="text-center py-2">Total Pesanan: {{ $orders->total() }}</td>
>>>>>>> 7a49855 (update error)
                        </tr>
                    </tfoot>
                </table>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<<<<<<< HEAD
=======
<!-- 🧾 DETAIL PESANAN (MUNCUL DI BAWAH TABEL) -->
@if ($detail)
    <div class="container py-4">
        <h3 class="fw-bold mb-4">🧾 Detail Pesanan #ORD{{ str_pad($detail->id, 3, '0', STR_PAD_LEFT) }}</h3>

        <div class="mb-3">
            <p><strong>Nama:</strong> {{ $detail->nama }}</p>
            <p><strong>Telepon:</strong> {{ $detail->telepon }}</p>
            <p><strong>Alamat:</strong> {{ $detail->alamat }}</p>
            <p><strong>Catatan:</strong> {{ $detail->catatan ?? '-' }}</p>
            <p><strong>Status:</strong>
                <span class="badge bg-warning text-dark">{{ ucfirst($detail->status) }}</span>
            </p>
            <p><strong>Tipe Order:</strong> {{ ucfirst($detail->tipe_order) }}</p>
            <p><strong>Ambil:</strong> {{ $detail->tanggal_ambil ?? '-' }}</p>
            <p><strong>Kirim:</strong> {{ $detail->tanggal_kirim ?? '-' }}</p>
        </div>

        <!-- Tabel Produk Pesanan -->
        <div class="card shadow border-0 rounded-3 overflow-hidden">
            <table class="table table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detail->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->product->nama }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Total</td>
                        <td class="fw-bold">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endif

>>>>>>> 7a49855 (update error)
@include('admin.footer')
