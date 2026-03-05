<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mis pedidos — MACUIN</title>
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

        /* ── Header ── */
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
        .header-nav { display: flex; align-items: center; gap: 0.25rem; }
        .header-nav a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9375rem;
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            transition: color 0.2s, background 0.2s;
        }
        .header-nav a:hover { color: var(--primary); background: rgba(31,58,95,0.06); }
        .header-nav a.active { color: var(--primary); background: rgba(31,58,95,0.08); }
        .header-user { position: relative; display: flex; align-items: center; gap: 0.5rem; }
        .header-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff; display: flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: 1rem; cursor: pointer; border: none;
        }
        .header-dropdown {
            position: absolute; top: calc(100% + 0.5rem); right: 0;
            min-width: 180px; background: var(--card); border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1); padding: 0.5rem;
            display: none; z-index: 10;
        }
        .header-dropdown.show { display: block; }
        .header-dropdown a {
            display: block; padding: 0.6rem 0.75rem;
            color: var(--text-primary); text-decoration: none; font-size: 0.9375rem;
            border-radius: 8px;
        }
        .header-dropdown a:hover { background: var(--bg); color: var(--primary); }

        /* ── Main ── */
        .main { position: relative; z-index: 1; max-width: 1100px; margin: 0 auto; padding: 1.5rem; }

        /* ── Page header ── */
        .page-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .page-header h1 { font-size: 1.75rem; font-weight: 700; color: var(--primary); margin-bottom: 0.25rem; }
        .page-header p { font-size: 0.9375rem; color: var(--text-secondary); }
        .btn-new {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
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
        .btn-new:hover { background: #2a4d7a; box-shadow: 0 4px 16px rgba(31,58,95,0.3); }
        .btn-new svg { width: 1.125rem; height: 1.125rem; }

        /* ── Stats strip ── */
        .stats-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .stat-card {
            background: var(--card);
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            border: 1px solid rgba(31,58,95,0.06);
            box-shadow: 0 2px 8px rgba(31,58,95,0.05);
        }
        .stat-card .label { font-size: 0.8125rem; color: var(--text-secondary); font-weight: 500; margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.03em; }
        .stat-card .value { font-size: 1.625rem; font-weight: 700; color: var(--primary); }
        .stat-card .sub { font-size: 0.8125rem; color: var(--text-secondary); margin-top: 0.2rem; }

        /* ── Filter bar ── */
        .filter-bar {
            background: var(--card);
            border-radius: 14px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.25rem;
            border: 1px solid rgba(31,58,95,0.06);
            box-shadow: 0 2px 8px rgba(31,58,95,0.04);
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: center;
        }
        .search-box {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
            min-width: 200px;
            padding: 0.5rem 0.875rem;
            background: var(--bg);
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .search-box:focus-within { border-color: var(--secondary); box-shadow: 0 0 0 3px rgba(74,111,165,0.12); }
        .search-box svg { width: 1rem; height: 1rem; color: var(--text-secondary); flex-shrink: 0; }
        .search-box input { border: none; background: none; outline: none; font-family: inherit; font-size: 0.9375rem; color: var(--text-primary); width: 100%; }
        .search-box input::placeholder { color: var(--text-secondary); opacity: 0.8; }

        .filter-select {
            padding: 0.5rem 0.875rem;
            font-family: inherit;
            font-size: 0.9375rem;
            color: var(--text-primary);
            background: var(--bg);
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            outline: none;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        .filter-select:focus { border-color: var(--secondary); }

        /* ── Status tabs ── */
        .status-tabs {
            display: flex;
            gap: 0.25rem;
            flex-wrap: wrap;
        }
        .status-tab {
            padding: 0.4rem 0.875rem;
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-secondary);
            background: none;
            border: 1px solid transparent;
            border-radius: 999px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .status-tab:hover { color: var(--primary); background: rgba(31,58,95,0.05); }
        .status-tab.active { color: var(--primary); background: rgba(31,58,95,0.08); border-color: rgba(31,58,95,0.15); }

        /* ── Table card ── */
        .table-card {
            background: var(--card);
            border-radius: 18px;
            border: 1px solid rgba(31,58,95,0.06);
            box-shadow: 0 2px 12px rgba(31,58,95,0.06);
            overflow: hidden;
        }
        .table-wrap { overflow-x: auto; }
        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }
        .orders-table thead tr { background: #f9fafb; border-bottom: 1px solid #eef0f3; }
        .orders-table th {
            text-align: left;
            padding: 0.875rem 1.25rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            white-space: nowrap;
        }
        .orders-table td {
            padding: 1.1rem 1.25rem;
            border-bottom: 1px solid #f0f2f5;
            font-size: 0.9375rem;
            vertical-align: middle;
        }
        .orders-table tbody tr:last-child td { border-bottom: none; }
        .orders-table tbody tr { transition: background 0.15s; }
        .orders-table tbody tr:hover td { background: #fafbfc; }

        .order-id { font-weight: 700; color: var(--primary); }
        .order-date { color: var(--text-secondary); font-size: 0.875rem; }
        .order-items { color: var(--text-secondary); font-size: 0.875rem; }
        .order-total { font-weight: 600; color: var(--text-primary); }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.275rem 0.625rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 999px;
            white-space: nowrap;
        }
        .badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
        .badge-success { background: rgba(76,175,80,0.12); color: #2e7d32; }
        .badge-success::before { background: #4CAF50; }
        .badge-warning { background: rgba(255,193,7,0.18); color: #8a6200; }
        .badge-warning::before { background: #FFC107; }
        .badge-error { background: rgba(229,57,53,0.12); color: #c62828; }
        .badge-error::before { background: #E53935; }
        .badge-info { background: rgba(74,111,165,0.12); color: var(--primary); }
        .badge-info::before { background: var(--secondary); }

        /* Action buttons */
        .action-btns { display: flex; align-items: center; gap: 0.5rem; }
        .btn-detail {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.875rem;
            font-family: inherit;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--secondary);
            background: rgba(74,111,165,0.08);
            border: 1px solid rgba(74,111,165,0.18);
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
            cursor: pointer;
        }
        .btn-detail:hover { background: rgba(74,111,165,0.15); color: var(--primary); }
        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: var(--card);
            color: var(--text-secondary);
            cursor: pointer;
            transition: border-color 0.2s, color 0.2s, background 0.2s;
        }
        .btn-icon:hover { border-color: var(--secondary); color: var(--primary); background: rgba(74,111,165,0.06); }
        .btn-icon svg { width: 14px; height: 14px; }

        /* ── Pagination ── */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-top: 1px solid #f0f2f5;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        .pagination-info { font-size: 0.875rem; color: var(--text-secondary); }
        .pagination-btns { display: flex; gap: 0.375rem; }
        .pg-btn {
            width: 34px; height: 34px;
            display: flex; align-items: center; justify-content: center;
            font-family: inherit; font-size: 0.875rem; font-weight: 500;
            color: var(--text-secondary);
            background: var(--card);
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .pg-btn:hover { border-color: var(--secondary); color: var(--primary); }
        .pg-btn.active { background: var(--primary); color: #fff; border-color: var(--primary); }
        .pg-btn:disabled { opacity: 0.4; cursor: not-allowed; }
        .pg-btn svg { width: 14px; height: 14px; }

        /* ── Mobile cards ── */
        .mobile-orders { display: none; padding: 1rem; }
        .mobile-order-card {
            background: var(--bg);
            border-radius: 14px;
            padding: 1rem 1.25rem;
            margin-bottom: 0.875rem;
            border: 1px solid rgba(31,58,95,0.07);
        }
        .mobile-order-card:last-child { margin-bottom: 0; }
        .mob-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
        .mob-row:last-child { margin-bottom: 0; }
        .mob-label { font-size: 0.8125rem; color: var(--text-secondary); }
        .mob-val { font-size: 0.9375rem; font-weight: 500; }

        /* ── Empty state ── */
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
        }
        .empty-icon {
            width: 80px; height: 80px; margin: 0 auto 1.25rem;
            background: rgba(74,111,165,0.08);
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
        }
        .empty-icon svg { width: 40px; height: 40px; color: var(--secondary); opacity: 0.6; }
        .empty-state h3 { font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; }
        .empty-state p { font-size: 0.9375rem; color: var(--text-secondary); margin-bottom: 1.5rem; }

        @media (max-width: 900px) {
            .stats-strip { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .header-nav { display: none; }
            .page-header { flex-direction: column; align-items: stretch; }
            .page-header .btn-new { align-self: flex-start; }
            .stats-strip { grid-template-columns: repeat(2, 1fr); }
            .table-card .table-wrap { display: none; }
            .mobile-orders { display: block; }
            .pagination { justify-content: center; }
            .pagination-info { width: 100%; text-align: center; }
        }
        @media (max-width: 480px) {
            .main { padding: 1rem; }
            .stats-strip { grid-template-columns: 1fr 1fr; gap: 0.75rem; }
            .stat-card { padding: 1rem; }
            .page-header h1 { font-size: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="page-bg"></div>

    <header class="header">
        <div class="header-left">
            <a href="{{ url('/dashboard') }}">
                <img src="{{ asset('images/logo_macuin.png') }}" alt="MACUIN" class="header-logo"
                    onerror="this.parentElement.innerHTML='<span style=\'font-weight:700;color:var(--primary);font-size:1.25rem;\'>MACUIN</span>'">
            </a>
            <nav class="header-nav">
                <a href="{{ url('/dashboard') }}">Dashboard</a>
                <a href="{{ url('/catalogo') }}">Catálogo</a>
                <a href="{{ url('/pedidos') }}" class="active">Mis pedidos</a>
                <a href="#">Perfil</a>
            </nav>
        </div>
        <div class="header-user">
            <button type="button" class="header-avatar" id="user-menu-btn" aria-expanded="false">
                {{ strtoupper(substr(optional(auth()->user())->name ?? 'U', 0, 1)) }}
            </button>
            <div class="header-dropdown" id="user-dropdown">
                <a href="#">Mi perfil</a>
                <a href="#">Configuración</a>
                <a href="{{ url('/login') }}">Cerrar sesión</a>
            </div>
        </div>
    </header>

    <main class="main">
        @php
            $orders = [
                ['id' => 'PED-2847', 'date' => '25 Feb 2025', 'items' => 4, 'status' => 'warning', 'status_label' => 'En proceso',  'total' => '$1,240.00'],
                ['id' => 'PED-2812', 'date' => '20 Feb 2025', 'items' => 2, 'status' => 'success', 'status_label' => 'Entregado',   'total' => '$890.50'],
                ['id' => 'PED-2798', 'date' => '15 Feb 2025', 'items' => 6, 'status' => 'error',   'status_label' => 'Cancelado',   'total' => '$320.00'],
                ['id' => 'PED-2761', 'date' => '08 Feb 2025', 'items' => 3, 'status' => 'success', 'status_label' => 'Entregado',   'total' => '$1,580.00'],
                ['id' => 'PED-2740', 'date' => '01 Feb 2025', 'items' => 1, 'status' => 'success', 'status_label' => 'Entregado',   'total' => '$245.00'],
                ['id' => 'PED-2718', 'date' => '26 Ene 2025', 'items' => 5, 'status' => 'info',    'status_label' => 'Enviado',     'total' => '$2,100.00'],
                ['id' => 'PED-2695', 'date' => '18 Ene 2025', 'items' => 2, 'status' => 'success', 'status_label' => 'Entregado',   'total' => '$460.00'],
                ['id' => 'PED-2670', 'date' => '10 Ene 2025', 'items' => 3, 'status' => 'error',   'status_label' => 'Cancelado',   'total' => '$730.00'],
            ];
        @endphp

        <div class="page-header">
            <div>
                <h1>Mis pedidos</h1>
                <p>Consulta y gestiona todo el historial de tus pedidos</p>
            </div>
            <a href="{{ url('/pedidos/crear') }}" class="btn-new">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo pedido
            </a>
        </div>

        {{-- Stats --}}
        <div class="stats-strip">
            <div class="stat-card">
                <div class="label">Total pedidos</div>
                <div class="value">24</div>
                <div class="sub">Histórico completo</div>
            </div>
            <div class="stat-card">
                <div class="label">En proceso</div>
                <div class="value" style="color:var(--warning)">3</div>
                <div class="sub">Pendientes de entrega</div>
            </div>
            <div class="stat-card">
                <div class="label">Entregados</div>
                <div class="value" style="color:var(--success)">18</div>
                <div class="sub">Completados con éxito</div>
            </div>
            <div class="stat-card">
                <div class="label">Gasto total</div>
                <div class="value">$14,320</div>
                <div class="sub">Acumulado 2025</div>
            </div>
        </div>

        {{-- Filter bar --}}
        <div class="filter-bar">
            <div class="search-box">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="search" placeholder="Buscar por ID o producto..." aria-label="Buscar pedidos">
            </div>
            <select class="filter-select" aria-label="Filtrar por fecha">
                <option>Últimos 30 días</option>
                <option>Últimos 90 días</option>
                <option>Este año</option>
                <option>Todo el historial</option>
            </select>
            <div class="status-tabs">
                <button class="status-tab active" type="button">Todos</button>
                <button class="status-tab" type="button">En proceso</button>
                <button class="status-tab" type="button">Enviados</button>
                <button class="status-tab" type="button">Entregados</button>
                <button class="status-tab" type="button">Cancelados</button>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-card">
            <div class="table-wrap">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Fecha</th>
                            <th>Artículos</th>
                            <th>Estatus</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td><span class="order-id">{{ $order['id'] }}</span></td>
                            <td><span class="order-date">{{ $order['date'] }}</span></td>
                            <td><span class="order-items">{{ $order['items'] }} {{ $order['items'] === 1 ? 'artículo' : 'artículos' }}</span></td>
                            <td><span class="badge badge-{{ $order['status'] }}">{{ $order['status_label'] }}</span></td>
                            <td><span class="order-total">{{ $order['total'] }}</span></td>
                            <td>
                                <div class="action-btns">
                                    <a href="{{ url('/pedidos/' . str_replace('PED-', '', $order['id'])) }}" class="btn-detail">
                                        Ver detalle
                                    </a>
                                    <button type="button" class="btn-icon" title="Descargar comprobante" aria-label="Descargar">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 4v11"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile cards --}}
            <div class="mobile-orders">
                @foreach($orders as $order)
                <div class="mobile-order-card">
                    <div class="mob-row">
                        <span class="order-id">{{ $order['id'] }}</span>
                        <span class="badge badge-{{ $order['status'] }}">{{ $order['status_label'] }}</span>
                    </div>
                    <div class="mob-row">
                        <span class="mob-label">Fecha</span>
                        <span class="mob-val">{{ $order['date'] }}</span>
                    </div>
                    <div class="mob-row">
                        <span class="mob-label">Artículos</span>
                        <span class="mob-val">{{ $order['items'] }}</span>
                    </div>
                    <div class="mob-row">
                        <span class="mob-label">Total</span>
                        <span class="mob-val order-total">{{ $order['total'] }}</span>
                    </div>
                    <div class="mob-row" style="margin-top:0.75rem;">
                        <a href="{{ url('/pedidos/' . str_replace('PED-', '', $order['id'])) }}" class="btn-detail" style="flex:1;justify-content:center;">Ver detalle</a>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="pagination">
                <span class="pagination-info">Mostrando 1–8 de 24 pedidos</span>
                <div class="pagination-btns">
                    <button class="pg-btn" disabled aria-label="Anterior">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button class="pg-btn active">1</button>
                    <button class="pg-btn">2</button>
                    <button class="pg-btn">3</button>
                    <button class="pg-btn" aria-label="Siguiente">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <script>
        (function () {
            // User dropdown
            document.getElementById('user-menu-btn').addEventListener('click', function () {
                var d = document.getElementById('user-dropdown');
                d.classList.toggle('show');
                this.setAttribute('aria-expanded', d.classList.contains('show'));
            });
            document.addEventListener('click', function (e) {
                if (!e.target.closest('.header-user')) {
                    document.getElementById('user-dropdown').classList.remove('show');
                    document.getElementById('user-menu-btn').setAttribute('aria-expanded', 'false');
                }
            });

            // Status tabs
            document.querySelectorAll('.status-tab').forEach(function (tab) {
                tab.addEventListener('click', function () {
                    document.querySelectorAll('.status-tab').forEach(function (t) { t.classList.remove('active'); });
                    this.classList.add('active');
                });
            });

            // Pagination buttons
            document.querySelectorAll('.pg-btn:not([disabled])').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    if (this.querySelector('svg')) return;
                    document.querySelectorAll('.pg-btn').forEach(function (b) { b.classList.remove('active'); });
                    this.classList.add('active');
                });
            });
        })();
    </script>
</body>
</html>