{{-- Header --}}
@include('admin.header')

{{-- Konten halaman dinamis --}}
<main style="padding-top: 80px; min-height: 100vh;">
    <div class="container-fluid px-4 py-4">
        @yield('content')
    </div>
</main>

{{-- Footer --}}
@include('admin.footer')
