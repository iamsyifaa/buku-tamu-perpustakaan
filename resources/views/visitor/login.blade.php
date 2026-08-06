@extends('layouts.app')

@section('title', 'Masuk - Perpustakaan Desa')
@section('body-class', 'visitor-page')

@push('styles')
<style>
    :root {
        --brand: #13273F;
        --brand-deep: #0C1B2C;
        --cream: #E9D4C3;
        --cream-soft: #F3E6DA;
        --text-soft: #5b5044;
        --white: #FFFFFF;
    }

    html, body { height: 100%; }
    body {
        margin: 0;
        padding: 0;
        display: block;
        background: var(--brand);
        font-family: 'Work Sans', sans-serif;
        overflow-x: hidden;
    }

    .split-shell { display: flex; min-height: 100vh; width: 100%; }

    .split-visual {
        position: relative;
        flex: 1 1 44%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 56px 48px;
        background:
            linear-gradient(165deg, rgba(7, 14, 24, 0.45), rgba(7, 14, 24, 0.2)),
            url('{{ asset("img/WhatsApp Image 2026-08-05 at 20.17.42.jpeg") }}');
        background-size: cover;
        background-position: center;
        overflow: hidden;
        animation: fadeIn 0.9s ease both;
    }

    .visual-blob { position: absolute; border-radius: 50%; filter: blur(46px); pointer-events: none; animation: floatBlob 9s ease-in-out infinite; }
    .blob-1 { width: 220px; height: 220px; background: rgba(233, 212, 195, 0.32); top: -60px; right: -70px; }
    .blob-2 { width: 170px; height: 170px; background: rgba(255, 255, 255, 0.18); bottom: 60px; left: -50px; animation-delay: 2.4s; }
    .blob-3 { width: 120px; height: 120px; background: rgba(233, 212, 195, 0.22); bottom: -30px; left: 45%; animation-delay: 4.5s; }

    .visual-text {
        position: relative; z-index: 2; color: var(--white);
        font-family: 'Work Sans', sans-serif; font-size: 1.2rem; font-weight: 600; line-height: 1.5;
        max-width: 300px; text-shadow: 0 2px 18px rgba(0, 0, 0, 0.32);
        animation: fadeInUp 0.9s ease 0.15s both;
    }

    .visual-icon-wrap { position: relative; z-index: 2; flex: 1; display: flex; align-items: center; justify-content: center; padding: 24px 0; }

    .visual-book-icon { width: 92px; height: 92px; filter: drop-shadow(0 6px 18px rgba(0, 0, 0, 0.35)); animation: bookFloat 5s ease-in-out infinite; }
    @keyframes bookFloat {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(2deg); }
    }

    .visual-caption {
        position: relative; z-index: 2; color: rgba(255, 255, 255, 0.88);
        font-size: 0.92rem; line-height: 1.65; letter-spacing: 0.02em; max-width: 320px;
        animation: fadeInUp 0.9s ease 0.3s both;
    }

    .split-form-wrap {
        flex: 1 1 56%;
        background: var(--cream);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 56px 40px;
        overflow-y: auto;
    }

    .split-form { width: 100%; max-width: 420px; animation: fadeInUp 0.8s cubic-bezier(.16,1,.3,1) 0.1s both; }

    .split-form .card-eyebrow {
        font-family: 'Work Sans', sans-serif; font-size: 0.72rem; letter-spacing: 0.18em;
        text-transform: uppercase; color: var(--brand); display: flex; align-items: center; gap: 10px;
        margin-bottom: 16px; font-weight: 600;
    }
    .split-form .card-eyebrow .rule { flex: 1; height: 1px; background: rgba(19, 39, 63, 0.24); max-width: 34px; }

    .split-form .card-title { font-family: 'Fraunces', serif; font-weight: 600; font-size: 1.9rem; color: var(--brand); line-height: 1.1; margin-bottom: 6px; }
    .split-form .card-subtitle { font-size: 0.95rem; color: var(--text-soft); margin-bottom: 24px; }

    .split-form .form-group { margin-bottom: 18px; }
    .split-form label { display: block; font-size: 0.84rem; font-weight: 600; color: var(--brand); margin-bottom: 7px; }

    .split-form input[type="text"] {
        width: 100%; box-sizing: border-box; padding: 12px 14px; border-radius: 12px;
        border: 1.5px solid rgba(19, 39, 63, 0.16); background: var(--cream-soft);
        font-size: 0.95rem; color: var(--brand); outline: none; appearance: none;
        transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
    }
    .split-form input::placeholder { color: #a89880; }
    .split-form input:focus {
        border-color: var(--brand); background: var(--white); box-shadow: 0 0 0 4px rgba(19, 39, 63, 0.12);
    }

    .split-form .error-msg { color: #b23b3b; font-size: 0.8rem; margin-top: 5px; min-height: 1em; }

    .split-form .btn {
        width: 100%; padding: 14px 18px; border: none; border-radius: 999px;
        background: var(--brand); color: var(--cream); font-weight: 700; font-size: 0.98rem;
        letter-spacing: 0.01em; cursor: pointer; margin-top: 4px;
        box-shadow: 0 10px 24px -8px rgba(19, 39, 63, 0.55);
        transition: transform 0.15s ease, background 0.2s ease, box-shadow 0.2s ease;
    }
    .split-form .btn:hover { background: var(--brand-deep); transform: translateY(-2px) scale(1.01); box-shadow: 0 14px 28px -8px rgba(19, 39, 63, 0.6); }
    .split-form .btn:active { transform: translateY(0) scale(0.99); }

    .split-form .link-text { margin-top: 20px; text-align: center; font-size: 0.86rem; color: var(--text-soft); }
    .split-form .link-text a { color: var(--brand); font-weight: 700; text-decoration: none; }
    .split-form .link-text a:hover { opacity: 0.8; }

    /* ===== Responsive: stack, photo becomes 3:2 banner, book icon hidden ===== */
    @media (max-width: 840px) {
        .split-shell { flex-direction: column; }

        .split-visual {
            flex: none;
            aspect-ratio: 3 / 2;
            min-height: unset;
            padding: 28px 24px;
            justify-content: flex-end;
            gap: 10px;
        }
        .visual-icon-wrap { display: none; }

        .split-form-wrap {
            flex: none;
            min-height: unset;
            padding: 40px 24px 48px;
        }
    }

    @media (max-width: 560px) {
        .split-visual { padding: 22px 18px; }
        .visual-text { font-size: 1.02rem; max-width: 100%; margin-bottom: 8px; }
        .visual-caption { font-size: 0.82rem; }
        .split-form-wrap { padding: 32px 18px 40px; }
        .split-form .card-title { font-size: 1.55rem; }
        .split-form .card-subtitle { font-size: 0.9rem; }
        .split-form .btn { padding: 13px 16px; }
    }
</style>
@endpush

@section('content')
    <div class="split-shell">
        <div class="split-visual">
            <span class="visual-blob blob-1"></span>
            <span class="visual-blob blob-2"></span>
            <span class="visual-blob blob-3"></span>

            <div class="visual-text">Siap Untuk Membaca Hari Ini?</div>
            <div class="visual-icon-wrap">
                <svg class="visual-book-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M32 12C27 8 18 7 12 8.5V47C18 45.5 27 46.5 32 50" stroke="#FFFFFF" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M32 12C37 8 46 7 52 8.5V47C46 45.5 37 46.5 32 50" stroke="#FFFFFF" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    <line x1="32" y1="12" x2="32" y2="50" stroke="#FFFFFF" stroke-width="2.2" stroke-linecap="round"/>
                    <line x1="17" y1="17" x2="26" y2="16" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round" opacity="0.85"/>
                    <line x1="17" y1="23" x2="26" y2="22" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round" opacity="0.85"/>
                    <line x1="38" y1="16" x2="47" y2="17" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round" opacity="0.85"/>
                    <line x1="38" y1="22" x2="47" y2="23" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round" opacity="0.85"/>
                </svg>
            </div>
            <div class="visual-caption">Ilmu tidak pernah habis bagi mereka yang terus mencari. Lanjutkan eksplorasimu</div>
        </div>

        <div class="split-form-wrap">
            <div class="split-form">
                <div class="card-eyebrow"><span class="rule"></span> Karya Pustaka <span class="rule"></span></div>
                <div class="card-title">Selamat Datang</div>
                <div class="card-subtitle">Masukkan ID pengunjung Anda untuk masuk</div>

                <div class="form-group">
                    <label for="visitor_id">ID Pengunjung</label>
                    <input type="text" id="visitor_id" placeholder="Contoh: faisal0001" autocomplete="off">
                    <div class="error-msg" id="login-error"></div>
                </div>

                <button class="btn" onclick="doLogin()">Masuk Sekarang</button>

                <div class="link-text">
                    Belum punya ID? <a href="{{ route('visitor.register') }}">Daftar di sini</a>
                </div>
            </div>
        </div>
    </div>

    @push('modals')
        <div class="modal-overlay" id="modal-login">
            <div class="modal-box">
                <div class="modal-stamp">✓</div>
                <div class="modal-title">Berhasil Masuk</div>
                <div class="modal-body">
                    Selamat datang, <strong id="modal-name"></strong>.<br>Silakan pilih aktivitas Anda.
                </div>
                <button class="btn" onclick="goToAktivitas()">Lanjut ke Aktivitas</button>
            </div>
        </div>
    @endpush

    @push('scripts')
        <script>
            function doLogin() {
                const visitorId = document.getElementById('visitor_id').value.trim();
                const errorEl = document.getElementById('login-error');
                errorEl.textContent = '';

                if (!visitorId) {
                    errorEl.textContent = 'ID pengunjung tidak boleh kosong.';
                    return;
                }

                fetch('{{ route('visitor.login.post') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ visitor_id: visitorId }),
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('modal-name').textContent = data.name;
                            document.getElementById('modal-login').classList.add('active');
                        } else {
                            errorEl.textContent = data.message || 'ID tidak ditemukan.';
                        }
                    })
                    .catch(() => {
                        errorEl.textContent = 'Terjadi kesalahan. Coba lagi.';
                    });
            }

            function goToAktivitas() {
                window.location.href = '{{ route('visitor.aktivitas') }}';
            }

            document.getElementById('visitor_id').addEventListener('keydown', function(e) {
                if (e.key === 'Enter') doLogin();
            });
        </script>
    @endpush
@endsection