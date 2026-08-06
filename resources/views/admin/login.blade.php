<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Perpustakaan Desa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;1,9..144,500&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #13273F;
            --navy-deep: #0B1A2B;
            --cream: #E9D4C3;
            --cream-soft: #F4ECE2;
            --ivory: #FBF8F4;
            --ink: #1D2733;
            --ink-muted: #6B7684;
            --line: #E7E0D6;
            --error-bg: #FBEDEA;
            --error-border: #E3B7AC;
            --error-text: #9C4A38;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
            background: var(--ivory);
            color: var(--ink);
            min-height: 100vh;
            display: flex;
            align-items: stretch;
            justify-content: center;
        }

        .stage {
            width: 100%;
            min-height: 100vh;
            display: flex;
        }

        /* ---------- Panel kiri (identitas) ---------- */
        .brand-panel {
            position: relative;
            flex: 0 0 44%;
            background: linear-gradient(160deg, var(--navy) 0%, var(--navy-deep) 100%);
            color: var(--cream-soft);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 56px 52px;
            overflow: hidden;
        }

        .brand-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                repeating-linear-gradient(90deg, rgba(233,212,195,0.05) 0px, rgba(233,212,195,0.05) 1px, transparent 1px, transparent 46px);
            pointer-events: none;
        }

        .shelf-mark {
            position: absolute;
            right: -60px;
            bottom: -40px;
            width: 320px;
            height: 320px;
            opacity: 0.16;
            pointer-events: none;
        }

        .brand-top {
            position: relative;
            z-index: 1;
        }

        .brand-crest {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            border: 1px solid rgba(233,212,195,0.4);
            margin-bottom: 28px;
        }

        .brand-crest svg { width: 24px; height: 24px; }

        .brand-eyebrow {
            font-size: 0.78rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--cream);
            opacity: 0.75;
            margin-bottom: 14px;
        }

        .brand-title {
            font-family: 'Fraunces', serif;
            font-weight: 500;
            font-size: 2.6rem;
            line-height: 1.15;
            color: #fff;
            max-width: 360px;
        }

        .brand-title em {
            font-style: italic;
            color: var(--cream);
        }

        .brand-desc {
            position: relative;
            z-index: 1;
            font-size: 0.94rem;
            line-height: 1.7;
            color: rgba(244,236,226,0.72);
            max-width: 340px;
            margin-top: 22px;
        }

        .brand-footer {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.8rem;
            color: rgba(244,236,226,0.55);
        }

        .brand-footer .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--cream);
        }

        /* ---------- Panel kanan (form) ---------- */
        .form-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 32px;
            background: var(--ivory);
        }

        .form-wrap {
            width: 100%;
            max-width: 380px;
        }

        .form-heading {
            margin-bottom: 8px;
        }

        .form-eyebrow {
            font-size: 0.78rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #A98A6C;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .form-title {
            font-family: 'Fraunces', serif;
            font-size: 1.6rem;
            font-weight: 600;
            color: var(--navy);
            margin-bottom: 6px;
        }

        .form-subtitle {
            font-size: 0.88rem;
            color: var(--ink-muted);
            margin-bottom: 30px;
        }

        .alert-error {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            background: var(--error-bg);
            border: 1px solid var(--error-border);
            color: var(--error-text);
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 0.85rem;
            line-height: 1.5;
            margin-bottom: 22px;
        }

        .alert-error svg { flex: 0 0 auto; margin-top: 1px; }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 0.82rem;
            color: var(--navy);
            letter-spacing: 0.01em;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 17px;
            height: 17px;
            color: #A6ADB6;
            transition: color 0.2s ease;
        }

        input {
            width: 100%;
            padding: 12px 14px 12px 40px;
            background: #fff;
            border: 1.5px solid var(--line);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.95rem;
            color: var(--ink);
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        input::placeholder { color: #ADB4BD; }

        input:focus {
            border-color: var(--navy);
            box-shadow: 0 0 0 3px rgba(19,39,63,0.08);
        }

        input:focus ~ svg,
        .input-wrap:focus-within svg {
            color: var(--navy);
        }

        .form-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: -6px 0 26px;
            font-size: 0.82rem;
        }

        .form-meta label {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-weight: 500;
            color: var(--ink-muted);
            margin: 0;
            cursor: pointer;
        }

        .form-meta input[type="checkbox"] {
            width: 15px;
            height: 15px;
            padding: 0;
            accent-color: var(--navy);
            cursor: pointer;
        }

        .btn {
            width: 100%;
            padding: 13px;
            background: var(--navy);
            color: var(--cream-soft);
            border: none;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 0.01em;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.15s ease;
        }

        .btn:hover { background: var(--navy-deep); }
        .btn:active { transform: translateY(1px); }

        .form-bottom-note {
            text-align: center;
            font-size: 0.8rem;
            color: var(--ink-muted);
            margin-top: 26px;
        }

        /* ---------- Responsif ---------- */
        @media (max-width: 860px) {
            .stage { flex-direction: column; }

            .brand-panel {
                flex: none;
                padding: 40px 32px 46px;
                min-height: 240px;
                justify-content: flex-start;
                gap: 18px;
            }

            .brand-crest { margin-bottom: 18px; width: 44px; height: 44px; }
            .brand-title { font-size: 1.9rem; max-width: 100%; }
            .brand-desc { display: none; }
            .brand-footer { margin-top: 6px; }
            .shelf-mark { width: 220px; height: 220px; right: -40px; bottom: -50px; }

            .form-panel { padding: 36px 24px 56px; }
        }

        @media (max-width: 400px) {
            .brand-panel { padding: 32px 22px 38px; }
            .form-panel { padding: 28px 18px 48px; }
            .brand-title { font-size: 1.6rem; }
        }
    </style>
</head>
<body>
<div class="stage">

    <!-- Panel identitas -->
    <aside class="brand-panel">
        <svg class="shelf-mark" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 170V30" stroke="#E9D4C3" stroke-width="1.5"/>
            <path d="M180 170V30" stroke="#E9D4C3" stroke-width="1.5"/>
            <path d="M20 60H180" stroke="#E9D4C3" stroke-width="1.5"/>
            <path d="M20 105H180" stroke="#E9D4C3" stroke-width="1.5"/>
            <path d="M20 150H180" stroke="#E9D4C3" stroke-width="1.5"/>
            <path d="M40 60V30" stroke="#E9D4C3" stroke-width="1.5"/>
            <path d="M65 60V30" stroke="#E9D4C3" stroke-width="1.5"/>
            <path d="M90 60V30" stroke="#E9D4C3" stroke-width="1.5"/>
            <path d="M120 60V30" stroke="#E9D4C3" stroke-width="1.5"/>
            <path d="M150 60V30" stroke="#E9D4C3" stroke-width="1.5"/>
            <path d="M35 105V60" stroke="#E9D4C3" stroke-width="1.5"/>
            <path d="M70 105V60" stroke="#E9D4C3" stroke-width="1.5"/>
            <path d="M100 105V60" stroke="#E9D4C3" stroke-width="1.5"/>
            <path d="M135 105V60" stroke="#E9D4C3" stroke-width="1.5"/>
            <path d="M45 150V105" stroke="#E9D4C3" stroke-width="1.5"/>
            <path d="M80 150V105" stroke="#E9D4C3" stroke-width="1.5"/>
            <path d="M115 150V105" stroke="#E9D4C3" stroke-width="1.5"/>
            <path d="M155 150V105" stroke="#E9D4C3" stroke-width="1.5"/>
        </svg>

        <div class="brand-top">
            <div class="brand-crest">
                <svg viewBox="0 0 24 24" fill="none" stroke="#E9D4C3" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 5.5C4 4.67 4.67 4 5.5 4H11v16H5.5A1.5 1.5 0 0 1 4 18.5v-13Z"/>
                    <path d="M20 5.5c0-.83-.67-1.5-1.5-1.5H13v16h5.5a1.5 1.5 0 0 0 1.5-1.5v-13Z"/>
                </svg>
            </div>
            <div class="brand-eyebrow">Sistem Perpustakaan Karya Pustaka</div>
            <h1 class="brand-title">Selamat datang<br><em>kembali,</em> Admin</h1>
            <p class="brand-desc">Kelola koleksi buku, data anggota, dan peminjaman perpustakaan desa dengan satu pintu akses yang aman dan terpercaya.</p>
        </div>

        <div class="brand-footer">
            <span class="dot"></span>
            <span>Akses khusus petugas perpustakaan terdaftar</span>
        </div>
    </aside>

    <!-- Panel form -->
    <main class="form-panel">
        <div class="form-wrap">
            <div class="form-heading">
                <div class="form-eyebrow">Masuk</div>
                <h2 class="form-title">Login Admin</h2>
                <p class="form-subtitle">Masukkan kredensial Anda untuk mengakses panel admin.</p>
            </div>

            @if($errors->has('login'))
                <div class="alert-error">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 8v5"/>
                        <path d="M12 16h.01"/>
                    </svg>
                    <span>{{ $errors->first('login') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf

                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21a8 8 0 0 0-16 0"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Masukkan username" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="10" width="16" height="10" rx="2"/>
                            <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                        </svg>
                        <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                    </div>
                </div>

                {{-- <div class="form-meta">
                    <label>
                        <input type="checkbox" name="remember">
                        Ingat saya
                    </label>
                </div> --}}

                <button class="btn" type="submit">Masuk ke Panel</button>
            </form>

            <p class="form-bottom-note">Hubungi pengelola perpustakaan jika mengalami kendala akses.</p>
        </div>
    </main>

</div>
</body>
</html>