@extends('layouts.app')

@section('title', 'Daftar - Perpustakaan Desa')
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

        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            display: block;
            background: var(--brand);
            font-family: 'Work Sans', sans-serif;
            overflow-x: hidden;
        }

        .split-shell {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        /* ---- Left: photo panel ---- */
        .split-visual {
    position: relative;
    flex: 1 1 42%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 44px 44px;
    background:
        linear-gradient(165deg, rgba(7, 14, 24, 0.45), rgba(7, 14, 24, 0.2)),
        url('https://dfowwwybmcqsrirzlhwb.supabase.co/storage/v1/object/public/assets/perpus.jpeg');
    background-size: cover;
    background-position: center;
    overflow: hidden;
    animation: fadeInUp 0.9s cubic-bezier(.16, 1, .3, 1) both;
}

        .visual-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(46px);
            pointer-events: none;
            animation: floatBlob 9s ease-in-out infinite;
        }

        .blob-1 {
            width: 220px;
            height: 220px;
            background: rgba(233, 212, 195, 0.32);
            top: -60px;
            left: -70px;
        }

        .blob-2 {
            width: 170px;
            height: 170px;
            background: rgba(255, 255, 255, 0.18);
            bottom: 60px;
            right: -50px;
            animation-delay: 2.4s;
        }

        .blob-3 {
            width: 120px;
            height: 120px;
            background: rgba(233, 212, 195, 0.22);
            bottom: -30px;
            left: 40%;
            animation-delay: 4.5s;
        }

        .visual-text {
            position: relative;
            z-index: 2;
            color: var(--white);
            font-family: 'Work Sans', sans-serif;
            font-size: 1.15rem;
            font-weight: 600;
            line-height: 1.5;
            max-width: 300px;
            text-shadow: 0 2px 18px rgba(0, 0, 0, 0.32);
            animation: fadeInUp 0.9s ease 0.15s both;
        }

        .visual-icon-wrap {
            position: relative;
            z-index: 2;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }

        .visual-book-icon {
            width: 82px;
            height: 82px;
            filter: drop-shadow(0 6px 18px rgba(0, 0, 0, 0.35));
            animation: bookFloat 5s ease-in-out infinite;
        }

        @keyframes bookFloat {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-10px) rotate(-2deg);
            }
        }

        .visual-caption {
            position: relative;
            z-index: 2;
            color: rgba(255, 255, 255, 0.88);
            font-size: 0.88rem;
            line-height: 1.6;
            letter-spacing: 0.02em;
            max-width: 320px;
            animation: fadeInUp 0.9s ease 0.3s both;
        }

        /* ---- Right: form panel ---- */
        .split-form-wrap {
            flex: 1 1 58%;
            background: var(--cream);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 40px;
            overflow-y: auto;
        }

        .split-form {
            width: 100%;
            max-width: 430px;
            animation: fadeInUp 0.8s cubic-bezier(.16, 1, .3, 1) 0.1s both;
        }

        .split-form .card-eyebrow {
            font-family: 'Work Sans', sans-serif;
            font-size: 0.7rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--brand);
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .split-form .card-eyebrow .rule {
            flex: 1;
            height: 1px;
            background: rgba(19, 39, 63, 0.24);
            max-width: 34px;
        }

        .split-form .card-title {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 1.55rem;
            color: var(--brand);
            line-height: 1.1;
            margin-bottom: 4px;
        }

        .split-form .card-subtitle {
            font-size: 0.86rem;
            color: var(--text-soft);
            margin-bottom: 14px;
        }

        .split-form .form-group {
            margin-bottom: 11px;
        }

        .split-form label {
            display: block;
            font-size: 0.79rem;
            font-weight: 600;
            color: var(--brand);
            margin-bottom: 5px;
        }

        .split-form label .req {
            color: #b23b3b;
        }

        .split-form input[type="text"],
        .split-form input[type="number"],
        .split-form select {
            width: 100%;
            box-sizing: border-box;
            padding: 10px 13px;
            border-radius: 11px;
            border: 1.5px solid rgba(19, 39, 63, 0.16);
            background: var(--cream-soft);
            font-size: 0.92rem;
            color: var(--brand);
            outline: none;
            appearance: none;
            transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
        }

        .split-form select {
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='9' viewBox='0 0 14 9'><path d='M1 1L7 7L13 1' stroke='%2313273F' stroke-width='1.6' fill='none' stroke-linecap='round' stroke-linejoin='round'/></svg>");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 34px;
        }

        .split-form input::placeholder {
            color: #a89880;
        }

        .split-form input:focus,
        .split-form select:focus {
            border-color: var(--brand);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(19, 39, 63, 0.12);
        }

        .split-form .error-msg {
            color: #b23b3b;
            font-size: 0.76rem;
            margin-top: 3px;
            min-height: 1em;
        }

        .section-divider {
            margin: 14px 0 10px !important;
        }

        .split-form .btn {
            width: 100%;
            padding: 12px 18px;
            border: none;
            border-radius: 999px;
            background: var(--brand);
            color: var(--cream);
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.01em;
            cursor: pointer;
            margin-top: 4px;
            box-shadow: 0 10px 24px -8px rgba(19, 39, 63, 0.55);
            transition: transform 0.15s ease, background 0.2s ease, box-shadow 0.2s ease;
        }

        .split-form .btn:hover {
            background: var(--brand-deep);
            transform: translateY(-2px) scale(1.01);
            box-shadow: 0 14px 28px -8px rgba(19, 39, 63, 0.6);
        }

        .split-form .btn:active {
            transform: translateY(0) scale(0.99);
        }

        .split-form .btn:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .split-form .btn:disabled:hover {
            background: var(--brand);
            transform: none;
        }

        .split-form .link-text {
            margin-top: 12px;
            text-align: center;
            font-size: 0.82rem;
            color: var(--text-soft);
        }

        .split-form .link-text a {
            color: var(--brand);
            font-weight: 700;
            text-decoration: none;
        }

        .split-form .link-text a:hover {
            opacity: 0.8;
        }

        .modal-box .modal-id-card {
            background: var(--brand);
            border-radius: 14px;
            padding: 16px 18px;
            margin: 16px 0;
        }

        .modal-box .modal-id-label {
            color: var(--cream);
            font-size: 0.72rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .modal-box .modal-id {
            color: var(--white);
            font-weight: 700;
            font-size: 1.3rem;
        }

        .modal-id-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.btn-copy-id {
    display: flex;
    align-items: center;
    gap: 5px;
    background: rgba(255, 255, 255, 0.14);
    color: var(--cream);
    border: 1px solid rgba(255, 255, 255, 0.28);
    border-radius: 999px;
    padding: 5px 11px;
    font-size: 0.72rem;
    font-weight: 700;
    cursor: pointer;
    flex-shrink: 0;
    transition: background 0.15s ease, transform 0.15s ease;
}
.btn-copy-id:hover { background: rgba(255, 255, 255, 0.24); transform: translateY(-1px); }
.btn-copy-id:active { transform: translateY(0); }

        /* Responsive */
        @media (max-width: 840px) {
    .split-shell {
        flex-direction: column;
        height: auto;
        min-height: 100vh;
        min-height: 100dvh;
    }

    .split-visual {
        flex: none;
        aspect-ratio: 16 / 9;
        padding: 22px 22px;
        justify-content: space-between;
        gap: 8px;
    }

    .visual-icon-wrap {
        display: none;
    }

    .split-form-wrap {
        flex: 1;
        padding: 32px 24px 44px;
        overflow-y: visible;
    }
}

        @media (max-width: 560px) {
            .split-visual {
                padding: 18px 16px;
            }

            .visual-text {
                font-size: 1rem;
                max-width: 100%;
                margin-bottom: 6px;
            }

            .visual-caption {
                font-size: 0.8rem;
            }

            .split-form-wrap {
                padding: 28px 18px 40px;
            }

            .split-form .card-title {
                font-size: 1.5rem;
            }

            .split-form .card-subtitle {
                font-size: 0.87rem;
            }

            .split-form .btn {
                padding: 13px 16px;
            }

            .form-row {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="split-shell">
        <!-- Left: visual panel -->
        <div class="split-visual">
            <span class="visual-blob blob-1"></span>
            <span class="visual-blob blob-2"></span>
            <span class="visual-blob blob-3"></span>

            <div class="visual-text" id="visual-text-typed">Waktunya belajar dan membaca</div>
            <div class="visual-icon-wrap">
                <svg class="visual-book-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M32 12C27 8 18 7 12 8.5V47C18 45.5 27 46.5 32 50" stroke="#FFFFFF" stroke-width="2.2"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M32 12C37 8 46 7 52 8.5V47C46 45.5 37 46.5 32 50" stroke="#FFFFFF" stroke-width="2.2"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <line x1="32" y1="12" x2="32" y2="50" stroke="#FFFFFF" stroke-width="2.2"
                        stroke-linecap="round" />
                    <line x1="17" y1="17" x2="26" y2="16" stroke="#FFFFFF" stroke-width="1.6"
                        stroke-linecap="round" opacity="0.85" />
                    <line x1="17" y1="23" x2="26" y2="22" stroke="#FFFFFF" stroke-width="1.6"
                        stroke-linecap="round" opacity="0.85" />
                    <line x1="38" y1="16" x2="47" y2="17" stroke="#FFFFFF" stroke-width="1.6"
                        stroke-linecap="round" opacity="0.85" />
                    <line x1="38" y1="22" x2="47" y2="23" stroke="#FFFFFF" stroke-width="1.6"
                        stroke-linecap="round" opacity="0.85" />
                </svg>
            </div>
            <div class="visual-caption" id="visual-caption-typed">Jadilah bagian dari generasi cerdas Desa Karyamukti. Setiap buku adalah petualangan baru</div>
        </div>

        <!-- Right: form panel -->
        <div class="split-form-wrap">
            <div class="split-form">
                <div class="card-eyebrow"><span class="rule"></span> Karya Pustaka <span class="rule"></span></div>
                <div class="card-title">Daftar Pengunjung</div>
                <div class="card-subtitle">Isi data diri Anda untuk mendapatkan ID pengunjung</div>

                <div class="form-group">
                    <label for="name">Nama Lengkap <span class="req">*</span></label>
                    <input type="text" id="name" placeholder="Contoh: Budi Santoso">
                    <div class="error-msg" id="err-name"></div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="umur">Umur <span class="req">*</span></label>
                        <input type="number" id="umur" placeholder="17" min="1" max="120">
                        <div class="error-msg" id="err-umur"></div>
                    </div>

                    <div class="form-group">
                        <label for="desa">Desa <span class="req">*</span></label>
                        <select id="desa">
                            <option value="">Pilih desa</option>
                            <option value="Karyamukti">Karyamukti</option>
                            <option value="Desa Lain">Desa Lain</option>
                        </select>
                        <div class="error-msg" id="err-desa"></div>
                    </div>
                </div>

                <div class="section-divider"><span class="line"></span><span class="label">Alamat</span><span
                        class="line"></span></div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="rw">RW <span class="req">*</span></label>
                        <select id="rw">
                            <option value="">Pilih RW</option>
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">RW
                                    {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                </option>
                            @endfor
                        </select>
                        <div class="error-msg" id="err-rw"></div>
                    </div>
                    <div class="form-group">
                        <label for="rt">RT <span class="req">*</span></label>
                        <select id="rt">
                            <option value="">Pilih RT</option>
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">RT
                                    {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                </option>
                            @endfor
                        </select>
                        <div class="error-msg" id="err-rt"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat Lengkap</label>
                    <input type="text" id="alamat" placeholder="Contoh: Kp. Cikaret No. 12">
                </div>

                <button class="btn" id="btn-register" type="button" onclick="doRegister()">Daftar</button>

                <div class="link-text">
                    Sudah punya ID? <a href="{{ route('visitor.login') }}">Masuk di sini</a>
                </div>
            </div>
        </div>
    </div>

    @push('modals')
        <div class="modal-overlay" id="modal-register">
            <div class="modal-box">
                <div class="modal-stamp">✓</div>
                <div class="modal-title">Pendaftaran Berhasil</div>
                <div class="modal-body">
                    Selamat datang, <strong id="modal-reg-name"></strong>.
                </div>
                <div class="modal-id-card">
    <div class="modal-id-label">ID Pengunjung Anda</div>
    <div class="modal-id-row">
        <div class="modal-id" id="modal-reg-id"></div>
        <button type="button" class="btn-copy-id" onclick="copyVisitorId()">
            <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="9" y="9" width="13" height="13" rx="2"></rect>
                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
            </svg>
            <span id="copy-label">Salin</span>
        </button>
    </div>
</div>
                <div class="modal-hint" style="margin-bottom:18px;">Simpan ID ini untuk masuk di kunjungan berikutnya.</div>
                <button class="btn" id="btn-go-login" type="button" onclick="goToLogin()">Lanjut ke Halaman
                    Masuk</button>
            </div>
        </div>
    @endpush

    @push('scripts')
        <script>
            function doRegister() {
                const btn = document.getElementById('btn-register');

                if (btn.disabled) return;

                const name = document.getElementById('name').value.trim();
                const umur = document.getElementById('umur').value.trim();
                const desa = document.getElementById('desa').value;
                const rw = document.getElementById('rw').value;
                const rt = document.getElementById('rt').value;
                const alamat = document.getElementById('alamat').value.trim();

                ['name', 'umur', 'desa', 'rw', 'rt'].forEach(id => {
                    document.getElementById('err-' + id).textContent = '';
                });

                let valid = true;
                if (!name) {
                    document.getElementById('err-name').textContent = 'Nama wajib diisi.';
                    valid = false;
                }
                if (!umur) {
                    document.getElementById('err-umur').textContent = 'Umur wajib diisi.';
                    valid = false;
                }
                if (!desa) {
                    document.getElementById('err-desa').textContent = 'Desa wajib dipilih.';
                    valid = false;
                }
                if (!rw) {
                    document.getElementById('err-rw').textContent = 'RW wajib dipilih.';
                    valid = false;
                }
                if (!rt) {
                    document.getElementById('err-rt').textContent = 'RT wajib dipilih.';
                    valid = false;
                }
                if (!valid) return;

                btn.disabled = true;
                btn.textContent = 'Mendaftar...';

                fetch('{{ route('visitor.register.post') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            name,
                            umur: parseInt(umur),
                            desa,
                            rw,
                            rt,
                            alamat
                        }),
                    })
                    .then(async (res) => {
                        let data = {};
                        try {
                            data = await res.json();
                        } catch (e) {}

                        if (!res.ok) {
                            const msg = data.message ||
                                (data.errors ? Object.values(data.errors).flat().join(' ') : null) ||
                                'Pendaftaran gagal. Coba lagi.';
                            document.getElementById('err-name').textContent = msg;
                            btn.disabled = false;
                            btn.textContent = 'Daftar';
                            return;
                        }

                        if (data.success) {
                            document.getElementById('modal-reg-name').textContent = data.name;
                            document.getElementById('modal-reg-id').textContent = data.visitor_id;
                            document.getElementById('modal-register').classList.add('active');
                        } else {
                            document.getElementById('err-name').textContent = data.message ||
                                'Pendaftaran gagal. Coba lagi.';
                            btn.disabled = false;
                            btn.textContent = 'Daftar';
                        }
                    })
                    .catch(() => {
                        document.getElementById('err-name').textContent = 'Terjadi kesalahan. Coba lagi.';
                        btn.disabled = false;
                        btn.textContent = 'Daftar';
                    });
            }

            function goToLogin() {
                const btn = document.getElementById('btn-go-login');
                if (btn.disabled) return;
                btn.disabled = true;
                btn.textContent = 'Mengalihkan...';
                window.location.href = '{{ route('visitor.login') }}';
            }

            function copyVisitorId() {
    const text = document.getElementById('modal-reg-id').textContent.trim();
    const label = document.getElementById('copy-label');
    navigator.clipboard.writeText(text).then(() => {
        const original = label.textContent;
        label.textContent = 'Tersalin!';
        setTimeout(() => { label.textContent = original; }, 1500);
    }).catch(() => {
        alert('Gagal menyalin otomatis. ID: ' + text);
    });
}

     document.addEventListener('DOMContentLoaded', function () {
    const titleEl = document.getElementById('visual-text-typed');
    const captionEl = document.getElementById('visual-caption-typed');
    if (!titleEl) return;

    const titleText = titleEl.textContent;
    const captionText = captionEl ? captionEl.textContent : '';
    titleEl.textContent = '';
    if (captionEl) captionEl.textContent = '';

    function typeText(el, text, speed, onDone) {
        let i = 0;
        (function type() {
            if (i < text.length) {
                el.textContent += text.charAt(i);
                i++;
                setTimeout(type, speed);
            } else if (onDone) {
                onDone();
            }
        })();
    }

    function startTyping() {
        typeText(titleEl, titleText, 45, function () {
            if (captionEl) {
                setTimeout(function () {
                    typeText(captionEl, captionText, 25);
                }, 200);
            }
        });
    }

    titleEl.addEventListener('animationend', startTyping, { once: true });
});
        </script>
    @endpush
@endsection
