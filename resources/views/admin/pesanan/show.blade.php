@include('admin.header')

<div class="container py-4">
    <h3 class="fw-bold mb-4">🧾 Detail Pesanan #{{ $order->id }}</h3>

    <div class="mb-3">
        <p><strong>Nama:</strong> {{ $order->nama }}</p>
        <p><strong>Telepon:</strong> {{ $order->telepon }}</p>
        <p><strong>Alamat:</strong> {{ $order->alamat }}</p>
        <p><strong>Catatan:</strong> {{ $order->catatan ?? '-' }}</p>
        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        <p><strong>Tipe Order:</strong> {{ ucfirst($order->tipe_order) }}</p>
        <p><strong>Tanggal Ambil:</strong> {{ $order->tanggal_ambil }}</p>
    </div>

    <div class="card shadow border-0 rounded-3 overflow-hidden">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    @if($order->metode_pembayaran === 'bank_transfer' && $order->status_pembayaran === 'belum_bayar' && $order->status == 'pending')
                    <hr>
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
                    @endif
                    @if($order->bukti_pembayaran)
                    <hr>
                    <h5 class="fw-bold mb-2">🧾 Bukti Pembayaran</h5>
                    <img src="{{ asset('uploads/bukti/'.$order->bukti_pembayaran) }}" 
                        alt="Bukti Pembayaran" 
                        class="img-fluid rounded shadow-sm mb-3" 
                        style="max-width: 350px;">
                    @endif


                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                <tr>
                    <td>{{ $index+1 }}</td>
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
</div>

@include('admin.footer')
