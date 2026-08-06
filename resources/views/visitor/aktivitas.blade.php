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
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    .card {
        width: min(100%, 720px);
        background: var(--cream);
        border-radius: 24px;
        padding: 36px 32px;
        box-shadow: 0 24px 60px -18px rgba(0, 0, 0, 0.4);
    }

    .card-title {
        font-family: 'Fraunces', serif;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--brand);
        margin-bottom: 12px;
    }

    .card-subtitle {
        color: var(--text-soft);
        margin-bottom: 18px;
        line-height: 1.6;
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
        transition: border-color 0.2s, background 0.2s;
        user-select: none;
        background: var(--cream-soft);
    }
    .aktivitas-card:hover { border-color: var(--brand); background: rgba(255,255,255,0.42); }
    .aktivitas-card.selected { border-color: var(--brand); background: rgba(19, 39, 63, 0.08); }
    .aktivitas-card input[type="checkbox"] { width: 20px; height: 20px; accent-color: var(--brand); flex-shrink: 0; }
    .aktivitas-icon { font-size: 1.8rem; }
    .aktivitas-label { font-size: 1rem; font-weight: 700; color: var(--brand); }
    .aktivitas-desc { font-size: 0.84rem; color: var(--text-soft); margin-top: 2px; }
    .error-msg { color: #c0392b; font-size: 0.88rem; margin: 8px 0 12px; }
    .visitor-info {
        background: rgba(19, 39, 63, 0.08);
        border-left: 4px solid var(--brand);
        border-radius: 10px;
        padding: 10px 14px;
        margin-bottom: 18px;
        font-size: 0.95rem;
        color: var(--brand);
        font-weight: 600;
    }

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
    }
    .btn:hover { background: var(--brand-deep); }

    @media (max-width: 760px) {
        body { padding: 20px 12px; }
        .card { padding: 28px 20px 24px; }
        .card-title { font-size: 1.55rem; }
        .aktivitas-card { flex-direction: column; align-items: flex-start; gap: 12px; padding: 14px; }
        .aktivitas-card input[type="checkbox"] { width: 18px; height: 18px; }
        .aktivitas-icon { font-size: 1.6rem; }
        .aktivitas-label { font-size: 0.96rem; }
        .aktivitas-desc { font-size: 0.8rem; }
        .visitor-info { padding: 10px 12px; font-size: 0.9rem; }
        .btn { padding: 13px 16px; font-size: 0.95rem; }
    }
    @media (max-width: 520px) {
        body { padding: 14px 10px; }
        .card { padding: 22px 16px 20px; }
        .card-title { font-size: 1.4rem; }
        .card-subtitle { font-size: 0.9rem; }
        .aktivitas-card { padding: 12px; gap: 10px; }
        .aktivitas-icon { font-size: 1.4rem; }
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
    <div class="card">
    <div class="card-title">📚 Perpustakaan Desa</div>

    <div class="visitor-info">
        👤 {{ $visitor->name }} &nbsp;|&nbsp; ID: {{ $visitor->visitor_id }}
    </div>

    <div class="card-subtitle" style="margin-bottom:16px;">Pilih aktivitas hari ini <span style="color:#c0392b">(wajib pilih minimal 1)</span></div>

    <!-- Checkbox aktivitas -->
    <div class="aktivitas-card" onclick="toggleCheck('cb-baca')">
        <input type="checkbox" id="cb-baca" value="baca_buku" onclick="event.stopPropagation()">
        <div class="aktivitas-icon">📖</div>
        <div>
            <div class="aktivitas-label">Baca Buku</div>
            <div class="aktivitas-desc">Membaca koleksi buku perpustakaan</div>
        </div>
    </div>

    <div class="aktivitas-card" onclick="toggleCheck('cb-pinjam')">
        <input type="checkbox" id="cb-pinjam" value="pinjam_buku" onclick="event.stopPropagation()">
        <div class="aktivitas-icon">📋</div>
        <div>
            <div class="aktivitas-label">Pinjam Buku</div>
            <div class="aktivitas-desc">Meminjam buku untuk dibawa pulang</div>
        </div>
    </div>

    <div class="aktivitas-card" onclick="toggleCheck('cb-komputer')">
        <input type="checkbox" id="cb-komputer" value="belajar_komputer" onclick="event.stopPropagation()">
        <div class="aktivitas-icon">💻</div>
        <div>
            <div class="aktivitas-label">Belajar Komputer</div>
            <div class="aktivitas-desc">Menggunakan fasilitas komputer perpustakaan</div>
        </div>
    </div>

    <div class="error-msg" id="err-aktivitas"></div>

    <button class="btn" onclick="simpanAktivitas()">Selesai & Simpan</button>
    </div>
</div>

<!-- Modal selesai -->
@push('modals')
<div class="modal-overlay" id="modal-selesai">
    <div class="modal-box">
        <div class="modal-icon">✅</div>
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
