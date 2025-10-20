@include('admin.header')

<div class="container py-5 text-white">
  <h2 class="mb-4 fw-bold">➕ Tambah Pesanan</h2>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form action="{{ route('admin.pesanan.store') }}" method="POST" enctype="multipart/form-data" class="bg-dark p-4 rounded-3 shadow-sm">
    @csrf

    <!-- Nama Pemesan -->
    <div class="mb-3">
      <label class="form-label text-white">Nama Pemesan</label>
      <input type="text" name="nama_pemesan" class="form-control bg-light text-dark" required>
    </div>

    <!-- Produk -->
    <div class="mb-3">
      <label class="form-label text-white">Produk</label>
      <select name="id_produk" class="form-select bg-light text-dark" required>
        <option value="">-- Pilih Produk --</option>
        @foreach($produk as $p)
          <option value="{{ $p->id_produk }}">{{ $p->nama_produk }}</option>
        @endforeach
      </select>
    </div>

    <!-- Jumlah -->
    <div class="mb-3">
      <label class="form-label text-white">Jumlah</label>
      <input type="number" name="jumlah" class="form-control bg-light text-dark" min="1" required>
    </div>

    <!-- Total Harga -->
    <div class="mb-3">
      <label class="form-label text-white">Total Harga (Rp)</label>
      <input type="number" name="total_harga" class="form-control bg-light text-dark" required>
    </div>

    <!-- Tanggal Pesanan -->
    <div class="mb-3">
      <label class="form-label text-white">Tanggal Pesanan</label>
      <input type="date" name="tanggal_pesanan" class="form-control bg-light text-dark" required>
    </div>

    <!-- Status Pesanan -->
    <div class="mb-3">
      <label class="form-label text-white">Status Pesanan</label>
      <select name="status" class="form-select bg-light text-dark" required>
        <option value="Menunggu Pembayaran">Menunggu Pembayaran</option>
        <option value="Diproses">Diproses</option>
        <option value="Dikirim">Dikirim</option>
        <option value="Selesai">Selesai</option>
        <option value="Dibatalkan">Dibatalkan</option>
      </select>
    </div>

    <!-- Tombol Aksi -->
    <div class="d-flex gap-2 mt-4">
      <button type="submit" class="btn btn-light text-dark fw-bold">💾 Simpan Pesanan</button>
      <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary">⬅ Kembali</a>
    </div>
  </form>
</div>

@include('admin.footer')
