<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MACUIN')</title>
    @stack('styles')
</head>
<body>
    {{-- 1. Header / barra superior --}}
    <header style="background:#1b1b18;color:#fff;padding:1rem 1.5rem;display:flex;justify-content:space-between;align-items:center;">
        <img src="{{ asset('images/logo_macuin.png') }}" alt="MACUIN" style="height:36px;width:auto;display:block;" onerror="this.style.display='none';this.nextElementSibling.style.display='inline';">
        <strong style="font-size:1.25rem;display:none;">MACUIN</strong>
        <nav>
            <a href="{{ url('/dashboard') }}" style="color:#fff;margin-right:1rem;text-decoration:none;">Dashboard</a>
            <a href="{{ url('/pedidos') }}" style="color:#fff;margin-right:1rem;text-decoration:none;">Pedidos</a>
            <a href="#" style="color:#fff;text-decoration:none;">Logout</a>
        </nav>
    </header>

    {{-- 2. Contenido dinámico --}}
    <main style="min-height:60vh;padding:1.5rem;">
        @yield('content')
    </main>

    {{-- 3. Footer --}}
    <footer style="background:#eee;padding:1rem 1.5rem;text-align:center;color:#555;font-size:0.9rem;">
        Sistema MACUIN — Segundo Parcial
    </footer>

    @stack('scripts')
</body>
</html>
