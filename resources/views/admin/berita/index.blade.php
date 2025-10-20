@extends('layouts.admin')

@section('title', 'Kelola Berita')

@section('content')

{{-- =========================
    🌸 HEADER ADMIN
   ========================= --}}
<header class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="#">🌸 Batik Wistara Admin</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/kategori*') ? 'active' : '' }}" href="{{ route('admin.kategori.index') }}">Kategori Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/produk*') ? 'active' : '' }}" href="{{ route('admin.produk.index') }}">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/berita*') ? 'active' : '' }}" href="{{ route('admin.berita.index') }}">Berita</a>
                </li>
            </ul>

            {{-- 🛒 Icon Pesanan --}}
            @if (Route::has('admin.pesanan.index'))
                <a href="{{ route('admin.pesanan.index') }}" class="nav-icon ms-3" title="Pesanan">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>
            @else
                <a href="#" class="nav-icon ms-3" title="Pesanan">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>
            @endif
        </div>
    </div>
</header>

<style>
/* ===============================
   🌸 GLOBAL STYLING ADMIN PAGE
   =============================== */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f0f3f8;
    background-image:
        url("{{ asset('storage/img/background1.svg') }}"),
        url("{{ asset('storage/img/background2.svg') }}"),
        url("{{ asset('storage/img/background3.svg') }}");
    background-repeat: no-repeat, no-repeat, no-repeat;
    background-position: top left, center center, bottom right;
    background-attachment: fixed;
    background-size: cover;
    min-height: 100vh;
    margin: 0;
    padding-top: 90px;
}

/* 🌸 HEADER STYLE */
header.navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    background-color: #071739;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    padding: 12px 0;
}

.navbar-brand {
    font-weight: 700;
    color: #f6b400 !important;
    letter-spacing: 1px;
}

.navbar-nav .nav-link {
    color: #fff !important;
    font-weight: 500;
    margin: 0 12px;
    transition: 0.3s;
}

.navbar-nav .nav-link:hover,
.navbar-nav .nav-link.active {
    color: #f6b400 !important;
}

.nav-icon {
    color: #f6b400;
    font-size: 1.4rem;
    margin-left: 15px;
    transition: 0.3s;
}

.nav-icon:hover {
    color: white;
}

/* 🌸 CONTENT STYLE */
h2 {
    color: #071739;
}

.btn-dark {
    background-color: #071739;
    border: none;
    transition: all 0.3s ease;
}

.btn-dark:hover {
    background-color: #f6b400;
    color: #071739;
}

.card {
    border-radius: 15px;
    overflow: hidden;
    background-color: #fff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.table-dark {
    background-color: #071739 !important;
}

.table th {
    color: #f6b400;
    border-bottom: 2px solid #f6b400;
}

.btn-warning {
    background-color: #f6b400;
    border: none;
    color: #071739;
}

.btn-warning:hover {
    background-color: #d8a100;
    color: white;
}

.btn-danger {
    background-color: #c0392b;
    border: none;
}

.btn-danger:hover {
    background-color: #a93226;
}

.alert-success {
    background-color: #d1f0d5;
    border-left: 6px solid #28a745;
    color: #155724;
    font-weight: 500;
}

@media (max-width: 768px) {
    .navbar-nav {
        text-align: center;
    }
}
</style>

{{-- =========================
    🌸 MAIN CONTENT
   ========================= --}}
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">📰 Kelola Berita</h2>
        <a href="{{ route('admin.berita.create') }}" class="btn btn-dark shadow-sm">
            <i class="fa-solid fa-plus me-2"></i> Tambah Berita
        </a>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- TABLE --}}
    <div class="card border-0">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 130px;">Gambar</th>
                        <th>Judul</th>
                        <th>Sumber</th>
                        <th>Tanggal</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($berita as $b)
                        <tr>
                            <td>
                                @if($b->gambar)
                                    <img src="{{ asset($b->gambar) }}" class="img-fluid rounded shadow-sm" alt="Gambar Berita">
                                @else
                                    <span class="text-muted">Tidak ada</span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $b->judul }}</td>
                            <td>
                                @if($b->sumber)
                                    @if($b->tautan_sumber)
                                        <a href="{{ $b->tautan_sumber }}" target="_blank" class="text-decoration-none text-primary">
                                            {{ $b->sumber }}
                                        </a>
                                    @else
                                        {{ $b->sumber }}
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($b->tanggal)->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.berita.edit', $b->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('admin.berita.delete', $b->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus berita ini?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada berita</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
