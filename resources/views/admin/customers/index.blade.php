@include('admin.header')

<style>
    body {
        font-family: 'Poppins', sans-serif;
        color: #0b1841;
        margin: 0;
        min-height: 100vh;
        background: url('{{ asset('img/background1.svg') }}') no-repeat center center fixed;
        background-size: cover;
    }
</style>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <h2 class="fw-bold text-dark border-bottom pb-2">Kelola Pelanggan</h2>
    </div>

    <!-- 🔍 Filter -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.customers.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary">Cari Nama / Email / No HP</label>
                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                        class="form-control border-0 shadow-sm" placeholder="Ketik nama, email, atau no HP...">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary">Dari</label>
                    <input type="date" name="start" value="{{ request('start') }}"
                        class="form-control border-0 shadow-sm">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary">Sampai</label>
                    <input type="date" name="end" value="{{ request('end') }}"
                        class="form-control border-0 shadow-sm">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary-navy w-100 rounded-pill shadow-sm">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 🧑 Tabel Pelanggan -->
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header py-3 text-center fw-semibold" style="background-color:#001f3f; color:white;">
            Daftar Pelanggan
        </div>

        <div class="table-responsive p-4">
            <table class="table table-striped align-middle text-center shadow-sm border">
                <thead class="table-header-navy">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Total Pesanan</th>
                        <th>Tanggal Daftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @php $no = 1; @endphp

                    @forelse ($customers as $cust)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td class="fw-semibold">{{ $cust->name }}</td>
                            <td>{{ $cust->email }}</td>
                            <td>{{ $cust->phone ?? '-' }}</td>

                            <!-- Total Order -->
                            <td>
                                <span class="badge text-bg-primary px-3 py-2">
                                    {{ $cust->orders_count ?? 0 }}
                                </span>
                            </td>

                            <td>{{ $cust->created_at->format('d M Y') }}</td>

                            <td>
                                <!-- <a href="{{ route('admin.customers.show', $cust->id) }}"
                                    class="btn btn-sm btn-primary-navy rounded-pill px-3 me-1">
                                    <i class="fa-solid fa-eye me-1"></i> Detail
                                </a> -->

                                <form action="{{ route('admin.customers.destroy', $cust->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger rounded-pill px-3">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="py-3 text-muted">Tidak ada pelanggan ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="7" class="text-center py-2 text-muted">
                            Total Pelanggan: {{ $customers->count() }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@include('admin.footer')
