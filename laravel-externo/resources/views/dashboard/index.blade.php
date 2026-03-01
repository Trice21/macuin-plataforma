<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard — MACUIN</title>
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

        /* Header */
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
        .header-logo {
            height: 36px;
            width: auto;
            display: block;
        }
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

        .main { position: relative; z-index: 1; padding: 1.5rem; max-width: 1200px; margin: 0 auto; }

        /* Welcome */
        .welcome {
            background: var(--card);
            border-radius: 18px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 12px rgba(31, 58, 95, 0.06);
            border: 1px solid rgba(31, 58, 95, 0.06);
        }
        .welcome h1 { font-size: 1.75rem; font-weight: 700; color: var(--primary); margin-bottom: 0.35rem; }
        .welcome p { font-size: 0.9375rem; color: var(--text-secondary); margin-bottom: 1.5rem; }
        .welcome-btns { display: flex; flex-wrap: wrap; gap: 0.75rem; }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.625rem 1.25rem;
            font-family: inherit;
            font-size: 0.9375rem;
            font-weight: 600;
            color: #fff;
            background: var(--primary);
            border: none;
            border-radius: 10px;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
        }
        .btn-primary:hover { background: #2a4d7a; box-shadow: 0 4px 16px rgba(31, 58, 95, 0.3); }
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            padding: 0.625rem 1.25rem;
            font-family: inherit;
            font-size: 0.9375rem;
            font-weight: 500;
            color: var(--secondary);
            background: transparent;
            border: 1px solid var(--secondary);
            border-radius: 10px;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }
        .btn-secondary:hover { background: rgba(74, 111, 165, 0.08); color: var(--primary); }

        /* Cards section */
        .section-title { font-size: 1.125rem; font-weight: 600; color: var(--primary); margin-bottom: 1rem; }
        .card {
            background: var(--card);
            border-radius: 18px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 12px rgba(31, 58, 95, 0.06);
            border: 1px solid rgba(31, 58, 95, 0.06);
        }

        /* Active orders - horizontal cards */
        .active-orders-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }
        .order-card {
            background: var(--bg);
            border-radius: 12px;
            padding: 1.25rem;
            border: 1px solid rgba(31, 58, 95, 0.06);
        }
        .order-card-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem; }
        .order-card-id { font-weight: 600; color: var(--primary); font-size: 0.9375rem; }
        .order-card-date { font-size: 0.8125rem; color: var(--text-secondary); }
        .order-card-meta { display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; flex-wrap: wrap; gap: 0.5rem; }
        .order-card-total { font-weight: 600; color: var(--text-primary); }
        .order-card .btn-link {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--secondary);
            text-decoration: none;
        }
        .order-card .btn-link:hover { text-decoration: underline; }

        /* Status badges */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.625rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 999px;
        }
        .badge-success { background: rgba(76, 175, 80, 0.15); color: var(--success); }
        .badge-warning { background: rgba(255, 193, 7, 0.2); color: #b38600; }
        .badge-error { background: rgba(229, 57, 53, 0.15); color: var(--error); }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 2.5rem 1.5rem;
        }
        .empty-state-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            background: linear-gradient(135deg, rgba(31, 58, 95, 0.08), rgba(74, 111, 165, 0.08));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .empty-state-icon svg { width: 40px; height: 40px; color: var(--secondary); opacity: 0.7; }
        .empty-state h3 { font-size: 1rem; color: var(--text-primary); margin-bottom: 0.35rem; }
        .empty-state p { font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 1rem; }

        /* Recent orders table */
        .table-wrap { overflow-x: auto; }
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9375rem;
        }
        .orders-table th {
            text-align: left;
            padding: 0.75rem 1rem;
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.8125rem;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }
        .orders-table td { padding: 1rem; border-top: 1px solid #eee; }
        .orders-table tr:hover td { background: var(--bg); }
        .orders-table .btn-link { color: var(--secondary); text-decoration: none; font-weight: 500; }
        .orders-table .btn-link:hover { text-decoration: underline; }

        /* Stacked cards for mobile */
        .orders-stacked { display: none; }
        .order-row-card {
            background: var(--bg);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            border: 1px solid rgba(31, 58, 95, 0.06);
        }
        .order-row-card .row { display: flex; justify-content: space-between; margin-bottom: 0.5rem; }
        .order-row-card .row:last-child { margin-bottom: 0; }
        .order-row-card .label { font-size: 0.8125rem; color: var(--text-secondary); }
        .order-row-card .value { font-weight: 500; }

        /* Quick actions */
        .quick-actions { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem; }
        .quick-action {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem;
            background: var(--card);
            border-radius: 16px;
            text-decoration: none;
            color: var(--text-primary);
            border: 1px solid rgba(31, 58, 95, 0.06);
            box-shadow: 0 2px 8px rgba(31, 58, 95, 0.04);
            transition: box-shadow 0.2s, border-color 0.2s;
        }
        .quick-action:hover { box-shadow: 0 4px 16px rgba(31, 58, 95, 0.08); border-color: rgba(74, 111, 165, 0.2); }
        .quick-action-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(74, 111, 165, 0.12);
            color: var(--secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .quick-action-icon svg { width: 22px; height: 22px; }
        .quick-action span { font-size: 0.9375rem; font-weight: 500; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        @media (max-width: 900px) { .grid-2 { grid-template-columns: 1fr; } }
        @media (max-width: 768px) {
            .header-nav { display: none; }
            .orders-table-wrap { display: none; }
            .orders-stacked { display: block; }
            .welcome h1 { font-size: 1.5rem; }
            .active-orders-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 480px) {
            .main { padding: 1rem; }
            .welcome, .card { padding: 1.25rem; }
            .welcome-btns { flex-direction: column; }
            .welcome-btns .btn-primary, .welcome-btns .btn-secondary { justify-content: center; }
            .quick-actions { grid-template-columns: 1fr; }
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
                <a href="{{ url('/dashboard') }}" class="active">Dashboard</a>
                <a href="{{ url('/catalogo') }}">Catálogo</a>
                <a href="{{ url('/catalogo') }}">Mis pedidos</a>
                <a href="#">Perfil</a>
            </nav>
        </div>
        <div class="header-user">
            <button type="button" class="header-avatar" id="user-menu-btn" aria-expanded="false" aria-haspopup="true">
                {{ strtoupper(substr(optional(auth()->user())->name ?? 'Usuario', 0, 1)) }}
            </button>
            <div class="header-dropdown" id="user-dropdown" role="menu">
                <a href="#">Mi perfil</a>
                <a href="#">Configuración</a>
                <a href="{{ url('/login') }}">Cerrar sesión</a>
            </div>
        </div>
    </header>

    <main class="main">
        @php
            $userName = optional(auth()->user())->name ?? 'Usuario';
            $activeOrders = [
                ['id' => 'PED-2847', 'date' => '25 Feb 2025', 'status' => 'warning', 'status_label' => 'En proceso', 'total' => '$1,240.00'],
                ['id' => 'PED-2812', 'date' => '20 Feb 2025', 'status' => 'success', 'status_label' => 'Entregado', 'total' => '$890.50'],
            ];
            $recentOrders = [
                ['id' => 'PED-2847', 'date' => '25 Feb 2025', 'status' => 'warning', 'status_label' => 'En proceso', 'total' => '$1,240.00'],
                ['id' => 'PED-2812', 'date' => '20 Feb 2025', 'status' => 'success', 'status_label' => 'Entregado', 'total' => '$890.50'],
                ['id' => 'PED-2798', 'date' => '15 Feb 2025', 'status' => 'error', 'status_label' => 'Cancelado', 'total' => '$320.00'],
            ];
            $hasActiveOrders = count($activeOrders) > 0;
        @endphp

        <section class="welcome">
            <h1>Bienvenido, {{ $userName }}</h1>
            <p>Aquí puedes gestionar tus pedidos y explorar autopartes</p>
            <div class="welcome-btns">
                <a href="#" class="btn-primary">Explorar catálogo</a>
                <a href="#" class="btn-secondary">Ver todos mis pedidos</a>
            </div>
        </section>

        <div class="grid-2">
            <div>
                <section class="card">
                    <h2 class="section-title">Pedidos activos</h2>
                    @if($hasActiveOrders)
                        <div class="active-orders-grid">
                            @foreach($activeOrders as $order)
                                <div class="order-card">
                                    <div class="order-card-header">
                                        <span class="order-card-id">{{ $order['id'] }}</span>
                                        <span class="order-card-date">{{ $order['date'] }}</span>
                                    </div>
                                    <span class="badge badge-{{ $order['status'] }}">{{ $order['status_label'] }}</span>
                                    <div class="order-card-meta">
                                        <span class="order-card-total">{{ $order['total'] }}</span>
                                        <a href="{{ url('/pedidos/' . str_replace('PED-', '', $order['id'])) }}" class="btn-link">Ver detalle</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8 4-8-4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <h3>No tienes pedidos activos</h3>
                            <p>Cuando realices un pedido, aparecerá aquí.</p>
                            <a href="#" class="btn-primary">Explorar catálogo</a>
                        </div>
                    @endif
                </section>
            </div>
            <div>
                <section class="card">
                    <h2 class="section-title">Acciones rápidas</h2>
                    <div class="quick-actions">
                        <a href="#" class="quick-action">
                            <div class="quick-action-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <span>Explorar catálogo</span>
                        </a>
                        <a href="#" class="quick-action">
                            <div class="quick-action-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <span>Descargar comprobante</span>
                        </a>
                        <a href="#" class="quick-action">
                            <div class="quick-action-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <span>Contactar soporte</span>
                        </a>
                    </div>
                </section>
            </div>
        </div>

        <section class="card">
            <h2 class="section-title">Pedidos recientes</h2>
            <div class="table-wrap orders-table-wrap">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Estatus</th>
                            <th>Total</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr>
                                <td><strong>{{ $order['id'] }}</strong></td>
                                <td>{{ $order['date'] }}</td>
                                <td><span class="badge badge-{{ $order['status'] }}">{{ $order['status_label'] }}</span></td>
                                <td>{{ $order['total'] }}</td>
                                <td><a href="{{ url('/pedidos/' . str_replace('PED-', '', $order['id'])) }}" class="btn-link">Ver detalle</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="orders-stacked">
                @foreach($recentOrders as $order)
                    <div class="order-row-card">
                        <div class="row">
                            <span class="label">ID</span>
                            <span class="value">{{ $order['id'] }}</span>
                        </div>
                        <div class="row">
                            <span class="label">Fecha</span>
                            <span class="value">{{ $order['date'] }}</span>
                        </div>
                        <div class="row">
                            <span class="label">Estatus</span>
                            <span><span class="badge badge-{{ $order['status'] }}">{{ $order['status_label'] }}</span></span>
                        </div>
                        <div class="row">
                            <span class="label">Total</span>
                            <span class="value">{{ $order['total'] }}</span>
                        </div>
                        <div class="row" style="margin-top:0.75rem;">
                            <a href="{{ url('/pedidos/' . str_replace('PED-', '', $order['id'])) }}" class="btn-link">Ver detalle</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
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
