<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suwetres.in - Deteksi Tingkat Stress & Fatigue Mahasiswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    @yield('styles')
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('landing') }}" class="navbar-brand">SUWETRES.IN</a>
        <div class="navbar-info">UAS / PRAKTIKUM AI</div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    <footer class="footer">
        <div class="neo-badge" style="background-color: var(--white); transform: rotate(1deg);">
            © {{ date('Y') }} - SUWETRES.IN - GENERASI MAHASISWA ANTI-BURNOUT
        </div>
    </footer>
    @yield('scripts')
</body>
</html>
