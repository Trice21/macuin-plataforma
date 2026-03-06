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
        
        .main { max-width: 1000px; margin: 3rem auto; padding: 0 1.5rem; }
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
        .btn-confirm:hover { background: var(--secondary); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(31, 58, 95, 0.2); }
        
        .product-preview { display: flex; align-items: center; gap: 1rem; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05); margin-bottom: 1.5rem; }
        .preview-img { width: 80px; height: 80px; background: white; border-radius: 12px; object-fit: contain; padding: 0.5rem; border: 1px solid rgba(0,0,0,0.05); }
        .preview-info { flex: 1; }
        .preview-name { font-weight: 600; font-size: 1rem; color: var(--primary); margin-bottom: 0.25rem; }
        .preview-sku { font-size: 0.75rem; color: var(--text-secondary); }
    </style>
</head>
<body>
    @php
        $id = request()->query('id');
        $products = [
            1 => ['name' => 'Filtro de aceite premium', 'sku' => 'SKU-2041', 'price' => '245.00'],
            2 => ['name' => 'Pastillas de freno delanteras', 'sku' => 'SKU-3082', 'price' => '420.00'],
            3 => ['name' => 'Bujía de encendido iridio', 'sku' => 'SKU-5103', 'price' => '185.00'],
            4 => ['name' => 'Bomba de agua', 'sku' => 'SKU-7204', 'price' => '680.00'],
            5 => ['name' => 'Correa de distribución', 'sku' => 'SKU-8305', 'price' => '320.00'],
            6 => ['name' => 'Sensor de oxígeno', 'sku' => 'SKU-9406', 'price' => '395.00'],
            7 => ['name' => 'Amortiguador trasero', 'sku' => 'SKU-1057', 'price' => '550.00'],
            8 => ['name' => 'Bomba de combustible', 'sku' => 'SKU-2188', 'price' => '720.00'],
        ];
        $p = $products[$id] ?? ['name' => 'Autoparte Genérica', 'sku' => 'MAC-9821', 'price' => '0.00'];
        $productImg = strtolower(explode(' ', trim($p['name']))[0]) . '.png';
    @endphp
    <header class="header">
        <a href="/" class="logo">MACUIN</a>
        <nav class="nav">
            <a href="/catalogo">Catálogo</a>
            <a href="/pedidos">Mis Pedidos</a>
        </nav>
    </header>

    <main class="main">
        <div class="grid-checkout">
            <!-- Datos de envío -->
            <div class="card">
                <h3 class="section-title"><i class="fas fa-shipping-fast"></i> Información de Envío</h3>
                <form>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Nombre(s)</label>
                            <input type="text" class="form-input" placeholder="Ej. Juan">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Apellidos</label>
                            <input type="text" class="form-input" placeholder="Ej. Pérez">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Dirección Completa</label>
                        <input type="text" class="form-input" placeholder="Calle, Número, Colonia, CP">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Ciudad</label>
                            <input type="text" class="form-input" placeholder="Ej. Ciudad de México">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-input" placeholder="+52 ...">
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
                </form>
            </div>

            <!-- Resumen lateral -->
            <div>
                <div class="card" style="padding: 1.5rem; position: sticky; top: 2rem;">
                    <h4 style="font-weight: 700; margin-bottom: 1.5rem; color: var(--primary);">Resumen de Pedido</h4>
                    
                    <div class="product-preview">
                        <img src="{{ asset('images/' . $productImg) }}" class="preview-img" onerror="this.src='https://via.placeholder.com/150'">
                        <div class="preview-info">
                            <p class="preview-name">{{ $p['name'] }}</p>
                            <p class="preview-sku">SKU: {{ $p['sku'] }}</p>
                        </div>
                    </div>
                    
                    <div class="order-summary">
                        <div class="summary-item">
                            <span>Subtotal</span>
                            <span>${{ $p['price'] }}</span>
                        </div>
                        <div class="summary-item">
                            <span>Envío</span>
                            <span style="color: var(--success); font-weight: 600;">GRATIS</span>
                        </div>
                        <div class="summary-total">
                            <span>Total</span>
                            <span>${{ $p['price'] }}</span>
                        </div>
                    </div>
                    
                    <button class="btn-confirm">
                        <i class="fas fa-lock"></i> Finalizar Pedido
                    </button>
                    
                    <p style="text-align: center; font-size: 0.75rem; color: var(--text-secondary); margin-top: 1rem;">
                        <i class="fas fa-shield-alt"></i> Compra segura y protegida
                    </p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
