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
                    @foreach($orderBaru as $order)
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
                    <small class="text-muted">Tips: klik salah satu batang untuk drill-down ke penjualan per produk.</small>
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
                <button type="button" class="btn btn-outline-dark btn-sm" id="sales-back-btn" hidden>Level Bulanan</button>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartElement = document.querySelector('#sales-chart');
        const backButton = document.querySelector('#sales-back-btn');

        if (!chartElement || !backButton) {
            return;
        }

        const currencyFormatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        });

        let analyticsData = null;
        let currentLevel = 'summary';
        let productList = [];
        let productLabels = [];
        let quarterSlices = [];
        let currentQuarterIndex = 0;
        let visibleSummaryItems = [];

        const chart = new ApexCharts(chartElement, {
            chart: {
                type: 'bar',
                height: 400,
                toolbar: { show: false },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 1000,
                    animateGradually: { enabled: true, delay: 150 },
                    dynamicAnimation: { enabled: true, speed: 400 }
                },
                events: {
                    dataPointSelection(event, chartContext, config) {
                        if (!visibleSummaryItems.length || currentLevel !== 'summary') {
                            return;
                        }

                        const clickedIndex = config.dataPointIndex;
                        const selected = visibleSummaryItems[clickedIndex];

                        if (selected) {
                            renderDetail(selected.key, selected.label);
                        }
                    }
                }
            },
            colors: ['#d4af37'],
            plotOptions: {
                bar: {
                    horizontal: true, // animasi kiri -> kanan
                    barHeight: '55%',
                    borderRadius: 8
                }
            },
            series: [],
            xaxis: {
                categories: [],
                labels: {
                    style: { fontSize: '12px' }
                }
            },
            yaxis: {
                labels: {
                    style: { fontSize: '12px' }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: (value) => currencyFormatter.format(value)
            },
            tooltip: {
                y: {
                    formatter: (value) => currencyFormatter.format(value)
                }
            },
            noData: {
                text: 'Memuat data penjualan...',
                align: 'center',
                style: {
                    fontSize: '14px',
                    color: '#071739'
                }
            }
        });

        chart.render();

        const toValidNumber = (value) => {
            const numericValue = typeof value === 'number' ? value : parseFloat(value);
            return Number.isFinite(numericValue) ? numericValue : 0;
        };

        const applyCurrencyFormatting = (maxValue = 0, categories = null, labelCssClass = '') => {
            // Tentukan step dinamis supaya axis tidak terlalu rapat untuk nilai besar
            const niceSteps = [100_000, 250_000, 500_000, 1_000_000, 2_500_000, 5_000_000, 10_000_000];
            const targetMax = Math.max(toValidNumber(maxValue), 100_000);
            const pickedStep = niceSteps.find((s) => targetMax / s <= 8) || 10_000_000;
            const safeMax = Math.ceil(targetMax / pickedStep) * pickedStep;
            const tickAmount = Math.max(Math.round(safeMax / pickedStep), 2);

            let customFormatter = (value, index) => currencyFormatter.format(toValidNumber(value));

            if (Array.isArray(categories)) {
                const labelCount = categories.length;
                const viewportWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

                if (viewportWidth && viewportWidth < 400 && labelCount > 6) {
                    customFormatter = (value, index) => {
                        const displayIndex = index ?? categories.indexOf(value);
                        if (displayIndex === 0 || displayIndex === labelCount - 1 || displayIndex % 3 === 0) {
                            return currencyFormatter.format(toValidNumber(value));
                        }
                        return '';
                    };
                }
            }

            const xaxisConfig = {
                min: 0,
                max: safeMax,
                tickAmount,
                labels: {
                    formatter: customFormatter,
                    style: {
                        fontSize: '12px',
                        ...(labelCssClass ? { cssClass: labelCssClass } : {})
                    }
                }
            };

            if (Array.isArray(categories)) {
                xaxisConfig.categories = categories;
            }

            chart.updateOptions({
                xaxis: xaxisConfig,
                dataLabels: {
                    enabled: true,
                    formatter: (value) => currencyFormatter.format(toValidNumber(value))
                },
                tooltip: {
                    y: {
                        formatter: (value) => currencyFormatter.format(toValidNumber(value))
                    }
                }
            }, false, true);
        };

        const applyQuantityFormatting = (maxValue = 0, categories = null, labelCssClass = '') => {
            const step = 1;
            const targetMax = Math.max(toValidNumber(maxValue), step);
            const safeMax = Math.max(10, Math.ceil(targetMax / step) * step);
            const tickAmount = Math.max(Math.round(safeMax / step), 1);

            let customFormatter = (value, index) => `${toValidNumber(value)}`;

            if (Array.isArray(categories)) {
                const labelCount = categories.length;
                const viewportWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

                if (viewportWidth && viewportWidth < 400 && labelCount > 6) {
                    customFormatter = (value, index) => {
                        if (index === 0 || index === labelCount - 1 || index % 3 === 0) {
                            return `${toValidNumber(value)}`;
                        }
                        return '';
                    };
                }
            }

            const xaxisConfig = {
                min: 0,
                max: safeMax,
                tickAmount,
                labels: {
                    formatter: customFormatter,
                    style: {
                        fontSize: '12px',
                        ...(labelCssClass ? { cssClass: labelCssClass } : {})
                    }
                }
            };

            if (Array.isArray(categories)) {
                xaxisConfig.categories = categories;
            }

            chart.updateOptions({
                xaxis: xaxisConfig,
                dataLabels: {
                    enabled: true,
                    formatter: (value) => `${toValidNumber(value)}`
                },
                tooltip: {
                    y: {
                        formatter: (value) => `${toValidNumber(value)} pcs`
                    }
                }
            }, false, true);
        };

        const quarterPrevBtn = document.querySelector('#quarter-prev-btn');
        const quarterNextBtn = document.querySelector('#quarter-next-btn');
        const quarterLabel = document.querySelector('#quarter-label');
        const quarterNav = document.querySelector('.quarter-nav');
        const summaryCurrencyLabelClass = 'apexcharts-currency-vertical';

        const wrapCategoryLabel = (label = '', maxLength = 18) => {
            if (typeof label !== 'string') {
                return '';
            }

            const normalized = label.trim();
            if (normalized.length <= maxLength) {
                return normalized;
            }

            const words = normalized.split(' ');
            const lines = [];
            let currentLine = '';

            words.forEach((word) => {
                const tentative = currentLine ? `${currentLine} ${word}` : word;
                if (tentative.length > maxLength) {
                    if (currentLine) {
                        lines.push(currentLine);
                    }

                    if (word.length > maxLength) {
                        for (let i = 0; i < word.length; i += maxLength) {
                            lines.push(word.slice(i, i + maxLength));
                        }
                        currentLine = '';
                    } else {
                        currentLine = word;
                    }
                } else {
                    currentLine = tentative;
                }
            });

            if (currentLine) {
                lines.push(currentLine);
            }

            return lines.join('\n');
        };

        function prepareQuarterSlices(summary = []) {
            if (!Array.isArray(summary) || !summary.length) {
                return [];
            }

            const sorted = [...summary].sort((a, b) => {
                return new Date(a.key + '-01') - new Date(b.key + '-01');
            });

            const slices = [];
            for (let i = 0; i < sorted.length; i += 3) {
                const chunk = sorted.slice(i, i + 3);
                if (!chunk.length) continue;
                const quarterLabel = buildQuarterLabel(chunk[0]);
                slices.push({
                    label: quarterLabel,
                    items: chunk,
                });
            }
            return slices;
        }

        function buildQuarterLabel(item) {
            if (!item || !item.key) return '-';
            const [year, month] = item.key.split('-');
            const quarter = Math.floor((parseInt(month, 10) - 1) / 3) + 1;
            return `Q${quarter} ${year}`;
        }

        function updateQuarterNav() {
            if (!quarterSlices.length) {
                quarterLabel.textContent = '-';
                quarterPrevBtn.disabled = true;
                quarterNextBtn.disabled = true;
                return;
            }
            quarterLabel.textContent = quarterSlices[currentQuarterIndex]?.label ?? '-';
            quarterPrevBtn.disabled = currentQuarterIndex === 0;
            quarterNextBtn.disabled = quarterSlices.length && currentQuarterIndex === quarterSlices.length - 1;
        }

        fetch('{{ route('admin.sales.data') }}')
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Gagal memuat data penjualan');
                }
                return response.json();
            })
            .then((data) => {
                analyticsData = data;
                productList = Array.isArray(data.products) ? data.products : [];
                productLabels = productList.map((product) => product.name);
                quarterSlices = prepareQuarterSlices(data.summary || []);
                currentQuarterIndex = quarterSlices.length ? quarterSlices.length - 1 : 0;
                renderSummary();
            })
            .catch((error) => {
                console.error(error);
                chart.updateOptions({
                    noData: { text: error.message || 'Data penjualan gagal dimuat' }
                });
            });

        backButton.addEventListener('click', () => {
            renderSummary();
        });

        quarterPrevBtn.addEventListener('click', () => {
            if (currentQuarterIndex > 0) {
                currentQuarterIndex -= 1;
                renderSummary();
            }
        });

        quarterNextBtn.addEventListener('click', () => {
            if (currentQuarterIndex < quarterSlices.length - 1) {
                currentQuarterIndex += 1;
                renderSummary();
            }
        });

        function renderSummary() {
            if (!quarterSlices.length) {
                chart.updateSeries([]);
                chart.updateOptions({
                    noData: { text: 'Belum ada data penjualan' }
                });
                visibleSummaryItems = [];
                applyCurrencyFormatting(0, [], summaryCurrencyLabelClass);
                updateQuarterNav();
                return;
            }

            currentLevel = 'summary';
            backButton.hidden = true;
            if (quarterNav) {
                quarterNav.hidden = false;
            }

            const activeSlice = quarterSlices[currentQuarterIndex] || { items: [] };
            visibleSummaryItems = activeSlice.items;

            const labels = activeSlice.items.map((item) => item.label);
            const totals = activeSlice.items.map((item) => item.total);
            const maxTotal = totals.reduce((acc, value) => Math.max(acc, value), 0);

            chart.updateOptions({
                title: {
                    text: 'Total Penjualan Bulanan',
                    style: { fontSize: '18px' }
                },
                subtitle: {
                    text: 'Klik batang bulan tertentu untuk melihat penjualan per produk',
                    style: { fontSize: '13px' }
                }
            });

            chart.updateSeries([
                {
                    name: 'Total Penjualan',
                    data: totals
                }
            ]);

            applyCurrencyFormatting(maxTotal, labels, summaryCurrencyLabelClass);
            updateQuarterNav();
        }

        function renderDetail(monthKey, label) {
            currentLevel = 'detail';
            backButton.hidden = false;
            if (quarterNav) {
                quarterNav.hidden = true;
            }

            const detail =
                analyticsData && analyticsData.details && analyticsData.details[monthKey]
                    ? analyticsData.details[monthKey]
                    : [];

            if (!detail.length) {
                applyQuantityFormatting(0, []);
                chart.updateSeries([]);
                chart.updateOptions({
                    title: { text: `Detail Penjualan (${label})` },
                    subtitle: { text: 'Belum ada data produk pada bulan ini' },
                    noData: { text: 'Detail produk belum tersedia' }
                });
                return;
            }

            const detailCategories = detail.map((item) => wrapCategoryLabel(item.name));

            chart.updateOptions({
                title: { text: `Penjualan per Produk (${label})` },
                subtitle: { text: 'Tekan "Level Bulanan" untuk kembali' }
            });

            chart.updateSeries([
                {
                    name: 'Qty Terjual',
                    data: detail.map((item) => item.qty)
                }
            ]);

            const maxQty = detail.reduce((acc, item) => Math.max(acc, item.qty), 0);
            applyQuantityFormatting(maxQty, detailCategories);
        }
    });
</script>
