<!-- Bootstrap Bundle -->
<footer style="background: linear-gradient(135deg, #071739, #1b2a4a);" class="text-light pt-5 pb-4 mt-5">
    <div class="container">
        <div class="row gy-4 text-center text-md-start align-items-start">

            <!-- Logo & Deskripsi -->
            <div class="col-12 col-md-5">
                <div class="mb-3">
                    <img src="{{ asset('img/logoputih.png') }}" alt="Batik Wistara" height="70">
                </div>
                <p class="mx-auto mx-md-0" style="max-width: 90%;">
                    <strong>Admin Panel Batik Wistara</strong> - Sistem manajemen untuk mengelola produk, pesanan, dan konten website Batik Wistara.
                </p>
            </div>

            <!-- Navigasi Admin -->
            <div class="d-none d-md-block col-md-3">
                <h5 class="fw-bold mb-3">Navigasi Admin</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ url('/admin/dashboard') }}" class="text-light text-decoration-none d-block">Dashboard</a></li>
                    <li><a href="{{ url('/admin/kategori') }}" class="text-light text-decoration-none d-block">Kategori Produk</a></li>
                    <li><a href="{{ url('/admin/produk') }}" class="text-light text-decoration-none d-block">Kelola Produk</a></li>
                    <li><a href="{{ url('/admin/berita') }}" class="text-light text-decoration-none d-block">Kelola Berita</a></li>
                    <li><a href="{{ url('/admin/reviews') }}" class="text-light text-decoration-none d-block">Moderasi Review</a></li>
                </ul>
            </div>

            <!-- Kontak & Support -->
            <div class="col-12 col-md-4">
                <h5 class="fw-bold mb-3">Kontak Support</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong>Alamat:</strong><br>
                        <a href="https://maps.app.goo.gl/WqHPo5eNBDqHykhM8" target="_blank" class="text-light text-decoration-none d-block">
                            Jl. Tambak Medokan Ayu VI C No.56B, Surabaya, Jawa Timur 60295
                        </a>
                    </li>
                    <li class="mb-2">
                        <strong>WhatsApp:</strong><br>
                        <a href="https://wa.me/6281234567890" class="text-light text-decoration-none d-block">
                            0812-3456-7890
                        </a>
                    </li>
                    <li>
                        <strong>Email:</strong><br>
                        <a href="mailto:official.batikwistara@gmail.com" class="text-light text-decoration-none d-block">
                            official.batikwistara@gmail.com
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <hr class="border-secondary my-4">

        <div class="text-center small">
            &copy; {{ date('Y') }} Batik Wistara Admin Panel. Seluruh hak cipta dilindungi.
        </div>
    </div>
</footer>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="{{ asset('css/admin/footer.css') }}">
