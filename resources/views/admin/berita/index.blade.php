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
                    <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Beranda</a>
                </li>
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
            <a href="{{ url('/admin/pesanan') }}" class="nav-icon ms-3" title="Pesanan">
                <i class="fa-solid fa-cart-shopping"></i>
            </a>
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
    font-weight: 700;
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

/* 🌸 CARD TABLE */
.card {
    border-radius: 16px;
    overflow: hidden;
    background-color: #fff;
    box-shadow: 0 5px 15px rgba(0,0,0,0.12);
    border: 2px solid #071739;
}

/* ✅ HEADER TABEL WARNA NAVY & PUTIH */
.table thead {
    background-color: #071739 !important;
    color: #ffffff !important;
}

.table thead tr th {
    background-color: #071739 !important;
    color: #ffffff !important;
    border: none;
    padding: 14px;
    font-size: 0.95rem;
    letter-spacing: 0.5px;
    text-align: center;
    vertical-align: middle;
}

/* 🌸 ISI TABEL */
.table tbody tr {
    background-color: #f8f9fb;
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: #e7eef9;
    transform: scale(1.01);
    box-shadow: 0 3px 8px rgba(7, 23, 57, 0.2);
}

.table tbody td {
    vertical-align: middle;
    padding: 14px;
    text-align: center;
    border-top: 1px solid #dee2e6;
}

.table img {
    width: 110px;
    border-radius: 12px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.15);
}

/* 🌸 BUTTON STYLE */
.btn-warning {
    background-color: #f6b400;
    border: none;
    color: #071739;
    transition: all 0.3s ease;
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

/* 🌸 ALERT STYLE */
.alert-success {
    background-color: #d1f0d5;
    border-left: 6px solid #28a745;
    color: #155724;
    font-weight: 500;
}

/* 🌸 RESPONSIVE */
@media (max-width: 768px) {
    .navbar-nav {
        text-align: center;
    }

    .table img {
        width: 70px;
    }
}
</style>


{{-- =========================
    🌸 MAIN CONTENT
   ========================= --}}
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📰 Kelola Berita</h2>
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
    <div class="card border-0 p-3">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Judul</th>
                    <th>Sumber</th>
                    <th>Tanggal</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($berita as $b)
                    <tr>
                        <td>
                            @if($b->gambar)
                                <img src="{{ asset($b->gambar) }}" alt="Gambar Berita">
                            @else
                                <span class="text-muted">Tidak ada</span>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $b->judul }}</td>
                        <td>
                            @if($b->sumber)
                                @if($b->tautan_sumber)
                                    <a href="{{ $b->tautan_sumber }}" target="_blank" class="text-decoration-none text-primary fw-semibold">
                                        {{ $b->sumber }}
                                    </a>
                                @else
                                    {{ $b->sumber }}
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ \Carbon\Carbon::parse($b->tanggal)->format('d M Y') }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.berita.edit', $b->id) }}" class="btn btn-sm btn-warning shadow-sm">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.berita.delete', $b->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Yakin ingin menghapus berita ini?')">
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

@endsection
