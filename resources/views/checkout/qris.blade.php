@include('inc.header')

<section class="py-5" style="background: url('{{ asset('img/bghero.svg') }}') center/cover no-repeat;">
    <div class="container" style="max-width: 600px;">
        <div class="card shadow-lg p-4 rounded-4 text-center">
            <h3 class="fw-bold mb-3">📱 QRIS Pembayaran</h3>
            <p>Selesaikan pembayaran sebelum waktu habis ⏳</p>

            <div class="alert alert-warning fw-bold fs-5" id="countdown">00:00:00</div>

            <img src="{{ asset('img/qris.png') }}" alt="QRIS Batik Wistara" 
                 style="max-width: 250px; border-radius: 12px; margin-bottom: 15px;">

            <div class="d-flex justify-content-between border-top pt-3 mb-3">
                <span class="fw-semibold">Total Pembayaran:</span>
                <span class="fw-bold text-dark">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>

            <div id="upload-section">
                <h5 class="fw-bold mb-3">📤 Upload Bukti Pembayaran</h5>
                <form action="{{ route('checkout.uploadBukti', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="bukti_pembayaran" accept="image/*" class="form-control mb-3" required>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-semibold">
                        <i class="fa-solid fa-upload me-2"></i> Upload Bukti
                    </button>
                </form>
            </div>

            @if($order->bukti_pembayaran)
                <hr>
                <h5 class="fw-bold mb-2">🧾 Bukti Pembayaran Anda</h5>
                <img src="{{ asset('uploads/bukti/'.$order->bukti_pembayaran) }}" 
                     alt="Bukti Pembayaran" 
                     class="img-fluid rounded shadow-sm mb-3" 
                     style="max-width: 300px;">
            @endif

            <hr>
            <a href="{{ url('/user/dashboard') }}" class="btn btn-dark rounded-pill px-4">
                <i class="fa-solid fa-arrow-right me-2"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>
</section>

<script>
    const orderTime = new Date("{{ $order->created_at }}").getTime();
    const deadline = orderTime + (24 * 60 * 60 * 1000); // 24 jam

    const countdownEl = document.getElementById('countdown');
    const uploadSection = document.getElementById('upload-section');

    const timer = setInterval(() => {
        const now = new Date().getTime();
        const distance = deadline - now;

        if (distance <= 0) {
            clearInterval(timer);
            countdownEl.innerHTML = "Waktu pembayaran telah habis ❌";
            countdownEl.classList.remove('alert-warning');
            countdownEl.classList.add('alert-danger');

            if (uploadSection) {
                uploadSection.innerHTML = `<p class="text-danger fw-bold">Anda tidak dapat mengunggah bukti pembayaran lagi.</p>`;
            }
        } else {
            const hours = Math.floor((distance / (1000 * 60 * 60)) % 24);
            const minutes = Math.floor((distance / (1000 * 60)) % 60);
            const seconds = Math.floor((distance / 1000) % 60);
            countdownEl.innerHTML =
                String(hours).padStart(2, '0') + ":" +
                String(minutes).padStart(2, '0') + ":" +
                String(seconds).padStart(2, '0');
        }
    }, 1000);
</script>

@include('inc.footer')
