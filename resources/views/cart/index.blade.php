@extends('layouts.app')

@section('content')
<div class="container py-5">
  <h2 class="fw-bold mb-4">🛒 Keranjang Belanja</h2>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if($cartItems->count() > 0)
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Total</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($cartItems as $item)
            <tr>
              <td>{{ $item->produk->nama_produk }}</td>
              <td>Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</td>
              <td>{{ $item->jumlah }}</td>
              <td>Rp {{ number_format($item->jumlah * $item->produk->harga, 0, ',', '.') }}</td>
              <td>
                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">
                    Hapus
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="mt-4 text-end">
      <a href="#" class="btn btn-dark">Checkout</a>
    </div>
  @else
    <div class="alert alert-info">Keranjang Anda kosong 😄</div>
  @endif
</div>
@endsection
