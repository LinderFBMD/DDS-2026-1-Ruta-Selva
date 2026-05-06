<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Turismo Quillabamba')</title>
    <link rel="stylesheet" href="{{ asset('app.css') }}">
</head>
<body>

    <nav class="nav">
        <a class="nav-logo" href="{{ route('establecimientos.index') }}">
            <span class="titulo">🌿 Turismo Quillabamba</span>
            <span class="subtitulo">La Ciudad del Eterno Verano</span>
        </a>
    </nav>

    <div class="main">
        @yield('content')
    </div>

    <footer class="footer">
        © {{ date('Y') }} Turismo Quillabamba — Cusco, Perú
    </footer>

</body>
</html>