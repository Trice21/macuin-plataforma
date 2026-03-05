<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle de pedido — MACUIN</title>
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

        /* ── Breadcrumb ── */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }
        .breadcrumb a { color: var(--secondary); text-decoration: none; font-weight: 500; }
        .breadcrumb a:hover { text-decoration: underline; }
        .breadcrumb svg { width: 14px; height: 14px; opacity: 0.5; }

        /* ── Page header ── */
        .page-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .page-header-left h1 {
            font-size: 1.625rem;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-bottom: 0.3rem;
        }
        .page-header-left p { font-size: 0.9375rem; color: var(--text-secondary); }

        .header-actions { display: flex; gap: 0.625rem; flex-wrap: wrap; }
        .btn-outline {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.5rem 1rem;
            font-family: inherit; font-size: 0.875rem; font-weight: 600;
            color: var(--secondary);
            background: var(--card);
            border: 1px solid rgba(74,111,165,0.25);
            border-radius: 10px;
            text-decoration: none; cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
        }
        .btn-outline:hover { border-color: var(--secondary); background: rgba(74,111,165,0.06); }
        .btn-outline svg { width: 1rem; height: 1rem; }
        .btn-primary {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.5rem 1rem;
            font-family: inherit; font-size: 0.875rem; font-weight: 600;
            color: #fff;
            background: var(--primary);
            border: none; border-radius: 10px;
            text-decoration: none; cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
        }
        .btn-primary:hover { background: #2a4d7a; box-shadow: 0 4px 14px rgba(31,58,95,0.3); }
        .btn-primary svg { width: 1rem; height: 1rem; }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.3rem 0.75rem;
            font-size: 0.8125rem;
            font-weight: 600;
            border-radius: 999px;
        }
        .badge::before { content: ''; width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
        .badge-success { background: rgba(76,175,80,0.12); color: #2e7d32; }
        .badge-success::before { background: #4CAF50; }
        .badge-warning { background: rgba(255,193,7,0.18); color: #8a6200; }
        .badge-warning::before { background: #FFC107; }
        .badge-error { background: rgba(229,57,53,0.12); color: #c62828; }
        .badge-error::before { background: #E53935; }
        .badge-info { background: rgba(74,111,165,0.12); color: var(--primary); }
        .badge-info::before { background: var(--secondary); }

        /* ── Grid layout ── */
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 1.25rem;
            align-items: start;
        }

        /* ── Card base ── */
        .card {
            background: var(--card);
            border-radius: 18px;
            border: 1px solid rgba(31,58,95,0.06);
            box-shadow: 0 2px 12px rgba(31,58,95,0.06);
            margin-bottom: 1.25rem;
        }
        .card:last-child { margin-bottom: 0; }
        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-title { font-size: 1rem; font-weight: 600; color: var(--primary); }
        .card-body { padding: 1.5rem; }

        /* ── Progress tracker ── */
        .tracker {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            position: relative;
            padding: 0 0.5rem;
        }
        .tracker::before {
            content: '';
            position: absolute;
            top: 18px;
            left: calc(0.5rem + 18px);
            right: calc(0.5rem + 18px);
            height: 3px;
            background: #e5e7eb;
            z-index: 0;
        }
        .tracker-progress {
            position: absolute;
            top: 18px;
            left: calc(0.5rem + 18px);
            height: 3px;
            background: linear-gradient(90deg, var(--success), var(--secondary));
            z-index: 1;
            transition: width 0.6s ease;
        }
        .tracker-step { display: flex; flex-direction: column; align-items: center; gap: 0.5rem; z-index: 2; flex: 1; }
        .step-dot {
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700;
            border: 2.5px solid #e5e7eb;
            background: var(--card);
            color: var(--text-secondary);
            transition: all 0.3s;
        }
        .step-dot.done { background: var(--success); border-color: var(--success); color: #fff; }
        .step-dot.active { background: var(--primary); border-color: var(--primary); color: #fff; box-shadow: 0 0 0 4px rgba(31,58,95,0.12); }
        .step-dot svg { width: 16px; height: 16px; }
        .step-label { font-size: 0.75rem; font-weight: 500; color: var(--text-secondary); text-align: center; max-width: 70px; line-height: 1.3; }
        .step-label.done { color: var(--success); }
        .step-label.active { color: var(--primary); font-weight: 600; }
        .step-date { font-size: 0.6875rem; color: var(--text-secondary); text-align: center; }

        /* ── Products table ── */
        .products-table { width: 100%; border-collapse: collapse; }
        .products-table thead tr { background: #f9fafb; }
        .products-table th {
            text-align: left;
            padding: 0.75rem 1rem;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border-bottom: 1px solid #eef0f3;
        }
        .products-table td {
            padding: 1rem;
            border-bottom: 1px solid #f0f2f5;
            vertical-align: middle;
        }
        .products-table tbody tr:last-child td { border-bottom: none; }
        .products-table tbody tr:hover td { background: #fafbfc; }
        .prod-thumb {
            width: 48px; height: 48px; border-radius: 10px;
            background: var(--bg);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; overflow: hidden;
        }
        .prod-thumb img { width: 100%; height: 100%; object-fit: contain; }
        .prod-info { display: flex; align-items: center; gap: 0.875rem; }
        .prod-name { font-weight: 600; font-size: 0.9375rem; margin-bottom: 0.15rem; }
        .prod-sku { font-size: 0.8125rem; color: var(--text-secondary); }
        .prod-price { font-weight: 500; font-size: 0.9375rem; color: var(--text-secondary); }
        .prod-qty { font-weight: 600; font-size: 0.9375rem; text-align: center; }
        .prod-subtotal { font-weight: 700; font-size: 0.9375rem; color: var(--primary); text-align: right; }

        /* Totals */
        .totals-section { padding: 1.25rem 1.5rem; border-top: 1px solid #f0f2f5; }
        .totals-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.35rem 0;
            font-size: 0.9375rem;
            color: var(--text-secondary);
        }
        .totals-row.grand {
            padding-top: 0.75rem;
            margin-top: 0.5rem;
            border-top: 1px solid #e5e7eb;
            font-size: 1.0625rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        .totals-row.grand .amount { color: var(--primary); font-size: 1.25rem; }

        /* ── Sidebar cards ── */
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f4f5f7;
            gap: 0.5rem;
        }
        .info-row:last-child { border-bottom: none; padding-bottom: 0; }
        .info-row:first-child { padding-top: 0; }
        .info-label { font-size: 0.8125rem; color: var(--text-secondary); font-weight: 500; flex-shrink: 0; }
        .info-value { font-size: 0.9375rem; font-weight: 500; text-align: right; }

        /* Timeline */
        .timeline { padding: 0; }
        .tl-item {
            display: flex;
            gap: 1rem;
            padding-bottom: 1.25rem;
            position: relative;
        }
        .tl-item:last-child { padding-bottom: 0; }
        .tl-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 14px;
            top: 28px;
            bottom: 0;
            width: 2px;
            background: #eef0f3;
        }
        .tl-dot {
            width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            background: rgba(74,111,165,0.1);
            color: var(--secondary);
            z-index: 1;
        }
        .tl-dot.success { background: rgba(76,175,80,0.12); color: var(--success); }
        .tl-dot.warning { background: rgba(255,193,7,0.15); color: #8a6200; }
        .tl-dot svg { width: 14px; height: 14px; }
        .tl-content { flex: 1; padding-top: 0.2rem; }
        .tl-event { font-size: 0.9rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.15rem; }
        .tl-time { font-size: 0.8125rem; color: var(--text-secondary); }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            .detail-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .header-nav { display: none; }
            .page-header { flex-direction: column; align-items: stretch; }
            .header-actions { justify-content: flex-start; }
            .tracker { gap: 0; }
            .step-label { font-size: 0.6875rem; max-width: 54px; }
        }
        @media (max-width: 600px) {
            .main { padding: 1rem; }
            .card-body { padding: 1rem; }
            .card-header { padding: 1rem 1.125rem; }
            .page-header-left h1 { font-size: 1.375rem; }
            .prod-sku { display: none; }
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
            $orderId = $id ?? '2847';
            $orderRef = 'PED-' . $orderId;
            $products = [
                ['name' => 'Filtro de aceite premium',       'sku' => 'SKU-2041', 'price' => 245.00, 'qty' => 2],
                ['name' => 'Pastillas de freno delanteras',  'sku' => 'SKU-3082', 'price' => 420.00, 'qty' => 1],
                ['name' => 'Bujía de encendido iridio',      'sku' => 'SKU-5103', 'price' => 185.00, 'qty' => 3],
                ['name' => 'Sensor de oxígeno',              'sku' => 'SKU-9406', 'price' => 395.00, 'qty' => 1],
            ];
            $subtotal = array_sum(array_map(fn($p) => $p['price'] * $p['qty'], $products));
            $envio = 80.00;
            $total = $subtotal + $envio;
        @endphp

        {{-- Breadcrumb --}}
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="{{ url('/dashboard') }}">Dashboard</a>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ url('/pedidos') }}">Mis pedidos</a>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span>{{ $orderRef }}</span>
        </nav>

        {{-- Page header --}}
        <div class="page-header">
            <div class="page-header-left">
                <h1>
                    {{ $orderRef }}
                    <span class="badge badge-warning">En proceso</span>
                </h1>
                <p>Realizado el 25 de febrero de 2025 · 4 artículos</p>
            </div>
            <div class="header-actions">
                <button type="button" class="btn-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 4v11"/></svg>
                    Descargar PDF
                </button>
                <button type="button" class="btn-outline" style="color:var(--error);border-color:rgba(229,57,53,0.25);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Cancelar pedido
                </button>
            </div>
        </div>

        <div class="detail-grid">
            {{-- LEFT COLUMN --}}
            <div>
                {{-- Progress tracker --}}
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Seguimiento del pedido</span>
                        <span style="font-size:0.8125rem;color:var(--text-secondary);">Actualizado: hoy 09:42</span>
                    </div>
                    <div class="card-body">
                        <div class="tracker">
                            <div class="tracker-progress" style="width:37%;"></div>

                            <div class="tracker-step">
                                <div class="step-dot done">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div class="step-label done">Confirmado</div>
                                <div class="step-date">25 Feb</div>
                            </div>

                            <div class="tracker-step">
                                <div class="step-dot active">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <div class="step-label active">Preparando</div>
                                <div class="step-date">26 Feb</div>
                            </div>

                            <div class="tracker-step">
                                <div class="step-dot">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                </div>
                                <div class="step-label">Enviado</div>
                                <div class="step-date">—</div>
                            </div>

                            <div class="tracker-step">
                                <div class="step-dot">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                                <div class="step-label">Entregado</div>
                                <div class="step-date">—</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Products --}}
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Artículos del pedido</span>
                        <span style="font-size:0.8125rem;color:var(--text-secondary);">{{ count($products) }} productos</span>
                    </div>
                    <div style="overflow-x:auto;">
                        <table class="products-table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th style="text-align:center;">Precio</th>
                                    <th style="text-align:center;">Cant.</th>
                                    <th style="text-align:right;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $p)
                                @php $productImg = strtolower(explode(' ', trim($p['name']))[0]) . '.png'; @endphp
                                <tr>
                                    <td>
                                        <div class="prod-info">
                                            <div class="prod-thumb">
                                                <img src="{{ asset('images/' . $productImg) }}" alt="{{ $p['name'] }}" onerror="this.style.display='none'">
                                            </div>
                                            <div>
                                                <div class="prod-name">{{ $p['name'] }}</div>
                                                <div class="prod-sku">{{ $p['sku'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="prod-price" style="text-align:center;">${{ number_format($p['price'], 2) }}</td>
                                    <td class="prod-qty">{{ $p['qty'] }}</td>
                                    <td class="prod-subtotal">${{ number_format($p['price'] * $p['qty'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Totals --}}
                    <div class="totals-section">
                        <div class="totals-row">
                            <span>Subtotal</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="totals-row">
                            <span>Envío</span>
                            <span>${{ number_format($envio, 2) }}</span>
                        </div>
                        <div class="totals-row">
                            <span>Descuento</span>
                            <span style="color:var(--success);">— $0.00</span>
                        </div>
                        <div class="totals-row grand">
                            <span>Total</span>
                            <span class="amount">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN --}}
            <div>
                {{-- Order info --}}
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Información del pedido</span>
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <span class="info-label">Número</span>
                            <span class="info-value" style="font-weight:700;color:var(--primary);">{{ $orderRef }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Fecha</span>
                            <span class="info-value">25 Feb 2025</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Estatus</span>
                            <span class="info-value"><span class="badge badge-warning">En proceso</span></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Método de pago</span>
                            <span class="info-value">Transferencia</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Entrega est.</span>
                            <span class="info-value">01 Mar 2025</span>
                        </div>
                    </div>
                </div>

                {{-- Shipping address --}}
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Dirección de envío</span>
                    </div>
                    <div class="card-body">
                        <div style="font-weight:600;margin-bottom:0.35rem;">{{ optional(auth()->user())->name ?? 'Carlos Méndez' }}</div>
                        <div style="font-size:0.9375rem;color:var(--text-secondary);line-height:1.6;">
                            Av. Constituyentes 456, Col. Centro<br>
                            Querétaro, Qro. 76000<br>
                            México<br>
                            <span style="display:inline-flex;align-items:center;gap:0.25rem;margin-top:0.35rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                +52 442 123 4567
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Activity timeline --}}
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Actividad</span>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="tl-item">
                                <div class="tl-dot">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <div class="tl-content">
                                    <div class="tl-event">Pedido en preparación</div>
                                    <div class="tl-time">26 Feb 2025 · 09:42</div>
                                </div>
                            </div>
                            <div class="tl-item">
                                <div class="tl-dot success">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="tl-content">
                                    <div class="tl-event">Pago confirmado</div>
                                    <div class="tl-time">25 Feb 2025 · 15:30</div>
                                </div>
                            </div>
                            <div class="tl-item">
                                <div class="tl-dot success">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <div class="tl-content">
                                    <div class="tl-event">Pedido creado</div>
                                    <div class="tl-time">25 Feb 2025 · 14:17</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        (function () {
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
        })();
    </script>
</body>
</html>