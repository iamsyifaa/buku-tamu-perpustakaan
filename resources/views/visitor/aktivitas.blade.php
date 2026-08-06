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

    body {
        min-height: 100vh;
        margin: 0;
        padding: 32px 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(180deg, #021726 0%, #052c44 45%, var(--brand) 100%);
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

    .stage-blob { position: absolute; border-radius: 50%; filter: blur(60px); pointer-events: none; animation: floatBlob 10s ease-in-out infinite; }
    .stage-blob-1 { width: 260px; height: 260px; background: rgba(233, 212, 195, 0.14); top: -40px; left: -60px; }
    .stage-blob-2 { width: 200px; height: 200px; background: rgba(233, 212, 195, 0.1); bottom: -50px; right: -40px; animation-delay: 3s; }

    .card {
        position: relative;
        z-index: 2;
        width: min(100%, 720px);
        background: var(--cream);
        border-radius: 24px;
        padding: 36px 32px;
        box-shadow: 0 24px 60px -18px rgba(0, 0, 0, 0.4);
        animation: fadeInUp 0.7s cubic-bezier(.16,1,.3,1) both;
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
    .card-title-icon { width: 30px; height: 30px; flex-shrink: 0; }

    .card-subtitle { color: var(--text-soft); margin-bottom: 18px; line-height: 1.6; }

    .aktivitas-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(19, 39, 63, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: background 0.2s ease, transform 0.2s ease;
    }
    .aktivitas-icon-wrap svg { width: 24px; height: 24px; stroke: var(--brand); }

    .aktivitas-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px;
        border: 1.5px solid rgba(19, 39, 63, 0.16);
        border-radius: 14px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: border-color 0.2s ease, background 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease;
        user-select: none;
        background: var(--cream-soft);
    }
    .aktivitas-card:hover {
        border-color: var(--brand);
        background: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
        box-shadow: 0 8px 18px -10px rgba(19, 39, 63, 0.35);
    }
    .aktivitas-card.selected {
        border-color: var(--brand);
        background: rgba(19, 39, 63, 0.08);
        transform: scale(1.01);
    }
    .aktivitas-card.selected .aktivitas-icon-wrap {
        background: var(--brand);
        transform: scale(1.08);
    }
    .aktivitas-card.selected .aktivitas-icon-wrap svg { stroke: var(--cream); }

    .aktivitas-card input[type="checkbox"] { width: 20px; height: 20px; accent-color: var(--brand); flex-shrink: 0; }
    .aktivitas-label { font-size: 1rem; font-weight: 700; color: var(--brand); }
    .aktivitas-desc { font-size: 0.84rem; color: var(--text-soft); margin-top: 2px; }
    .error-msg { color: #c0392b; font-size: 0.88rem; margin: 8px 0 12px; }

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
    .visitor-info svg { width: 18px; height: 18px; stroke: var(--brand); flex-shrink: 0; }

    .btn {
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
    .btn:hover { background: var(--brand-deep); transform: translateY(-2px) scale(1.01); }
    .btn:active { transform: translateY(0) scale(0.99); }

    /* ===== Mobile: fullscreen, flat, no navy peeking through ===== */
    @media (max-width: 760px) {
        body {
            padding: 0;
            background: var(--cream);
            align-items: stretch;
        }
        .aktivitas-stage {
            min-height: 100vh;
            min-height: 100svh;
        }
        .stage-blob { display: none; }
        .card {
            width: 100%;
            min-height: 100vh;
            min-height: 100svh;
            border-radius: 0;
            box-shadow: none;
            padding: 28px 20px 24px;
            display: flex;
            flex-direction: column;
        }
        .card-title { font-size: 1.55rem; }
        .aktivitas-card { padding: 14px; gap: 12px; }
        .aktivitas-icon-wrap { width: 42px; height: 42px; }
        .aktivitas-icon-wrap svg { width: 21px; height: 21px; }
        .aktivitas-card input[type="checkbox"] { width: 18px; height: 18px; }
        .aktivitas-label { font-size: 0.96rem; }
        .aktivitas-desc { font-size: 0.8rem; }
        .visitor-info { padding: 10px 12px; font-size: 0.9rem; }
        .btn { padding: 13px 16px; font-size: 0.95rem; margin-top: auto; }
    }
    @media (max-width: 520px) {
        .card { padding: 22px 16px 20px; }
        .card-title { font-size: 1.4rem; }
        .card-subtitle { font-size: 0.9rem; }
        .aktivitas-card { padding: 12px; gap: 10px; }
        .aktivitas-icon-wrap { width: 38px; height: 38px; }
        .aktivitas-icon-wrap svg { width: 19px; height: 19px; }
        .aktivitas-label { font-size: 0.92rem; }
        .aktivitas-desc { font-size: 0.78rem; }
        .visitor-info { font-size: 0.86rem; }
        .error-msg { font-size: 0.84rem; }
        .btn { padding: 12px 14px; }
    }
</style>
@endpush

@section('content')
<div class="aktivitas-stage">
    <span class="stage-blob stage-blob-1"></span>
    <span class="stage-blob stage-blob-2"></span>

    <div class="card">
    <div class="card-title">
        <svg class="card-title-icon" viewBox="0 0 24 24" fill="none" stroke="#13273F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 6C10 4.5 6.5 4 3.5 4.8V18.8C6.5 18 10 18.5 12 20"/>
            <path d="M12 6C14 4.5 17.5 4 20.5 4.8V18.8C17.5 18 14 18.5 12 20"/>
            <line x1="12" y1="6" x2="12" y2="20"/>
        </svg>
        Perpustakaan Desa
    </div>

    <div class="visitor-info">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="8" r="3.5"/>
            <path d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6"/>
        </svg>
        {{ $visitor->name }} &nbsp;|&nbsp; ID: {{ $visitor->visitor_id }}
    </div>

    <div class="card-subtitle" style="margin-bottom:16px;">Pilih aktivitas hari ini <span style="color:#c0392b">(wajib pilih minimal 1)</span></div>

    <!-- Baca Buku -->
    <div class="aktivitas-card" onclick="toggleCheck('cb-baca')">
        <input type="checkbox" id="cb-baca" value="baca_buku" onclick="event.stopPropagation()">
        <div class="aktivitas-icon-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5.5C10.3 4.2 7.3 3.8 4.8 4.5V17.5C7.3 16.8 10.3 17.2 12 18.5"/>
                <path d="M12 5.5C13.7 4.2 16.7 3.8 19.2 4.5V17.5C16.7 16.8 13.7 17.2 12 18.5"/>
                <line x1="12" y1="5.5" x2="12" y2="18.5"/>
            </svg>
        </div>
        <div>
            <div class="aktivitas-label">Baca Buku</div>
            <div class="aktivitas-desc">Membaca koleksi buku perpustakaan</div>
        </div>
    </div>

    <!-- Pinjam Buku -->
    <div class="aktivitas-card" onclick="toggleCheck('cb-pinjam')">
        <input type="checkbox" id="cb-pinjam" value="pinjam_buku" onclick="event.stopPropagation()">
        <div class="aktivitas-icon-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <rect x="5" y="3.5" width="14" height="17" rx="2"/>
                <path d="M9 3.5h6v2.4a1 1 0 0 1-1 1H10a1 1 0 0 1-1-1V3.5Z"/>
                <line x1="8.2" y1="11" x2="15.8" y2="11"/>
                <line x1="8.2" y1="14.4" x2="15.8" y2="14.4"/>
                <line x1="8.2" y1="17.8" x2="12.5" y2="17.8"/>
            </svg>
        </div>
        <div>
            <div class="aktivitas-label">Pinjam Buku</div>
            <div class="aktivitas-desc">Meminjam buku untuk dibawa pulang</div>
        </div>
    </div>

    <!-- Belajar Komputer -->
    <div class="aktivitas-card" onclick="toggleCheck('cb-komputer')">
        <input type="checkbox" id="cb-komputer" value="belajar_komputer" onclick="event.stopPropagation()">
        <div class="aktivitas-icon-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4.5" width="18" height="12" rx="1.6"/>
                <line x1="8.5" y1="20" x2="15.5" y2="20"/>
                <line x1="12" y1="16.5" x2="12" y2="20"/>
            </svg>
        </div>
        <div>
            <div class="aktivitas-label">Belajar Komputer</div>
            <div class="aktivitas-desc">Menggunakan fasilitas komputer perpustakaan</div>
        </div>
    </div>

    <div class="error-msg" id="err-aktivitas"></div>

    <button class="btn" onclick="simpanAktivitas()">Selesai & Simpan</button>
    </div>
</div>

@push('modals')
<div class="modal-overlay" id="modal-selesai">
    <div class="modal-box">
        <div class="modal-stamp">
            <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 6L9 17l-5-5"/>
            </svg>
        </div>
        <div class="modal-title">Terima Kasih!</div>
        <div class="modal-body">Kunjungan Anda telah tercatat.<br>Sampai jumpa di kunjungan berikutnya!</div>
        <button class="btn" onclick="window.location.href='{{ route('visitor.login') }}'">Selesai</button>
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
    cb.addEventListener('change', function() { updateCardStyle(this); });
});

function updateCardStyle(cb) {
    const card = cb.closest('.aktivitas-card');
    if (cb.checked) {
        card.classList.add('selected');
    } else {
        card.classList.remove('selected');
    }
}

function simpanAktivitas() {
    const checkboxes = document.querySelectorAll('.aktivitas-card input[type="checkbox"]');
    const aktivitas  = [];
    checkboxes.forEach(cb => { if (cb.checked) aktivitas.push(cb.value); });

    const errEl = document.getElementById('err-aktivitas');
    errEl.textContent = '';

    if (aktivitas.length === 0) {
        errEl.textContent = 'Pilih minimal satu aktivitas.';
        return;
    }

    fetch('{{ route("visitor.aktivitas.post") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ aktivitas }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('modal-selesai').classList.add('active');
        } else {
            errEl.textContent = data.message || 'Gagal menyimpan. Coba lagi.';
        }
    })
    .catch(() => {
        errEl.textContent = 'Terjadi kesalahan. Coba lagi.';
    });
}
</script>
@endpush
@endsection