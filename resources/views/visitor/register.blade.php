@extends('layouts.app')

@section('title', 'Daftar - Perpustakaan Desa')

@section('content')
    <div class="card" style="max-width:480px">
        <div class="card-eyebrow"><span class="rule"></span> Perpustakaan Desa <span class="rule"></span></div>
        <div class="card-title">Daftar Pengunjung</div>
        <div class="card-subtitle">Isi data diri Anda untuk mendapatkan ID pengunjung</div>

        <!-- Nama -->
        <div class="form-group">
            <label for="name">Nama Lengkap <span class="req">*</span></label>
            <input type="text" id="name" placeholder="Contoh: Faisal Ramadhan">
            <div class="error-msg" id="err-name"></div>
        </div>

        <div class="form-row">
            <!-- Umur -->
            <div class="form-group">
                <label for="umur">Umur <span class="req">*</span></label>
                <input type="number" id="umur" placeholder="25" min="1" max="120">
                <div class="error-msg" id="err-umur"></div>
            </div>

            <!-- Desa -->
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

        <!-- RW RT -->
        <div class="form-row">
            <div class="form-group">
                <label for="rw">RW <span class="req">*</span></label>
                <select id="rw">
                    <option value="">Pilih RW</option>
                    @for ($i = 1; $i <= 10; $i++)
                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">RW {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
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
                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">RT {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                        </option>
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
                <div class="modal-stamp">✓</div>
                <div class="modal-title">Pendaftaran Berhasil</div>
                <div class="modal-body">
                    Selamat datang, <strong id="modal-reg-name"></strong>.
                </div>
                <div class="modal-id-card">
                    <div class="modal-id-label">ID Pengunjung Anda</div>
                    <div class="modal-id" id="modal-reg-id"></div>
                </div>
                <div class="modal-hint" style="margin-bottom:18px;">Simpan ID ini untuk masuk di kunjungan berikutnya.</div>
                <button class="btn" onclick="goToLogin()">Lanjut ke Halaman Masuk</button>
            </div>
        </div>
    @endpush

    @push('scripts')
        <script>
            function doRegister() {
                const name = document.getElementById('name').value.trim();
                const umur = document.getElementById('umur').value.trim();
                const desa = document.getElementById('desa').value;
                const rw = document.getElementById('rw').value;
                const rt = document.getElementById('rt').value;
                const alamat = document.getElementById('alamat').value.trim();

                // Reset errors
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

                fetch('{{ route('visitor.register.post') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
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
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('modal-reg-name').textContent = data.name;
                            document.getElementById('modal-reg-id').textContent = data.visitor_id;
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
                window.location.href = '{{ route('visitor.login') }}';
            }
        </script>
    @endpush
@endsection
