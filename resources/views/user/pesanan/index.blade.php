@include('inc.header')

<div class="dashboard-page" style="padding-top: 120px; background: url('{{ asset('img/bghero.svg') }}') center/cover no-repeat;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">

                <!-- 👤 Header -->
                <div class="card shadow-lg rounded-4 p-4 text-center border-0 mb-4">
                    <i class="fa-solid fa-shopping-cart fa-3x text-primary mb-3"></i>
                    <h2 class="fw-bold mb-2 text-dark">Pesanan Saya</h2>
                    <p class="text-muted mb-0">Kelola semua pesanan yang telah Anda buat</p>
                </div>

                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- 📦 Daftar Pesanan -->
                <div class="card shadow-lg rounded-4 p-4 border-0">
                    <h4 class="fw-bold mb-4">
                        <i class="fa-solid fa-list me-2 text-dark"></i> Daftar Pesanan
                    </h4>

                    @if ($orders->count() > 0)
                        @foreach ($orders as $order)
                            <div class="border rounded-4 p-3 mb-3 shadow-sm bg-light">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-bold mb-0">Order #{{ $order->id }}</h6>
                                    <small class="text-muted">{{ $order->created_at->format('d M Y H:i') }}</small>
                                </div>

                                <div class="mb-2">
                                    <span class="badge bg-primary me-2">{{ $order->metode_pembayaran }}</span>
                                    @if ($order->status === 'pending')
                                        <span class="badge bg-warning text-dark">⏳ Pending</span>
                                    @elseif ($order->status === 'diproses')
                                        <span class="badge bg-info">🔄 Diproses</span>
                                    @elseif ($order->status === 'dikirim')
                                        <span class="badge bg-primary">🚚 Dikirim</span>
                                    @elseif ($order->status === 'selesai')
                                        <span class="badge bg-success">✅ Selesai</span>
                                    @else
                                        <span class="badge bg-danger">❌ Batal</span>
                                    @endif
                                </div>

                                <p class="mb-2 text-secondary">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</p>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('user.order.show', $order->id) }}" class="btn btn-primary btn-sm flex-fill rounded-pill">
                                        <i class="fa-solid fa-eye me-1"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fa-solid fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted mb-2">Belum Ada Pesanan</h5>
                            <p class="text-muted mb-4">Anda belum membuat pesanan apapun.</p>
                            <a href="{{ route('katalog') }}" class="btn btn-primary rounded-pill px-4">
                                <i class="fa-solid fa-store me-2"></i> Mulai Belanja
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('inc.footer')
