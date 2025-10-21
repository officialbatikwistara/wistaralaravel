@include('inc.header')
<div class="detailorder-page" style="padding-top: 120px; background: url('{{ asset('img/bghero.svg') }}') center/cover no-repeat;">
    <div class="container py-5" style="padding-top: 120px; max-width: 800px;">
        <div class="card shadow-lg rounded-4 border-0 p-4">
            <h3 class="fw-bold mb-3">
                <i class="fa-solid fa-receipt me-2 text-dark"></i> Detail Pesanan #{{ $order->id }}
            </h3>

            <div class="mb-3">
                <p><strong>Tanggal Pesan:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                <p><strong>Tanggal Ambil:</strong> {{ \Carbon\Carbon::parse($order->tanggal_ambil)->format('d M Y') }}</p>
                <p><strong>Metode Pembayaran:</strong> 
                    @if($order->metode_pembayaran === 'bank_transfer') 🏦 Bank Transfer 
                    @elseif($order->metode_pembayaran === 'qris') 📱 QRIS 
                    @else 💵 COD 
                    @endif
                </p>
                <p><strong>Status:</strong>
                    @if($order->status == 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @elseif($order->status == 'proses')
                        <span class="badge bg-primary">Diproses</span>
                    @elseif($order->status == 'selesai')
                        <span class="badge bg-success">Selesai</span>
                    @else
                        <span class="badge bg-danger">Batal</span>
                    @endif
                </p>
            </div>

            <hr>

            <h5 class="fw-bold mb-3">🛍️ Barang yang Dipesan</h5>
            <div class="table-responsive mb-4">
                <table class="table align-middle">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset($item->produk->gambar) }}" 
                                        alt="{{ $item->produk->nama_produk }}" 
                                        style="width: 50px; height: 50px; object-fit: cover;" 
                                        class="rounded shadow-sm border">
                                    <div>
                                        <span class="fw-semibold">{{ $item->produk->nama_produk }}</span><br>
                                        <small class="text-muted">#{{ $item->id_produk }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->qty }}</td>
                            <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center p-3 bg-dark text-white rounded-3 shadow-sm">
                <strong>Total</strong>
                <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
            </div>

            @if($order->status == 'pending')
            <div class="text-end mt-4">
                <form action="{{ route('user.order.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Yakin batalkan pesanan ini?')">
                    @csrf
                    <button type="submit" class="btn btn-danger rounded-pill px-4">
                        <i class="fa-solid fa-times me-2"></i> Batalkan Pesanan
                    </button>
                </form>
            </div>
            @endif

            <div class="mt-3">
                <a href="{{ url('/user/dashboard') }}" class="btn btn-outline-dark rounded-pill px-4">
                    <i class="fa-solid fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@include('inc.footer')