<!-- Product Review Component -->
@if(!isset($product))
    <div class="alert alert-danger">Error: Product data not found</div>
@else
<div class="card shadow-lg rounded-4 border-0 p-4">
    <h2 class="h4 mb-4 text-dark">Ulasan Produk</h2>

    <!-- Rating Summary -->
    @php
        $avgRating = $product->average_rating ?? 0;
        $reviewCount = $product->review_count ?? 0;
        $avgRating = is_numeric($avgRating) ? $avgRating : 0;
        $reviewCount = is_numeric($reviewCount) ? $reviewCount : 0;
    @endphp
    <div class="bg-light p-4 rounded-3 mb-4">
        <div class="row align-items-center">
            <div class="col-md-4 text-center">
                <div class="display-4 fw-bold text-warning">
                    {{ number_format($avgRating, 1) }}
                </div>
                <div class="text-muted small">
                    dari 5 ⭐
                </div>
                <div class="text-muted small mt-2">
                    {{ $reviewCount }} ulasan
                </div>
            </div>

            <div class="col-md-8">
                @for ($i = 5; $i >= 1; $i--)
                    @php
                        $count = $product->approvedReviews()->where('rating', $i)->count();
                        $percentage = $reviewCount > 0 ? ($count / $reviewCount) * 100 : 0;
                    @endphp
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="small text-muted" style="min-width: 30px;">{{ $i }} ⭐</span>
                        <div class="progress flex-fill" style="height: 8px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="small text-muted" style="min-width: 30px; text-align: right;">{{ $count }}</span>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Add Review Form (User Only) -->
    @auth
        <div id="review-form" class="bg-light p-4 rounded-3 mb-4" style="scroll-margin-top: 100px;">
            <h3 class="h5 mb-3 text-dark">Tulis Ulasan Anda</h3>

            <form id="reviewForm" class="row g-3">
                @csrf

                <!-- Rating -->
                <div class="col-12">
                    <label class="form-label fw-semibold">Rating <span class="text-danger">*</span></label>
                    <input type="hidden" name="rating" id="ratingInput" required>
                    <div id="starRating" class="d-flex gap-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="star" data-value="{{ $i }}" style="cursor: pointer; font-size: 2rem; color: #6c757d; transition: color 0.2s;">⭐</span>
                        @endfor
                    </div>
                    <div id="ratingError" class="text-danger small d-none">Pilih rating terlebih dahulu</div>
                </div>

                <!-- Comment -->
                <div class="col-12">
                    <label class="form-label fw-semibold">Komentar</label>
                    <textarea name="comment" placeholder="Bagikan pengalaman Anda dengan produk ini..." required class="form-control" rows="4"></textarea>
                </div>

                <!-- Photos -->
                <div class="col-12">
                    <label class="form-label fw-semibold">Foto (Opsional)</label>
                    <input type="file" name="photos[]" multiple accept="image/*" class="form-control">
                    <small class="text-muted">Maksimal 2MB per foto, format: JPG, PNG, GIF</small>
                </div>

                <!-- Video -->
                <div class="col-12">
                    <label class="form-label fw-semibold">Video (Opsional)</label>
                    <input type="file" name="video" accept="video/*" class="form-control">
                    <small class="text-muted">Maksimal 20MB, format: MP4, MOV, AVI, WebM</small>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-gold px-4">Kirim Ulasan</button>
                </div>
            </form>

            <div id="reviewMessage" class="mt-3 p-3 rounded d-none"></div>
        </div>
    @else
        <div class="bg-light p-4 rounded-3 mb-4 text-center">
            <p class="mb-0">
                <a href="{{ route('login') }}" class="text-gold fw-semibold text-decoration-none">Login</a> untuk menulis ulasan
            </p>
        </div>
    @endauth

    <!-- Reviews List -->
    <div id="reviewsList">
        <h3 class="h5 mb-3 text-dark">Ulasan Terbaru</h3>

        @forelse ($product->approvedReviews()->latest()->get() as $review)
            <div class="p-3 border-bottom">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <strong class="text-dark">{{ $review->user->name }}</strong>
                        <div class="text-muted small">
                            {{ $review->created_at->format('d M Y') }}
                        </div>
                    </div>
                    <span class="badge bg-warning text-dark">
                        ⭐ {{ $review->rating }}/5
                    </span>
                </div>

                <p class="mb-2 text-secondary">{{ $review->comment }}</p>

                @php
                    $photos = is_array($review->photos) ? $review->photos : (is_string($review->photos) ? json_decode($review->photos, true) : []);
                @endphp
                @if ($photos && count($photos) > 0)
                    <div class="row g-2 mt-3">
                        @foreach ($photos as $photo)
                            <div class="col-auto">
                                @php
                                    $photoPath = storage_path('app/public/' . $photo);
                                    $photoUrl = file_exists($photoPath) ? asset('storage/' . $photo) : asset('img/logo.png');
                                @endphp
                                <img src="{{ $photoUrl }}" alt="Review photo" class="rounded shadow-sm" style="width: 100px; height: 100px; object-fit: cover; cursor: pointer;" onclick="openModal(this.src)">
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($review->video)
                    <div class="mt-3">
                        <video width="300" height="200" controls class="rounded">
                            <source src="{{ asset('storage/' . $review->video) }}" type="video/mp4">
                            Browser Anda tidak mendukung video.
                        </video>
                    </div>
                @endif

                @auth
                    @if (auth()->user()->id === $review->user_id)
                        <div class="d-flex gap-2 mt-3">
                            <button onclick="editReview({{ $review->id }})" class="btn btn-primary btn-sm">Edit</button>
                            <button onclick="deleteReview({{ $review->id }})" class="btn btn-danger btn-sm">Hapus</button>
                        </div>
                    @endif
                @endauth
            </div>
        @empty
            <div class="text-center py-4 text-muted">
                <p>Belum ada ulasan untuk produk ini.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal untuk foto -->
