@extends('layouts.admin')

@section('title', 'Kelola Kategori')

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
   🌸 STYLE GLOBAL ADMIN
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

/* 🌸 HEADER */
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

/* 🌸 CONTENT */
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

/* 🌸 TABEL NAVY STYLE */
.table thead {
    background-color: #071739 !important;
}

.table thead th {
    color: #f6b400 !important;
    font-weight: 600;
    border-bottom: 2px solid #f6b400;
}

.table tbody tr:hover {
    background-color: #071739; !important;
    transition: 0.3s;
}

.table td {
    color: #071739;
}

/* 🌸 BUTTONS */
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
</style>

{{-- =========================
    🌸 MAIN CONTENT
   ========================= --}}
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">📂 Kelola Kategori</h2>
        <a href="{{ route('admin.kategori.create') }}" class="btn btn-dark shadow-sm">
            <i class="fa-solid fa-plus me-2"></i> Tambah Kategori
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
                <thead>
                    <tr>
                        <th style="width: 10%">#</th>
                        <th>Nama Kategori</th>
                        <th style="width: 20%">Tanggal</th>
                        <th style="width: 15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategori as $k)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $k->nama_kategori }}</td>
                            <td>{{ \Carbon\Carbon::parse($k->created_at)->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                    {{-- Tombol Edit --}}
                                        <a href="{{ route('admin.kategori.edit', $k->id_kategori) }}"
                                        class="btn btn-sm btn-warning shadow-sm"
                                        title="Edit Kategori">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.kategori.delete', $k->id_kategori) }}"
                                        method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger shadow-sm"
                                                onclick="return confirm('Yakin ingin menghapus kategori ini?')"
                                                title="Hapus Kategori">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Belum ada kategori
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
