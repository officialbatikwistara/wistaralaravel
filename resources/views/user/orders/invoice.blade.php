<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->order_code }}</title>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 35px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header img {
            width: 130px;
            margin-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #555;
        }
        .info-box, .summary-box {
            margin-top: 25px;
        }
        h3 {
            font-size: 18px;
            margin-bottom: 8px;
            color: #222;
        }
        .divider {
            height: 2px;
            background: #ddd;
            margin: 15px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 9px;
            font-size: 14px;
        }
        table th {
            background: #f8f8f8;
            font-weight: bold;
            text-align: left;
        }
        .total-table {
            width: 50%;
            margin-left: auto;
            border-collapse: collapse;
        }
        .total-table td {
            padding: 8px 10px;
            border: 1px solid #bbb;
        }
        .total-table .label {
            background: #f5f5f5;
            font-weight: bold;
            width: 45%;
        }
        footer {
            margin-top: 40px;
            text-align: center;
            font-size: 13px;
            color: #666;
        }
    </style>
</head>

<body>

<button onclick="window.print()" 
        style="padding:10px 18px; background:#000; color:#fff; border:none; border-radius:8px; cursor:pointer; margin-bottom:20px;">
    🖨️ Download / Print Invoice
</button>

    <!-- ===================== HEADER ===================== -->
    <div class="header">
        <img src="{{ asset('img/logowarna.png') }}" alt="Batik Wistara">

        <h1>INVOICE</h1>
        <p>Kode Pesanan: <strong>{{ $order->order_code }}</strong></p>
    </div>

    <div class="divider"></div>

    <!-- ===================== INFORMASI PEMESAN ===================== -->
    <div class="info-box">
        <h3>Informasi Pemesan</h3>
        <p><strong>Nama:</strong> {{ $order->nama }}</p>
        <p><strong>Telepon:</strong> {{ $order->telepon }}</p>
        <p><strong>Alamat:</strong> {{ $order->alamat }}</p>
        <p><strong>Tanggal Pesan:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
        <p><strong>Tanggal Ambil:</strong> {{ \Carbon\Carbon::parse($order->tanggal_ambil)->format('d M Y') }}</p>
        <p><strong>Metode Pembayaran:</strong>
            @if($order->metode_pembayaran === 'bank_transfer')
                Bank Transfer
            @elseif($order->metode_pembayaran === 'qris')
                QRIS
            @else
                COD
            @endif
        </p>
        <p><strong>Status Pembayaran:</strong>
            {{ ucfirst(str_replace('_',' ', $order->status_pembayaran)) }}
        </p>
    </div>

    <div class="divider"></div>

    <!-- ===================== RINCIAN PRODUK ===================== -->
    <h3>Rincian Pesanan</h3>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th width="60">Qty</th>
                <th width="120">Harga</th>
                <th width="140">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->produk->nama_produk }}</td>
                <td style="text-align:center">{{ $item->qty }}</td>
                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- ===================== TOTAL ===================== -->
    <div class="summary-box">
        <table class="total-table">
            <tr>
                <td class="label">Subtotal</td>
                <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>

            @if(($order->discount_amount ?? 0) > 0)
            <tr>
                <td class="label">Diskon</td>
                <td>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Total Akhir</td>
                <td><strong>Rp {{ number_format($order->final_total, 0, ',', '.') }}</strong></td>
            </tr>
            @else
            <tr>
                <td class="label">Total Akhir</td>
                <td><strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></td>
            </tr>
            @endif
        </table>
    </div>

    <footer>
        <p>Invoice ini dibuat otomatis oleh sistem <strong>Batik Wistara</strong>.</p>
        <p>Terima kasih telah berbelanja!</p>
    </footer>

</body>
</html>
