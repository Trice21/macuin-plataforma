<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión — MACUIN</title>
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

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Background: geometric shapes + faint gear pattern */
        .page-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            background: linear-gradient(135deg, var(--bg) 0%, #e8ecf1 50%, var(--bg) 100%);
        }

        .page-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 20% 80%, rgba(31, 58, 95, 0.04) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(74, 111, 165, 0.05) 0%, transparent 50%);
        }

        /* Subtle gear-like pattern */
        .page-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0.03;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 8v4l-2 1 2 2 2-2-2-1v-4zm0 32v4l2 1-2 2-2-2 2-1v-4zm-8-20l-4 2v4l2 2 2-2 4-2v-4l-4-2zm20 8l4-2v-4l-2-2-2 2-4 2v4l4 2zm-6-14l2-2 4 2-1 2-2-1-2 2-1-2zm-8 20l-2 2-4-2 1-2 2 1 2-2 1 2zm14-6l2 2v4l-2 1-2-1v-4l2-2zm-16 0l-2 2v4l2 1 2-1v-4l-2-2z' fill='%231F3A5F' fill-opacity='1'/%3E%3C/svg%3E");
        }

        .shape {
            position: absolute;
            border-radius: 4px;
            opacity: 0.06;
        }
        .shape-1 { width: 120px; height: 120px; background: var(--primary); top: 10%; left: 8%; transform: rotate(15deg); }
        .shape-2 { width: 80px; height: 80px; background: var(--secondary); top: 70%; right: 10%; transform: rotate(-20deg); }
        .shape-3 { width: 60px; height: 60px; background: var(--primary); bottom: 15%; left: 15%; transform: rotate(45deg); }
        .shape-4 { width: 40px; height: 40px; background: var(--secondary); top: 25%; right: 25%; transform: rotate(-10deg); }

        .login-wrap {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .login-card {
            background: var(--card);
            width: 100%;
            max-width: 420px;
            padding: 2.5rem 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(31, 58, 95, 0.08), 0 1px 3px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(31, 58, 95, 0.06);
        }

        .login-logo {
            display: block;
            margin: 0 auto 1.75rem;
            height: 160px;
            width: auto;
            object-fit: contain;
        }

        .login-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary);
            text-align: center;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            font-size: 0.9375rem;
            color: var(--text-secondary);
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-input {
            width: 100%;
            height: 52px;
            padding: 1rem 1rem 0.35rem;
            font-family: inherit;
            font-size: 1rem;
            color: var(--text-primary);
            background: var(--card);
            border: 1px solid #d1d5db;
            border-radius: 10px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input::placeholder {
            color: transparent;
        }

        .form-input:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.15);
        }

        .form-input.error {
            border-color: var(--error);
        }

        .form-input.error:focus {
            box-shadow: 0 0 0 3px rgba(229, 57, 53, 0.15);
        }

        .form-label {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
            color: var(--text-secondary);
            pointer-events: none;
            transition: transform 0.2s, font-size 0.2s, color 0.2s;
        }

        .form-input:focus ~ .form-label,
        .form-input:not(:placeholder-shown) ~ .form-label,
        .form-input.filled ~ .form-label {
            transform: translateY(-1.5rem) scale(0.85);
            font-size: 0.75rem;
            color: var(--secondary);
        }

        .form-input.error ~ .form-label {
            color: var(--error);
        }

        .form-error {
            font-size: 0.8125rem;
            color: var(--error);
            margin-top: 0.35rem;
        }

        .form-success {
            font-size: 0.8125rem;
            color: var(--success);
            margin-top: 0.35rem;
        }

        .btn-primary {
            width: 100%;
            height: 48px;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
            background: var(--primary);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
            margin-top: 0.5rem;
        }

        .btn-primary:hover {
            background: #2a4d7a;
            box-shadow: 0 4px 20px rgba(31, 58, 95, 0.35);
        }

        .btn-primary:active {
            transform: translateY(1px);
        }

        .login-footer {
            text-align: center;
            margin-top: 1.75rem;
        }

        .login-footer a {
            color: var(--secondary);
            font-size: 0.9375rem;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s;
        }

        .login-footer a:hover {
            color: var(--primary);
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-wrap { padding: 1.5rem 1rem; }
            .login-card { padding: 2rem 1.5rem; }
            .login-title { font-size: 1.5rem; }
            .shape { display: none; }
        }
    </style>
</head>
<body>
    <div class="page-bg">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
    </div>

    <div class="login-wrap">
        <div class="login-card">
            <img src="{{ asset('images/logo_macuin.png') }}" alt="MACUIN" class="login-logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            

            <h1 class="login-title">Iniciar sesión</h1>
            <p class="login-subtitle">Accede a tu cuenta del portal de repuestos</p>

            @if(session('success'))
                <div class="form-success" role="alert">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ url('/login') }}" autocomplete="on">
                @csrf

                <div class="form-group">
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-input @error('email') error @enderror"
                        placeholder=" "
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                    <label class="form-label" for="email">Correo electrónico</label>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-input @error('password') error @enderror"
                        placeholder=" "
                        required
                    >
                    <label class="form-label" for="password">Contraseña</label>
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                @error('login')
                    <p class="form-error" style="margin-bottom:1rem;">{{ $message }}</p>
                @enderror

                <button type="submit" class="btn-primary">Entrar</button>
            </form>

            <p class="login-footer">
                ¿No tienes cuenta? <a href="{{ url('/registro') }}">Crear cuenta</a>
            </p>
        </div>
    </div>

    <script>
        document.querySelectorAll('.form-input').forEach(function(input) {
            if (input.value) input.classList.add('filled');
            input.addEventListener('input', function() {
                this.classList.toggle('filled', this.value.length > 0);
            });
        });
    </script>
</body>
</html>
