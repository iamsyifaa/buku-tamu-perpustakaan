<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpustakaan Desa')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=Work+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@600&display=swap" rel="stylesheet">
    <style>
        :root {
            /* === Palet utama (navy + cream) === */
            --brand: #13273F;
            --brand-deep: #0C1B2C;
            --cream: #E9D4C3;
            --cream-soft: #F3E6DA;
            --text-soft: #5b5044;
            --white: #FFFFFF;

            /* alias supaya kompatibel dgn markup lama */
            --color-bg: var(--cream-soft);
            --color-bg-pattern: #ECDAC7;
            --color-surface: var(--white);
            --color-ink: var(--brand);
            --color-ink-soft: var(--text-soft);
            --color-ink-faint: #A89880;
            --color-primary: var(--brand);
            --color-primary-dark: var(--brand-deep);
            --color-primary-tint: rgba(19, 39, 63, 0.08);
            --color-accent: #A8823A;
            --color-accent-tint: #F6EEDD;
            --color-border: rgba(19, 39, 63, 0.16);
            --color-danger: #B23B3B;
            --color-danger-tint: #F8EAE6;

            --radius-md: 10px;
            --radius-lg: 16px;
            --font-display: 'Fraunces', Georgia, serif;
            --font-body: 'Inter', 'Work Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-mono: 'JetBrains Mono', ui-monospace, monospace;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes floatBlob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(14px, -18px) scale(1.06); }
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font-body);
            color: var(--color-ink);
            min-height: 100vh;
            background-color: var(--color-bg);
            background-image: radial-gradient(circle at 1px 1px, var(--color-bg-pattern) 1.5px, transparent 0);
            background-size: 26px 26px;
            animation: fadeIn 0.5s ease both;
        }

        .card {
            position: relative;
            background: var(--color-surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--color-border);
            box-shadow: 0 1px 2px rgba(19,39,63,0.06), 0 16px 40px -12px rgba(19,39,63,0.18);
            padding: 40px 36px 32px;
            width: 100%;
            max-width: 440px;
            animation: fadeInUp 0.7s cubic-bezier(.16,1,.3,1) both;
        }

        .card::before {
            content: '';
            position: absolute; top: 0; left: 28px; right: 28px; height: 3px;
            background: linear-gradient(90deg, var(--color-primary), var(--color-accent));
            border-radius: 0 0 3px 3px;
        }

        .card-eyebrow {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            font-size: 0.72rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase;
            color: var(--color-accent); margin-bottom: 14px;
        }
        .card-eyebrow .rule { width: 16px; height: 1px; background: var(--color-border); }

        .card-title {
            font-family: var(--font-display);
            font-size: 1.7rem; font-weight: 600;
            text-align: center; color: var(--color-primary-dark);
            margin-bottom: 6px; letter-spacing: -0.01em;
        }
        .card-subtitle { text-align: center; color: var(--color-ink-soft); font-size: 0.9rem; margin-bottom: 28px; line-height: 1.5; }

        .form-group { margin-bottom: 18px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

        label {
            display: block; margin-bottom: 7px; font-weight: 600; font-size: 0.82rem;
            color: var(--color-ink); letter-spacing: 0.01em;
        }
        label .req { color: var(--color-danger); }

        input[type="text"], input[type="password"], input[type="tel"], input[type="number"], select {
            width: 100%; padding: 11px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md);
            font-family: var(--font-body); font-size: 0.95rem; color: var(--color-ink);
            background: var(--color-surface);
            outline: none; transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        input::placeholder { color: var(--color-ink-faint); }
        input:focus, select:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3.5px var(--color-primary-tint);
        }

        select {
            appearance: none; cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='none' stroke='%235b5044' stroke-width='1.6' stroke-linecap='round' stroke-linejoin='round' d='M1 1.5l5 5 5-5'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px;
        }
        select:invalid { color: var(--color-ink-faint); }

        .section-divider { display: flex; align-items: center; gap: 10px; margin: 26px 0 16px; }
        .section-divider .line { flex: 1; height: 1px; background: var(--color-border); }
        .section-divider .label {
            font-size: 0.72rem; font-weight: 600; letter-spacing: 0.12em; text-transform: uppercase;
            color: var(--color-accent); white-space: nowrap;
        }

        .btn {
            width: 100%; padding: 13px; background: var(--color-primary); color: var(--cream); border: none;
            border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.96rem; font-weight: 600;
            letter-spacing: 0.01em; cursor: pointer;
            box-shadow: 0 1px 2px rgba(19,39,63,0.15), 0 6px 14px -6px rgba(19,39,63,0.45);
            transition: background 0.15s ease, transform 0.15s ease, box-shadow 0.15s ease;
        }
        .btn:hover { background: var(--color-primary-dark); transform: translateY(-2px) scale(1.01); box-shadow: 0 2px 4px rgba(19,39,63,0.2), 0 12px 20px -6px rgba(19,39,63,0.5); }
        .btn:active { transform: translateY(0) scale(0.99); }
        .btn-outline { background: transparent; color: var(--color-primary); border: 1.5px solid var(--color-primary); box-shadow: none; }
        .btn-outline:hover { background: var(--color-primary-tint); transform: none; box-shadow: none; }

        .link-text { text-align: center; margin-top: 20px; font-size: 0.87rem; color: var(--color-ink-soft); }
        .link-text a { color: var(--color-primary); font-weight: 600; text-decoration: none; }
        .link-text a:hover { text-decoration: underline; }
        .error-msg { color: var(--color-danger); font-size: 0.8rem; margin-top: 5px; min-height: 1px; }

        /* Modal */
        .modal-overlay {
            display: none; position: fixed; inset: 0; background: rgba(12,27,44,0.55);
            backdrop-filter: blur(3px);
            z-index: 999; align-items: center; justify-content: center; padding: 16px;
        }
        .modal-overlay.active { display: flex; animation: fadeIn 0.25s ease; }
        .modal-box {
            background: var(--cream); border-radius: var(--radius-lg);
            padding: 36px 30px 30px; text-align: center; max-width: 380px; width: 100%;
            box-shadow: 0 24px 60px -16px rgba(12,27,44,0.4);
            animation: modal-rise 0.3s cubic-bezier(.16,1,.3,1);
        }
        @keyframes modal-rise { from { opacity: 0; transform: translateY(12px) scale(0.97); } to { opacity: 1; transform: none; } }

        .modal-stamp, .modal-icon {
            width: 56px; height: 56px; border-radius: 50%;
            background: linear-gradient(160deg, var(--brand), var(--brand-deep));
            color: var(--white);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; margin: 0 auto 16px;
        }
        .modal-title { font-family: var(--font-display); font-size: 1.3rem; font-weight: 600; color: var(--color-primary-dark); margin-bottom: 8px; }
        .modal-body { color: var(--color-ink-soft); font-size: 0.92rem; margin-bottom: 20px; line-height: 1.55; }
        .modal-body strong { color: var(--color-ink); }

        .modal-id-card {
            position: relative; margin: 4px 0 18px; padding: 16px 18px;
            background: var(--brand); border-radius: var(--radius-md);
        }
        .modal-id-label { font-size: 0.66rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--cream); margin-bottom: 6px; }
        .modal-id { font-family: var(--font-mono); font-size: 1.5rem; font-weight: 600; color: var(--white); letter-spacing: 0.06em; }
        .modal-hint { font-size: 0.78rem; color: var(--color-ink-faint); }
    </style>
    @stack('styles')
</head>
<body class="@yield('body-class')">
    @yield('content')

    @stack('modals')
    @stack('scripts')
</body>
</html>