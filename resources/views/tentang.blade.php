{{-- Header dan Navbar --}}
@include('inc.header')
<!-- ===== Page Header / Banner Section ===== -->
<section class="page-header position-relative d-flex align-items-center justify-content-center text-center text-light" style="min-height: 250px;">
  <div class="overlay" style="position:absolute; inset:0; background:rgba(0,0,0,0.5);"></div>
  <div class="container position-relative">
    <h1 class="fw-bold page-title">Tentang Kami</h1>
  </div>
</section>

<!-- ===== Section Tentang Kami ===== -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="row align-items-center g-4 flex-column-reverse flex-md-row text-center text-md-start">
      
      <!-- Gambar / Logo -->
      <div class="col-12 col-md-6 d-flex justify-content-center">
        <img 
          src="{{ asset('img/logowarna.png') }}" 
          alt="Tentang Batik Wistara" 
          class="img-fluid"
          style="max-width: 300px; height: auto;">
      </div>

      <!-- Deskripsi -->
      <div class="col-12 col-md-6">
        <h2 class="fw-bold mb-3 text-warning">Batik Wistara</h2>
        <p class="mb-3">
          <strong>Batik Wistara</strong> adalah brand batik modern yang mengusung nilai-nilai tradisional Indonesia dengan sentuhan estetika masa kini. Kami berdedikasi untuk memperkenalkan keindahan batik kepada generasi muda, sekaligus memberdayakan pengrajin lokal agar terus berkarya melalui produk-produk autentik dan berkualitas tinggi.
        </p>
        <p class="mb-4">
          Berbasis di Indonesia, Batik Wistara bekerja sama langsung dengan komunitas pengrajin dari berbagai daerah. Setiap produk kami tidak hanya memancarkan keindahan visual, tetapi juga memiliki cerita dan filosofi budaya yang mendalam.
        </p>
        <div class="d-grid d-sm-inline-block">
          <a href="{{ url('/katalog') }}" class="btn btn-dark px-4 py-2">Lihat Koleksi Kami</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== Section Visi & Misi ===== -->
<section class="py-5 bg-white">
  <div class="container text-center">
    <h2 class="fw-bold mb-4 text-warning">Visi & Misi Kami</h2>
    <p class="mb-5 text-muted">
      Komitmen kami adalah menjaga warisan budaya Indonesia melalui pelestarian dan inovasi batik yang berkelanjutan.
    </p>

    <div class="row justify-content-center g-4">
      
      <!-- Visi -->
      <div class="col-12 col-md-5">
        <div class="card h-100 border-0 shadow-sm">
          <div class="card-body p-4 d-flex flex-column justify-content-center">
            <h4 class="fw-bold mb-3 text-uppercase text-warning">Visi</h4>
            <p class="mb-0 text-secondary">
              Menjadi pelopor dalam pelestarian dan inovasi batik Indonesia melalui pendekatan yang modern, inklusif, dan berkelanjutan.
            </p>
          </div>
        </div>
      </div>

      <!-- Misi -->
      <div class="col-12 col-md-5">
        <div class="card h-100 border-0 shadow-sm">
          <div class="card-body p-4">
            <h4 class="fw-bold mb-3 text-uppercase text-warning">Misi</h4>
            <ul class="mb-0 text-start text-secondary">
              <li class="mb-2">Memproduksi batik berkualitas tinggi dengan desain elegan dan kontemporer.</li>
              <li class="mb-2">Memberdayakan pengrajin lokal melalui kolaborasi yang adil dan berkelanjutan.</li>
              <li class="mb-2">Mengedukasi masyarakat tentang nilai budaya dan sejarah batik Indonesia.</li>
            </ul>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- Footer --}}
@include('inc.footer')
