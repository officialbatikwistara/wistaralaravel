<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Batik Wistara Admin</title>

    {{-- Bootstrap & FontAwesome --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    {{-- Style umum --}}
    <link href="{{ asset('style.css') }}" rel="stylesheet">
</head>
<body>

    {{-- Header --}}
    @include('admin.header')

    {{-- Konten halaman dinamis --}}
    <main class="container-fluid px-4 py-4">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('admin.footer')

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
