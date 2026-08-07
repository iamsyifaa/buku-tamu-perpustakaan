<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Admin - Perpustakaan Desa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@600&display=swap"
        rel="stylesheet">
    <!-- Library export: dibuat di sisi browser (client-side), jadi tidak butuh
         paket composer tambahan di server. -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.4.0/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
    <link rel="stylesheet" href="{{ secure_asset('css/admindashboard.css') }}">
</head>

<body>

    <nav class="navbar">
        <div class="navbar-brand">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
            </svg>
            <div class="navbar-brand-text">
                <b>Perpustakaan Desa</b>
                <span>Panel Admin</span>
            </div>
        </div>
        <div class="navbar-right">
            <div class="navbar-user">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                <span class="navbar-username">{{ session('admin')->username }}</span>
            </div>
            <a href="{{ route('admin.logout') }}" class="btn-logout">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                    <polyline points="16 17 21 12 16 7" />
                    <line x1="21" y1="12" x2="9" y2="12" />
                </svg>
                <span class="btn-logout-text">Keluar</span>
            </a>
        </div>
    </nav>

    <div class="container">

        <div class="layout-grid">

            <!-- Kolom kiri: statistik (padat, ikon + nomor berdampingan) -->
            <aside class="stats-col">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                            </svg></div>
                        <div class="stat-num">{{ $stats['total_kunjungan'] }}</div>
                    </div>
                    <div class="stat-label">Total Kunjungan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon neutral"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg></div>
                        <div class="stat-num">{{ $stats['total_pengunjung'] }}</div>
                    </div>
                    <div class="stat-label">Terdaftar</div>
                </div>
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon accent"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg></div>
                        <div class="stat-num">{{ $stats['hari_ini'] }}</div>
                    </div>
                    <div class="stat-label">Hari Ini</div>
                </div>
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                            </svg></div>
                        <div class="stat-num">{{ $stats['baca_buku'] }}</div>
                    </div>
                    <div class="stat-label">Baca Buku</div>
                </div>
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z" />
                            </svg></div>
                        <div class="stat-num">{{ $stats['pinjam_buku'] }}</div>
                    </div>
                    <div class="stat-label">Pinjam Buku</div>
                </div>
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
                                <line x1="8" y1="21" x2="16" y2="21" />
                                <line x1="12" y1="17" x2="12" y2="21" />
                            </svg></div>
                        <div class="stat-num">{{ $stats['belajar_komputer'] }}</div>
                    </div>
                    <div class="stat-label">Belajar Komputer</div>
                </div>
            </aside>

            <!-- Kolom kanan: filter + tabel -->
            <div class="main-col">

                <!-- Filter Panel -->
                <div class="filter-panel">
                    <div class="filter-panel-head">
                        <div class="eyebrow">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <line x1="21" y1="21" x2="16.65" y2="16.65" />
                            </svg>
                            Filter Data
                        </div>
                        <a href="{{ route('admin.dashboard') }}" class="btn-reset-filter">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="15" y1="9" x2="9" y2="15" />
                                <line x1="9" y1="9" x2="15" y2="15" />
                            </svg>
                            Hapus semua filter
                        </a>
                    </div>
                    <form method="GET" action="{{ route('admin.dashboard') }}" id="filterForm">
                        <input type="hidden" name="filter" value="{{ $filter }}">
                        <input type="hidden" name="peringkat" value="{{ $peringkat }}">
                        <div class="filter-row">
                            <div class="filter-group">
                                <label>Bulan</label>
                                <select name="bulan">
                                    <option value="">Semua Bulan</option>
                                    @foreach (['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'] as $val => $label)
                                        <option value="{{ $val }}" {{ $bulan == $val ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-group">
                                <label>Tahun</label>
                                <select name="tahun">
                                    @for ($y = date('Y'); $y >= 2024; $y--)
                                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                            {{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="filter-group">
                                <label>RW</label>
                                <select name="rw">
                                    <option value="">Semua RW</option>
                                    @for ($i = 1; $i <= 10; $i++)
                                        @php $val = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                                        <option value="{{ $val }}" {{ $rw == $val ? 'selected' : '' }}>RW
                                            {{ $val }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="filter-group">
                                <label>RT</label>
                                <select name="rt">
                                    <option value="">Semua RT</option>
                                    @for ($i = 1; $i <= 10; $i++)
                                        @php $val = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                                        <option value="{{ $val }}" {{ $rt == $val ? 'selected' : '' }}>RT
                                            {{ $val }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="filter-group">
                                <label>Cari nama / ID</label>
                                <div class="search-wrap">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8" />
                                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                    </svg>
                                    <input type="text" name="search" id="search-input"
                                        value="{{ $search }}" placeholder="Contoh: muhammad0001">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="filter-hint">Filter otomatis diterapkan begitu kamu ganti pilihan atau berhenti
                        mengetik.</div>
                </div>

                <!-- Pills utama: menentukan MODE tabel (kunjungan biasa / aktivitas tertentu / peringkat) -->
                @php
                    $filterOptions = [
                        'semua' => [
                            'label' => 'Semua Kunjungan',
                            'icon' =>
                                '<line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>',
                        ],
                        'baca_buku' => [
                            'label' => 'Baca Buku',
                            'icon' =>
                                '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
                        ],
                        'pinjam_buku' => [
                            'label' => 'Pinjam Buku',
                            'icon' => '<path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>',
                        ],
                        'belajar_komputer' => [
                            'label' => 'Belajar Komputer',
                            'icon' =>
                                '<rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>',
                        ],
                        'pengunjung_terbanyak' => [
                            'label' => 'Pengunjung Terbanyak',
                            'icon' =>
                                '<circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>',
                        ],
                    ];
                @endphp
                <div class="pills">
                    @foreach ($filterOptions as $key => $opt)
                        <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['filter' => $key])) }}"
                            class="pill {{ $filter === $key ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"
                                stroke-linecap="round" stroke-linejoin="round">{!! $opt['icon'] !!}</svg>
                            {{ $opt['label'] }}
                        </a>
                    @endforeach
                </div>

                @php
                    // Dipakai untuk sub-kontrol "Urutkan" di dalam tabel Pengunjung Terbanyak,
                    // dan untuk memberi nama kolom "Nilai" yang sesuai konteks urutan yang dipilih.
                    $peringkatOptions = [
                        'semua' => 'Semua Aktivitas',
                        'baca_buku' => 'Baca Buku',
                        'pinjam_buku' => 'Pinjam Buku',
                        'belajar_komputer' => 'Belajar Komputer',
                    ];
                    $nilaiLabel = $peringkatOptions[$peringkat] ?? 'Nilai';
                @endphp

                <!-- Tabel -->
                <div class="table-card">
                    <div class="table-header">
                        <div class="table-header-left">
                            <div class="table-header-title">
                                {{ $isTopMode ? 'Pengunjung Terbanyak' : 'Data Kunjungan' }}</div>
                            <div class="table-header-count">
                                {{ $isTopMode ? $topVisitors->count() : $visits->total() }} data ditemukan
                            </div>

                            @if ($isTopMode)
                                <!-- Kontrol urutan: sengaja berbentuk dropdown (bukan pill kedua) supaya
                                     tidak terlihat mengulang pilihan "Baca Buku / Pinjam Buku / Belajar Komputer"
                                     yang sudah ada di pills utama. Konteksnya jelas: ini mengurutkan tabel
                                     di bawahnya, bukan memilih mode filter lagi. -->
                                <div class="sort-select-wrap">
                                    <label class="sort-select-label" for="peringkat-select">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                                            <polyline points="17 6 23 6 23 12" />
                                        </svg>
                                        Urutkan
                                    </label>
                                    <select id="peringkat-select"
                                        onchange="if(this.value) location.href=this.value;">
                                        @foreach ($peringkatOptions as $key => $label)
                                            <option
                                                value="{{ route('admin.dashboard', array_merge(request()->query(), ['peringkat' => $key])) }}"
                                                {{ $peringkat === $key ? 'selected' : '' }}>
                                                {{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                        <div class="export-wrap">
                            <button type="button" class="btn-export" id="btn-export" onclick="toggleExportMenu()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="7 10 12 15 17 10" />
                                    <line x1="12" y1="15" x2="12" y2="3" />
                                </svg>
                                <span id="btn-export-label">Export</span>
                            </button>
                            <div class="export-menu" id="export-menu">
                                <button type="button" class="export-menu-item" onclick="exportSekarang('excel')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <polyline points="14 2 14 8 20 8" />
                                        <line x1="16" y1="13" x2="8" y2="13" />
                                        <line x1="16" y1="17" x2="8" y2="17" />
                                    </svg>
                                    <div>
                                        <div class="export-menu-title">Export sebagai Excel</div>
                                        <div class="export-menu-sub">Semua data sesuai filter (bukan hanya halaman ini)
                                        </div>
                                    </div>
                                </button>
                                <button type="button" class="export-menu-item" onclick="exportSekarang('pdf')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <polyline points="14 2 14 8 20 8" />
                                        <line x1="10" y1="12" x2="14" y2="12" />
                                        <line x1="10" y1="16" x2="14" y2="16" />
                                    </svg>
                                    <div>
                                        <div class="export-menu-title">Export sebagai PDF</div>
                                        <div class="export-menu-sub">Semua data sesuai filter (bukan hanya halaman ini)
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-scroll">
                        @if ($isTopMode)
                            <table>
                                <thead>
                                    <tr>
                                        <th>Peringkat</th>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Lokasi</th>
                                        <th>Aktivitas</th>
                                        <th>{{ $nilaiLabel }}</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topVisitors as $idx => $top)
                                        <tr>
                                            <td><span
                                                    class="rank-badge {{ $idx === 0 ? 'gold' : '' }}">{{ $idx + 1 }}</span>
                                            </td>
                                            <td><span class="visitor-id">{{ $top->visitor_id }}</span></td>
                                            <td><span class="visitor-name">{{ $top->name }}</span></td>
                                            <td><span
                                                    class="time-text">RW{{ $top->rw ?? '-' }}/RT{{ $top->rt ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <div class="activity-icons">
                                                    <span
                                                        class="act-badge {{ $top->baca_buku_count > 0 ? 'on' : '' }}"
                                                        title="Baca Buku">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="1.75" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                                                        </svg>{{ $top->baca_buku_count }}
                                                    </span>
                                                    <span
                                                        class="act-badge {{ $top->pinjam_buku_count > 0 ? 'on' : '' }}"
                                                        title="Pinjam Buku">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="1.75" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path
                                                                d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z" />
                                                        </svg>{{ $top->pinjam_buku_count }}
                                                    </span>
                                                    <span
                                                        class="act-badge {{ $top->belajar_komputer_count > 0 ? 'on' : '' }}"
                                                        title="Belajar Komputer">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="1.75" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <rect x="2" y="3" width="20" height="14"
                                                                rx="2" ry="2" />
                                                            <line x1="8" y1="21" x2="16"
                                                                y2="21" />
                                                            <line x1="12" y1="17" x2="12"
                                                                y2="21" />
                                                        </svg>{{ $top->belajar_komputer_count }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td><span class="nilai-peringkat">{{ $top->{$kolomPeringkat} }}</span>
                                            </td>
                                            <td><button class="btn-detail"
                                                    onclick="showDetail({{ $top->id }})">Detail</button></td>
                                        </tr>
                                    @endforeach

                                    {{-- Baris pengisi: tabel Pengunjung Terbanyak selalu tampil fix 10 baris,
                                         walaupun data aslinya kurang dari 10. Jumlah data sebenarnya tetap
                                         terlihat lewat badge "X data ditemukan" di atas. --}}
                                    @for ($i = $topVisitors->count(); $i < 10; $i++)
                                        <tr class="row-placeholder">
                                            <td><span class="rank-badge">{{ $i + 1 }}</span></td>
                                            <td><span class="placeholder-text">—</span></td>
                                            <td><span class="placeholder-text">—</span></td>
                                            <td><span class="placeholder-text">—</span></td>
                                            <td><span class="placeholder-text">—</span></td>
                                            <td><span class="placeholder-text">—</span></td>
                                            <td><span class="placeholder-text">—</span></td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        @else
                            <table>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Waktu</th>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Lokasi</th>
                                        <th>Aktivitas</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($visits as $i => $visit)
                                        <tr>
                                            <td>{{ $visits->firstItem() + $i }}</td>
                                            <td><span
                                                    class="time-text">{{ $visit->visited_at->format('d/m/y H:i') }}</span>
                                            </td>
                                            <td><span
                                                    class="visitor-id">{{ $visit->visitor->visitor_id ?? '-' }}</span>
                                            </td>
                                            <td><span class="visitor-name">{{ $visit->visitor->name ?? '-' }}</span>
                                            </td>
                                            <td><span
                                                    class="time-text">RW{{ $visit->visitor->rw ?? '-' }}/RT{{ $visit->visitor->rt ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <div class="activity-icons">
                                                    <span class="act-badge {{ $visit->baca_buku ? 'on' : '' }}"
                                                        title="Baca Buku: {{ $visit->baca_buku ? 'Ya' : 'Tidak' }}">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="1.75" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                                                        </svg>
                                                    </span>
                                                    <span class="act-badge {{ $visit->pinjam_buku ? 'on' : '' }}"
                                                        title="Pinjam Buku: {{ $visit->pinjam_buku ? 'Ya' : 'Tidak' }}">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="1.75" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path
                                                                d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z" />
                                                        </svg>
                                                    </span>
                                                    <span class="act-badge {{ $visit->belajar_komputer ? 'on' : '' }}"
                                                        title="Belajar Komputer: {{ $visit->belajar_komputer ? 'Ya' : 'Tidak' }}">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="1.75" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <rect x="2" y="3" width="20" height="14"
                                                                rx="2" ry="2" />
                                                            <line x1="8" y1="21" x2="16"
                                                                y2="21" />
                                                            <line x1="12" y1="17" x2="12"
                                                                y2="21" />
                                                        </svg>
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($visit->visitor)
                                                    <button class="btn-detail"
                                                        onclick="showDetail({{ $visit->visitor->id }})">Detail</button>
                                                @else
                                                    <span
                                                        style="color:var(--color-ink-faint);font-size:0.8rem">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">
                                                <div class="empty-msg">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <polyline points="22 12 16 12 14 15 10 15 8 12 2 12" />
                                                        <path
                                                            d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z" />
                                                    </svg>
                                                    <p>Belum ada data kunjungan.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @endif
                    </div>

                    @if (!$isTopMode && $visits->hasPages())
                        <div class="pagination">
                            @if (!$visits->onFirstPage())
                                <a href="{{ $visits->previousPageUrl() }}" class="page-btn"><svg viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="15 18 9 12 15 6" />
                                    </svg>Sebelumnya</a>
                            @else
                                <span class="page-btn disabled"><svg viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="15 18 9 12 15 6" />
                                    </svg>Sebelumnya</span>
                            @endif
                            <span class="page-info">Halaman {{ $visits->currentPage() }} dari
                                {{ $visits->lastPage() }}</span>
                            @if ($visits->hasMorePages())
                                <a href="{{ $visits->nextPageUrl() }}" class="page-btn">Berikutnya<svg
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="9 18 15 12 9 6" />
                                    </svg></a>
                            @else
                                <span class="page-btn disabled">Berikutnya<svg viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="9 18 15 12 9 6" />
                                    </svg></span>
                            @endif
                        </div>
                    @endif
                </div>

            </div>

        </div>

    </div>

    <!-- Modal Detail -->
    <div class="modal-overlay" id="modal-detail">
        <div class="modal-box">
            <div class="modal-head">
                <div class="modal-head-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    Detail Pengunjung
                </div>
                <button class="modal-close" onclick="closeDetail()"><svg viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg></button>
            </div>
            <div class="modal-body" id="modal-content">
                <div style="text-align:center;padding:24px;color:var(--color-ink-faint);">Memuat data...</div>
            </div>
        </div>
    </div>

    <script>
        const detailUrl = "{{ url('admin/visitor') }}";
        const exportDataUrl = "{{ route('admin.export-data') }}";

        function showDetail(id) {
            document.getElementById('modal-detail').classList.add('active');
            document.getElementById('modal-content').innerHTML =
                '<div style="text-align:center;padding:24px;color:var(--color-ink-faint);">Memuat data...</div>';

            fetch(detailUrl + '/' + id)
                .then(r => r.json())
                .then(d => {
                    const v = d.visitor;
                    document.getElementById('modal-content').innerHTML = `
                <div class="modal-id-badge">
                    <div class="modal-id-text">${v.visitor_id}</div>
                    <div class="modal-id-name">${v.name}</div>
                </div>
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-item-label">Umur</div>
                        <div class="detail-item-value">${v.umur ?? '-'} tahun</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-item-label">Desa</div>
                        <div class="detail-item-value">${v.desa ?? '-'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-item-label">RW / RT</div>
                        <div class="detail-item-value">RW ${v.rw ?? '-'} / RT ${v.rt ?? '-'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-item-label">Alamat</div>
                        <div class="detail-item-value">${v.alamat ?? '-'}</div>
                    </div>
                </div>
                <div class="eyebrow" style="margin-bottom:8px;">Statistik Kunjungan</div>
                <div class="stat-row">
                    <div class="stat-mini">
                        <div class="stat-mini-num">${d.total_kunjungan}</div>
                        <div class="stat-mini-label">Total</div>
                    </div>
                    <div class="stat-mini">
                        <div class="stat-mini-num">${d.total_baca}</div>
                        <div class="stat-mini-label">Baca Buku</div>
                    </div>
                    <div class="stat-mini">
                        <div class="stat-mini-num">${d.total_pinjam}</div>
                        <div class="stat-mini-label">Pinjam</div>
                    </div>
                    <div class="stat-mini">
                        <div class="stat-mini-num">${d.total_komputer}</div>
                        <div class="stat-mini-label">Komputer</div>
                    </div>
                </div>
                <div style="margin-top:10px;font-size:0.8rem;color:var(--color-ink-faint);">Kunjungan terakhir: ${d.kunjungan_terakhir}</div>
            `;
                })
                .catch(() => {
                    document.getElementById('modal-content').innerHTML =
                        '<div style="text-align:center;padding:24px;color:var(--color-danger);">Gagal memuat data.</div>';
                });
        }

        function closeDetail() {
            document.getElementById('modal-detail').classList.remove('active');
        }

        document.getElementById('modal-detail').addEventListener('click', function(e) {
            if (e.target === this) closeDetail();
        });

        // Filter otomatis: begitu select berubah, form langsung submit.
        document.querySelectorAll('#filterForm select').forEach(function(el) {
            el.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });

        // Kolom pencarian: submit otomatis setelah user berhenti mengetik (debounce).
        let searchTimeout;
        document.getElementById('search-input').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                document.getElementById('filterForm').submit();
            }, 500);
        });

        // Dropdown menu export: toggle buka/tutup, tutup kalau klik di luar.
        function toggleExportMenu() {
            document.getElementById('export-menu').classList.toggle('open');
        }
        document.addEventListener('click', function(e) {
            const wrap = document.querySelector('.export-wrap');
            if (wrap && !wrap.contains(e.target)) {
                document.getElementById('export-menu').classList.remove('open');
            }
        });

        // ================= EXPORT (mengikuti filter & mode tabel yang aktif) =================
        async function exportSekarang(format) {
            document.getElementById('export-menu').classList.remove('open');

            const btn = document.getElementById('btn-export');
            const btnLabel = document.getElementById('btn-export-label');
            btn.disabled = true;
            btnLabel.textContent = 'Menyiapkan...';

            try {
                // Filter ikut URL; param "page" dibuang supaya jelas = semua hasil filter
                const params = new URLSearchParams(window.location.search);
                params.delete('page');
                const qs = params.toString();
                const res = await fetch(exportDataUrl + (qs ? '?' + qs : ''), {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();

                if (!data.rows || data.rows.length === 0) {
                    alert('Tidak ada data untuk diekspor (sesuai filter saat ini).');
                    return;
                }

                if (format === 'excel') {
                    await exportExcel(data);
                } else {
                    exportPdf(data);
                }
            } catch (err) {
                alert('Gagal menyiapkan file export. Coba lagi.');
                console.error(err);
            } finally {
                btn.disabled = false;
                btnLabel.textContent = 'Export';
            }
        }

        function kolomExport(mode) {
            if (mode === 'pengunjung_terbanyak') {
                return [{
                        header: 'No',
                        key: 'no',
                        width: 6
                    },
                    {
                        header: 'ID Pengunjung',
                        key: 'id',
                        width: 16
                    },
                    {
                        header: 'Nama',
                        key: 'nama',
                        width: 24
                    },
                    {
                        header: 'Desa',
                        key: 'desa',
                        width: 16
                    },
                    {
                        header: 'RW',
                        key: 'rw',
                        width: 8
                    },
                    {
                        header: 'RT',
                        key: 'rt',
                        width: 8
                    },
                    {
                        header: 'Total Kunjungan',
                        key: 'total_kunjungan',
                        width: 14
                    },
                    {
                        header: 'Baca Buku',
                        key: 'baca_buku',
                        width: 12
                    },
                    {
                        header: 'Pinjam Buku',
                        key: 'pinjam_buku',
                        width: 12
                    },
                    {
                        header: 'Belajar Komputer',
                        key: 'belajar_komputer',
                        width: 15
                    },
                ];
            }
            return [{
                    header: 'No',
                    key: 'no',
                    width: 6
                },
                {
                    header: 'Waktu',
                    key: 'waktu',
                    width: 16
                },
                {
                    header: 'ID Pengunjung',
                    key: 'id',
                    width: 16
                },
                {
                    header: 'Nama',
                    key: 'nama',
                    width: 22
                },
                {
                    header: 'Desa',
                    key: 'desa',
                    width: 14
                },
                {
                    header: 'RW',
                    key: 'rw',
                    width: 7
                },
                {
                    header: 'RT',
                    key: 'rt',
                    width: 7
                },
                {
                    header: 'Alamat',
                    key: 'alamat',
                    width: 22
                },
                {
                    header: 'Umur',
                    key: 'umur',
                    width: 8
                },
                {
                    header: 'Baca Buku',
                    key: 'baca_buku',
                    width: 11
                },
                {
                    header: 'Pinjam Buku',
                    key: 'pinjam_buku',
                    width: 12
                },
                {
                    header: 'Belajar Komputer',
                    key: 'belajar_komputer',
                    width: 15
                },
            ];
        }

        async function exportExcel(data) {
            const kolom = kolomExport(data.mode);
            const jumlahKolom = kolom.length;

            const wb = new ExcelJS.Workbook();
            wb.creator = 'Perpustakaan Desa';
            wb.created = new Date();
            const ws = wb.addWorksheet('Data', {
                views: [{
                    state: 'frozen',
                    ySplit: 4
                }]
            });
            ws.columns = kolom.map(k => ({
                width: k.width
            }));

            ws.mergeCells(1, 1, 1, jumlahKolom);
            const judul = ws.getCell(1, 1);
            judul.value = data.judul.toUpperCase();
            judul.font = {
                bold: true,
                size: 14,
                color: {
                    argb: 'FF13273F'
                }
            };
            ws.getRow(1).height = 24;

            ws.mergeCells(2, 1, 2, jumlahKolom);
            const sub = ws.getCell(2, 1);
            sub.value =
                `Periode: ${data.periode}  |  Tanggal Export: ${new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })}`;
            sub.font = {
                italic: true,
                size: 10.5,
                color: {
                    argb: 'FF55637A'
                }
            };

            const headerRow = ws.getRow(4);
            kolom.forEach((k, i) => {
                const cell = headerRow.getCell(i + 1);
                cell.value = k.header;
                cell.font = {
                    bold: true,
                    color: {
                        argb: 'FFFFFFFF'
                    },
                    size: 11
                };
                cell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: {
                        argb: 'FF13273F'
                    }
                };
                cell.alignment = {
                    vertical: 'middle',
                    horizontal: i === 0 ? 'center' : 'left'
                };
                cell.border = {
                    top: {
                        style: 'thin',
                        color: {
                            argb: 'FF0B1A2B'
                        }
                    },
                    bottom: {
                        style: 'thin',
                        color: {
                            argb: 'FF0B1A2B'
                        }
                    },
                    left: {
                        style: 'thin',
                        color: {
                            argb: 'FF0B1A2B'
                        }
                    },
                    right: {
                        style: 'thin',
                        color: {
                            argb: 'FF0B1A2B'
                        }
                    },
                };
            });
            headerRow.height = 20;

            data.rows.forEach((r, i) => {
                const row = ws.getRow(5 + i);
                kolom.forEach((k, c) => {
                    const cell = row.getCell(c + 1);
                    cell.value = r[k.key] ?? '-';
                    cell.font = {
                        size: 10.5,
                        color: {
                            argb: 'FF13273F'
                        }
                    };
                    cell.alignment = {
                        vertical: 'middle',
                        horizontal: c === 0 ? 'center' : 'left'
                    };
                    cell.border = {
                        top: {
                            style: 'thin',
                            color: {
                                argb: 'FFE5E2D9'
                            }
                        },
                        bottom: {
                            style: 'thin',
                            color: {
                                argb: 'FFE5E2D9'
                            }
                        },
                        left: {
                            style: 'thin',
                            color: {
                                argb: 'FFE5E2D9'
                            }
                        },
                        right: {
                            style: 'thin',
                            color: {
                                argb: 'FFE5E2D9'
                            }
                        },
                    };
                    if (i % 2 === 1) cell.fill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: {
                            argb: 'FFFAF8F4'
                        }
                    };
                });
                row.height = 18;
            });

            const totalRowIdx = 5 + data.rows.length + 1;
            ws.mergeCells(totalRowIdx, 1, totalRowIdx, jumlahKolom);
            const totalCell = ws.getCell(totalRowIdx, 1);
            totalCell.value = `Total: ${data.rows.length} data`;
            totalCell.font = {
                bold: true,
                size: 10.5,
                color: {
                    argb: 'FF13273F'
                }
            };

            const buffer = await wb.xlsx.writeBuffer();
            const blob = new Blob([buffer], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });
            unduhBlob(blob, namaFileExport(data, 'xlsx'));
        }

        function exportPdf(data) {
            const {
                jsPDF
            } = window.jspdf;
            const kolom = kolomExport(data.mode);
            const doc = new jsPDF({
                orientation: 'landscape',
                unit: 'pt',
                format: 'a4'
            });

            doc.setFont('helvetica', 'bold');
            doc.setFontSize(14);
            doc.setTextColor(19, 39, 63);
            doc.text(data.judul.toUpperCase(), 40, 40);

            doc.setFont('helvetica', 'italic');
            doc.setFontSize(9.5);
            doc.setTextColor(85, 99, 122);
            const tanggalExport = new Date().toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
            doc.text(`Periode: ${data.periode}   |   Tanggal Export: ${tanggalExport}`, 40, 56);

            doc.autoTable({
                startY: 72,
                head: [kolom.map(k => k.header)],
                body: data.rows.map(r => kolom.map(k => String(r[k.key] ?? '-'))),
                styles: {
                    font: 'helvetica',
                    fontSize: 8.5,
                    textColor: [19, 39, 63],
                    lineColor: [229, 226, 217],
                    lineWidth: 0.5
                },
                headStyles: {
                    fillColor: [19, 39, 63],
                    textColor: [255, 255, 255],
                    fontStyle: 'bold'
                },
                alternateRowStyles: {
                    fillColor: [250, 248, 244]
                },
                margin: {
                    left: 40,
                    right: 40
                },
            });

            const finalY = doc.lastAutoTable.finalY || 72;
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(9.5);
            doc.setTextColor(19, 39, 63);
            doc.text(`Total: ${data.rows.length} data`, 40, finalY + 20);

            doc.save(namaFileExport(data, 'pdf'));
        }

        function namaFileExport(data, ext) {
            const slug = data.judul.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
            const tanggal = new Date().toISOString().slice(0, 10);
            return `${slug}-${tanggal}.${ext}`;
        }

        function unduhBlob(blob, filename) {
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }
    </script>
</body>

</html>