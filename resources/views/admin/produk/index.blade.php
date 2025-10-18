@include('admin.header')

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>📦 Kelola Produk</h2>
    <a href="{{ route('admin.produk.create') }}" class="btn btn-dark">+ Tambah Produk</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Gambar</th>
          <th>Nama Produk</th>
          <th>Harga</th>
          <th>Kategori</th>
          <th>Shopee</th>
          <th>TikTok</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($produk as $p)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td><img src="{{ asset($p->gambar) }}" width="60"></td>
            <td>{{ $p->nama_produk }}</td>
            <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
            <td>{{ $p->nama_kategori }}</td>
            <td>
              @if($p->link_shopee)
                <a href="{{ $p->link_shopee }}" target="_blank" class="btn btn-warning btn-sm">Shopee</a>
              @endif
            </td>
            <td>
              @if($p->link_tiktok)
                <a href="{{ $p->link_tiktok }}" target="_blank" class="btn btn-dark btn-sm">TikTok</a>
              @endif
            </td>
            <td>
              <a href="{{ route('admin.produk.edit', $p->id_produk) }}" class="btn btn-primary btn-sm">Edit</a>
              <form action="{{ route('admin.produk.delete', $p->id_produk) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk ini?')">Hapus</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@include('admin.footer')
