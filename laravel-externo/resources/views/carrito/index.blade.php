<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mi Carrito - MACUIN</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1e3a8a;
            --secondary: #2563eb;
            --bg: #f8fafc;
            --card: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --success: #10b981;
            --error: #ef4444;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text-primary);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .page-bg { position: fixed; top: 0; left: 0; width: 100%; height: 100vh; background: linear-gradient(135deg, #f0f4f8 0%, #e2e8f0 100%); z-index: -1; }
        
        /* Header estandarizado */
        .header {
            background: var(--card);
            height: 72px;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .header-left { display: flex; align-items: center; gap: 2rem; }
        .header-logo { height: 32px; object-fit: contain; }
        .header-nav { display: flex; gap: 1.5rem; }
        .header-nav a {
            text-decoration: none;
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 0.9375rem;
            transition: color 0.2s;
        }
        .header-nav a:hover, .header-nav a.active { color: var(--primary); }
        .header-user { position: relative; }
        .header-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white; font-weight: 600;
            display: flex; align-items: center; justify-content: center;
            border: none; cursor: pointer;
        }
        .header-dropdown {
            position: absolute; top: 110%; right: 0;
            background: var(--card); border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 200px; padding: 0.5rem;
            display: none; border: 1px solid rgba(0,0,0,0.05);
        }
        .header-dropdown.show { display: block; }
        .header-dropdown a {
            display: block; padding: 0.5rem 1rem;
            color: var(--text-primary); text-decoration: none;
            font-size: 0.875rem; border-radius: 8px;
            transition: background 0.2s;
        }
        .header-dropdown a:hover { background: rgba(37,99,235,0.08); color: var(--secondary); }

        .main { width: 100%; max-width: 1200px; margin: 2rem auto; padding: 0 1.5rem; flex: 1; }
        .page-header { margin-bottom: 2rem; }
        .page-header h1 { font-size: 1.875rem; font-weight: 700; color: var(--primary); }
        .page-header p { color: var(--text-secondary); margin-top: 0.25rem; }

        .cart-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        .card {
            background: var(--card);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }

        .cart-list { display: flex; flex-direction: column; gap: 1rem; }
        .cart-item {
            display: flex; align-items: center; gap: 1rem;
            padding-bottom: 1rem; border-bottom: 1px solid #e5e7eb;
        }
        .cart-item:last-child { border-bottom: none; padding-bottom: 0; }
        .item-img {
            width: 80px; height: 80px; border-radius: 8px;
            background: rgba(0,0,0,0.03); display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }
        .item-img img { width: 100%; height: 100%; object-fit: contain; }
        .item-info { flex: 1; }
        .item-name { font-weight: 600; font-size: 1rem; color: var(--text-primary); margin-bottom: 0.25rem; }
        .item-sku { font-size: 0.8125rem; color: var(--text-secondary); }
        
        .item-price { font-weight: 700; color: var(--primary); font-size: 1.125rem; }
        .item-actions { display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem; }
        
        .btn-remove {
            background: none; border: none; color: var(--error);
            font-size: 0.875rem; cursor: pointer; text-decoration: underline; opacity: 0.8;
        }
        .btn-remove:hover { opacity: 1; }

        /* Summary */
        .summary-card { position: sticky; top: 100px; }
        .summary-row { display: flex; justify-content: space-between; font-size: 0.9375rem; margin-bottom: 0.75rem; color: var(--text-secondary); }
        .summary-total { display: flex; justify-content: space-between; font-size: 1.125rem; font-weight: 700; color: var(--text-primary); padding-top: 1rem; border-top: 1px solid #e5e7eb; margin-top: 1rem; margin-bottom: 1.5rem; }
        
        .btn-checkout {
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            width: 100%; padding: 0.875rem; border-radius: 10px;
            background: var(--secondary); color: white;
            font-weight: 600; border: none; cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            text-decoration: none; font-size: 1rem;
        }
        .btn-checkout:hover { background: var(--primary); transform: translateY(-1px); }
        .btn-empty { margin-top: 1rem; display: block; text-align: center; color: var(--text-secondary); text-decoration: underline; font-size: 0.875rem; }

        .empty-cart { text-align: center; padding: 4rem 2rem; color: var(--text-secondary); }
        .empty-cart i { font-size: 4rem; opacity: 0.3; margin-bottom: 1rem; color: var(--primary); }
        .btn-continue { display: inline-block; margin-top: 1.5rem; padding: 0.75rem 1.5rem; background: var(--card); color: var(--primary); border: 1px solid var(--primary); border-radius: 8px; font-weight: 600; text-decoration: none; }
        .btn-continue:hover { background: var(--primary); color: white; }

        @media (max-width: 768px) {
            .cart-grid { grid-template-columns: 1fr; }
            .header-nav { display: none; }
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
        <div class="page-header">
            <h1>Tu Carrito</h1>
            <p>Revisa tus autopartes antes de proceder al pago seguro.</p>
        </div>

        @if($cartItems->isEmpty())
        <div class="card empty-cart">
            <i class="fas fa-shopping-basket"></i>
            <h2>Tu carrito está vacío</h2>
            <p>¡Explora nuestro catálogo y descubre miles de refacciones listas para enviarse!</p>
            <a href="{{ url('/catalogo') }}" class="btn-continue">Explorar catálogo</a>
        </div>
        @else
        <div class="cart-grid">
            <div class="card">
                <div class="cart-list">
                    @foreach($cartItems as $item)
                    <div class="cart-item">
                        <div class="item-img">
                            @if($item->autopart->image_url)
                                <img src="{{ $item->autopart->image_url }}" alt="{{ $item->autopart->name }}">
                            @else
                                <i class="fas fa-box" style="font-size: 2rem; color: #ccc;"></i>
                            @endif
                        </div>
                        <div class="item-info">
                            <h3 class="item-name">{{ $item->autopart->name }}</h3>
                            <p class="item-sku">ID: {{ $item->autopart->id }} | Categoría: {{ $item->autopart->category ?: 'Autoparte' }}</p>
                            <p style="margin-top:0.5rem; font-size:0.875rem;">Cantidad: <strong>{{ $item->quantity }}</strong></p>
                        </div>
                        <div class="item-actions">
                            <div class="item-price">${{ number_format($item->autopart->price * $item->quantity, 2) }}</div>
                            <button onclick="removeItem({{ $item->id }})" class="btn-remove">Remover</button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div>
                <div class="card summary-card">
                    <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Resumen de Orden</h3>
                    
                    <div class="summary-row">
                        <span>Subtotal de productos</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>IVA Temporal (16%)</span>
                        <span>Estimado</span>
                    </div>
                    
                    <div class="summary-total">
                        <span>Total Parcial</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    
                    <a href="{{ url('/pedidos/crear') }}" class="btn-checkout">
                        Proceder al Pago <i class="fas fa-arrow-right"></i>
                    </a>
                    
                    <a href="{{ url('/catalogo') }}" class="btn-empty">Continuar comprando</a>
                </div>
            </div>
        </div>
        @endif
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

        function removeItem(cartId) {
            if(!confirm('¿Estás seguro de eliminar esta pieza de tu carrito?')) return;
            fetch('{{ url("/carrito/eliminar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ id: cartId })
            })
            .then(res => res.json())
            .then(data => {
                window.location.reload();
            }).catch(e => console.error(e));
        }
    </script>
</body>
</html>
