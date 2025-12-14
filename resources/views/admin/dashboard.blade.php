@include('admin.header')

<link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">

<!-- ================= HERO SECTION ================= -->
<section class="admin-hero py-4">
    <div class="container">
        <div
            class="hero-box p-4 rounded-4 shadow-sm d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <div class="hero-text">
                <h2 class="fw-bold mb-1">
                    <i class="fa-solid fa-chart-line me-2 text-warning"></i> Dashboard Admin
                </h2>
                <p class="text-muted mb-0">Pantau performa toko Batik Wistara secara real-time</p>
            </div>
            <div class="hero-meta mt-3 mt-md-0 text-md-end">
                <h6 class="fw-semibold mb-1">{{ date('d M Y') }}</h6>
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm">Real-Time Data</span>
            </div>
        </div>
    </div>
</section>

<!-- ================= MAIN CONTENT ================= -->
<section class="dashboard-main py-4">
    <div class="container">

        <!-- ===== TOP STATISTICS ===== -->
        <div class="row g-4 mb-4">

            <!-- Total Produk -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-primary"><i class="fa-solid fa-shirt"></i></div>
                    <div>
                        <h3>{{ $totalProduk }}</h3>
                        <p>Total Produk</p>
                    </div>
                </div>
            </div>

            <!-- Total Pesanan -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-success"><i class="fa-solid fa-cart-shopping"></i></div>
                    <div>
                        <h3>{{ $totalOrder }}</h3>
                        <p>Total Pesanan</p>
                    </div>
                </div>
            </div>

            <!-- Total Pendapatan -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-warning"><i class="fa-solid fa-coins"></i></div>
                    <div>
                        <h3>Rp {{ number_format($pendapatan, 0, ',', '.') }}</h3>
                        <p>Total Pendapatan</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- (Bagian CHART.JS DIHAPUS, SESUAI PERMINTAAN) -->

        <!-- ===== TABEL PESANAN TERBARU ===== -->
        <div class="card shadow-sm p-3 rounded-4 mb-5">
            <h5 class="fw-bold mb-3">Pesanan Terbaru</h5>
            <table class="table table-hover align-middle">
                <thead class="table-warning">
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderBaru as $order)
                        <tr>
                            <td><strong>{{ $order->id }}</strong></td>
                            <td>{{ $order->nama }}</td>
                            <td>Rp {{ number_format($order->final_total ?? $order->total, 0, ',', '.') }}</td>
                            <td><span class="badge bg-info">{{ $order->status }}</span></td>
                            <td>{{ date('d M Y', strtotime($order->created_at)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</section>

<!-- ===== SALES ANALYTICS SECTION (APEXCHARTS) ===== -->
<section class="dashboard-analytics bg-megamendung">
    <div class="container">
        <div class="analytics-card">
            <div class="analytics-header">
                <div>
                    <h3 class="mb-1">Monitoring Penjualan</h3>
                    <p class="text-muted mb-0">Ringkasan transaksi 12 bulan terakhir / klik untuk detail produk</p>
                    <small class="text-muted">Tips: klik salah satu batang untuk drill-down ke penjualan per
                        produk.</small>
                </div>
                <div class="quarter-nav">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="quarter-prev-btn">
                        <i class="fa-solid fa-chevron-left me-1"></i> Quartal Sebelumnya
                    </button>
                    <span class="quarter-label text-muted mx-2" id="quarter-label">-</span>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="quarter-next-btn">
                        Quartal Berikutnya <i class="fa-solid fa-chevron-right ms-1"></i>
                    </button>
                </div>
                <button type="button" class="btn btn-outline-dark btn-sm" id="sales-back-btn" hidden>Level
                    Bulanan</button>
            </div>
            <div id="sales-chart" class="analytics-chart"></div>
            <div class="analytics-legend mt-3">

            </div>
        </div>
    </div>
</section>

<!-- ===== MENU CARDS (BOTTOM SHORTCUTS) ===== -->
<section class="py-4">
    <div class="container">
        <h4 class="fw-bold mb-3">Manajemen Toko</h4>

        <div class="row g-4">

            <div class="col-md-6 col-lg-3">
                <div class="dashboard-card">
                    <div class="icon-badge"><i class="fa-solid fa-layer-group"></i></div>
                    <h4>Kategori</h4>
                    <p>Kelola kategori produk Wistara.</p>
                    <a href="{{ url('/admin/kategori') }}" class="btn dashboard-btn w-100">Kelola Kategori</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="dashboard-card">
                    <div class="icon-badge"><i class="fa-solid fa-shirt"></i></div>
                    <h4>Produk</h4>
                    <p>Tambah & kelola koleksi Batik Wistara.</p>
                    <a href="{{ url('/admin/produk') }}" class="btn dashboard-btn w-100">Kelola Produk</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="dashboard-card">
                    <div class="icon-badge"><i class="fa-solid fa-box"></i></div>
                    <h4>Pesanan</h4>
                    <p>Pantau transaksi & atur status pesanan.</p>
                    <a href="{{ url('/admin/pesanan') }}" class="btn dashboard-btn w-100">Kelola Pesanan</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="dashboard-card">
                    <div class="icon-badge"><i class="fa-solid fa-star"></i></div>
                    <h4>Review</h4>
                    <p>Kelola ulasan pelanggan.</p>
                    <a href="{{ url('/admin/reviews') }}" class="btn dashboard-btn w-100">Kelola Review</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ===== FOOTER DECORATION ===== -->
<div class="footer-image"></div>

@include('admin.footer')

<!-- APEXCHARTS -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="{{ asset('js/dashboardadmin.js') }}"></script>
<script>
    window.APP_CONFIG = {
        salesDataUrl: "{{ route('admin.sales.data') }}"
    };
</script>
