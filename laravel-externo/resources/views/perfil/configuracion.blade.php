<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configuración — MACUIN</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1F3A5F;
            --secondary: #4A6FA5;
            --success: #4CAF50;
            --warning: #FFC107;
            --error: #E53935;
            --bg: #F4F6F8;
            --card: #FFFFFF;
            --text-primary: #333333;
            --text-secondary: #666666;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            min-height: 100vh;
        }
        .page-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            background: var(--bg);
        }
        .page-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0.025;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 8v4l-2 1 2 2 2-2-2-1v-4zm0 32v4l2 1-2 2-2-2 2-1v-4z' fill='%231F3A5F'/%3E%3C/svg%3E");
        }
        .header {
            position: relative;
            z-index: 2;
            background: var(--card);
            padding: 0.875rem 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .header-left { display: flex; align-items: center; gap: 2rem; }
        .header-logo { height: 36px; width: auto; display: block; }
        .header-nav {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .header-nav a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9375rem;
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            transition: color 0.2s, background 0.2s;
        }
        .header-nav a:hover { color: var(--primary); background: rgba(31, 58, 95, 0.06); }
        .header-nav a.active { color: var(--primary); background: rgba(31, 58, 95, 0.08); }
        .header-user {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .header-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            border: none;
        }
        .header-dropdown {
            position: absolute;
            top: calc(100% + 0.5rem);
            right: 0;
            min-width: 180px;
            background: var(--card);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 0.5rem;
            display: none;
            z-index: 10;
        }
        .header-dropdown.show { display: block; }
        .header-dropdown a {
            display: block;
            padding: 0.6rem 0.75rem;
            color: var(--text-primary);
            text-decoration: none;
            font-size: 0.9375rem;
            border-radius: 8px;
        }
        .header-dropdown a:hover { background: var(--bg); color: var(--primary); }
        .main {
            position: relative;
            z-index: 1;
            max-width: 1100px;
            margin: 0 auto;
            padding: 1.5rem;
        }
        .hero,
        .card {
            background: var(--card);
            border-radius: 18px;
            border: 1px solid rgba(31, 58, 95, 0.06);
            box-shadow: 0 2px 12px rgba(31, 58, 95, 0.06);
        }
        .hero {
            padding: 2rem;
            margin-bottom: 1.5rem;
        }
        .hero h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.35rem;
        }
        .hero p {
            color: var(--text-secondary);
            font-size: 0.9375rem;
        }
        .profile-tabs {
            display: inline-flex;
            gap: 0.5rem;
            margin-top: 1.25rem;
            padding: 0.4rem;
            border-radius: 12px;
            background: var(--bg);
        }
        .profile-tabs a {
            text-decoration: none;
            color: var(--text-secondary);
            font-size: 0.9375rem;
            font-weight: 500;
            padding: 0.65rem 1rem;
            border-radius: 10px;
            transition: background 0.2s, color 0.2s;
        }
        .profile-tabs a.active {
            background: var(--card);
            color: var(--primary);
            box-shadow: 0 2px 8px rgba(31, 58, 95, 0.08);
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        .card { padding: 1.5rem; }
        .section-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        .setting-list {
            display: grid;
            gap: 1rem;
        }
        .setting-item {
            padding: 1rem 1.1rem;
            border-radius: 12px;
            background: var(--bg);
        }
        .setting-item strong {
            display: block;
            margin-bottom: 0.25rem;
            color: var(--text-primary);
            font-size: 0.9375rem;
        }
        .setting-item span {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        .switch-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(31, 58, 95, 0.08);
        }
        .switch-row:last-child { border-bottom: none; }
        .switch-copy strong {
            display: block;
            margin-bottom: 0.25rem;
            color: var(--text-primary);
            font-size: 0.9375rem;
        }
        .switch-copy span {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        .switch {
            position: relative;
            width: 52px;
            height: 30px;
            flex-shrink: 0;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            inset: 0;
            cursor: pointer;
            background: #cbd5e1;
            border-radius: 999px;
            transition: background 0.2s;
        }
        .slider::before {
            content: '';
            position: absolute;
            width: 22px;
            height: 22px;
            left: 4px;
            top: 4px;
            background: #fff;
            border-radius: 50%;
            transition: transform 0.2s;
            box-shadow: 0 1px 4px rgba(0,0,0,0.15);
        }
        .switch input:checked + .slider {
            background: var(--secondary);
        }
        .switch input:checked + .slider::before {
            transform: translateX(22px);
        }
        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1.25rem;
        }
        .btn-secondary,
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            text-decoration: none;
            font-size: 0.9375rem;
            font-weight: 600;
            border: 1px solid transparent;
            cursor: pointer;
        }
        .btn-secondary {
            color: var(--secondary);
            background: transparent;
            border-color: rgba(74, 111, 165, 0.25);
        }
        .btn-primary {
            color: #fff;
            background: var(--primary);
        }
        @media (max-width: 900px) {
            .grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .header-nav { display: none; }
        }
        @media (max-width: 480px) {
            .main { padding: 1rem; }
            .hero, .card { padding: 1.25rem; }
            .actions { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="page-bg"></div>

    <header class="header">
        <div class="header-left">
            <a href="{{ url('/dashboard') }}">
                <img src="{{ asset('images/logo_macuin.png') }}" alt="MACUIN" class="header-logo" onerror="this.parentElement.innerHTML='<span style=\'font-weight:700;color:var(--primary);font-size:1.25rem;\'>MACUIN</span>'">
            </a>
            <nav class="header-nav">
                <a href="{{ url('/dashboard') }}">Dashboard</a>
                <a href="{{ url('/catalogo') }}">Catálogo</a>
                <a href="{{ url('/pedidos') }}">Mis pedidos</a>
                <a href="{{ url('/perfil') }}" class="active">Perfil</a>
            </nav>
        </div>
        <div class="header-user">
            <button type="button" class="header-avatar" id="user-menu-btn" aria-expanded="false" aria-haspopup="true">
                {{ strtoupper(substr(optional(auth()->user())->name ?? 'Usuario', 0, 1)) }}
            </button>
            <div class="header-dropdown" id="user-dropdown" role="menu">
                <a href="{{ url('/perfil') }}">Mi perfil</a>
                <a href="{{ url('/perfil/configuracion') }}">Configuración</a>
                <a href="{{ url('/login') }}">Cerrar sesión</a>
            </div>
        </div>
    </header>

    <main class="main">
        <section class="hero">
            <h1>Configuración</h1>
            <p>Administra tus preferencias y opciones generales de tu cuenta.</p>
            <div class="profile-tabs">
                <a href="{{ url('/perfil') }}">Mi perfil</a>
                <a href="{{ url('/perfil/configuracion') }}" class="active">Configuración</a>
            </div>
        </section>

        <div class="grid">
            <section class="card">
                <h2 class="section-title">Preferencias de cuenta</h2>
                <div class="setting-list">
                    <div class="setting-item">
                        <strong>Idioma</strong>
                        <span>Español</span>
                    </div>
                    <div class="setting-item">
                        <strong>Zona horaria</strong>
                        <span>Ciudad de México (GMT-6)</span>
                    </div>
                    <div class="setting-item">
                        <strong>Moneda</strong>
                        <span>MXN - Peso mexicano</span>
                    </div>
                </div>
            </section>

            <section class="card">
                <h2 class="section-title">Notificaciones</h2>
                <div class="switch-row">
                    <div class="switch-copy">
                        <strong>Actualizaciones de pedidos</strong>
                        <span>Recibe avisos cuando el estado de un pedido cambie.</span>
                    </div>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="switch-row">
                    <div class="switch-copy">
                        <strong>Promociones y novedades</strong>
                        <span>Notificaciones sobre nuevos productos y campañas.</span>
                    </div>
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="switch-row">
                    <div class="switch-copy">
                        <strong>Resumen semanal</strong>
                        <span>Recibe un resumen con la actividad reciente de tu cuenta.</span>
                    </div>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="actions">
                    <a href="{{ url('/perfil') }}" class="btn-secondary">Volver al perfil</a>
                    <button type="button" class="btn-primary">Guardar cambios</button>
                </div>
            </section>
        </div>
    </main>

    <script>
        document.getElementById('user-menu-btn').addEventListener('click', function() {
            var d = document.getElementById('user-dropdown');
            d.classList.toggle('show');
            this.setAttribute('aria-expanded', d.classList.contains('show'));
        });
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.header-user')) {
                document.getElementById('user-dropdown').classList.remove('show');
                document.getElementById('user-menu-btn').setAttribute('aria-expanded', 'false');
            }
        });
    </script>
</body>
</html>
