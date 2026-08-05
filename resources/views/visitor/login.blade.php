@extends('layouts.app')

@section('title', 'Masuk - Perpustakaan Desa')

@section('content')
    <div class="card">
        <div class="card-eyebrow"><span class="rule"></span> Perpustakaan Desa <span class="rule"></span></div>
        <div class="card-title">Selamat Datang</div>
        <div class="card-subtitle">Masukkan ID pengunjung Anda untuk masuk</div>

        <div class="form-group">
            <label for="visitor_id">ID Pengunjung</label>
            <input type="text" id="visitor_id" placeholder="Contoh: faisal0001" autocomplete="off">
            <div class="error-msg" id="login-error"></div>
        </div>

        <button class="btn" onclick="doLogin()">Masuk</button>

        <div class="link-text">
            Belum punya ID? <a href="{{ route('visitor.register') }}">Daftar di sini</a>
        </div>
    </div>

    <!-- Modal berhasil masuk -->
    @push('modals')
        <div class="modal-overlay" id="modal-login">
            <div class="modal-box">
                <div class="modal-stamp">✓</div>
                <div class="modal-title">Berhasil Masuk</div>
                <div class="modal-body">Selamat datang, <strong id="modal-name"></strong>.<br>Silakan pilih aktivitas Anda.
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
                        body: JSON.stringify({
                            visitor_id: visitorId
                        }),
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

            // Enter key support
            document.getElementById('visitor_id').addEventListener('keydown', function(e) {
                if (e.key === 'Enter') doLogin();
            });
        </script>
    @endpush
@endsection
