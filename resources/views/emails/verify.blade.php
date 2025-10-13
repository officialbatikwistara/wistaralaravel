<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verifikasi Email</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 0;">
  <table align="center" width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
    <tr>
      <td style="background-color: #CDA349; padding: 20px; text-align: center;">
        <img src="{{ asset('img/logowarna.png') }}" alt="Batik Wistara" width="120">
      </td>
    </tr>
    <tr>
      <td style="padding: 30px; text-align: center;">
        <h2 style="color: #222;">Verifikasi Email Anda 📩</h2>
        <p style="color: #555; font-size: 15px; line-height: 1.6;">
          Terima kasih telah bergabung dengan <strong>Batik Wistara</strong>.<br>
          Klik tombol di bawah ini untuk memverifikasi email Anda dan mengaktifkan akun.
        </p>
        <a href="{{ $url }}" style="display: inline-block; padding: 14px 28px; background-color: #CDA349; color: #fff; text-decoration: none; font-weight: bold; border-radius: 6px; margin-top: 15px;">
          Verifikasi Sekarang
        </a>
        <p style="color: #999; font-size: 13px; margin-top: 20px;">
          Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser Anda:
        </p>
        <p style="font-size: 12px; color: #666; word-wrap: break-word;">{{ $url }}</p>
      </td>
    </tr>
    <tr>
      <td style="background-color: #f2f2f2; padding: 15px; text-align: center; font-size: 12px; color: #777;">
        &copy; {{ date('Y') }} Batik Wistara. Semua Hak Dilindungi.
      </td>
    </tr>
  </table>
</body>
</html>
