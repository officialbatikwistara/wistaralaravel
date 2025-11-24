@include('admin.header')

<link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">

<!-- ================= HERO SECTION ================= -->
<section class="admin-hero py-4">
    <div class="container">
        <div class="hero-box p-4 rounded-4 shadow-sm d-flex flex-column flex-md-row align-items-md-center justify-content-between">
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

        <!-- ===== CHARTS SECTION ===== -->
        <div class="row g-4 mb-4">

            <!-- Grafik Penjualan -->
            <div class="col-lg-8">
                <div class="chart-card p-3 rounded-4">
                    <h5 class="fw-bold mb-3">Grafik Penjualan Bulanan</h5>
                    <canvas id="salesChart" height="120"></canvas>
                </div>
            </div>

            <!-- Produk Terlaris -->
            <div class="col-lg-4">
                <div class="chart-card p-3 rounded-4">
                    <h5 class="fw-bold mb-3">Produk Terlaris</h5>
                    <canvas id="bestProductChart" height="200"></canvas>
                </div>
            </div>

        </div>

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
                    @foreach($orderBaru as $order)
                    <tr>
                        <td><strong>{{ $order->id }}</strong></td>
                        <td>{{ $order->nama }}</td>
                        <td>Rp {{ number_format($order->final_total, 0, ',', '.') }}</td>
                        <td><span class="badge bg-info">{{ $order->status }}</span></td>
                        <td>{{ date('d M Y', strtotime($order->created_at)) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- ===== MENU CARDS (BOTTOM SHORTCUTS) ===== -->
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

@include('admin.footer')

<!-- CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
/* ===== SALES CHART ===== */
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: @json($bulan),
        datasets: [{
            label: "Penjualan",
            data: @json($penjualan),
            borderColor: "#ff9d00",
            backgroundColor: "rgba(255,157,0,0.3)",
            tension: 0.35,
            borderWidth: 3
        }]
    }
});

/* ===== BEST PRODUCT CHART ===== */
new Chart(document.getElementById('bestProductChart'), {
    type: 'bar',
    data: {
        labels: @json($namaProdukTerlaris),
        datasets: [{
            label: "Jumlah Terjual",
            data: @json($jumlahTerjual),
            backgroundColor: ["#ff9d00", "#ffce73", "#ffc04f"],
        }]
    }
});
</script>
