<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Admin - Perpustakaan Desa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@600&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-bg: #F4F6F1;
            --color-surface: #FFFFFF;
            --color-ink: #1E2A22;
            --color-ink-soft: #647065;
            --color-ink-faint: #97A196;
            --color-primary: #1F5F3A;
            --color-primary-dark: #163F27;
            --color-primary-tint: #E7F0E9;
            --color-accent: #A8823A;
            --color-accent-tint: #F6EEDD;
            --color-neutral-tint: #EEF0EB;
            --color-border: #DFE5DA;
            --color-danger: #AB4A34;
            --color-danger-tint: #F8EAE6;
            --radius-md: 10px;
            --radius-lg: 14px;
            --font-display: 'Fraunces', Georgia, serif;
            --font-body: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-mono: 'JetBrains Mono', ui-monospace, monospace;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: var(--font-body); background: var(--color-bg); color: var(--color-ink); min-height: 100vh; }
        svg.icon { width: 18px; height: 18px; flex-shrink: 0; }

        /* Navbar */
        .navbar {
            background: var(--color-ink); border-bottom: 2px solid var(--color-accent);
            color: #fff; padding: 0 28px;
            display: flex; align-items: center; justify-content: space-between;
            height: 64px;
        }
        .navbar-brand { display: flex; align-items: center; gap: 10px; }
        .navbar-brand svg { width: 22px; height: 22px; color: var(--color-accent); }
        .navbar-brand-text { display: flex; flex-direction: column; line-height: 1.2; }
        .navbar-brand-text b { font-family: var(--font-display); font-size: 1.05rem; font-weight: 600; }
        .navbar-brand-text span { font-size: 0.7rem; color: rgba(255,255,255,0.55); letter-spacing: 0.05em; text-transform: uppercase; }
        .navbar-right { display: flex; align-items: center; gap: 16px; }
        .navbar-user { display: flex; align-items: center; gap: 7px; font-size: 0.85rem; color: rgba(255,255,255,0.85); }
        .navbar-user svg { width: 15px; height: 15px; color: rgba(255,255,255,0.6); }
        .btn-logout {
            display: flex; align-items: center; gap: 6px;
            color: #fff; text-decoration: none; font-size: 0.82rem; font-weight: 600;
            background: rgba(255,255,255,0.08); padding: 7px 14px;
            border-radius: 20px; border: 1px solid rgba(255,255,255,0.18);
            transition: background 0.15s;
        }
        .btn-logout svg { width: 14px; height: 14px; }
        .btn-logout:hover { background: rgba(255,255,255,0.16); }

        .container { max-width: 1240px; margin: 0 auto; padding: 28px 20px 48px; }

        .page-header { margin-bottom: 22px; }
        .page-header h1 { font-family: var(--font-display); font-size: 1.5rem; font-weight: 600; color: var(--color-ink); }
        .page-header p { color: var(--color-ink-soft); font-size: 0.88rem; margin-top: 3px; }

        .eyebrow {
            display: flex; align-items: center; gap: 8px;
            font-size: 0.72rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--color-accent); margin-bottom: 14px;
        }
        .eyebrow svg { width: 14px; height: 14px; }

        /* Stat cards */
        .stats-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 12px; margin-bottom: 22px; }
        @media(max-width:1000px) { .stats-grid { grid-template-columns: repeat(3, 1fr); } }
        @media(max-width:560px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
        .stat-card {
            background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-lg);
            padding: 16px; display: flex; flex-direction: column; gap: 10px;
        }
        .stat-icon {
            width: 34px; height: 34px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
        }
        .stat-icon svg { width: 17px; height: 17px; }
        .stat-icon.primary { background: var(--color-primary-tint); color: var(--color-primary); }
        .stat-icon.neutral { background: var(--color-neutral-tint); color: var(--color-ink-soft); }
        .stat-icon.accent  { background: var(--color-accent-tint); color: var(--color-accent); }
        .stat-num { font-family: var(--font-display); font-size: 1.5rem; font-weight: 600; line-height: 1; }
        .stat-label { font-size: 0.72rem; color: var(--color-ink-soft); font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }

        /* Filter panel */
        .filter-panel {
            background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-lg);
            padding: 18px 20px; margin-bottom: 16px;
        }
        .filter-panel-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
        .filter-panel-head .eyebrow { margin-bottom: 0; }
        .link-reset { font-size: 0.8rem; color: var(--color-ink-soft); text-decoration: none; }
        .link-reset:hover { color: var(--color-danger); }
        .filter-row { display: grid; grid-template-columns: repeat(4, 1fr) 1.6fr; gap: 12px; align-items: end; }
        @media(max-width:900px) { .filter-row { grid-template-columns: repeat(2, 1fr); } }
        .filter-group { display: flex; flex-direction: column; gap: 5px; }
        .filter-group label { font-size: 0.74rem; color: var(--color-ink-soft); font-weight: 600; }
        .filter-group select, .filter-group input {
            padding: 9px 12px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md);
            font-family: var(--font-body); font-size: 0.87rem; outline: none; background: var(--color-surface); color: var(--color-ink);
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .filter-group select { appearance: none; cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7' viewBox='0 0 11 7'%3E%3Cpath fill='none' stroke='%23647065' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' d='M1 1l4.5 4.5L10 1'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 12px center; padding-right: 30px;
        }
        .filter-group select:focus, .filter-group input:focus { border-color: var(--color-primary); box-shadow: 0 0 0 3px var(--color-primary-tint); }
        .search-wrap { position: relative; }
        .search-wrap svg { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); width: 15px; height: 15px; color: var(--color-ink-faint); }
        .search-wrap input { padding-left: 32px; width: 100%; }
        .filter-hint { font-size: 0.74rem; color: var(--color-ink-faint); margin-top: 10px; }

        /* Pills aktivitas */
        .pills { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 18px; }
        .pill {
            padding: 8px 16px; border-radius: 20px; border: 1.5px solid var(--color-border);
            background: var(--color-surface); font-size: 0.84rem; cursor: pointer;
            text-decoration: none; color: var(--color-ink-soft); font-weight: 600; transition: all 0.15s;
            display: flex; align-items: center; gap: 7px;
        }
        .pill svg { width: 15px; height: 15px; }
        .pill:hover { border-color: var(--color-primary); color: var(--color-primary); }
        .pill.active { background: var(--color-ink); color: #fff; border-color: var(--color-ink); }
        .pill.active svg { color: var(--color-accent); }

        /* Table */
        .table-card { background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-lg); overflow: hidden; }
        .table-header { padding: 16px 20px; border-bottom: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center; }
        .table-header-title { font-family: var(--font-display); font-size: 1rem; font-weight: 600; }
        .table-header-count { font-size: 0.8rem; color: var(--color-ink-soft); }
        table { width: 100%; border-collapse: collapse; }
        thead { background: var(--color-primary-tint); }
        th { padding: 11px 16px; text-align: left; font-size: 0.72rem; font-weight: 700; color: var(--color-primary-dark); text-transform: uppercase; letter-spacing: 0.05em; }
        td { padding: 13px 16px; font-size: 0.86rem; color: var(--color-ink); border-bottom: 1px solid var(--color-border); vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #FAFBF8; }
        .visitor-id { font-weight: 600; color: var(--color-primary); font-family: var(--font-mono); font-size: 0.82rem; }
        .visitor-name { font-weight: 600; }
        .time-text { color: var(--color-ink-soft); font-size: 0.82rem; }
        .status-dot { display: inline-flex; align-items: center; gap: 7px; font-size: 0.82rem; }
        .status-dot .dot { width: 7px; height: 7px; border-radius: 50%; background: var(--color-ink-faint); }
        .status-dot.yes .dot { background: var(--color-primary); }
        .status-dot.yes { color: var(--color-ink); font-weight: 500; }
        .status-dot.no { color: var(--color-ink-faint); }
        .btn-detail {
            padding: 6px 14px; background: transparent; color: var(--color-primary);
            border: 1.5px solid var(--color-primary); border-radius: var(--radius-md); font-size: 0.78rem;
            cursor: pointer; font-weight: 600; transition: all 0.15s; white-space: nowrap;
        }
        .btn-detail:hover { background: var(--color-primary); color: #fff; }
        .empty-msg { text-align: center; padding: 56px 20px; color: var(--color-ink-faint); }
        .empty-msg svg { width: 36px; height: 36px; margin-bottom: 10px; color: var(--color-ink-faint); }
        .empty-msg p { font-size: 0.9rem; }

        /* Pagination */
        .pagination { padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--color-border); }
        .page-info { font-size: 0.82rem; color: var(--color-ink-soft); }
        .page-btn {
            padding: 7px 14px; border-radius: var(--radius-md); border: 1.5px solid var(--color-border);
            background: var(--color-surface); font-size: 0.82rem; font-weight: 600; text-decoration: none; color: var(--color-ink);
            transition: all 0.15s; display: inline-flex; align-items: center; gap: 4px;
        }
        .page-btn svg { width: 14px; height: 14px; }
        .page-btn:hover { border-color: var(--color-primary); color: var(--color-primary); }
        .page-btn.disabled { opacity: 0.4; pointer-events: none; }

        /* Top Visitors */
        .top-section { margin-top: 22px; }
        .top-card { background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-lg); padding: 18px 20px; }
        .top-card-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; gap: 12px; flex-wrap: wrap; }
        .top-card-head .eyebrow { margin-bottom: 0; }
        .top-card-head select {
            padding: 8px 30px 8px 12px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md);
            font-family: var(--font-body); font-size: 0.82rem; font-weight: 600; color: var(--color-ink);
            background: var(--color-surface); outline: none; cursor: pointer; appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7' viewBox='0 0 11 7'%3E%3Cpath fill='none' stroke='%23647065' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' d='M1 1l4.5 4.5L10 1'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 11px center;
        }
        .top-card-head select:focus { border-color: var(--color-primary); }
        .top-item { display: flex; align-items: center; gap: 14px; padding: 11px 0; border-bottom: 1px solid var(--color-border); }
        .top-item:last-child { border-bottom: none; }
        .top-rank {
            width: 30px; height: 30px; border-radius: 50%; background: var(--color-neutral-tint);
            color: var(--color-ink-soft); font-weight: 700; font-size: 0.85rem;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .top-rank.gold { background: var(--color-accent-tint); color: var(--color-accent); }
        .top-info { flex: 1; min-width: 0; }
        .top-name { font-weight: 600; font-size: 0.9rem; }
        .top-id { font-size: 0.76rem; color: var(--color-ink-soft); font-family: var(--font-mono); }
        .top-count { font-weight: 700; font-size: 1.05rem; color: var(--color-primary); text-align: right; }
        .top-count-label { font-size: 0.7rem; color: var(--color-ink-faint); text-align: right; }
        .top-empty { text-align: center; padding: 30px; color: var(--color-ink-faint); font-size: 0.86rem; }

        /* Modal */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(20,28,22,0.5); z-index: 999; align-items: center; justify-content: center; padding: 16px; }
        .modal-overlay.active { display: flex; }
        .modal-box { background: var(--color-surface); border-radius: var(--radius-lg); width: 100%; max-width: 480px; box-shadow: 0 24px 60px -16px rgba(20,28,22,0.35); overflow: hidden; }
        .modal-head { background: var(--color-ink); border-bottom: 2px solid var(--color-accent); color: #fff; padding: 18px 22px; display: flex; justify-content: space-between; align-items: center; }
        .modal-head-title { display: flex; align-items: center; gap: 9px; font-size: 0.98rem; font-weight: 600; }
        .modal-head-title svg { width: 17px; height: 17px; color: var(--color-accent); }
        .modal-close { background: rgba(255,255,255,0.1); border: none; color: #fff; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .modal-close svg { width: 14px; height: 14px; }
        .modal-close:hover { background: rgba(255,255,255,0.2); }
        .modal-body { padding: 22px; }
        .modal-id-badge { background: var(--color-primary-tint); border: 1px dashed var(--color-primary); border-radius: var(--radius-md); padding: 12px 16px; margin-bottom: 16px; }
        .modal-id-text { font-family: var(--font-mono); font-size: 1.05rem; font-weight: 700; color: var(--color-primary-dark); letter-spacing: 0.03em; }
        .modal-id-name { font-size: 0.86rem; color: var(--color-ink-soft); margin-top: 2px; }
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px; }
        .detail-item { background: var(--color-neutral-tint); border-radius: 8px; padding: 10px 12px; }
        .detail-item-label { font-size: 0.68rem; color: var(--color-ink-soft); font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }
        .detail-item-value { font-size: 0.9rem; color: var(--color-ink); font-weight: 600; margin-top: 2px; }
        .stat-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin-bottom: 6px; }
        .stat-mini { background: var(--color-primary-tint); border-radius: 8px; padding: 10px 8px; text-align: center; }
        .stat-mini-num { font-family: var(--font-display); font-size: 1.3rem; font-weight: 600; color: var(--color-primary-dark); }
        .stat-mini-label { font-size: 0.66rem; color: var(--color-ink-soft); font-weight: 600; text-transform: uppercase; margin-top: 2px; }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="navbar-brand">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        <div class="navbar-brand-text">
            <b>Perpustakaan Desa</b>
            <span>Panel Admin</span>
        </div>
    </div>
    <div class="navbar-right">
        <div class="navbar-user">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            {{ session('admin')->username }}
        </div>
        <a href="{{ route('admin.logout') }}" class="btn-logout">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Keluar
        </a>
    </div>
