<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detalle de Autoparte — MACUIN</title>
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
        }
        .header {
            background: var(--card);
            padding: 1rem 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo { font-size: 1.5rem; font-weight: 700; color: var(--primary); text-decoration: none; }
        .nav a { text-decoration: none; color: var(--text-secondary); margin-left: 1.5rem; font-weight: 500; }
        .nav a:hover { color: var(--primary); }

        .main { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .back-link { display: inline-flex; align-items: center; gap: 0.5rem; color: var(--text-secondary); text-decoration: none; margin-bottom: 2rem; font-weight: 500; }
        .back-link:hover { color: var(--primary); }

        .product-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; background: var(--card); border-radius: 20px; padding: 3rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .product-image-container { background: #fff; border-radius: 16px; padding: 2rem; display: flex; align-items: center; justify-content: center; border: 1px solid #f1f5f9; }
        .product-image { max-height: 400px; width: auto; mix-blend-mode: multiply; }

        .badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; margin-bottom: 1rem; }
        .badge-success { background: #D1FAE5; color: #065F46; }

        .product-title { font-size: 2.5rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem; }
        .product-sku { color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 2rem; }
        .product-price { font-size: 2.5rem; font-weight: 700; color: var(--secondary); margin-bottom: 2rem; }

        .section-title { font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: var(--primary); border-bottom: 2px solid var(--bg); padding-bottom: 0.5rem; }
        .product-desc { font-size: 1rem; line-height: 1.6; color: var(--text-secondary); margin-bottom: 2rem; }

        .product-actions { display: flex; flex-direction: column; gap: 0.75rem; margin-top: 1.5rem; }
        .qty-row { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.25rem; }
        .qty-row label { font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); }
        .qty-controls { display: inline-flex; align-items: center; border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; }
        .qty-controls button { width: 40px; height: 40px; border: none; background: var(--bg); color: var(--primary); font-size: 1.25rem; cursor: pointer; }
        .qty-controls button:hover { background: #e2e8f0; }
        .qty-controls span { min-width: 2.5rem; text-align: center; font-weight: 600; }
        .btn-add-cart { display: flex; align-items: center; justify-content: center; gap: 0.75rem; width: 100%; padding: 1.1rem; background: #fff; color: var(--primary); border: 2px solid var(--primary); border-radius: 12px; font-weight: 700; font-size: 1.05rem; cursor: pointer; transition: all 0.2s; font-family: inherit; }
        .btn-add-cart:hover:not(:disabled) { background: rgba(31, 58, 95, 0.06); }
        .btn-add-cart:disabled { opacity: 0.55; cursor: not-allowed; }
        .btn-order { display: flex; align-items: center; justify-content: center; gap: 0.75rem; width: 100%; padding: 1.25rem; background: var(--primary); color: white; text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 1.125rem; transition: all 0.2s; border: none; cursor: pointer; font-family: inherit; }
        .btn-order:hover { background: var(--secondary); transform: translateY(-2px); }
        .toast { position: fixed; bottom: 2rem; left: 50%; transform: translateX(-50%) translateY(120%); background: var(--text-primary); color: #fff; padding: 0.875rem 1.5rem; border-radius: 12px; font-weight: 500; font-size: 0.9375rem; box-shadow: 0 8px 24px rgba(0,0,0,0.15); z-index: 100; opacity: 0; transition: transform 0.3s, opacity 0.3s; }
        .toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }
    </style>
</head>
<body>
    @php
        $productImg = strtolower(explode(' ', trim($autopart->name))[0]) . '.png';
    @endphp
    <header class="header">
        <a href="/" class="logo">MACUIN</a>
        <nav class="nav">
            <a href="/catalogo">Catálogo</a>
            <a href="/pedidos">Mis Pedidos</a>
            <a href="/login">Cerrar Sesión</a>
        </nav>
    </header>

    <main class="main">
        <a href="/catalogo" class="back-link"><i class="fas fa-arrow-left"></i> Volver al Catálogo</a>

        <div class="product-grid">
            <div class="product-image-container">
                <img src="{{ App\MacuinApi::imageUrl($autopart->image_url) }}" alt="{{ $autopart->name }}" class="product-image">
            </div>

            <div>
                @if($autopart->stock > 0)
                    <span class="badge badge-success">Disponible ({{ $autopart->stock }} en stock)</span>
                @else
                    <span class="badge" style="background: #FEE2E2; color: #991B1B;">Sin Stock</span>
                @endif
                <h1 class="product-title">{{ $autopart->name }}</h1>
                <p class="product-sku">ID: {{ $autopart->id }} | Categoría: {{ $autopart->category ?? 'General' }}</p>

                <div class="product-price">${{ number_format($autopart->price, 2) }} MXN</div>

                <h3 class="section-title">Descripción</h3>
                <p class="product-desc">
                    {{ $autopart->description ?? 'Esta autoparte ha sido fabricada bajo los más altos estándares de calidad OEM. Garantiza un ajuste perfecto y un rendimiento superior para su vehículo, asegurando durabilidad incluso en las condiciones más exigentes.' }}
                </p>

                <h3 class="section-title">Compatibilidad</h3>
                <p class="product-desc" style="font-size: 0.875rem;">
                    • Compatible con modelos 2018 - 2024.<br>
                    • Diseñado para motores de 4 y 6 cilindros.<br>
                    • Instalación rápida plug-and-play.
                </p>

                <div class="product-actions">
                    @if(($autopart->stock ?? 0) > 0)
                        <div class="qty-row">
                            <label for="detail-qty">Cantidad</label>
                            <div class="qty-controls">
                                <button type="button" id="detail-qty-minus" aria-label="Menos">−</button>
                                <span id="detail-qty-val" data-qty="1">1</span>
                                <button type="button" id="detail-qty-plus" aria-label="Más">+</button>
                            </div>
                        </div>
                        <button type="button" class="btn-add-cart" id="btn-add-cart-detail" data-autopart-id="{{ $autopart->id }}" data-max-stock="{{ (int) $autopart->stock }}">
                            <i class="fas fa-cart-plus"></i> Agregar al carrito
                        </button>
                    @else
                        <p style="color: var(--danger); font-weight: 600; margin-bottom: 0.5rem;">No hay existencias disponibles.</p>
                    @endif
                    <a href="{{ url('/pedidos/crear') }}" class="btn-order">
                        <i class="fas fa-shopping-cart"></i> Realizar pedido ahora
                    </a>
                </div>
            </div>
        </div>
    </main>

    <div class="toast" id="detail-toast" role="status" aria-live="polite">Producto agregado al carrito</div>

    <script>
        (function() {
            var btn = document.getElementById('btn-add-cart-detail');
            var toast = document.getElementById('detail-toast');
            var qtyEl = document.getElementById('detail-qty-val');
            var minus = document.getElementById('detail-qty-minus');
            var plus = document.getElementById('detail-qty-plus');
            if (!btn || !qtyEl) return;

            var maxStock = parseInt(btn.getAttribute('data-max-stock') || '99', 10);

            function getQty() {
                return parseInt(qtyEl.getAttribute('data-qty') || '1', 10);
            }
            function setQty(n) {
                n = Math.max(1, Math.min(maxStock, n));
                qtyEl.setAttribute('data-qty', n);
                qtyEl.textContent = n;
            }
            if (minus) minus.addEventListener('click', function() { setQty(getQty() - 1); });
            if (plus) plus.addEventListener('click', function() { setQty(getQty() + 1); });

            btn.addEventListener('click', function() {
                if (btn.disabled) return;
                var id = btn.getAttribute('data-autopart-id');
                var q = getQty();
                var oldHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Agregando…';
                btn.disabled = true;

                fetch('{{ url("/carrito/agregar") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ autopart_id: parseInt(id, 10), quantity: q })
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    btn.innerHTML = oldHtml;
                    btn.disabled = false;
                    if (data.success) {
                        if (toast) {
                            toast.classList.add('show');
                            setTimeout(function() { toast.classList.remove('show'); }, 2500);
                        }
                    } else {
                        alert(data.message || 'No se pudo agregar al carrito.');
                    }
                })
                .catch(function() {
                    btn.innerHTML = oldHtml;
                    btn.disabled = false;
                    alert('Error de red. Intenta de nuevo.');
                });
            });
        })();
    </script>
    <script>
        (function() {
            // Script de respaldo para corregir URLs de imágenes si fallan al cargar
            document.querySelectorAll('img').forEach(function(img) {
                img.onerror = function() {
                    if (this.dataset.retried) return;
                    this.dataset.retried = 'true';

                    let src = this.getAttribute('src');
                    if (!src) return;

                    try {
                        let url = new URL(src, window.location.origin);
                        url.port = '5000';
                        this.src = url.toString();
                    } catch(e) {
                        this.src = src.replace(/:\d+/, ':5000');
                    }
                };
            });
        })();
    </script>
</body>
</html>
