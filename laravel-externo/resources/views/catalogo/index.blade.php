<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catálogo — MACUIN</title>
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
            padding-bottom: 80px;
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

        /* Header (consistent with dashboard) */
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
        .header-nav a:hover { color: var(--primary); background: rgba(31, 58, 95, 0.06); }
        .header-nav a.active { color: var(--primary); background: rgba(31, 58, 95, 0.08); }
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

        .main { position: relative; z-index: 1; max-width: 1200px; margin: 0 auto; padding: 1.5rem; }

        /* Page header */
        .page-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .page-header h1 { font-size: 1.75rem; font-weight: 700; color: var(--primary); margin-bottom: 0.25rem; }
        .page-header p { font-size: 0.9375rem; color: var(--text-secondary); }
        .btn-cart {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            font-family: inherit;
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--primary);
            background: var(--card);
            border: 1px solid rgba(31, 58, 95, 0.2);
            border-radius: 10px;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(31, 58, 95, 0.06);
            transition: box-shadow 0.2s, border-color 0.2s;
        }
        .btn-cart:hover { border-color: var(--primary); box-shadow: 0 4px 12px rgba(31, 58, 95, 0.12); }
        .btn-cart svg { width: 1.25rem; height: 1.25rem; }
        .btn-cart .count { background: var(--primary); color: #fff; font-size: 0.75rem; padding: 0.15rem 0.5rem; border-radius: 999px; }

        /* Search */
        .search-wrap { margin-bottom: 1.25rem; }
        .search-bar {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            max-width: 560px;
            margin: 0 auto 1rem;
            padding: 0.75rem 1rem;
            background: var(--card);
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .search-bar:focus-within { border-color: var(--secondary); box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.12); }
        .search-bar svg { width: 1.25rem; height: 1.25rem; color: var(--text-secondary); flex-shrink: 0; }
        .search-bar input {
            flex: 1;
            border: none;
            outline: none;
            font-family: inherit;
            font-size: 0.9375rem;
            color: var(--text-primary);
        }
        .search-bar input::placeholder { color: var(--text-secondary); opacity: 0.8; }

        /* Filters */
        .filters-wrap { margin-bottom: 1.5rem; }
        .filters-toggle {
            display: none;
            width: 100%;
            padding: 0.75rem 1rem;
            font-family: inherit;
            font-size: 0.9375rem;
            font-weight: 500;
            color: var(--primary);
            background: var(--card);
            border: 1px solid rgba(31, 58, 95, 0.15);
            border-radius: 10px;
            margin-bottom: 0.75rem;
            cursor: pointer;
        }
        .filters-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
        }
        .filter-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.4rem 0.75rem;
            font-family: inherit;
            font-size: 0.875rem;
            color: var(--text-secondary);
            background: var(--card);
            border: 1px solid #e5e7eb;
            border-radius: 999px;
            cursor: pointer;
            transition: border-color 0.2s, color 0.2s;
        }
        .filter-pill:hover { border-color: var(--secondary); color: var(--primary); }
        .filter-pill.active { border-color: var(--secondary); color: var(--secondary); background: rgba(74, 111, 165, 0.06); }
        .filter-pill select { border: none; background: none; font: inherit; color: inherit; cursor: pointer; outline: none; }
        .active-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.75rem; }
        .active-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.6rem;
            font-size: 0.8125rem;
            color: var(--secondary);
            border: 1px solid var(--secondary);
            border-radius: 999px;
            background: rgba(74, 111, 165, 0.06);
        }
        .active-tag button { background: none; border: none; color: inherit; cursor: pointer; padding: 0; display: flex; }

        /* Recommended strip */
        .recommended { margin-bottom: 1.5rem; }
        .recommended h3 { font-size: 0.9375rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.75rem; }
        .recommended-scroll {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
        }
        .recommended-scroll::-webkit-scrollbar { height: 6px; }
        .recommended-scroll::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }
        .rec-card {
            flex: 0 0 200px;
            scroll-snap-align: start;
            background: var(--card);
            border-radius: 12px;
            padding: 1rem;
            border: 1px solid rgba(31, 58, 95, 0.06);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .rec-card .img { height: 120px; background: #fff; border-radius: 8px; margin-bottom: 0.5rem; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid #f1f5f9; }
        .rec-card .img img { width: 100%; height: 100%; object-fit: contain; mix-blend-mode: multiply; }
        .rec-card .name { font-size: 0.875rem; font-weight: 600; color: var(--text-primary); }
        .rec-card .price { font-size: 0.875rem; font-weight: 600; color: var(--primary); }

        /* Product grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
        }
        .product-card {
            background: var(--card);
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid rgba(31, 58, 95, 0.06);
            box-shadow: 0 2px 12px rgba(31, 58, 95, 0.06);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
        }
        .product-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(31, 58, 95, 0.1); }
        .product-card .img-wrap {
            aspect-ratio: 1;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            font-size: 0.75rem;
            overflow: hidden;
            border-bottom: 1px solid #f1f5f9;
        }
        .product-card .img-wrap img { width: 100%; height: 100%; object-fit: contain; mix-blend-mode: multiply; }
        .product-card .body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
        .product-card .name { font-size: 1rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.25rem; line-height: 1.3; }
        .product-card .sku { font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.5rem; }
        .product-card .price { font-size: 1.125rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem; }
        .product-card .stock-badge {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }
        .stock-available { background: rgba(76, 175, 80, 0.15); color: var(--success); }
        .stock-low { background: rgba(255, 193, 7, 0.2); color: #b38600; }
        .stock-none { background: rgba(229, 57, 53, 0.15); color: var(--error); }
        .qty-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .qty-btn {
            width: 36px;
            height: 36px;
            border: 1px solid #e5e7eb;
            background: var(--card);
            border-radius: 8px;
            font-size: 1.125rem;
            color: var(--text-primary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: border-color 0.2s, background 0.2s;
        }
        .qty-btn:hover:not(:disabled) { border-color: var(--secondary); background: rgba(74, 111, 165, 0.06); }
        .qty-btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .qty-value { font-size: 0.9375rem; font-weight: 500; min-width: 1.5rem; text-align: center; }
        .btn-add {
            width: 100%;
            padding: 0.625rem 1rem;
            font-family: inherit;
            font-size: 0.9375rem;
            font-weight: 600;
            color: #fff;
            background: var(--primary);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
            margin-top: auto;
        }
        .btn-add:hover:not(:disabled) { background: #2a4d7a; box-shadow: 0 4px 12px rgba(31, 58, 95, 0.3); }
        .btn-add:disabled { background: #b0bec5; cursor: not-allowed; box-shadow: none; }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 1.5rem;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            padding: 0.75rem 1.25rem;
            background: var(--success);
            color: #fff;
            font-size: 0.9375rem;
            font-weight: 500;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(76, 175, 80, 0.4);
            z-index: 100;
            opacity: 0;
            transition: transform 0.3s, opacity 0.3s;
        }
        .toast.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }

        /* Mobile cart bar */
        .mobile-cart-bar {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 0.75rem 1rem;
            background: var(--card);
            box-shadow: 0 -2px 12px rgba(0,0,0,0.08);
            z-index: 5;
        }
        .mobile-cart-bar .btn-cart { width: 100%; justify-content: center; }

        @media (max-width: 1024px) {
            .products-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 768px) {
            .header-nav { display: none; }
            .products-grid { grid-template-columns: repeat(2, 1fr); gap: 1rem; }
            .page-header { flex-direction: column; align-items: stretch; }
            .page-header .btn-cart { align-self: flex-end; }
            .filters-toggle { display: block; }
            .filters-row { display: none; }
            .filters-row.open { display: flex; }
            .mobile-cart-bar { display: block; }
            body { padding-bottom: 72px; }
        }
        @media (max-width: 480px) {
            .main { padding: 1rem; }
            .products-grid { grid-template-columns: 1fr; }
            .page-header h1 { font-size: 1.5rem; }
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
                <a href="{{ url('/catalogo') }}" class="active">Catálogo</a>
                <a href="{{ url('/pedidos') }}">Mis pedidos</a>
                <a href="{{ url('/perfil') }}">Perfil</a>
            </nav>
        </div>
        <div class="header-user">
            <button type="button" class="header-avatar" id="user-menu-btn" aria-expanded="false">
                {{ strtoupper(substr(optional(auth()->user())->name ?? 'U', 0, 1)) }}
            </button>
            <div class="header-dropdown" id="user-dropdown">
                <a href="{{ url('/perfil') }}">Mi perfil</a>
                <a href="{{ url('/perfil/configuracion') }}">Configuración</a>
                <a href="{{ url('/logout') }}">Cerrar sesión</a>
            </div>
        </div>
    </header>

    <main class="main">
        @if($errors->any())
            <div style="background: rgba(239, 68, 68, 0.1); color: var(--error); padding: 1rem; border-radius: 12px; margin-bottom: 2rem; border: 1px solid rgba(239, 68, 68, 0.2); font-weight: 500;">
                <i class="fas fa-exclamation-circle" style="margin-right:0.5rem;"></i> {{ $errors->first('msg') ?? 'Ocurrió un problema con el carrito.' }}
            </div>
        @endif

        <div class="page-header">
            <div>
                <h1>Catálogo de autopartes</h1>
                <p>Encuentra y agrega productos a tu pedido</p>
            </div>
            <a href="{{ url('/carrito') }}" class="btn-cart" id="cart-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Ver Carrito (<span id="cart-count">{{ $cartCount ?? 0 }}</span>)
            </a>
        </div>

        <form class="search-wrap" method="GET" action="{{ url('/catalogo') }}">
            <div class="search-bar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="search" name="q" value="{{ $activeSearch ?? '' }}" placeholder="Buscar por nombre o descripción" aria-label="Buscar productos">
                @if($activeCategory)
                    <input type="hidden" name="category" value="{{ $activeCategory }}">
                @endif
            </div>
            <div class="filters-wrap">
                <button type="button" class="filters-toggle" id="filters-toggle" aria-expanded="false">Filtros</button>
                <div class="filters-row" id="filters-row" style="display:flex; justify-content:center;">
                    <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="filter-pill {{ empty($activeCategory) ? 'active' : '' }}" style="text-decoration:none;">Todos</a>
                    <a href="{{ request()->fullUrlWithQuery(['category' => 'Motor']) }}" class="filter-pill {{ ($activeCategory ?? '') == 'Motor' ? 'active' : '' }}" style="text-decoration:none;">Motor</a>
                    <a href="{{ request()->fullUrlWithQuery(['category' => 'Suspensión']) }}" class="filter-pill {{ ($activeCategory ?? '') == 'Suspensión' ? 'active' : '' }}" style="text-decoration:none;">Suspensión</a>
                    <a href="{{ request()->fullUrlWithQuery(['category' => 'Encendido']) }}" class="filter-pill {{ ($activeCategory ?? '') == 'Encendido' ? 'active' : '' }}" style="text-decoration:none;">Encendido</a>
                </div>
            </div>
        </form>

        <section class="recommended">
            <h3>Más solicitados</h3>
            <div class="recommended-scroll">
                @foreach($autoparts->take(4) as $p)
                <div class="rec-card">
                    <div class="img">
                        <img src="{{ App\MacuinApi::imageUrl($p->image_url) }}" alt="{{ $p->name }}">
                    </div>
                    <div class="name">{{ $p->name }}</div>
                    <div class="price">${{ number_format($p->price, 2) }}</div>
                </div>
                @endforeach
            </div>
        </section>

        <div class="products-grid" id="products-grid">
            @foreach($autoparts as $p)
            @php
                $stockStatus = 'available';
                $stockLabel = 'Disponible';
                if ($p->stock <= 0) {
                    $stockStatus = 'none';
                    $stockLabel = 'Sin stock';
                } elseif ($p->stock < 10) {
                    $stockStatus = 'low';
                    $stockLabel = 'Bajo stock';
                }
            @endphp
            <article class="product-card" data-product-id="{{ $p->id }}">
                <a href="{{ url('/catalogo/' . $p->id) }}" style="text-decoration: none; color: inherit;">
                    <div class="img-wrap">
                        <img src="{{ App\MacuinApi::imageUrl($p->image_url) }}" alt="{{ $p->name }}">
                    </div>
                </a>
                <div class="body">
                    <a href="{{ url('/catalogo/' . $p->id) }}" style="text-decoration: none; color: inherit;">
                        <h3 class="name">{{ $p->name }}</h3>
                    </a>
                    <div class="sku">ID: {{ $p->id }} | {{ $p->category }}</div>
                    <div class="price">${{ number_format($p->price, 2) }}</div>
                    <span class="stock-badge stock-{{ $stockStatus }}">{{ $stockLabel }} ({{ $p->stock }})</span>
                    <div class="qty-row">
                        <button type="button" class="qty-btn qty-minus" aria-label="Menos">−</button>
                        <span class="qty-value" data-qty="1">1</span>
                        <button type="button" class="qty-btn qty-plus" aria-label="Más">+</button>
                    </div>
                    <button type="button" class="btn-add" {{ $p->stock <= 0 ? 'disabled' : '' }}>Agregar</button>
                </div>
            </article>
            @endforeach
            @if($autoparts->count() == 0)
                <div style="grid-column: 1 / -1; text-align: center; padding: 4rem;">
                    <h3>No hay productos disponibles en este momento.</h3>
                </div>
            @endif
        </div>
    </main>

    <div class="toast" id="toast" role="status" aria-live="polite">Producto agregado</div>

    <div class="mobile-cart-bar">
        <a href="{{ url('/carrito') }}" class="btn-cart" id="cart-btn-mobile">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:1.25rem;height:1.25rem"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            Ver carrito (<span id="cart-count-mobile">{{ $cartCount ?? 0 }}</span>)
        </a>
    </div>

    <script>
        (function() {
            // Script de respaldo para corregir URLs de imágenes si fallan al cargar
            document.querySelectorAll('img').forEach(function(img) {
                img.onerror = function() {
                    if (this.dataset.retried) return;
                    this.dataset.retried = 'true';

                    let src = this.getAttribute('src');
                    if (!src) return;

                    // Si falla, intentamos con el mismo host actual pero puerto 5000 (Flask)
                    try {
                        let url = new URL(src, window.location.origin);
                        url.port = '5000';
                        this.src = url.toString();
                    } catch(e) {
                        // Si falla el objeto URL, probamos un reemplazo simple
                        this.src = src.replace(/:\d+/, ':5000');
                    }
                };
            });

            var cartCount = {{ (int) ($cartCount ?? 0) }};
            var toast = document.getElementById('toast');
            var cartEls = document.querySelectorAll('#cart-count, #cart-count-mobile');

            function updateCartCount(n) {
                cartCount = n;
                cartEls.forEach(function(el) { if (el) el.textContent = cartCount; });
            }

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

            document.getElementById('filters-toggle').addEventListener('click', function() {
                var row = document.getElementById('filters-row');
                row.classList.toggle('open');
                this.setAttribute('aria-expanded', row.classList.contains('open'));
            });

            document.querySelectorAll('.filter-pill').forEach(function(pill) {
                // If the filter pill is an anchor tag, let it naturally navigate.
            });

            document.querySelectorAll('.product-card').forEach(function(card) {
                var qtyVal = card.querySelector('.qty-value');
                var minus = card.querySelector('.qty-minus');
                var plus = card.querySelector('.qty-plus');
                var btnAdd = card.querySelector('.btn-add');
                if (!qtyVal || !btnAdd) return;

                function getQty() { return parseInt(qtyVal.getAttribute('data-qty') || '1', 10); }
                function setQty(n) {
                    n = Math.max(0, Math.min(99, n));
                    qtyVal.setAttribute('data-qty', n);
                    qtyVal.textContent = n;
                }

                if (minus) minus.addEventListener('click', function() { setQty(getQty() - 1); });
                if (plus) plus.addEventListener('click', function() { setQty(getQty() + 1); });

                btnAdd.addEventListener('click', function() {
                    if (this.disabled) return;

                    var q = getQty();
                    var id = card.getAttribute('data-product-id');
                    var oldText = this.textContent;
                    this.textContent = '...';
                    this.disabled = true;

                    fetch('{{ url("/carrito/agregar") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ autopart_id: id, quantity: q })
                    })
                    .then(r => r.json())
                    .then(data => {
                        this.textContent = oldText;
                        this.disabled = false;
                        updateCartCount(data.cartCount);
                        toast.classList.add('show');
                        setTimeout(function() { toast.classList.remove('show'); }, 2500);
                    }).catch(e => {
                        this.textContent = oldText;
                        this.disabled = false;
                    });
                });
            });
        })();
    </script>
</body>
</html>
