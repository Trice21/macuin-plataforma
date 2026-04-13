<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear Pedido — MACUIN</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1F3A5F;
            --secondary: #4A6FA5;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --bg: #F8FAFC;
            --card: #FFFFFF;
            --text-primary: #0F172A;
            --text-secondary: #64748B;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
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
        
        .main { position: relative; z-index: 1; max-width: 1000px; margin: 3rem auto; padding: 0 1.5rem; }
        .grid-checkout { display: grid; grid-template-columns: 1fr 380px; gap: 2rem; }
        
        .card { background: var(--card); border-radius: 16px; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .section-title { font-size: 1.25rem; font-weight: 700; color: var(--primary); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; }
        
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem; }
        .form-input { width: 100%; padding: 0.75rem 1rem; border: 1.5px solid var(--bg); border-radius: 10px; font-family: inherit; font-size: 0.9375rem; outline: none; transition: border-color 0.2s; background: var(--bg); }
        .form-input:focus { border-color: var(--secondary); background: white; }
        
        .order-summary { background: var(--bg); border-radius: 12px; padding: 1.5rem; margin-top: 1.5rem; }
        .summary-item { display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.9375rem; }
        .summary-total { display: flex; justify-content: space-between; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(0,0,0,0.05); font-weight: 700; font-size: 1.25rem; color: var(--primary); }
        
        .btn-confirm { width: 100%; padding: 1.25rem; background: var(--primary); color: white; border: none; border-radius: 12px; font-weight: 700; font-size: 1.125rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.75rem; margin-top: 2rem; }
        .btn-confirm:hover:not(:disabled) { background: var(--secondary); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(31, 58, 95, 0.2); }
        .btn-confirm:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
        .checkout-alert { padding: 0.875rem 1rem; border-radius: 10px; margin-bottom: 1.25rem; font-size: 0.9375rem; }
        .checkout-alert.error { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        
        .product-preview { display: flex; align-items: center; gap: 1rem; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05); margin-bottom: 1.5rem; }
        .preview-img { width: 80px; height: 80px; background: white; border-radius: 12px; object-fit: contain; padding: 0.5rem; border: 1px solid rgba(0,0,0,0.05); }
        .preview-info { flex: 1; }
        .preview-name { font-weight: 600; font-size: 1rem; color: var(--primary); margin-bottom: 0.25rem; }
        .preview-sku { font-size: 0.75rem; color: var(--text-secondary); }
        @media (max-width: 900px) {
            .grid-checkout { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .header-nav { display: none; }
        }
        @media (max-width: 480px) {
            .main { margin: 1.5rem auto; padding: 0 1rem; }
            .card { padding: 1.25rem; }
        }
    </style>
</head>
<body>
    <div class="page-bg"></div>
    @php
        // Carrito ya nos es proveído como $cartItems
    @endphp
    <header class="header">
        <div class="header-left">
            <a href="{{ url('/dashboard') }}">
                <img src="{{ asset('images/logo_macuin.png') }}" alt="MACUIN" class="header-logo" onerror="this.parentElement.innerHTML='<span style=\'font-weight:700;color:var(--primary);font-size:1.25rem;\'>MACUIN</span>'">
            </a>
            <nav class="header-nav">
                <a href="{{ url('/dashboard') }}">Dashboard</a>
                <a href="{{ url('/catalogo') }}">Catálogo</a>
                <a href="{{ url('/pedidos') }}" class="active">Mis pedidos</a>
                <a href="{{ url('/perfil') }}">Perfil</a>
            </nav>
        </div>
        <div class="header-user">
            <button type="button" class="header-avatar" id="user-menu-btn" aria-expanded="false" aria-haspopup="true">
                {{ strtoupper(substr(optional(auth()->user())->name ?? 'Usuario', 0, 1)) }}
            </button>
            <div class="header-dropdown" id="user-dropdown" role="menu">
                <a href="{{ url('/perfil') }}">Mi perfil</a>
                <a href="{{ url('/perfil/configuracion') }}">Configuración</a>
                <a href="{{ url('/logout') }}">Cerrar sesión</a>
            </div>
        </div>
    </header>

    <main class="main">
        <form method="POST" action="{{ url('/pedidos/finalizar') }}" id="checkout-form">
            @csrf
            @php
                $nameParts = preg_split('/\s+/', trim(auth()->user()->name ?? ''), 2);
                $defaultFirst = $nameParts[0] ?? '';
                $defaultLast = $nameParts[1] ?? '';
            @endphp
            <div class="grid-checkout">
            <!-- Datos de envío (solo UI; el pedido se crea con el carrito vía API) -->
            <div class="card">
                @if($errors->has('order'))
                    <div class="checkout-alert error" role="alert">{{ $errors->first('order') }}</div>
                @endif
                <h3 class="section-title"><i class="fas fa-shipping-fast"></i> Información de Envío</h3>
                <div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Nombre(s)</label>
                            <input type="text" name="ship_first" class="form-input" placeholder="Ej. Juan" autocomplete="given-name" value="{{ old('ship_first', $defaultFirst) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Apellidos</label>
                            <input type="text" name="ship_last" class="form-input" placeholder="Ej. Pérez" autocomplete="family-name" value="{{ old('ship_last', $defaultLast) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Dirección Completa</label>
                        <input type="text" class="form-input" placeholder="Calle, Número, Colonia, CP" autocomplete="street-address">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Ciudad</label>
                            <input type="text" class="form-input" placeholder="Ej. Ciudad de México" autocomplete="address-level2">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-input" placeholder="+52 ..." autocomplete="tel">
                        </div>
                    </div>
                    
                    <h3 class="section-title" style="margin-top: 2rem;"><i class="fas fa-credit-card"></i> Método de Pago</h3>
                    <div style="padding: 1rem; border: 2px solid var(--secondary); border-radius: 12px; background: rgba(74, 111, 165, 0.05); display: flex; align-items: center; gap: 1rem;">
                        <i class="fas fa-university" style="font-size: 1.5rem; color: var(--secondary);"></i>
                        <div>
                            <p style="font-weight: 600; font-size: 0.9375rem; color: var(--primary);">Transferencia Bancaria</p>
                            <p style="font-size: 0.8125rem; color: var(--text-secondary);">Recibirás los datos al finalizar.</p>
                        </div>
                        <i class="fas fa-check-circle" style="margin-left: auto; color: var(--secondary);"></i>
                    </div>
                </div>
            </div>

            <!-- Resumen lateral -->
            <div>
                <div class="card" style="padding: 1.5rem; position: sticky; top: 2rem;">
                    <h4 style="font-weight: 700; margin-bottom: 1.5rem; color: var(--primary);">Resumen de Pedido</h4>
                    
                    @foreach($cartItems as $item)
                    <div class="product-preview" style="margin-bottom: 0.75rem;">
                        <img src="{{ App\MacuinApi::imageUrl($item->autopart->image_url) }}" class="preview-img" alt="{{ $item->autopart->name }}">
                        <div class="preview-info" style="flex:1;">
                            <p class="preview-name">{{ $item->autopart->name }} (x{{ $item->quantity }})</p>
                            <p class="preview-sku">ID: {{ $item->autopart->id }}</p>
                        </div>
                        <div style="font-weight: 700; color: var(--primary);">
                            ${{ number_format($item->autopart->price * $item->quantity, 2) }}
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="order-summary" style="margin-top: 1.5rem; border-top: 1px solid #e5e7eb; padding-top: 1rem;">
                        <div class="summary-item">
                            <span>Subtotal</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="summary-item">
                            <span>IVA (16%)</span>
                            <span>${{ number_format($taxes, 2) }}</span>
                        </div>
                        <div class="summary-item">
                            <span>Envío</span>
                            <span style="color: var(--success); font-weight: 600;">GRATIS</span>
                        </div>
                        <div class="summary-total" style="margin-top: 1rem; border-top: 1px solid #e5e7eb; padding-top: 1rem;">
                            <span>Total Estimado</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-confirm" id="btn-finalizar">
                        <i class="fas fa-lock"></i> Finalizar Pedido
                    </button>
                    
                    <p style="text-align: center; font-size: 0.75rem; color: var(--text-secondary); margin-top: 1rem;">
                        <i class="fas fa-shield-alt"></i> Compra segura y protegida
                    </p>
                </div>
            </div>
            </div>
        </form>
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
        var checkoutForm = document.getElementById('checkout-form');
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function() {
                var btn = document.getElementById('btn-finalizar');
                if (btn) { btn.disabled = true; }
            });
        }
    </script>
</body>
</html>
