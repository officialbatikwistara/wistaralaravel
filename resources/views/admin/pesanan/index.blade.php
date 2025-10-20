@include('admin.header')

{{-- <style>
    .table-navy thead {
        background-color: #001f3f !important;
        color: white !important;
    }

    .table-navy th {
        font-weight: 600;
    }

    .table-navy tbody tr:nth-child(odd) {
        background-color: #ffffff !important;
    }

    .table-navy tbody tr:nth-child(even) {
        background-color: #f8f9fa !important;
    }

    .table-navy tfoot {
        background-color: #001f3f !important;
        color: white !important;
    }

    .nav-tabs .nav-link {
        color: #000000 !important;
        /* warna teks normal */
    }

    .nav-tabs .nav-link.active {
        color: #fff;
        /* warna teks tab aktif */
        background-color: #1b4e9549 !important;
        /* warna background aktif */
        border-color: #1b4e95 !important;
    }
</style> --}}
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h2 class="fw-bold text-dark border-bottom pb-2">📦 Kelola Pesanan</h2>
    </div>

    <!-- Filter Form -->
    <form class="row g-2 mb-3">
        <div class="col-md-auto">
            <input type="date" class="form-control" name="start">
        </div>
        <div class="col-md-auto">
            <input type="date" class="form-control" name="end">
        </div>
        <div class="col-md">
            <input type="text" class="form-control" name="keyword" placeholder="Cari order...">
        </div>
        <div class="col-md-auto">
            <button type="submit" class="btn btn-dark w-100">Cari</button>
        </div>
    </form>

    <!-- Tabs -->
    <div class="card shadow border-0 rounded-3 overflow-hidden">
        <ul class="nav nav-tabs mb-3" id="orderTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#Semua" type="button">📋
                    Semua</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#proses" type="button">⏳ Proses</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#dikirim" type="button">🚚
                    Dikirim</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#selesai" type="button">✅
                    Selesai</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#batal" type="button">❌ Batal</button>
            </li>
        </ul>


        <div class="tab-content p-3">
            <!-- Contoh Tab -->
            <div class="tab-pane fade show active" id="proses">
                <table class="table table-navy align-middle text-center m-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Pesanan</th>
                            <th>Nama</th>
                            <th>Tanggal Order</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tipe Order</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>#ORD002</td>
                            <td>Budi</td>
                            <td>2025-10-19</td>
                            <td>Rp 250.000</td>
                            <td><span class="badge bg-warning text-dark">Proses</span></td>
                            <td>Pickup</td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm">Lihat Detail</button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="8" class="text-center py-2">Total Pesanan: 1</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Detail Pesanan -->
<div class="container py-4">
    <h3 class="fw-bold mb-4">🧾 Detail Pesanan #ORD002</h3>

    <div class="mb-3">
        <p><strong>Nama:</strong> Budi</p>
        <p><strong>Telepon:</strong> 08129876543</p>
        <p><strong>Alamat:</strong> Jl. Sudirman No. 5</p>
        <p><strong>Catatan:</strong> -</p>
        <p><strong>Status:</strong> <span class="badge bg-warning text-dark">Proses</span></p>
        <p><strong>Tipe Order:</strong> Pickup</p>
        <p><strong>Ambil:</strong> 2025-10-19 09:00</p>
        <p><strong>Kirim:</strong> -</p>
    </div>

    <div class="card shadow border-0 rounded-3 overflow-hidden">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Batik Mega Mendung</td>
                    <td>2</td>
                    <td>Rp 125.000</td>
                    <td>Rp 250.000</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end fw-bold">Total</td>
                    <td class="fw-bold">Rp 250.000</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@include('admin.footer')
