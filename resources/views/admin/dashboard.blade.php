@include('admin.header')

<style>
  body {
    background-color: #f4f6f9;
    font-family: 'Poppins', sans-serif;
  }

  /* HEADER */
  .dashboard-header {
    background: linear-gradient(135deg, #1c1f26, #2d313b);
    padding: 2rem 5%;
    border-radius: 0 0 25px 25px;
    color: white;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  }

  .dashboard-header h1 {
    font-weight: 700;
    font-size: 2.2rem;
  }

  .dashboard-header i {
    color: #ffc107;
  }

  /* CONTAINER */
  .dashboard-container {
    padding: 50px 5%;
  }

  /* CARD STYLE */
  .dashboard-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 2.2rem 1.8rem;
    text-align: center;
    box-shadow: 0 6px 20px rgba(0,0,0,0.05);
    transition: all 0.4s ease;
    transform: translateY(0);
    opacity: 0;
    animation: fadeInUp 0.8s ease forwards;
  }

  .dashboard-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 25px rgba(255,193,7,0.2);
  }

  @keyframes fadeInUp {
    from {opacity: 0; transform: translateY(30px);}
    to {opacity: 1; transform: translateY(0);}
  }

  .icon-badge {
    font-size: 2.2rem;
    background: linear-gradient(45deg, #ffdd57, #ffc107);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 1rem;
  }

  .dashboard-card h4 {
    font-weight: 600;
    color: #212529;
    margin-bottom: 0.5rem;
  }

  .dashboard-card p {
    color: #6c757d;
    font-size: 0.95rem;
    margin-bottom: 1.5rem;
  }

  .dashboard-btn {
    border-radius: 10px;
    font-weight: 500;
    background-color: #212529;
    color: #fff;
    padding: 8px 16px;
    transition: all 0.3s ease;
  }

  .dashboard-btn:hover {
    background: linear-gradient(45deg, #ffc107, #ffb300);
    color: #212529;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .dashboard-header h1 {
      font-size: 1.8rem;
    }
  }
</style>

<!-- HEADER -->
<div class="dashboard-header">
  <h1><i class="fa-solid fa-chart-line me-2"></i> Dashboard Admin</h1>
  <p class="text-light mb-0">Selamat datang kembali di panel Batik Wistara</p>
</div>

<!-- CONTENT -->
<div class="dashboard-container">
  <div class="row g-4">

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

@include('admin.footer')
