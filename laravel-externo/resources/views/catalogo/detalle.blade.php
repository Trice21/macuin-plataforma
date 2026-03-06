<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        .product-image-container { background: var(--bg); border-radius: 16px; padding: 2rem; display: flex; align-items: center; justify-content: center; }
        .product-image { max-height: 400px; width: auto; }
        
        .badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; margin-bottom: 1rem; }
        .badge-success { background: #D1FAE5; color: #065F46; }
        
        .product-title { font-size: 2.5rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem; }
        .product-sku { color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 2rem; }
        .product-price { font-size: 2.5rem; font-weight: 700; color: var(--secondary); margin-bottom: 2rem; }
        
        .section-title { font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: var(--primary); border-bottom: 2px solid var(--bg); padding-bottom: 0.5rem; }
        .product-desc { font-size: 1rem; line-height: 1.6; color: var(--text-secondary); margin-bottom: 2rem; }
        
        .btn-order { display: flex; align-items: center; justify-content: center; gap: 0.75rem; width: 100%; padding: 1.25rem; background: var(--primary); color: white; text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 1.125rem; transition: all 0.2s; }
        .btn-order:hover { background: var(--secondary); transform: translateY(-2px); }
    </style>
</head>
<body>
    @php
        $products = [
            1 => ['name' => 'Filtro de aceite premium', 'sku' => 'SKU-2041', 'price' => '245.00', 'stock' => 15],
            2 => ['name' => 'Pastillas de freno delanteras', 'sku' => 'SKU-3082', 'price' => '420.00', 'stock' => 8],
            3 => ['name' => 'Bujía de encendido iridio', 'sku' => 'SKU-5103', 'price' => '185.00', 'stock' => 5],
            4 => ['name' => 'Bomba de agua', 'sku' => 'SKU-7204', 'price' => '680.00', 'stock' => 12],
            5 => ['name' => 'Correa de distribución', 'sku' => 'SKU-8305', 'price' => '320.00', 'stock' => 0],
            6 => ['name' => 'Sensor de oxígeno', 'sku' => 'SKU-9406', 'price' => '395.00', 'stock' => 20],
            7 => ['name' => 'Amortiguador trasero', 'sku' => 'SKU-1057', 'price' => '550.00', 'stock' => 4],
            8 => ['name' => 'Bomba de combustible', 'sku' => 'SKU-2188', 'price' => '720.00', 'stock' => 10],
        ];
        $p = $products[$id] ?? $products[1];
        $productImg = strtolower(explode(' ', trim($p['name']))[0]) . '.png';
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
                <img src="{{ asset('images/' . $productImg) }}" alt="{{ $p['name'] }}" class="product-image" onerror="this.src='https://via.placeholder.com/400x300?text=Autoparte'">
            </div>
            
            <div>
                @if($p['stock'] > 0)
                    <span class="badge badge-success">Disponible ({{ $p['stock'] }} en stock)</span>
                @else
                    <span class="badge" style="background: #FEE2E2; color: #991B1B;">Sin Stock</span>
                @endif
                <h1 class="product-title">{{ $p['name'] }}</h1>
                <p class="product-sku">SKU: {{ $p['sku'] }} | Categoría: Motor</p>
                
                <div class="product-price">${{ $p['price'] }} MXN</div>
                
                <h3 class="section-title">Descripción</h3>
                <p class="product-desc">
                    Esta autoparte ha sido fabricada bajo los más altos estándares de calidad OEM. 
                    Garantiza un ajuste perfecto y un rendimiento superior para su vehículo, 
                    asegurando durabilidad incluso en las condiciones más exigentes.
                </p>
                
                <h3 class="section-title">Compatibilidad</h3>
                <p class="product-desc" style="font-size: 0.875rem;">
                    • Compatible con modelos 2018 - 2024.<br>
                    • Diseñado para motores de 4 y 6 cilindros.<br>
                    • Instalación rápida plug-and-play.
                </p>
                
                <a href="/pedidos/crear?id={{ $id }}" class="btn-order">
                    <i class="fas fa-shopping-cart"></i> Realizar Pedido Ahora
                </a>
            </div>
        </div>
    </main>
</body>
</html>