<div id="photoModal" class="d-none position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-75 d-flex align-items-center justify-content-center" style="z-index: 1050;">
    <span onclick="closeModal()" class="position-absolute top-0 end-0 text-white fs-1 fw-bold" style="cursor: pointer; top: 20px; right: 30px;">&times;</span>
    <img id="modalImage" src="" alt="Modal Image" class="mw-100 mh-100">
</div>

<script>
    const productId = {{ $product->id_produk }};

    // Get order_id from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('order_id');

    // Get CSRF token - try meta tag first, then fallback
    let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        // Fallback: try to get from a hidden input or other sources
        csrfToken = document.querySelector('input[name="_token"]')?.value;
    }

    // Rating stars interaction
    let selectedRating = 0;

    document.querySelectorAll('.star').forEach(star => {
        star.addEventListener('click', function() {
            selectedRating = parseInt(this.dataset.value);
            document.getElementById('ratingInput').value = selectedRating;
            const ratingError = document.getElementById('ratingError');
            if (ratingError) ratingError.classList.add('d-none');
            updateStars(selectedRating);
        });

        star.addEventListener('mouseover', function() {
            const value = parseInt(this.dataset.value);
            updateStars(value);
        });
    });

    document.querySelector('#starRating')?.addEventListener('mouseleave', function() {
        updateStars(selectedRating);
    });

    function updateStars(rating) {
        document.querySelectorAll('.star').forEach(s => {
            const starValue = parseInt(s.dataset.value);
            s.style.color = starValue <= rating ? '#ffc107' : '#6c757d';
        });
    }

    // Submit review
    document.getElementById('reviewForm')?.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Validasi rating
        const rating = document.getElementById('ratingInput')?.value;
        if (!rating) {
            const ratingError = document.getElementById('ratingError');
            if (ratingError) ratingError.classList.remove('d-none');
            return;
        } else {
            const ratingError = document.getElementById('ratingError');
            if (ratingError) ratingError.classList.add('d-none');
        }

        // Check CSRF token
        if (!csrfToken) {
            showMessage('error', 'CSRF token tidak ditemukan. Silakan refresh halaman.');
            return;
        }

        const formData = new FormData();
        formData.append('id_produk', productId);
        if (orderId) {
            formData.append('order_id', orderId);
        }
        formData.append('rating', rating);
        const commentTextarea = document.querySelector('textarea[name="comment"]');
        if (commentTextarea && commentTextarea.value.trim()) {
            formData.append('comment', commentTextarea.value.trim());
        }

        // Add photos
        const photosInput = document.querySelector('input[name="photos[]"]');
        if (photosInput && photosInput.files) {
            for (let photo of photosInput.files) {
                formData.append('photos[]', photo);
            }
        }

        // Add video
        const videoInput = document.querySelector('input[name="video"]');
        const video = videoInput?.files?.[0];
        if (video) {
            formData.append('video', video);
        }

        try {
            const response = await fetch('/api/reviews', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData,
                credentials: 'same-origin'
            });

            const data = await response.json();

            if (response.ok) {
                showMessage('success', data.message || 'Review berhasil ditambahkan!');
                document.getElementById('reviewForm').reset();
                selectedRating = 0;
                document.getElementById('ratingInput').value = '';
                document.querySelectorAll('.star').forEach(s => s.style.color = '#6c757d');
                setTimeout(() => location.reload(), 1500);
            } else {
                showMessage('error', data.message || 'Gagal mengirim ulasan');
            }
        } catch (error) {
            console.error('Error:', error);
            showMessage('error', 'Terjadi kesalahan: ' + error.message);
        }
    });

    function showMessage(type, message) {
        const messageDiv = document.getElementById('reviewMessage');
        messageDiv.textContent = message;
        messageDiv.className = `alert ${type === 'success' ? 'alert-success' : 'alert-danger'} mt-3`;
        messageDiv.classList.remove('d-none');
    }

    function editReview(reviewId) {
        alert('Fitur edit review akan segera tersedia');
    }

    async function deleteReview(reviewId) {
        if (!confirm('Yakin ingin menghapus ulasan ini?')) return;

        try {
            const response = await fetch(`/api/reviews/${reviewId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            if (response.ok) {
                showMessage('success', 'Ulasan dihapus');
                setTimeout(() => location.reload(), 1500);
            } else {
                showMessage('error', 'Gagal menghapus ulasan');
            }
        } catch (error) {
            console.error('Error:', error);
            showMessage('error', 'Terjadi kesalahan: ' + error.message);
        }
    }

    function openModal(src) {
        document.getElementById('photoModal').style.display = 'flex';
        document.getElementById('modalImage').src = src;
    }

    function closeModal() {
        document.getElementById('photoModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('photoModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }
</script>
@endif
