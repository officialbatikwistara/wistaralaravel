@include('admin.header')

<div class="container py-4">
    <h3 class="fw-bold mb-4">🧾 Detail Pesanan #{{ $order->id }}</h3>

    <!-- Informasi Pemesan -->
    <div class="mb-4">
        <p><strong>Nama:</strong> {{ $order->nama }}</p>
        <p><strong>Telepon:</strong> {{ $order->telepon }}</p>
        <p><strong>Alamat:</strong> {{ $order->alamat }}</p>
        <p><strong>Catatan:</strong> {{ $order->catatan ?? '-' }}</p>
        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        <p><strong>Status Pembayaran:</strong> {{ ucfirst(str_replace('_', ' ', $order->status_pembayaran)) }}</p>
        <p><strong>Tipe Order:</strong> {{ ucfirst($order->tipe_order) }}</p>
        <p><strong>Tanggal Ambil:</strong> {{ $order->tanggal_ambil ?? '-' }}</p>
        <p><strong>Metode Pembayaran:</strong> {{ ucfirst(str_replace('_', ' ', $order->metode_pembayaran)) }}</p>
    </div>

    <!-- Bukti Pembayaran -->
    @if($order->bukti_pembayaran)
        <div class="mb-4">
            <h5 class="fw-bold mb-2">🧾 Bukti Pembayaran</h5>
            <img src="{{ asset('uploads/bukti/'.$order->bukti_pembayaran) }}"
                alt="Bukti Pembayaran"
                class="img-fluid rounded shadow-sm"
                style="max-width: 350px;">
        </div>
    @endif

    <!-- Form Upload Bukti Pembayaran -->
    @if($order->metode_pembayaran === 'bank_transfer' &&
        $order->status_pembayaran === 'belum_bayar' &&
        $order->status === 'pending')
        <div class="mb-4 border-top pt-3">
            <h5 class="fw-bold mb-3">📤 Upload Bukti Pembayaran (Bank Transfer)</h5>
            <form action="{{ route('user.order.uploadBukti', $order->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <input type="file" name="bukti_pembayaran" accept="image/*" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-warning rounded-pill px-4">
                    <i class="fa-solid fa-upload me-2"></i> Upload Bukti
                </button>
            </form>
        </div>
    @endif

    <!-- Detail Produk -->
    <div class="card shadow border-0 rounded-3 overflow-hidden">
        <table class="table table-striped align-middle m-0">
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
                @foreach($order->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->produk->nama_produk }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end fw-bold">Total</td>
                    <td class="fw-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Tombol Kembali -->
    <div class="mt-4">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            ← Kembali ke Daftar Pesanan
        </a>
    </div>
</div>

@include('admin.footer')
