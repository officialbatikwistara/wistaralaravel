@extends('layouts.admin')

@section('title', 'Edit Review')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/user/review/edit.css') }}">

    <style>
        /* 🌄 Background fullscreen solid */
        body {
            font-family: 'Poppins', sans-serif;
            color: #0b1841;
            margin: 0;
            min-height: 100vh;
            background: url('{{ asset('img/background1.svg') }}') no-repeat center center fixed;
            background-size: cover;
        }
    </style>

    {{-- =========================
    🌸 MAIN CONTENT
========================= --}}
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Edit Review</h1>
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- FORM --}}
        <div class="form-container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="mb-4">
                        <h5 class="text-muted mb-3">
                            <i class="fa-solid fa-user me-2"></i> {{ $review->user->name }}
                            <span class="badge bg-primary ms-2">{{ $review->product->nama_produk }}</span>
                        </h5>
                    </div>

                    <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="rating" class="form-label fw-semibold">Rating <span
                                    class="text-danger">*</span></label>
                            <select name="rating" id="rating" class="form-select" required>
                                <option value="">Pilih Rating</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>
                                        {{ $i }} ⭐
                                        {{ $i == 1 ? '(Sangat Buruk)' : ($i == 2 ? '(Buruk)' : ($i == 3 ? '(Cukup)' : ($i == 4 ? '(Baik)' : '(Sangat Baik)'))) }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label fw-semibold">Komentar <span
                                    class="text-danger">*</span></label>
                            <textarea name="comment" id="comment" class="form-control" rows="5" required
                                placeholder="Masukkan komentar review...">{{ $review->comment }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status Review</label>
                            <div class="d-flex gap-2">
                                <span
                                    class="badge
                @if ($review->status === 'pending') bg-warning text-dark
                @elseif($review->status === 'approved') bg-success
                @else bg-danger @endif">
                                    @if ($review->status === 'pending')
                                        Pending
                                    @elseif($review->status === 'approved')
                                        Disetujui
                                    @else
                                        Ditolak
                                    @endif
                                </span>
                            </div>
                            <small class="text-muted">Status dapat diubah melalui halaman utama review</small>
                        </div>

                        @if ($review->photos && count($review->photos) > 0)
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Foto Review</label>
                                <div class="row g-2">
                                    @foreach ($review->photos as $photo)
                                        <div class="col-auto">
                                            <img src="{{ asset('storage/' . $photo) }}" alt="Review photo" class="rounded"
                                                style="width: 100px; height: 100px; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($review->video)
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Video Review</label>
                                <video width="300" height="200" controls class="rounded">
                                    <source src="{{ asset('storage/' . $review->video) }}" type="video/mp4">
                                    Browser Anda tidak mendukung video.
                                </video>
                            </div>
                        @endif

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-dark">
                                <i class="fa-solid fa-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
