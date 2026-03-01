<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear cuenta — MACUIN</title>
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
            overflow-x: hidden;
        }

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

        .reg-wrap {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .reg-card {
            background: var(--card);
            width: 100%;
            max-width: 440px;
            padding: 2.5rem 2rem;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(31, 58, 95, 0.08), 0 1px 3px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(31, 58, 95, 0.06);
        }

        .reg-logo {
            display: block;
            margin: 0 auto 1.75rem;
            height: 180px;
            width: auto;
            object-fit: contain;
        }

        .reg-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary);
            text-align: center;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }

        .reg-subtitle {
            font-size: 0.9375rem;
            color: var(--text-secondary);
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-group .optional {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-left: 0.25rem;
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

        .form-input::placeholder { color: transparent; }

        .form-input:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.15);
        }

        .form-input.error { border-color: var(--error); }
        .form-input.error:focus { box-shadow: 0 0 0 3px rgba(229, 57, 53, 0.15); }

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
        .form-input.error ~ .form-label { color: var(--error); }

        .input-with-toggle { position: relative; }
        .input-with-toggle .form-input { padding-right: 2.75rem; }
        .toggle-password {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            width: 2rem;
            height: 2rem;
            padding: 0;
            border: none;
            background: none;
            color: var(--secondary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: color 0.2s, background 0.2s;
        }
        .toggle-password:hover { color: var(--primary); background: rgba(74, 111, 165, 0.08); }
        .toggle-password svg { width: 1.25rem; height: 1.25rem; }

        .password-strength {
            display: flex;
            gap: 4px;
            margin-top: 0.5rem;
        }
        .password-strength span {
            flex: 1;
            height: 4px;
            border-radius: 2px;
            background: #e5e7eb;
            transition: background 0.2s;
        }
        .password-strength.weak span:nth-child(1) { background: var(--error); }
        .password-strength.medium span:nth-child(1),
        .password-strength.medium span:nth-child(2) { background: var(--warning); }
        .password-strength.strong span:nth-child(1),
        .password-strength.strong span:nth-child(2),
        .password-strength.strong span:nth-child(3) { background: var(--success); }
        .password-strength-text {
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
        .password-strength.weak .password-strength-text { color: var(--error); }
        .password-strength.medium .password-strength-text { color: var(--warning); }
        .password-strength.strong .password-strength-text { color: var(--success); }

        .form-error { font-size: 0.8125rem; color: var(--error); margin-top: 0.35rem; }
        .form-success { font-size: 0.8125rem; color: var(--success); margin-top: 0.35rem; }

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        .checkbox-group input[type="checkbox"] {
            width: 1.125rem;
            height: 1.125rem;
            margin-top: 0.2rem;
            accent-color: var(--primary);
            cursor: pointer;
        }
        .checkbox-group label {
            font-size: 0.9375rem;
            color: var(--text-primary);
            cursor: pointer;
            line-height: 1.4;
        }
        .checkbox-group a {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 500;
        }
        .checkbox-group a:hover { text-decoration: underline; }

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
            transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
            margin-top: 0.25rem;
        }
        .btn-primary:hover {
            background: #2a4d7a;
            box-shadow: 0 4px 20px rgba(31, 58, 95, 0.35);
            transform: translateY(-1px);
        }
        .btn-primary:active { transform: translateY(0); }

        .reg-footer {
            text-align: center;
            margin-top: 1.75rem;
        }
        .reg-footer a {
            color: var(--secondary);
            font-size: 0.9375rem;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s;
        }
        .reg-footer a:hover { color: var(--primary); text-decoration: underline; }

        @media (max-width: 480px) {
            .reg-wrap { padding: 1.5rem 1rem; }
            .reg-card { padding: 2rem 1.5rem; border-radius: 16px; }
            .reg-title { font-size: 1.5rem; }
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

    <div class="reg-wrap">
        <div class="reg-card">
            <img src="{{ asset('images/logo_macuin.png') }}" alt="MACUIN" class="reg-logo" onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
            <div style="display:none;font-size:1.5rem;font-weight:700;color:var(--primary);text-align:center;margin-bottom:1.75rem;">MACUIN</div>

            <h1 class="reg-title">Crear cuenta</h1>
            <p class="reg-subtitle">Regístrate para gestionar tus pedidos y autopartes</p>

            @if(session('success'))
                <div class="form-success" role="alert">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ url('/registro') }}" autocomplete="on" id="reg-form">
                @csrf

                <div class="form-group">
                    <input type="text" name="name" id="name" class="form-input @error('name') error @enderror" placeholder=" " value="{{ old('name') }}" required autofocus>
                    <label class="form-label" for="name">Nombre completo</label>
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <input type="email" name="email" id="email" class="form-input @error('email') error @enderror" placeholder=" " value="{{ old('email') }}" required>
                    <label class="form-label" for="email">Correo electrónico</label>
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <div class="input-with-toggle">
                        <input type="password" name="password" id="password" class="form-input @error('password') error @enderror" placeholder=" " required>
                        <label class="form-label" for="password">Contraseña</label>
                        <button type="button" class="toggle-password" aria-label="Mostrar contraseña" data-target="password">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    <div class="password-strength" id="password-strength" aria-live="polite">
                        <span></span><span></span><span></span>
                    </div>
                    <div class="password-strength-text" id="password-strength-text"></div>
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <div class="input-with-toggle">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-input @error('password_confirmation') error @enderror" placeholder=" " required>
                        <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
                        <button type="button" class="toggle-password" aria-label="Mostrar contraseña" data-target="password_confirmation">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    @error('password_confirmation')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <input type="tel" name="phone" id="phone" class="form-input @error('phone') error @enderror" placeholder=" " value="{{ old('phone') }}">
                    <label class="form-label" for="phone">Teléfono <span class="optional">(opcional)</span></label>
                    @error('phone')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" name="terms" id="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
                    <label for="terms">Acepto los <a href="#" target="_blank" rel="noopener">términos y condiciones</a></label>
                </div>
                @error('terms')<p class="form-error">{{ $message }}</p>@enderror

                @error('register')
                    <p class="form-error" style="margin-bottom:1rem;">{{ $message }}</p>
                @enderror

                <button type="submit" class="btn-primary">Crear cuenta</button>
            </form>

            <p class="reg-footer">
                ¿Ya tienes cuenta? <a href="{{ url('/login') }}">Inicia sesión</a>
            </p>
        </div>
    </div>

    <script>
        (function() {
            var form = document.getElementById('reg-form');
            if (!form) return;

            function updateFilled(e) {
                var el = e.target;
                el.classList.toggle('filled', el.value.length > 0);
            }
            form.querySelectorAll('.form-input').forEach(function(input) {
                if (input.value) input.classList.add('filled');
                input.addEventListener('input', updateFilled);
            });

            function strength(pwd) {
                if (!pwd) return 0;
                var s = 0;
                if (pwd.length >= 8) s++;
                if (/[a-z]/.test(pwd) && /[A-Z]/.test(pwd)) s++;
                if (/\d/.test(pwd) && /[^a-zA-Z0-9]/.test(pwd)) s++;
                if (pwd.length >= 12) s++;
                return Math.min(3, s);
            }

            var pwdEl = document.getElementById('password');
            var strengthEl = document.getElementById('password-strength');
            var strengthText = document.getElementById('password-strength-text');
            if (pwdEl && strengthEl && strengthText) {
                pwdEl.addEventListener('input', function() {
                    var v = this.value;
                    var s = strength(v);
                    strengthEl.className = 'password-strength' + (s === 1 ? ' weak' : s === 2 ? ' medium' : s === 3 ? ' strong' : '');
                    strengthText.textContent = v.length === 0 ? '' : (s === 1 ? 'Contraseña débil' : s === 2 ? 'Contraseña media' : 'Contraseña fuerte');
                });
            }

            form.querySelectorAll('.toggle-password').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var id = this.getAttribute('data-target');
                    var input = document.getElementById(id);
                    if (!input) return;
                    var isPass = input.type === 'password';
                    input.type = isPass ? 'text' : 'password';
                    this.setAttribute('aria-label', isPass ? 'Ocultar contraseña' : 'Mostrar contraseña');
                    this.innerHTML = isPass
                        ? '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>'
                        : '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>';
                });
            });
        })();
    </script>
</body>
</html>
