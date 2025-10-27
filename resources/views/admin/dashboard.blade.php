@include('admin.header')

<style>
  body {
    background-color: #f8f9fa;
    font-family: 'Poppins', sans-serif;
    overflow-x: hidden;
  }

  /* ===== HERO SECTION ===== */
  .hero-admin {
    position: relative;
    background: url('{{ asset("img/background1.svg") }}') no-repeat center/cover;
    height: 70vh;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    color: #fff;
    overflow: hidden;
  }

  .hero-admin::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(7, 23, 57, 0.65);
    z-index: 0;
  }

  .hero-content {
    position: relative;
    z-index: 2;
  }

  .hero-content h1 {
    font-size: 2.8rem;
    font-weight: 700;
    color: #f8f9fa;
  }

  .hero-content p {
    color: #dcdcdc;
    font-size: 1.1rem;
  }

  /* ===== MAIN CONTAINER ===== */
  .dashboard-main {
    background: url('{{ asset("img/background2.svg") }}') repeat;
    background-size: contain;
    padding: 80px 5%;
  }

  /* ===== CARD STYLE ===== */
  .dashboard-card {
    background: #fff;
    border-radius: 20px;
    padding: 2rem 1.5rem;
    text-align: center;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    border-top: 5px solid #d4af37;
    transition: all 0.35s ease;
  }

  .dashboard-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 25px rgba(212,175,55,0.3);
  }

  .dashboard-card .icon-badge {
    font-size: 2.8rem;
    color: #071739;
    margin-bottom: 1rem;
  }

  .dashboard-card h4 {
    font-weight: 700;
    color: #071739;
  }

  .dashboard-card p {
    color: #4b6382;
    font-size: 0.95rem;
    margin-bottom: 1.5rem;
  }

  .dashboard-btn {
    background-color: #071739;
    color: #fff;
    border-radius: 10px;
    padding: 10px 18px;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .dashboard-btn:hover {
    background: #d4af37;
    color: #071739;
  }

  /* ===== FOOTER IMAGE ===== */
  .footer-image {
    background: url('{{ asset("img/background3.svg") }}') no-repeat center/cover;
    height: 200px;
    opacity: 0.2;
    margin-top: 80px;
  }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 768px) {
    .hero-content h1 { font-size: 2rem; }
    .dashboard-main { padding: 50px 3%; }
  }
</style>

<!-- ===== HERO SECTION ===== -->
<section class="hero-admin">
  <div class="hero-content">
    <h1><i class="fa-solid fa-chart-line me-2 text-warning"></i> Dashboard Admin</h1>
    <p>Selamat datang Admin Batik Wistara <span class="text-warning">Batik Wistara</span></p>
  </div>
</section>

<!-- ===== MAIN DASHBOARD CONTENT ===== -->
<section class="dashboard-main">
  <div class="container">
    <div class="row g-4 justify-content-center">

      <div class="col-md-6 col-lg-3">
        <div class="dashboard-card">
          <div class="icon-badge"><i class="fa-solid fa-layer-group"></i></div>
          <h4>Kategori Produk</h4>
          <p>Kelola kategori produk Batik Wistara & tambahkan koleksi baru.</p>
          <a href="{{ url('/admin/kategori') }}" class="btn dashboard-btn w-100">Kelola Kategori</a>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="dashboard-card">
          <div class="icon-badge"><i class="fa-solid fa-shirt"></i></div>
          <h4>Produk</h4>
          <p>Kelola katalog produk Batik Wistara, tambah dan ubah koleksi.</p>
          <a href="{{ url('/admin/produk') }}" class="btn dashboard-btn w-100">Kelola Produk</a>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="dashboard-card">
          <div class="icon-badge"><i class="fa-solid fa-box"></i></div>
          <h4>Pesanan</h4>
          <p>Kelola transaksi & pantau status pesanan pelanggan.</p>
          <a href="{{ url('/admin/pesanan') }}" class="btn dashboard-btn w-100">Kelola Pesanan</a>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="dashboard-card">
          <div class="icon-badge"><i class="fa-solid fa-newspaper"></i></div>
          <h4>Berita</h4>
          <p>Posting informasi & artikel terbaru untuk pengguna.</p>
          <a href="{{ url('/admin/berita') }}" class="btn dashboard-btn w-100">Kelola Berita</a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ===== FOOTER DECORATION ===== -->
<div class="footer-image"></div>

@include('admin.footer')
