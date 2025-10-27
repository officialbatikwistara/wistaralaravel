@extends('layouts.admin')

@section('title', 'Kelola Berita')

@section('content')

<style>
body {
  background-color: #edf2f7;
  font-family: 'Poppins', sans-serif;
  color: #0b1841;
}

h2 {
  font-weight: 700;
  color: #0b1841;
}

/* 🌟 Container tabel */
.table-container {
  background: #ffffff;
  border-radius: 18px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  margin-top: 20px;
}

/* 🧭 Header tabel */
.table thead.table-header th {
  background-color: #081738 !important;
  color: #ffffff !important;
  font-weight: 600 !important;
  font-size: 15px !important;
  border: none !important;
  padding: 16px !important;
  text-align: center !important;
}

/* Buat header membulat di pojok atas */
.table-container table {
  border-collapse: separate !important;
  border-spacing: 0 !important;
  border-radius: 18px !important;
  overflow: hidden !important;
}

.table-container thead th:first-child {
  border-top-left-radius: 18px !important;
}

.table-container thead th:last-child {
  border-top-right-radius: 18px !important;
}

/* 📦 Baris produk */
.produk-row {
  border-bottom: 1px solid #e5e7eb;
  transition: 0.25s ease;
}

.produk-row:hover {
  background-color: #f9fafb;
  transform: scale(1.001);
}

/* 📋 Isi tabel */
.produk-cell {
  padding: 20px 16px;
  vertical-align: middle;
}

/* 🖼️ Gambar berita */
.produk-img {
  width: 120px;
  height: 120px;
  object-fit: cover;
  border-radius: 16px;
  box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
}

/* 🏷️ Judul berita */
.produk-name {
  font-weight: 600;
  font-size: 16px;
  color: #0b1841;
}

/* ✏️ Tombol aksi */
.btn-action {
  border: none;
  padding: 10px 12px;
  border-radius: 8px;
  color: white;
  transition: 0.3s ease;
}

.btn-edit {
  background-color: #fbbf24;
}

.btn-edit:hover {
  background-color: #d1a106;
}

.btn-delete {
  background-color: #dc2626;
}

.btn-delete:hover {
  background-color: #b91c1c;
}

/* 🧩 Tombol utama */
.btn-dark {
  background-color: #081738 !important;
  border: none !important;
  border-radius: 12px !important;
  font-weight: 600 !important;
}

.btn-dark:hover {
  background-color: #152a6e !important;
}
</style>

{{-- =========================
    🌸 MAIN CONTENT
   ========================= --}}
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2> Kelola Berita</h2>
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
  <div class="table-container">
    <table class="table align-middle">
      <thead class="table-header">
        <tr>
          <th>No</th>
          <th>Gambar</th>
          <th>Judul</th>
          <th>Sumber</th>
          <th>Tanggal</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($berita as $index => $b)
        <tr class="produk-row">
          <td class="produk-cell text-center">{{ $index + 1 }}</td>
          <td class="produk-cell text-center">
            @if($b->gambar)
            <img src="{{ asset($b->gambar) }}" alt="Gambar Berita" class="produk-img">
            @else
            <span class="text-muted">Tidak ada</span>
            @endif
          </td>
          <td class="produk-cell produk-name">{{ $b->judul }}</td>
          <td class="produk-cell">
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
          <td class="produk-cell fw-semibold">{{ \Carbon\Carbon::parse($b->tanggal)->format('d M Y') }}</td>
          <td class="produk-cell text-center">
            <div class="d-flex justify-content-center gap-2">
              <a href="{{ route('admin.berita.edit', $b->id) }}" class="btn btn-action btn-edit">
                <i class="fa-solid fa-pen"></i>
              </a>
              <form action="{{ route('admin.berita.delete', $b->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-action btn-delete"
                  onclick="return confirm('Yakin ingin menghapus berita ini?')">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center text-muted py-4">Belum ada berita</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
