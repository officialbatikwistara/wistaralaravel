<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Baru - Batik Wistara</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            margin: 20px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #8B4513;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #8B4513;
            margin-bottom: 10px;
        }
        .alert {
            background: linear-gradient(135deg, #FF6B35, #F7931E);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
            font-size: 18px;
            font-weight: bold;
        }
        .order-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #8B4513;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
        }
        .detail-label {
            font-weight: bold;
            color: #555;
        }
        .detail-value {
            color: #333;
        }
        .total-row {
            border-top: 2px solid #8B4513;
            padding-top: 15px;
            margin-top: 15px;
            font-size: 18px;
            font-weight: bold;
            color: #8B4513;
        }
        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #8B4513, #A0522D);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
        }
        .action-button:hover {
            background: linear-gradient(135deg, #A0522D, #8B4513);
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .contact-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }
        .urgent {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🧵 Batik Wistara</div>
            <h1 style="color: #8B4513; margin: 10px 0;">Pesanan Baru Masuk!</h1>
        </div>

        <div class="alert">
            🚨 Ada pesanan baru yang perlu segera diproses!
        </div>

        <div class="urgent">
            <strong>PENTING:</strong> Segera konfirmasi pesanan ini untuk menghindari keterlambatan pengiriman.
        </div>

        <div class="order-details">
            <h3 style="color: #8B4513; margin-bottom: 15px;">📋 Detail Pesanan</h3>

            <div class="detail-row">
                <span class="detail-label">Nomor Pesanan:</span>
                <span class="detail-value"><strong>#{{ $order->id }}</strong></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Nama Customer:</span>
                <span class="detail-value">{{ $order->nama }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value">{{ $user->email }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Telepon:</span>
                <span class="detail-value">{{ $order->telepon }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Tipe Order:</span>
                <span class="detail-value">{{ $order->tipe_order === 'ambil' ? 'Ambil di Toko' : 'Delivery' }}</span>
            </div>

            @if($order->tipe_order !== 'ambil')
            <div class="detail-row">
                <span class="detail-label">Alamat:</span>
                <span class="detail-value">{{ $order->alamat }}</span>
            </div>
            @endif

            <div class="detail-row">
                <span class="detail-label">Tanggal Ambil/Delivery:</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($order->tanggal_ambil)->format('d M Y') }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Metode Pembayaran:</span>
                <span class="detail-value">{{ $order->metode_pembayaran === 'bank_transfer' ? 'Transfer Bank' : 'QRIS' }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Status Pembayaran:</span>
                <span class="detail-value">
                    @if($order->status_pembayaran === 'belum_bayar')
                        <span style="color: #dc3545;">⏳ Belum Bayar</span>
                    @elseif($order->status_pembayaran === 'menunggu_verifikasi')
                        <span style="color: #ffc107;">⏳ Menunggu Verifikasi</span>
                    @elseif($order->status_pembayaran === 'lunas')
                        <span style="color: #28a745;">✅ Lunas</span>
                    @else
                        <span style="color: #dc3545;">❌ Gagal</span>
                    @endif
                </span>
            </div>

            <div class="detail-row total-row">
                <span class="detail-label">Total Pembayaran:</span>
                <span class="detail-value">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>

            @if($order->discount_amount > 0)
            <div class="detail-row">
                <span class="detail-label">Diskon:</span>
                <span class="detail-value" style="color: #28a745;">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total Akhir:</span>
                <span class="detail-value" style="color: #8B4513; font-size: 20px;"><strong>Rp {{ number_format($order->final_total ?? $order->total, 0, ',', '.') }}</strong></span>
            </div>
            @endif
        </div>

        @if($order->catatan)
        <div class="order-details">
            <h4 style="color: #8B4513; margin-bottom: 10px;">📝 Catatan Customer:</h4>
            <p style="margin: 0; font-style: italic;">"{{ $order->catatan }}"</p>
        </div>
        @endif

        <div style="text-align: center;">
            <a href="{{ url('/admin/pesanan/' . $order->id) }}" class="action-button">
                🔍 Lihat Detail Pesanan Lengkap
            </a>
        </div>

        <div class="contact-info">
            <h4 style="color: #8B4513; margin-bottom: 10px;">📞 Info Kontak Customer</h4>
            <p><strong>Nama:</strong> {{ $order->nama }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Telepon:</strong> {{ $order->telepon }}</p>
        </div>

        <div class="footer">
            <p><strong>Batik Wistara</strong></p>
            <p>Jl. Tambak Medokan Ayu VI C No.56B, Medokan Ayu, Rungkut, Jawa Timur</p>
            <p>Email ini dikirim otomatis pada {{ now()->format('d M Y H:i') }}</p>
            <p style="margin-top: 15px; font-size: 12px; color: #999;">
                Sistem Manajemen Pesanan Batik Wistara © {{ date('Y') }}
            </p>
        </div>
    </div>
</body>
</html>
