<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Pesanan #{{ $order->id }} | Batik Wistara</title>
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background-color: #f5f5f5;
      padding: 30px;
      margin: 0;
      color: #333;
    }
    .container {
      max-width: 600px;
      background-color: #ffffff;
      margin: 0 auto;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .header {
      background: #1e3a8a;
      padding: 25px;
      text-align: center;
      color: white;
    }
    .header img {
      max-height: 55px;
      margin-bottom: 10px;
    }
    .header h1 {
      margin: 0;
      font-size: 22px;
    }
    .content {
      padding: 25px;
      font-size: 15px;
      line-height: 1.6;
    }
    .content table {
      width: 100%;
      margin-top: 15px;
      border-collapse: collapse;
    }
    .content table td {
      padding: 6px 0;
    }
    .cta-button {
      display: inline-block;
      background: #1e3a8a;
      color: #fff !important;
      padding: 12px 20px;
      border-radius: 6px;
      text-decoration: none;
      margin-top: 20px;
      font-weight: bold;
    }
    .footer {
      background: #f1f1f1;
      text-align: center;
      padding: 15px;
      font-size: 13px;
      color: #555;
    }
    .footer strong {
      color: #1e3a8a;
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Header -->
    <div class="header">
      <img src="{{ asset('img/logowarna.png') }}" alt="Batik Wistara">
      <h1>Notifikasi Pesanan</h1>
    </div>

    <!-- Content -->
    <div class="content">
      <p>Halo <strong>{{ $order->nama }}</strong>,</p>

      <p>{{ $textMessage }}</p>

      <table>
        <tr>
          <td><strong>ID Pesanan</strong></td>
          <td>: #{{ $order->id }}</td>
        </tr>
        <tr>
          <td><strong>Total</strong></td>
          <td>: Rp {{ number_format($order->total, 0, ',', '.') }}</td>
        </tr>
        <tr>
          <td><strong>Status</strong></td>
          <td>: {{ ucfirst($order->status) }}</td>
        </tr>
        <tr>
          <td><strong>Pembayaran</strong></td>
          <td>: {{ ucfirst(str_replace('_', ' ', $order->status_pembayaran)) }}</td>
        </tr>
      </table>

      <a href="{{ route('user.order.show', $order->id) }}" class="cta-button">
          Lihat Pesanan
      </a>
    </div>

    <!-- Footer -->
    <div class="footer">
      Terima kasih telah berbelanja di <strong>Batik Wistara</strong> 💛<br>
      Jl. Tambak Medokan Ayu VI C No.56B, Surabaya
    </div>
  </div>
</body>
</html>
