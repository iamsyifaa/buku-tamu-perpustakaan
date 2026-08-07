@extends('layouts.app')

@section('title', 'Pilih Aktivitas - Perpustakaan Desa')
@section('body-class', 'aktivitas-stage')

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

        html {
            min-height: 100%;
            background: var(--brand);
        }

        body {
            min-height: 100vh;
            min-height: 100dvh;
            margin: 0;
            padding: 32px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(180deg, #021726 0%, #052c44 55%, var(--brand) 100%);
            font-family: 'Work Sans', sans-serif;
        }

        .aktivitas-stage {
            position: relative;
            width: 100%;
            min-height: calc(100vh - 64px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            overflow: hidden;
        }

        .stage-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            pointer-events: none;
            animation: floatBlob 10s ease-in-out infinite;
        }

        .stage-blob-1 {
            width: 260px;
            height: 260px;
            background: rgba(233, 212, 195, 0.14);
            top: -40px;
            left: -60px;
        }

        .stage-blob-2 {
            width: 200px;
            height: 200px;
            background: rgba(233, 212, 195, 0.1);
            bottom: -50px;
            right: -40px;
            animation-delay: 3s;
        }

        .stage-blob-3 {
            width: 150px;
            height: 150px;
            background: rgba(168, 130, 58, 0.12);
            top: 30%;
            right: 8%;
            animation-delay: 5.5s;
        }

        .card {
            position: relative;
            z-index: 2;
            width: min(100%, 720px);
            background: var(--cream);
            border-radius: 24px;
            padding: 36px 32px;
            box-shadow: 0 24px 60px -18px rgba(0, 0, 0, 0.4);
            animation: fadeInUp 0.7s cubic-bezier(.16, 1, .3, 1) both;
        }

        .card-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Fraunces', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--brand);
            margin-bottom: 12px;
        }

        .card-title-icon {
            width: 30px;
            height: 30px;
            flex-shrink: 0;
            animation: bookFloat 4s ease-in-out infinite;
        }

        @keyframes bookFloat {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-4px) rotate(-4deg);
            }
        }

        .card-subtitle {
            color: var(--text-soft);
            margin-bottom: 6px;
            line-height: 1.6;
        }

        .selected-counter {
            display: inline-block;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: var(--brand);
            background: rgba(19, 39, 63, 0.08);
            border-radius: 999px;
            padding: 4px 12px;
            margin-bottom: 16px;
            transition: transform 0.25s cubic-bezier(.34, 1.56, .64, 1), background 0.25s ease;
        }

        .selected-counter.bump {
            animation: counterBump 0.35s ease;
        }

        @keyframes counterBump {
            0% {
                transform: scale(1);
            }

            40% {
                transform: scale(1.12);
                background: rgba(19, 39, 63, 0.16);
            }

            100% {
                transform: scale(1);
            }
        }

        .aktivitas-icon-wrap {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(19, 39, 63, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background 0.25s ease, transform 0.25s ease;
        }

        .aktivitas-icon-wrap svg {
            width: 24px;
            height: 24px;
            stroke: var(--brand);
            transition: stroke 0.25s ease;
        }

        .aktivitas-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px;
            border: 1.5px solid rgba(19, 39, 63, 0.16);
            border-radius: 14px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: border-color 0.2s ease, background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
            user-select: none;
            background: var(--cream-soft);
            opacity: 0;
            animation: fadeInUp 0.55s cubic-bezier(.16, 1, .3, 1) both;
        }

        .aktivitas-card:hover {
            border-color: var(--brand);
            background: rgba(255, 255, 255, 0.5);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px -10px rgba(19, 39, 63, 0.4);
        }

        .aktivitas-card:hover .aktivitas-icon-wrap {
            transform: rotate(-6deg) scale(1.05);
        }

        .aktivitas-card.selected {
            border-color: var(--brand);
            background: rgba(19, 39, 63, 0.08);
            transform: scale(1.015);
            box-shadow: 0 8px 18px -12px rgba(19, 39, 63, 0.35);
        }

        .aktivitas-card.selected .aktivitas-icon-wrap {
            background: var(--brand);
            animation: iconPop 0.4s cubic-bezier(.34, 1.56, .64, 1);
        }

        .aktivitas-card.selected .aktivitas-icon-wrap svg {
            stroke: var(--cream);
        }

        @keyframes iconPop {
            0% {
                transform: scale(1) rotate(0deg);
            }

            45% {
                transform: scale(1.28) rotate(-10deg);
            }

            100% {
                transform: scale(1.06) rotate(0deg);
            }
        }

        .aktivitas-card input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: var(--brand);
            flex-shrink: 0;
        }

        .aktivitas-label {
            font-size: 1rem;
            font-weight: 700;
            color: var(--brand);
        }

        .aktivitas-desc {
            font-size: 0.84rem;
            color: var(--text-soft);
            margin-top: 2px;
        }

        .error-msg {
            color: #c0392b;
            font-size: 0.88rem;
            margin: 8px 0 12px;
        }

        .visitor-info {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(19, 39, 63, 0.08);
            border-left: 4px solid var(--brand);
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 18px;
            font-size: 0.95rem;
            color: var(--brand);
            font-weight: 600;
        }

        .visitor-info svg {
            width: 18px;
            height: 18px;
            stroke: var(--brand);
            flex-shrink: 0;
        }

        .btn {
            position: relative;
            overflow: hidden;
            width: 100%;
            background: var(--brand);
            color: var(--cream);
            border: none;
            border-radius: 999px;
            padding: 14px 16px;
            font-size: 0.98rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 24px -10px rgba(19, 39, 63, 0.55);
            transition: background 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease;
        }

        .btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -60%;
            width: 40%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.25), transparent);
            transform: skewX(-20deg);
            transition: left 0.6s ease;
        }

        .btn:hover::after {
            left: 130%;
        }

        .btn:hover {
            background: var(--brand-deep);
            transform: translateY(-2px) scale(1.01);
        }

        .btn:active {
            transform: translateY(0) scale(0.99);
        }

        .btn:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn:disabled:hover {
            background: var(--brand);
            transform: none;
        }

        .btn:disabled:hover::after {
            left: -60%;
        }

        @media (max-width: 760px) {
            html {
                background: var(--cream-soft);
            }

            body {
                padding: 16px 12px;
                background: var(--cream-soft);
                align-items: flex-start;
            }

            .stage-blob {
                display: none;
            }

            .aktivitas-stage {
                min-height: 100vh;
                min-height: 100dvh;
            }

            .card {
                display: flex;
                flex-direction: column;
                border-radius: 18px;
                box-shadow: 0 6px 20px -10px rgba(19, 39, 63, 0.25);
                min-height: 100vh;
                min-height: 100dvh;
                width: 100%;
                max-width: 100%;
                margin: 0 auto;
            }

            .card {
                padding: 25px 14px 20px;
            }

            .card-title {
                font-size: 1.40rem;
                margin-bottom: 10px;
            }

            .card-title-icon {
                width: 28px;
                height: 28px;
            }

            .card-subtitle {
                font-size: 0.86rem;
                margin-bottom: 5px;
            }

            .selected-counter {
                font-size: 0.78rem;
                padding: 8px 12px;
                margin-bottom: 15px;
            }

            .visitor-info {
                padding: 12px 15px;
                font-size: 0.80rem;
                margin-bottom: 12px;
            }

            .visitor-info svg {
                width: 20px;
                height: 20px;
            }

            .aktivitas-card {
                flex-direction: row;
                align-items: center;
                gap: 25px;
                padding: 35px;
                margin-bottom: 18px;
            }

            .aktivitas-card input[type="checkbox"] {
                width: 20px;
                height: 20px;
            }

            .aktivitas-icon-wrap {
                width: 42px;
                height: 42px;
                border-radius: 11px;
            }

            .aktivitas-icon-wrap svg {
                width: 21px;
                height: 21px;
            }

            .aktivitas-label {
                font-size: 0.98rem;
            }

            .aktivitas-desc {
                font-size: 0.78rem;
                margin-top: 2px;
            }

            .error-msg {
                margin: 3px 0 6px;
                font-size: 0.74rem;
            }

            .btn {
                margin-top: auto;
                padding: 10px 14px;
                font-size: 0.84rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="aktivitas-stage">
        <span class="stage-blob stage-blob-1"></span>
        <span class="stage-blob stage-blob-2"></span>
        <span class="stage-blob stage-blob-3"></span>

        <div class="card">
            <div class="card-title">
                <svg class="card-title-icon" viewBox="0 0 24 24" fill="none" stroke="#13273F" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 6C10 4.5 6.5 4 3.5 4.8V18.8C6.5 18 10 18.5 12 20" />
                    <path d="M12 6C14 4.5 17.5 4 20.5 4.8V18.8C17.5 18 14 18.5 12 20" />
                    <line x1="12" y1="6" x2="12" y2="20" />
                </svg>
                Perpustakaan Desa
            </div>

            <div class="visitor-info">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="3.5" />
                    <path d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6" />
                </svg>
                {{ $visitor->name }} &nbsp;|&nbsp; ID: {{ $visitor->visitor_id }}
            </div>

            <div class="card-subtitle">Pilih aktivitas hari ini <span style="color:#c0392b">(wajib pilih minimal 1)</span>
            </div>
            <div class="selected-counter" id="selected-counter">0 aktivitas dipilih</div>

            <!-- Baca Buku -->
            <div class="aktivitas-card" style="animation-delay:0.08s" onclick="toggleCheck('cb-baca')">
                <input type="checkbox" id="cb-baca" value="baca_buku" onclick="event.stopPropagation()">
                <div class="aktivitas-icon-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M12 5.5C10.3 4.2 7.3 3.8 4.8 4.5V17.5C7.3 16.8 10.3 17.2 12 18.5" />
                        <path d="M12 5.5C13.7 4.2 16.7 3.8 19.2 4.5V17.5C16.7 16.8 13.7 17.2 12 18.5" />
                        <line x1="12" y1="5.5" x2="12" y2="18.5" />
                    </svg>
                </div>
                <div>
                    <div class="aktivitas-label">Baca Buku</div>
                    <div class="aktivitas-desc">Membaca koleksi buku perpustakaan</div>
                </div>
            </div>

            <!-- Pinjam Buku -->
            <div class="aktivitas-card" style="animation-delay:0.18s" onclick="toggleCheck('cb-pinjam')">
                <input type="checkbox" id="cb-pinjam" value="pinjam_buku" onclick="event.stopPropagation()">
                <div class="aktivitas-icon-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="5" y="3.5" width="14" height="17" rx="2" />
                        <path d="M9 3.5h6v2.4a1 1 0 0 1-1 1H10a1 1 0 0 1-1-1V3.5Z" />
                        <line x1="8.2" y1="11" x2="15.8" y2="11" />
                        <line x1="8.2" y1="14.4" x2="15.8" y2="14.4" />
                        <line x1="8.2" y1="17.8" x2="12.5" y2="17.8" />
                    </svg>
                </div>
                <div>
                    <div class="aktivitas-label">Pinjam Buku</div>
                    <div class="aktivitas-desc">Meminjam buku untuk dibawa pulang</div>
                </div>
            </div>

            <!-- Belajar Komputer -->
            <div class="aktivitas-card" style="animation-delay:0.28s" onclick="toggleCheck('cb-komputer')">
                <input type="checkbox" id="cb-komputer" value="belajar_komputer" onclick="event.stopPropagation()">
                <div class="aktivitas-icon-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="3" y="4.5" width="18" height="12" rx="1.6" />
                        <line x1="8.5" y1="20" x2="15.5" y2="20" />
                        <line x1="12" y1="16.5" x2="12" y2="20" />
                    </svg>
                </div>
                <div>
                    <div class="aktivitas-label">Belajar Komputer</div>
                    <div class="aktivitas-desc">Menggunakan fasilitas komputer perpustakaan</div>
                </div>
            </div>

            <div class="error-msg" id="err-aktivitas"></div>

            <button class="btn" id="btn-simpan" type="button" onclick="simpanAktivitas()">Selesai & Simpan</button>
        </div>
    </div>

    @push('modals')
        <div class="modal-overlay" id="modal-selesai">
            <div class="modal-box">
                <div class="modal-stamp">
                    <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="#FFFFFF"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6L9 17l-5-5" />
                    </svg>
                </div>
                <div class="modal-title">Terima Kasih!</div>
                <div class="modal-body">Kunjungan Anda telah tercatat.<br>Sampai jumpa di kunjungan berikutnya!</div>
                <button class="btn" id="btn-selesai" type="button" onclick="goSelesai()">Selesai</button>
            </div>
        </div>
    @endpush

    @push('scripts')
        <script>
            function toggleCheck(id) {
                const cb = document.getElementById(id);
                cb.checked = !cb.checked;
                updateCardStyle(cb);
            }

            document.querySelectorAll('.aktivitas-card input[type="checkbox"]').forEach(cb => {
                cb.addEventListener('change', function() {
                    updateCardStyle(this);
                });
            });

            function updateCardStyle(cb) {
                const card = cb.closest('.aktivitas-card');
                if (cb.checked) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
                updateCounter();
            }

            function updateCounter() {
                const total = document.querySelectorAll('.aktivitas-card input[type="checkbox"]:checked').length;
                const el = document.getElementById('selected-counter');
                el.textContent = total + ' aktivitas dipilih';
                el.classList.remove('bump');
                void el.offsetWidth;
                el.classList.add('bump');
            }

            function goSelesai() {
                const btn = document.getElementById('btn-selesai');
                if (btn && btn.disabled) return;
                if (btn) {
                    btn.disabled = true;
                    btn.textContent = 'Mengalihkan...';
                }
                window.location.href = '{{ route('visitor.login') }}';
            }

            function simpanAktivitas() {
                const btn = document.getElementById('btn-simpan');
                if (btn.disabled) return;

                const checkboxes = document.querySelectorAll('.aktivitas-card input[type="checkbox"]');
                const aktivitas = [];
                checkboxes.forEach(cb => {
                    if (cb.checked) aktivitas.push(cb.value);
                });

                const errEl = document.getElementById('err-aktivitas');
                errEl.textContent = '';

                if (aktivitas.length === 0) {
                    errEl.textContent = 'Pilih minimal satu aktivitas.';
                    return;
                }

                const csrf = document.querySelector('meta[name="csrf-token"]');
                if (!csrf) {
                    errEl.textContent = 'CSRF token tidak ditemukan. Refresh halaman.';
                    return;
                }

                btn.disabled = true;
                btn.textContent = 'Menyimpan...';

                fetch('{{ route('visitor.aktivitas.post') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf.content,
                        },
                        body: JSON.stringify({
                            aktivitas
                        }),
                    })
                    .then(async (res) => {
                        let data = {};
                        try {
                            data = await res.json();
                        } catch (e) {
                            errEl.textContent = 'Server error (' + res.status + '). Coba lagi.';
                            btn.disabled = false;
                            btn.textContent = 'Selesai & Simpan';
                            return;
                        }

                        if (!res.ok) {
                            errEl.textContent = data.message ||
                                (data.errors ? Object.values(data.errors).flat().join(' ') : null) ||
                                ('Gagal: HTTP ' + res.status);
                            btn.disabled = false;
                            btn.textContent = 'Selesai & Simpan';
                            return;
                        }

                        if (data.success) {
                            document.getElementById('modal-selesai').classList.add('active');
                        } else {
                            errEl.textContent = data.message || 'Gagal menyimpan. Coba lagi.';
                            btn.disabled = false;
                            btn.textContent = 'Selesai & Simpan';
                        }
                    })
                    .catch((err) => {
                        console.error(err);
                        errEl.textContent = 'Tidak bisa terhubung ke server. Coba lagi.';
                        btn.disabled = false;
                        btn.textContent = 'Selesai & Simpan';
                    });
            }
        </script>
    @endpush
@endsection