</nav>

<div class="container">

    <div class="page-header">
        <h1>Dashboard</h1>
        <p>Data kunjungan perpustakaan desa</p>
    </div>

    <!-- Statistik -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg></div>
            <div><div class="stat-num">{{ $stats['total_kunjungan'] }}</div><div class="stat-label">Total Kunjungan</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon neutral"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
            <div><div class="stat-num">{{ $stats['total_pengunjung'] }}</div><div class="stat-label">Terdaftar</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon accent"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
            <div><div class="stat-num">{{ $stats['hari_ini'] }}</div><div class="stat-label">Hari Ini</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></div>
            <div><div class="stat-num">{{ $stats['baca_buku'] }}</div><div class="stat-label">Baca Buku</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg></div>
            <div><div class="stat-num">{{ $stats['pinjam_buku'] }}</div><div class="stat-label">Pinjam Buku</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></div>
            <div><div class="stat-num">{{ $stats['belajar_komputer'] }}</div><div class="stat-label">Belajar Komputer</div></div>
        </div>
    </div>

    <!-- Filter Panel -->
    <div class="filter-panel">
        <div class="filter-panel-head">
            <div class="eyebrow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Filter Data
            </div>
            <a href="{{ route('admin.dashboard') }}" class="link-reset">Hapus semua filter</a>
        </div>
        <form method="GET" action="{{ route('admin.dashboard') }}" id="filterForm">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <input type="hidden" name="peringkat" value="{{ $peringkat }}">
            <div class="filter-row">
                <div class="filter-group">
                    <label>Bulan</label>
                    <select name="bulan">
                        <option value="">Semua Bulan</option>
                        @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $val=>$label)
                            <option value="{{ $val }}" {{ $bulan == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label>Tahun</label>
                    <select name="tahun">
                        @for($y = date('Y'); $y >= 2024; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="filter-group">
                    <label>RW</label>
                    <select name="rw">
                        <option value="">Semua RW</option>
                        @for($i = 1; $i <= 10; $i++)
                            @php $val = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                            <option value="{{ $val }}" {{ $rw == $val ? 'selected' : '' }}>RW {{ $val }}</option>
                        @endfor
                    </select>
                </div>
                <div class="filter-group">
                    <label>RT</label>
                    <select name="rt">
                        <option value="">Semua RT</option>
                        @for($i = 1; $i <= 10; $i++)
                            @php $val = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                            <option value="{{ $val }}" {{ $rt == $val ? 'selected' : '' }}>RT {{ $val }}</option>
                        @endfor
                    </select>
                </div>
                <div class="filter-group">
                    <label>Cari nama / ID</label>
                    <div class="search-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" name="search" id="search-input" value="{{ $search }}" placeholder="Contoh: muhammad0001">
                    </div>
                </div>
            </div>
        </form>
        <div class="filter-hint">Filter otomatis diterapkan begitu kamu ganti pilihan atau berhenti mengetik.</div>
    </div>

    <!-- Pills aktivitas -->
    @php
        $filterOptions = [
            'semua'            => ['label' => 'Semua Kunjungan', 'icon' => '<line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>'],
            'baca_buku'        => ['label' => 'Baca Buku', 'icon' => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>'],
            'pinjam_buku'      => ['label' => 'Pinjam Buku', 'icon' => '<path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>'],
            'belajar_komputer' => ['label' => 'Belajar Komputer', 'icon' => '<rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>'],
        ];
    @endphp
    <div class="pills">
        @foreach($filterOptions as $key => $opt)
            <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['filter' => $key])) }}"
               class="pill {{ $filter === $key ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">{!! $opt['icon'] !!}</svg>
                {{ $opt['label'] }}
            </a>
        @endforeach
    </div>

    <!-- Tabel -->
    <div class="table-card">
        <div class="table-header">
            <div class="table-header-title">Data Kunjungan</div>
            <div class="table-header-count">{{ $visits->total() }} data ditemukan</div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Waktu</th>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>RW/RT</th>
                    <th>Baca Buku</th>
                    <th>Pinjam Buku</th>
                    <th>Belajar Komputer</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visits as $i => $visit)
                <tr>
                    <td>{{ $visits->firstItem() + $i }}</td>
                    <td><span class="time-text">{{ $visit->visited_at->format('d/m/Y H:i') }}</span></td>
                    <td><span class="visitor-id">{{ $visit->visitor->visitor_id ?? '-' }}</span></td>
                    <td><span class="visitor-name">{{ $visit->visitor->name ?? '-' }}</span></td>
                    <td><span class="time-text">RW{{ $visit->visitor->rw ?? '-' }}/RT{{ $visit->visitor->rt ?? '-' }}</span></td>
                    <td><span class="status-dot {{ $visit->baca_buku ? 'yes' : 'no' }}"><span class="dot"></span>{{ $visit->baca_buku ? 'Ya' : 'Tidak' }}</span></td>
                    <td><span class="status-dot {{ $visit->pinjam_buku ? 'yes' : 'no' }}"><span class="dot"></span>{{ $visit->pinjam_buku ? 'Ya' : 'Tidak' }}</span></td>
                    <td><span class="status-dot {{ $visit->belajar_komputer ? 'yes' : 'no' }}"><span class="dot"></span>{{ $visit->belajar_komputer ? 'Ya' : 'Tidak' }}</span></td>
                    <td>
                        @if($visit->visitor)
                        <button class="btn-detail" onclick="showDetail({{ $visit->visitor->id }})">Lihat Detail</button>
                        @else
                        <span style="color:var(--color-ink-faint);font-size:0.8rem">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-msg">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                            <p>Belum ada data kunjungan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($visits->hasPages())
        <div class="pagination">
            @if(!$visits->onFirstPage())
                <a href="{{ $visits->previousPageUrl() }}" class="page-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>Sebelumnya</a>
            @else
                <span class="page-btn disabled"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>Sebelumnya</span>
            @endif
            <span class="page-info">Halaman {{ $visits->currentPage() }} dari {{ $visits->lastPage() }}</span>
            @if($visits->hasMorePages())
                <a href="{{ $visits->nextPageUrl() }}" class="page-btn">Berikutnya<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></a>
            @else
                <span class="page-btn disabled">Berikutnya<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></span>
            @endif
        </div>
        @endif
    </div>

    <!-- Top Pengunjung -->
    <div class="top-section">
        <div class="top-card">
            <div class="top-card-head">
                <div class="eyebrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                    Pengunjung Terbanyak
                </div>
                <select id="peringkat-select" onchange="gantiPeringkat(this.value)">
                    <option value="kunjungan" {{ $peringkat === 'kunjungan' ? 'selected' : '' }}>Kunjungan terbanyak</option>
                    <option value="baca_buku" {{ $peringkat === 'baca_buku' ? 'selected' : '' }}>Paling sering baca buku</option>
                    <option value="pinjam_buku" {{ $peringkat === 'pinjam_buku' ? 'selected' : '' }}>Paling sering pinjam buku</option>
                    <option value="belajar_komputer" {{ $peringkat === 'belajar_komputer' ? 'selected' : '' }}>Paling sering belajar komputer</option>
                </select>
            </div>

            @php
                $countKey = match($peringkat) {
                    'baca_buku' => 'baca_buku_count',
                    'pinjam_buku' => 'pinjam_buku_count',
                    'belajar_komputer' => 'belajar_komputer_count',
                    default => 'visits_count',
                };
                $countLabel = match($peringkat) {
                    'baca_buku' => 'kali baca',
                    'pinjam_buku' => 'kali pinjam',
                    'belajar_komputer' => 'kali belajar',
                    default => 'kunjungan',
                };
            @endphp

            @forelse($topVisitors as $idx => $top)
            <div class="top-item">
                <div class="top-rank {{ $idx === 0 ? 'gold' : '' }}">{{ $idx + 1 }}</div>
                <div class="top-info">
                    <div class="top-name">{{ $top->name }}</div>
                    <div class="top-id">{{ $top->visitor_id }} &middot; RW{{ $top->rw }}/RT{{ $top->rt }}</div>
                </div>
                <div>
                    <div class="top-count">{{ $top->{$countKey} }}</div>
                    <div class="top-count-label">{{ $countLabel }}</div>
                </div>
            </div>
            @empty
            <div class="top-empty">Belum ada data untuk kategori ini.</div>
            @endforelse
        </div>
    </div>

