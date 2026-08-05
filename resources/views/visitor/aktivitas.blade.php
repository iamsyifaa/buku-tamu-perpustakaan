@extends('layouts.app')

@section('title', 'Pilih Aktivitas - Perpustakaan Desa')

@push('styles')
<style>
    .aktivitas-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px;
        border: 2px solid #ddd;
        border-radius: 8px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
        user-select: none;
    }
    .aktivitas-card:hover { border-color: #2c7a3f; background: #f9fef9; }
    .aktivitas-card.selected { border-color: #2c7a3f; background: #f0f9f3; }
    .aktivitas-card input[type="checkbox"] { width: 20px; height: 20px; accent-color: #2c7a3f; flex-shrink: 0; }
    .aktivitas-icon { font-size: 1.8rem; }
    .aktivitas-label { font-size: 1rem; font-weight: 600; color: #333; }
    .aktivitas-desc { font-size: 0.82rem; color: #777; margin-top: 2px; }
    .error-msg { color: #c0392b; font-size: 0.88rem; margin-bottom: 12px; }
    .visitor-info { background: #f0f9f3; border-radius: 6px; padding: 10px 14px; margin-bottom: 20px; font-size: 0.9rem; color: #2c7a3f; font-weight: 600; }
</style>
@endpush

@section('content')
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
