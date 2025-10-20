@include('admin.header')

<div class="container my-4">

    <!-- Form filter & tombol -->
    <div class="d-flex mb-2 justify-content-between">
        <form class="d-flex align-items-center gap-2">
            <input type="date" class="form-control w-auto" value="2025-10-01">
            -
            <input type="date" class="form-control w-auto" value="2025-10-20">
            <input type="text" class="form-control w-auto" placeholder="Cari order">
            <button type="submit" class="btn btn-dark">Cari</button>
        </form>
    </div>

    <!-- Tabel Order -->
    <div class="card mb-2 overflow-hidden">
        <table class="table table-striped align-middle text-center m-0">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>ID User</th>
                    <th>Nama</th>
                    <th>No. Telp</th>
                    <th>Alamat</th>
                    <th>Catatan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tipe Order</th>
                    <th>Ambil</th>
                    <th>Kirim</th>
                </tr>
            </thead>
            {{-- Coba-coba --}}
            <tbody>
                <tr>
                    <td>Order #1</td>
                    <td>U001</td>
                    <td>Hanna</td>
                    <td>08123456789</td>
                    <td>Jl. Merdeka No. 10</td>
                    <td>Tambahkan pita</td>
                    <td>Rp 300.000</td>
                    <td><span class="badge bg-success">Selesai</span></td>
                    <td>Delivery</td>
                    <td>2025-10-20 10:00</td>
                    <td>2025-10-20 13:00</td>
                </tr>
                <tr>
                    <td>Order #2</td>
                    <td>U002</td>
                    <td>Budi</td>
                    <td>08129876543</td>
                    <td>Jl. Sudirman No. 5</td>
                    <td>-</td>
                    <td>Rp 250.000</td>
                    <td><span class="badge bg-warning text-dark">Proses</span></td>
                    <td>Pickup</td>
                    <td>2025-10-19 09:00</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>Order #3</td>
                    <td>U003</td>
                    <td>Sinta</td>
                    <td>081377788899</td>
                    <td>Jl. Melati No. 23</td>
                    <td>Cat warna pastel</td>
                    <td>Rp 450.000</td>
                    <td><span class="badge bg-danger">Batal</span></td>
                    <td>Delivery</td>
                    <td>2025-10-18 11:00</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>
    </div>