</div>

<!-- Modal Detail -->
<div class="modal-overlay" id="modal-detail">
    <div class="modal-box">
        <div class="modal-head">
            <div class="modal-head-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Detail Pengunjung
            </div>
            <button class="modal-close" onclick="closeDetail()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
        </div>
        <div class="modal-body" id="modal-content">
            <div style="text-align:center;padding:24px;color:var(--color-ink-faint);">Memuat data...</div>
        </div>
    </div>
</div>

<script>
const detailUrl = "{{ url('admin/visitor') }}";

function showDetail(id) {
    document.getElementById('modal-detail').classList.add('active');
    document.getElementById('modal-content').innerHTML = '<div style="text-align:center;padding:24px;color:var(--color-ink-faint);">Memuat data...</div>';

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
            document.getElementById('modal-content').innerHTML = '<div style="text-align:center;padding:24px;color:var(--color-danger);">Gagal memuat data.</div>';
        });
}

function closeDetail() {
    document.getElementById('modal-detail').classList.remove('active');
}

document.getElementById('modal-detail').addEventListener('click', function(e) {
    if (e.target === this) closeDetail();
});

// Filter otomatis: begitu select berubah, form langsung submit.
document.querySelectorAll('#filterForm select').forEach(function (el) {
    el.addEventListener('change', function () {
        document.getElementById('filterForm').submit();
    });
});

// Kolom pencarian: submit otomatis setelah user berhenti mengetik (debounce).
let searchTimeout;
document.getElementById('search-input').addEventListener('input', function () {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function () {
        document.getElementById('filterForm').submit();
    }, 500);
});

// Ganti kategori leaderboard "Pengunjung Terbanyak" tanpa tombol terapkan.
function gantiPeringkat(value) {
    const url = new URL(window.location.href);
    url.searchParams.set('peringkat', value);
    window.location.href = url.toString();
}
</script>
</body>
</html>
