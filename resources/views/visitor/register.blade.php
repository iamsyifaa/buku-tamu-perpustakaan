@extends('layouts.app')

@section('title', 'Daftar - Perpustakaan Desa')

@push('styles')
<style>
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    select {
        width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px;
        font-size: 0.95rem; outline: none; transition: border-color 0.2s;
        background: #fff; color: #333; appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 12px center;
    }
    select:focus { border-color: #2c7a3f; }
    .section-label { font-size: 0.78rem; font-weight: 700; color: #2c7a3f; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; margin-top: 4px; }
    .divider { border: none; border-top: 1px solid #eee; margin: 16px 0; }
</style>
@endpush

@section('content')
<div class="card" style="max-width:480px">
    <div class="card-title">📝 Daftar Pengunjung</div>
    <div class="card-subtitle">Isi data diri Anda untuk mendapatkan ID pengunjung</div>

    <!-- Nama -->
    <div class="form-group">
        <label for="name">Nama Lengkap <span style="color:red">*</span></label>
        <input type="text" id="name" placeholder="Contoh: Faisal Ramadhan">
        <div class="error-msg" id="err-name"></div>
    </div>

    <!-- Umur -->
    <div class="form-group">
        <label for="umur">Umur <span style="color:red">*</span></label>
        <input type="number" id="umur" placeholder="Contoh: 25" min="1" max="120">
        <div class="error-msg" id="err-umur"></div>
    </div>

    <!-- Desa -->
    <div class="form-group">
        <label for="desa">Desa <span style="color:red">*</span></label>
        <select id="desa">
            <option value="">-- Pilih Desa --</option>
            <option value="Karyamukti">Karyamukti</option>
            <option value="Desa Lain">Desa Lain</option>
        </select>
        <div class="error-msg" id="err-desa"></div>
    </div>

    <hr class="divider">
    <div class="section-label">📍 Alamat</div>

    <!-- RW RT -->
    <div class="form-row">
        <div class="form-group">
            <label for="rw">RW <span style="color:red">*</span></label>
            <select id="rw">
                <option value="">Pilih RW</option>
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">RW {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                @endfor
            </select>
            <div class="error-msg" id="err-rw"></div>
        </div>
        <div class="form-group">
            <label for="rt">RT <span style="color:red">*</span></label>
            <select id="rt">
                <option value="">Pilih RT</option>
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">RT {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                @endfor
            </select>
            <div class="error-msg" id="err-rt"></div>
        </div>
    </div>

    <!-- Alamat detail -->
    <div class="form-group">
        <label for="alamat">Alamat Lengkap</label>
        <input type="text" id="alamat" placeholder="Contoh: Kp. Cikaret No. 12">
    </div>

    <button class="btn" onclick="doRegister()">Daftar</button>

    <div class="link-text">
        Sudah punya ID? <a href="{{ route('visitor.login') }}">Masuk di sini</a>
    </div>
</div>

@push('modals')
<div class="modal-overlay" id="modal-register">
    <div class="modal-box">
        <div class="modal-icon">🎉</div>
        <div class="modal-title">Pendaftaran Berhasil!</div>
        <div class="modal-body">
            Selamat, <strong id="modal-reg-name"></strong>!<br>
            ID pengunjung Anda adalah:
            <div class="modal-id" id="modal-reg-id"></div>
            <span style="font-size:0.82rem;color:#888;">Simpan ID ini untuk masuk di kunjungan berikutnya.</span>
        </div>
        <button class="btn" onclick="goToLogin()">Lanjut ke Halaman Masuk</button>
    </div>
</div>
@endpush

@push('scripts')
<script>
function doRegister() {
    const name   = document.getElementById('name').value.trim();
    const umur   = document.getElementById('umur').value.trim();
    const desa   = document.getElementById('desa').value;
    const rw     = document.getElementById('rw').value;
    const rt     = document.getElementById('rt').value;
    const alamat = document.getElementById('alamat').value.trim();

    // Reset errors
    ['name','umur','desa','rw','rt'].forEach(id => {
        document.getElementById('err-' + id).textContent = '';
    });

    let valid = true;
    if (!name)  { document.getElementById('err-name').textContent  = 'Nama wajib diisi.'; valid = false; }
    if (!umur)  { document.getElementById('err-umur').textContent  = 'Umur wajib diisi.'; valid = false; }
    if (!desa)  { document.getElementById('err-desa').textContent  = 'Desa wajib dipilih.'; valid = false; }
    if (!rw)    { document.getElementById('err-rw').textContent    = 'RW wajib dipilih.'; valid = false; }
    if (!rt)    { document.getElementById('err-rt').textContent    = 'RT wajib dipilih.'; valid = false; }
    if (!valid) return;

    fetch('{{ route("visitor.register.post") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ name, umur: parseInt(umur), desa, rw, rt, alamat }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('modal-reg-name').textContent = data.name;
            document.getElementById('modal-reg-id').textContent   = data.visitor_id;
            document.getElementById('modal-register').classList.add('active');
        } else {
            document.getElementById('err-name').textContent = 'Pendaftaran gagal. Coba lagi.';
        }
    })
    .catch(() => {
        document.getElementById('err-name').textContent = 'Terjadi kesalahan. Coba lagi.';
    });
}

function goToLogin() {
    window.location.href = '{{ route("visitor.login") }}';
}
</script>
@endpush
@endsection
