@extends('layouts.app')

@section('title', 'Daftar - Perpustakaan Desa')

@section('content')
<div class="card">
    <div class="card-title">📝 Daftar Pengunjung</div>
    <div class="card-subtitle">Isi data diri Anda untuk mendapatkan ID pengunjung</div>

    <div class="form-group">
        <label for="name">Nama Lengkap <span style="color:red">*</span></label>
        <input type="text" id="name" placeholder="Contoh: Faisal Ramadhan">
        <div class="error-msg" id="err-name"></div>
    </div>

    <div class="form-group">
        <label for="address">Alamat</label>
        <input type="text" id="address" placeholder="Contoh: Kp. Cikaret RT 01/02">
    </div>

    <div class="form-group">
        <label for="phone">No. HP</label>
        <input type="tel" id="phone" placeholder="Contoh: 08123456789">
    </div>

    <button class="btn" onclick="doRegister()">Daftar</button>

    <div class="link-text">
        Sudah punya ID? <a href="{{ route('visitor.login') }}">Masuk di sini</a>
    </div>
</div>

<!-- Modal berhasil daftar -->
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
    const name    = document.getElementById('name').value.trim();
    const address = document.getElementById('address').value.trim();
    const phone   = document.getElementById('phone').value.trim();
    const errName = document.getElementById('err-name');

    errName.textContent = '';

    if (!name) {
        errName.textContent = 'Nama lengkap wajib diisi.';
        return;
    }

    fetch('{{ route("visitor.register.post") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ name, address, phone }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('modal-reg-name').textContent = data.name;
            document.getElementById('modal-reg-id').textContent   = data.visitor_id;
            document.getElementById('modal-register').classList.add('active');
        } else {
            errName.textContent = 'Pendaftaran gagal. Coba lagi.';
        }
    })
    .catch(() => {
        errName.textContent = 'Terjadi kesalahan. Coba lagi.';
    });
}

function goToLogin() {
    window.location.href = '{{ route("visitor.login") }}';
}
</script>
@endpush
@endsection
