<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar contraseña — MACUIN</title>
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

        .recovery-wrap {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .recovery-card {
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
            height: 120px;
            width: auto;
            object-fit: contain;
        }

        .icon-badge {
            width: 52px;
            height: 52px;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: rgba(74, 111, 165, 0.1);
            color: var(--secondary);
        }

        .icon-badge svg {
            width: 24px;
            height: 24px;
        }

        .recovery-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary);
            text-align: center;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }

        .recovery-subtitle {
            font-size: 0.9375rem;
            color: var(--text-secondary);
            text-align: center;
            margin-bottom: 2rem;
            line-height: 1.55;
        }

        .state-panel {
            transition: opacity 0.25s ease, transform 0.25s ease;
        }

        .state-panel.hidden {
            display: none;
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

        .form-error,
        .form-success {
            font-size: 0.8125rem;
            margin-top: 0.35rem;
        }

        .form-error {
            color: var(--error);
        }

        .form-success {
            color: var(--success);
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
            transition: background 0.2s, box-shadow 0.2s, opacity 0.2s;
            margin-top: 0.5rem;
        }

        .btn-primary:hover:not(:disabled) {
            background: #2a4d7a;
            box-shadow: 0 4px 20px rgba(31, 58, 95, 0.35);
        }

        .btn-primary:disabled {
            opacity: 0.7;
            cursor: wait;
        }

        .secondary-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: 1rem;
            color: var(--secondary);
            font-size: 0.9375rem;
            font-weight: 600;
            text-decoration: none;
        }

        .secondary-link:hover {
            color: var(--primary);
            text-decoration: underline;
        }

        .recovery-footer {
            text-align: center;
            margin-top: 1.5rem;
        }

        .success-state {
            text-align: center;
        }

        .success-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            background: rgba(76, 175, 80, 0.12);
            color: var(--success);
        }

        .success-icon svg {
            width: 28px;
            height: 28px;
        }

        .success-state h2 {
            font-size: 1.35rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .success-state p {
            color: var(--text-secondary);
            line-height: 1.55;
            margin-bottom: 1rem;
        }

        .status-note {
            margin-top: 1rem;
            padding: 0.95rem 1rem;
            border-radius: 12px;
            background: rgba(74, 111, 165, 0.06);
            border: 1px solid rgba(74, 111, 165, 0.12);
            color: var(--text-secondary);
            font-size: 0.875rem;
            line-height: 1.5;
        }

        @media (max-width: 480px) {
            .recovery-wrap { padding: 1.5rem 1rem; }
            .recovery-card { padding: 2rem 1.5rem; }
            .recovery-title { font-size: 1.5rem; }
            .shape { display: none; }
            .login-logo { height: 96px; }
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

    <div class="recovery-wrap">
        <div class="recovery-card">
            <img src="{{ asset('images/logo_macuin.png') }}" alt="MACUIN" class="login-logo" onerror="this.style.display='none';">

            <div class="icon-badge" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-16 9h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                </svg>
            </div>

            <div id="recovery-form-panel" class="state-panel">
                <h1 class="recovery-title">Recuperar contraseña</h1>
                <p class="recovery-subtitle">Ingresa tu correo electrónico y te enviaremos instrucciones para restablecer tu contraseña.</p>

                <form id="recovery-form" novalidate data-backend-ready="false">
                    <div class="form-group">
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-input"
                            placeholder=" "
                            required
                            autofocus
                        >
                        <label class="form-label" for="email">Correo electrónico</label>
                        <p id="email-error" class="form-error hidden" aria-live="polite"></p>
                    </div>

                    <button type="submit" class="btn-primary" id="submit-btn">Enviar instrucciones</button>
                </form>

                <div class="status-note">
                    Solo se mostrará una confirmación general por seguridad. Después podrás conectar esta pantalla con el backend real de recuperación.
                </div>

                <div class="recovery-footer">
                    <a href="{{ url('/login') }}" class="secondary-link">Volver al inicio de sesión</a>
                </div>
            </div>

            <div id="recovery-success-panel" class="state-panel success-state hidden" aria-live="polite">
                <div class="success-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h2>Revisa tu correo</h2>
                <p>Si el correo existe en MACUIN, hemos enviado instrucciones para restablecer tu contraseña.</p>
                <a href="{{ url('/login') }}" class="secondary-link">Volver al inicio de sesión</a>
            </div>
        </div>
    </div>

    <script>
        (function () {
            var form = document.getElementById('recovery-form');
            var emailInput = document.getElementById('email');
            var emailError = document.getElementById('email-error');
            var submitBtn = document.getElementById('submit-btn');
            var formPanel = document.getElementById('recovery-form-panel');
            var successPanel = document.getElementById('recovery-success-panel');

            function isValidEmail(value) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
            }

            function setFieldState(hasError, message) {
                emailInput.classList.toggle('error', hasError);
                emailError.textContent = message || '';
                emailError.classList.toggle('hidden', !hasError);
            }

            emailInput.addEventListener('input', function () {
                this.classList.toggle('filled', this.value.length > 0);
                if (emailError.textContent) {
                    setFieldState(false, '');
                }
            });

            form.addEventListener('submit', function (event) {
                event.preventDefault();

                var email = emailInput.value.trim();

                if (!email) {
                    setFieldState(true, 'Debes ingresar tu correo electrónico.');
                    emailInput.focus();
                    return;
                }

                if (!isValidEmail(email)) {
                    setFieldState(true, 'Ingresa un correo electrónico válido.');
                    emailInput.focus();
                    return;
                }

                setFieldState(false, '');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Enviando...';

                // Simulación temporal mientras se integra el backend real.
                window.setTimeout(function () {
                    formPanel.classList.add('hidden');
                    successPanel.classList.remove('hidden');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Enviar instrucciones';
                    form.reset();
                    emailInput.classList.remove('filled');
                }, 1500);
            });
        })();
    </script>
</body>
</html>
