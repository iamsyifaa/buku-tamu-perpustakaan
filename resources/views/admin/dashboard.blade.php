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
    <!-- Library export: dibuat di sisi browser (client-side), jadi tidak butuh
         paket composer tambahan di server. -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.4.0/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
    <style>
        :root {
            --color-bg: #F5F2ED;
            --color-surface: #FFFFFF;
            --color-ink: #13273F;
            --color-ink-soft: #55637A;
            --color-ink-faint: #92A0B3;
            --color-primary: #13273F;
            --color-primary-dark: #0B1A2B;
            --color-primary-tint: #E8ECF1;
            --color-accent: #E9D4C3;
            --color-accent-dark: #A07B52;
            --color-accent-tint: #F8F0E7;
            --color-neutral-tint: #EEF0F3;
            --color-border: #E5E2D9;
            --color-row-hover: #F7F4EF;
            --color-row-alt: #FAF8F4;
            --color-danger: #AB4A34;
            --color-danger-tint: #F8EAE6;
            --shadow-card: 0 1px 2px rgba(19,39,63,0.05), 0 1px 1px rgba(19,39,63,0.03);
            --shadow-card-hover: 0 6px 16px -4px rgba(19,39,63,0.12);
            --radius-md: 10px;
            --radius-lg: 14px;
            --font-display: 'Fraunces', Georgia, serif;
            --font-body: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-mono: 'JetBrains Mono', ui-monospace, monospace;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; overflow-x: hidden; }
        body { font-family: var(--font-body); background: var(--color-bg); color: var(--color-ink); min-height: 100vh; overflow-x: hidden; width: 100%; }
        ::selection { background: var(--color-accent); color: var(--color-primary-dark); }

        /* ---------- Navbar ---------- */
        .navbar {
            background: var(--color-ink); border-bottom: 2px solid var(--color-accent);
            color: #fff; padding: 0 24px;
            display: flex; align-items: center; justify-content: space-between;
            height: 64px; position: sticky; top: 0; z-index: 100;
            box-shadow: 0 2px 10px rgba(11,26,43,0.18);
        }
        .navbar-brand { display: flex; align-items: center; gap: 10px; min-width: 0; }
        .navbar-brand svg { width: 22px; height: 22px; color: var(--color-accent); flex-shrink: 0; }
        .navbar-brand-text { display: flex; flex-direction: column; line-height: 1.2; min-width: 0; }
        .navbar-brand-text b { font-family: var(--font-display); font-size: 1.05rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; max-width: 46vw; }
        .navbar-brand-text span { font-size: 0.7rem; color: rgba(255,255,255,0.55); letter-spacing: 0.05em; text-transform: uppercase; }
        .navbar-right { display: flex; align-items: center; gap: 14px; flex-shrink: 0; }
        .navbar-user { display: flex; align-items: center; gap: 7px; font-size: 0.85rem; color: rgba(255,255,255,0.85); }
        .navbar-user svg { width: 15px; height: 15px; color: rgba(255,255,255,0.6); flex-shrink: 0; }
        .navbar-username { max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .btn-logout {
            display: flex; align-items: center; gap: 6px;
            color: #fff; text-decoration: none; font-size: 0.82rem; font-weight: 600;
            background: rgba(255,255,255,0.08); padding: 7px 14px;
            border-radius: 20px; border: 1px solid rgba(255,255,255,0.18);
            transition: background 0.15s;
        }
        .btn-logout svg { width: 14px; height: 14px; flex-shrink: 0; }
        .btn-logout:hover { background: rgba(255,255,255,0.16); }

        /* Full-bleed: nempel ke pinggir layar, nggak dibatasin max-width lagi. */
        .container { width: 100%; margin: 0; padding: 28px clamp(20px, 3vw, 48px) 56px; }

        .page-header { margin-bottom: 24px; display: flex; align-items: baseline; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
        .page-header h1 { font-family: var(--font-display); font-size: 1.6rem; font-weight: 600; color: var(--color-ink); }
        .page-header p { color: var(--color-ink-soft); font-size: 0.9rem; margin-top: 4px; }
        .page-header-date { font-size: 0.82rem; color: var(--color-ink-faint); font-weight: 500; }

        .eyebrow {
            display: flex; align-items: center; gap: 8px;
            font-size: 0.72rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--color-accent-dark); margin-bottom: 14px;
        }
        .eyebrow svg { width: 14px; height: 14px; }

        /* ---------- Layout: statistik jadi kolom kiri, konten di kanan ---------- */
        .layout-grid { display: grid; grid-template-columns: 260px minmax(0,1fr); gap: 20px; align-items: start; }
        @media(max-width:900px) { .layout-grid { grid-template-columns: 1fr; } }

        .stats-col { display: flex; flex-direction: column; gap: 12px; }
        @media(max-width:900px) { .stats-col { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; } }
        @media(max-width:560px) { .stats-col { grid-template-columns: repeat(2, 1fr); } }

        .stat-card {
            background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-lg);
            padding: 14px 16px; display: flex; align-items: center; gap: 12px;
            box-shadow: var(--shadow-card); transition: box-shadow 0.2s ease;
        }
        .stat-card:hover { box-shadow: var(--shadow-card-hover); }
        .stat-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .stat-icon svg { width: 18px; height: 18px; }
        .stat-icon.primary { background: var(--color-primary-tint); color: var(--color-primary); }
        .stat-icon.neutral { background: var(--color-neutral-tint); color: var(--color-ink-soft); }
        .stat-icon.accent  { background: var(--color-accent-tint); color: var(--color-accent-dark); }
        .stat-text { min-width: 0; }
        .stat-num { font-family: var(--font-display); font-size: 1.35rem; font-weight: 600; line-height: 1.1; }
        .stat-label { font-size: 0.7rem; color: var(--color-ink-soft); font-weight: 600; text-transform: uppercase; letter-spacing: 0.03em; white-space: nowrap; }

        .main-col { min-width: 0; }

        /* ---------- Filter panel ---------- */
        .filter-panel {
            background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-lg);
            padding: 20px; margin-bottom: 16px; box-shadow: var(--shadow-card);
        }
        .filter-panel-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; gap: 10px; flex-wrap: wrap; }
        .filter-panel-head .eyebrow { margin-bottom: 0; }
        .btn-reset-filter {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 0.8rem; font-weight: 600; color: var(--color-danger);
            background: var(--color-danger-tint); border: 1px solid transparent;
            padding: 8px 15px; border-radius: 20px; text-decoration: none; transition: all 0.15s;
        }
        .btn-reset-filter svg { width: 13px; height: 13px; }
        .btn-reset-filter:hover { background: var(--color-danger); color: #fff; }
        .filter-row { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)) 1.5fr; gap: 12px; align-items: end; }
        @media(max-width:920px) { .filter-row { grid-template-columns: repeat(2, minmax(0,1fr)); } }
        @media(max-width:520px) { .filter-row { grid-template-columns: 1fr; } }
        .filter-group { display: flex; flex-direction: column; gap: 6px; min-width: 0; }
        .filter-group label { font-size: 0.74rem; color: var(--color-ink-soft); font-weight: 600; }
        .filter-group select, .filter-group input {
            padding: 10px 12px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md);
            font-family: var(--font-body); font-size: 0.88rem; outline: none; background: var(--color-surface); color: var(--color-ink);
            transition: border-color 0.15s, box-shadow 0.15s; width: 100%;
        }
        .filter-group select { appearance: none; cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7' viewBox='0 0 11 7'%3E%3Cpath fill='none' stroke='%2355637A' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' d='M1 1l4.5 4.5L10 1'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 12px center; padding-right: 30px;
        }
        .filter-group select:focus, .filter-group input:focus { border-color: var(--color-primary); box-shadow: 0 0 0 3px var(--color-primary-tint); }
        .search-wrap { position: relative; }
        .search-wrap svg { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); width: 15px; height: 15px; color: var(--color-ink-faint); }
        .search-wrap input { padding-left: 32px; }
        .filter-hint { font-size: 0.74rem; color: var(--color-ink-faint); margin-top: 12px; }

        /* ---------- Pills ---------- */
        .pills { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; }
        .pills-secondary { margin-bottom: 18px; padding: 12px 14px; background: var(--color-primary-tint); border-radius: var(--radius-md); }
        .pills-secondary-label { width: 100%; font-size: 0.72rem; font-weight: 700; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 8px; }
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
        .pill-sm { padding: 6px 13px; font-size: 0.79rem; }

        /* ---------- Table ---------- */
        .table-card { background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-card); margin-bottom: 24px; }
        .table-header { padding: 16px 20px; border-bottom: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
        .table-header-left { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .table-header-title { font-family: var(--font-display); font-size: 1.05rem; font-weight: 600; }
        .table-header-count { font-size: 0.8rem; color: var(--color-ink-soft); background: var(--color-primary-tint); padding: 4px 10px; border-radius: 20px; font-weight: 600; }
        .table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: var(--color-primary-tint); }
        th { padding: 12px 14px; text-align: left; font-size: 0.72rem; font-weight: 700; color: var(--color-primary-dark); text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; }
        td { padding: 13px 14px; font-size: 0.86rem; color: var(--color-ink); border-bottom: 1px solid var(--color-border); vertical-align: middle; }
        tbody tr:nth-child(even) td { background: var(--color-row-alt); }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: var(--color-row-hover); }
        .visitor-id { font-weight: 600; color: var(--color-primary); font-family: var(--font-mono); font-size: 0.8rem; white-space: nowrap; }
        .visitor-name { font-weight: 600; }
        .time-text { color: var(--color-ink-soft); font-size: 0.8rem; white-space: nowrap; }

        /* Kolom aktivitas dipadatkan jadi ikon kecil, bukan kolom terpisah,
           supaya tabel muat tanpa perlu digeser (scroll horizontal). */
        .activity-icons { display: flex; gap: 5px; }
        .act-badge {
            min-width: 26px; height: 26px; padding: 0 6px; border-radius: 7px;
            display: inline-flex; align-items: center; justify-content: center; gap: 3px;
            background: var(--color-neutral-tint); color: var(--color-ink-faint); font-size: 0.72rem; font-weight: 700;
        }
        .act-badge svg { width: 13px; height: 13px; }
        .act-badge.on { background: var(--color-primary-tint); color: var(--color-primary); }

        .rank-badge {
            width: 28px; height: 28px; border-radius: 50%; background: var(--color-neutral-tint);
            color: var(--color-ink-soft); font-weight: 700; font-size: 0.82rem;
            display: flex; align-items: center; justify-content: center;
        }
        .rank-badge.gold { background: var(--color-accent-tint); color: var(--color-accent-dark); }
        .nilai-peringkat { font-weight: 700; color: var(--color-primary); font-size: 0.95rem; }

        .btn-detail {
            padding: 6px 14px; background: transparent; color: var(--color-primary);
            border: 1.5px solid var(--color-primary); border-radius: var(--radius-md); font-size: 0.78rem;
            cursor: pointer; font-weight: 600; transition: all 0.15s; white-space: nowrap;
        }
        .btn-detail:hover { background: var(--color-primary); color: #fff; }
        .empty-msg { text-align: center; padding: 56px 20px; color: var(--color-ink-faint); }
        .empty-msg svg { width: 36px; height: 36px; margin-bottom: 10px; color: var(--color-ink-faint); }
        .empty-msg p { font-size: 0.9rem; }

        /* ---------- Export dropdown ---------- */
        .export-wrap { position: relative; }
        .btn-export {
            display: inline-flex; align-items: center; gap: 7px; padding: 8px 16px;
            background: var(--color-ink); color: #fff; border: none; border-radius: 20px;
            font-size: 0.82rem; font-weight: 600; cursor: pointer; transition: background 0.15s;
        }
        .btn-export svg { width: 15px; height: 15px; }
        .btn-export:hover:not(:disabled) { background: var(--color-primary-dark); }
        .btn-export:disabled { opacity: 0.6; cursor: wait; }
        .export-menu {
            display: none; position: absolute; right: 0; top: calc(100% + 8px);
            background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-md);
            box-shadow: var(--shadow-card-hover); min-width: 230px; padding: 6px; z-index: 60;
        }
        .export-menu.open { display: block; }
        .export-menu-item {
            display: flex; align-items: center; gap: 10px; padding: 10px 12px; width: 100%;
            border-radius: 8px; text-decoration: none; color: var(--color-ink); transition: background 0.15s;
            background: none; border: none; cursor: pointer; font-family: var(--font-body); text-align: left;
        }
        .export-menu-item:hover { background: var(--color-primary-tint); }
        .export-menu-item svg { width: 17px; height: 17px; color: var(--color-primary); flex-shrink: 0; }
        .export-menu-title { font-size: 0.85rem; font-weight: 600; }

        /* ---------- Pagination ---------- */
        .pagination { padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; border-top: 1px solid var(--color-border); }
        .page-info { font-size: 0.82rem; color: var(--color-ink-soft); }
        .page-btn {
            padding: 7px 14px; border-radius: var(--radius-md); border: 1.5px solid var(--color-border);
            background: var(--color-surface); font-size: 0.82rem; font-weight: 600; text-decoration: none; color: var(--color-ink);
            transition: all 0.15s; display: inline-flex; align-items: center; gap: 4px;
        }
        .page-btn svg { width: 14px; height: 14px; }
        .page-btn:hover { border-color: var(--color-primary); color: var(--color-primary); }
        .page-btn.disabled { opacity: 0.4; pointer-events: none; }

        /* ---------- Modal ---------- */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(19,39,63,0.5); z-index: 999; align-items: center; justify-content: center; padding: 16px; }
        .modal-overlay.active { display: flex; }
        .modal-box { background: var(--color-surface); border-radius: var(--radius-lg); width: 100%; max-width: 480px; box-shadow: 0 24px 60px -16px rgba(19,39,63,0.35); overflow: hidden; }
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

        @media(max-width:480px) {
            .container { padding: 18px 12px 40px; }
            .navbar { padding: 0 14px; }
            .navbar-username { display: none; }
            .btn-logout span.btn-logout-text { display: none; }
            .btn-logout { padding: 8px; }
            .page-header { margin-bottom: 18px; }
            .page-header h1 { font-size: 1.3rem; }
            .page-header-date { font-size: 0.74rem; }
            .filter-panel { padding: 14px; }
            .filter-panel-head { align-items: flex-start; }
            .table-header { padding: 12px 14px; }
            .table-header-title { font-size: 0.95rem; }
            th, td { padding: 10px 10px; font-size: 0.8rem; }
            .btn-export span#btn-export-label { display: none; }
            .btn-export { padding: 8px; }
            .export-menu { right: -8px; min-width: 210px; }
            .modal-box { max-width: 100%; }
            .detail-grid, .stat-row { grid-template-columns: 1fr 1fr; }
        }
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
            <span class="navbar-username">{{ session('admin')->username }}</span>
        </div>
        <a href="{{ route('admin.logout') }}" class="btn-logout">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            <span class="btn-logout-text">Keluar</span>
        </a>
    </div>
</nav>

<div class="container">

    <div class="page-header">
        <div>
            <h1>Dashboard</h1>
            <p>Data kunjungan perpustakaan desa</p>
        </div>
        <div class="page-header-date">{{ now()->translatedFormat('l, d F Y') }}</div>
    </div>

    <div class="layout-grid">

        <!-- Kolom kiri: statistik -->
        <aside class="stats-col">
            <div class="stat-card">
                <div class="stat-icon primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg></div>
                <div class="stat-text"><div class="stat-num">{{ $stats['total_kunjungan'] }}</div><div class="stat-label">Total Kunjungan</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon neutral"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
                <div class="stat-text"><div class="stat-num">{{ $stats['total_pengunjung'] }}</div><div class="stat-label">Terdaftar</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon accent"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
                <div class="stat-text"><div class="stat-num">{{ $stats['hari_ini'] }}</div><div class="stat-label">Hari Ini</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></div>
                <div class="stat-text"><div class="stat-num">{{ $stats['baca_buku'] }}</div><div class="stat-label">Baca Buku</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg></div>
                <div class="stat-text"><div class="stat-num">{{ $stats['pinjam_buku'] }}</div><div class="stat-label">Pinjam Buku</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></div>
                <div class="stat-text"><div class="stat-num">{{ $stats['belajar_komputer'] }}</div><div class="stat-label">Belajar Komputer</div></div>
            </div>
        </aside>

        <!-- Kolom kanan: filter + tabel -->
        <div class="main-col">

            <!-- Filter Panel -->
            <div class="filter-panel">
                <div class="filter-panel-head">
                    <div class="eyebrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Filter Data
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="btn-reset-filter">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
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

            <!-- Pills utama -->
            @php
                $filterOptions = [
                    'semua'            => ['label' => 'Semua Kunjungan', 'icon' => '<line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>'],
                    'baca_buku'        => ['label' => 'Baca Buku', 'icon' => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>'],
                    'pinjam_buku'      => ['label' => 'Pinjam Buku', 'icon' => '<path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>'],
                    'belajar_komputer' => ['label' => 'Belajar Komputer', 'icon' => '<rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>'],
                    'pengunjung_terbanyak' => ['label' => 'Pengunjung Terbanyak', 'icon' => '<circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>'],
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

            <!-- Sub-filter kategori peringkat, cuma muncul pas mode Pengunjung Terbanyak -->
            @if($isTopMode)
                @php
                    $peringkatOptions = [
                        'semua'            => 'Semua Kunjungan',
                        'baca_buku'        => 'Baca Buku',
                        'pinjam_buku'      => 'Pinjam Buku',
                        'belajar_komputer' => 'Belajar Komputer',
                    ];
                @endphp
                <div class="pills pills-secondary">
                    <div class="pills-secondary-label">Urutkan berdasarkan</div>
                    @foreach($peringkatOptions as $key => $label)
                        <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['peringkat' => $key])) }}"
                           class="pill pill-sm {{ $peringkat === $key ? 'active' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Tabel -->
            <div class="table-card">
                <div class="table-header">
                    <div class="table-header-left">
                        <div class="table-header-title">{{ $isTopMode ? 'Pengunjung Terbanyak' : 'Data Kunjungan' }}</div>
                        <div class="table-header-count">
                            {{ $isTopMode ? $topVisitors->count() : $visits->total() }} data ditemukan
                        </div>
                    </div>
                    <div class="export-wrap">
                        <button type="button" class="btn-export" id="btn-export" onclick="toggleExportMenu()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            <span id="btn-export-label">Export</span>
                        </button>
                        <div class="export-menu" id="export-menu">
                            <button type="button" class="export-menu-item" onclick="exportSekarang('excel')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                <div class="export-menu-title">Export sebagai Excel</div>
                            </button>
                            <button type="button" class="export-menu-item" onclick="exportSekarang('pdf')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="10" y1="12" x2="14" y2="12"/><line x1="10" y1="16" x2="14" y2="16"/></svg>
                                <div class="export-menu-title">Export sebagai PDF</div>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-scroll">
                @if($isTopMode)
                    <table>
                        <thead>
                            <tr>
                                <th>Peringkat</th>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Lokasi</th>
                                <th>Aktivitas</th>
                                <th>Nilai ({{ $labelPeringkat }})</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topVisitors as $idx => $top)
                            <tr>
                                <td><span class="rank-badge {{ $idx === 0 ? 'gold' : '' }}">{{ $idx + 1 }}</span></td>
                                <td><span class="visitor-id">{{ $top->visitor_id }}</span></td>
                                <td><span class="visitor-name">{{ $top->name }}</span></td>
                                <td><span class="time-text">RW{{ $top->rw ?? '-' }}/RT{{ $top->rt ?? '-' }}</span></td>
                                <td>
                                    <div class="activity-icons">
                                        <span class="act-badge {{ $top->baca_buku_count > 0 ? 'on' : '' }}" title="Baca Buku">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>{{ $top->baca_buku_count }}
                                        </span>
                                        <span class="act-badge {{ $top->pinjam_buku_count > 0 ? 'on' : '' }}" title="Pinjam Buku">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>{{ $top->pinjam_buku_count }}
                                        </span>
                                        <span class="act-badge {{ $top->belajar_komputer_count > 0 ? 'on' : '' }}" title="Belajar Komputer">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>{{ $top->belajar_komputer_count }}
                                        </span>
                                    </div>
                                </td>
                                <td><span class="nilai-peringkat">{{ $top->{$kolomPeringkat} }}</span></td>
                                <td><button class="btn-detail" onclick="showDetail({{ $top->id }})">Detail</button></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-msg">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                                        <p>Belum ada data untuk kategori ini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
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
                                <td><span class="time-text">{{ $visit->visited_at->format('d/m/y H:i') }}</span></td>
                                <td><span class="visitor-id">{{ $visit->visitor->visitor_id ?? '-' }}</span></td>
                                <td><span class="visitor-name">{{ $visit->visitor->name ?? '-' }}</span></td>
                                <td><span class="time-text">RW{{ $visit->visitor->rw ?? '-' }}/RT{{ $visit->visitor->rt ?? '-' }}</span></td>
                                <td>
                                    <div class="activity-icons">
                                        <span class="act-badge {{ $visit->baca_buku ? 'on' : '' }}" title="Baca Buku: {{ $visit->baca_buku ? 'Ya' : 'Tidak' }}">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                                        </span>
                                        <span class="act-badge {{ $visit->pinjam_buku ? 'on' : '' }}" title="Pinjam Buku: {{ $visit->pinjam_buku ? 'Ya' : 'Tidak' }}">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                                        </span>
                                        <span class="act-badge {{ $visit->belajar_komputer ? 'on' : '' }}" title="Belajar Komputer: {{ $visit->belajar_komputer ? 'Ya' : 'Tidak' }}">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    @if($visit->visitor)
                                    <button class="btn-detail" onclick="showDetail({{ $visit->visitor->id }})">Detail</button>
                                    @else
                                    <span style="color:var(--color-ink-faint);font-size:0.8rem">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-msg">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                                        <p>Belum ada data kunjungan.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif
                </div>

                @if(!$isTopMode && $visits->hasPages())
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
const exportDataUrl = "{{ route('admin.export-data') }}";

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

// Dropdown menu export: toggle buka/tutup, tutup kalau klik di luar.
function toggleExportMenu() {
    document.getElementById('export-menu').classList.toggle('open');
}
document.addEventListener('click', function (e) {
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
        const res = await fetch(exportDataUrl + window.location.search);
        const data = await res.json();

        if (!data.rows || data.rows.length === 0) {
            alert('Tidak ada data untuk diekspor.');
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
        return [
            { header: 'No', key: 'no', width: 6 },
            { header: 'ID Pengunjung', key: 'id', width: 16 },
            { header: 'Nama', key: 'nama', width: 24 },
            { header: 'Desa', key: 'desa', width: 16 },
            { header: 'RW', key: 'rw', width: 8 },
            { header: 'RT', key: 'rt', width: 8 },
            { header: 'Total Kunjungan', key: 'total_kunjungan', width: 14 },
            { header: 'Baca Buku', key: 'baca_buku', width: 12 },
            { header: 'Pinjam Buku', key: 'pinjam_buku', width: 12 },
            { header: 'Belajar Komputer', key: 'belajar_komputer', width: 15 },
        ];
    }
    return [
        { header: 'No', key: 'no', width: 6 },
        { header: 'Waktu', key: 'waktu', width: 16 },
        { header: 'ID Pengunjung', key: 'id', width: 16 },
        { header: 'Nama', key: 'nama', width: 22 },
        { header: 'Desa', key: 'desa', width: 14 },
        { header: 'RW', key: 'rw', width: 7 },
        { header: 'RT', key: 'rt', width: 7 },
        { header: 'Alamat', key: 'alamat', width: 22 },
        { header: 'Umur', key: 'umur', width: 8 },
        { header: 'Baca Buku', key: 'baca_buku', width: 11 },
        { header: 'Pinjam Buku', key: 'pinjam_buku', width: 12 },
        { header: 'Belajar Komputer', key: 'belajar_komputer', width: 15 },
    ];
}

async function exportExcel(data) {
    const kolom = kolomExport(data.mode);
    const jumlahKolom = kolom.length;

    const wb = new ExcelJS.Workbook();
    wb.creator = 'Perpustakaan Desa';
    wb.created = new Date();
    const ws = wb.addWorksheet('Data', { views: [{ state: 'frozen', ySplit: 4 }] });
    ws.columns = kolom.map(k => ({ width: k.width }));

    ws.mergeCells(1, 1, 1, jumlahKolom);
    const judul = ws.getCell(1, 1);
    judul.value = data.judul.toUpperCase();
    judul.font = { bold: true, size: 14, color: { argb: 'FF13273F' } };
    ws.getRow(1).height = 24;

    ws.mergeCells(2, 1, 2, jumlahKolom);
    const sub = ws.getCell(2, 1);
    sub.value = `Periode: ${data.periode}  |  Tanggal Export: ${new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })}`;
    sub.font = { italic: true, size: 10.5, color: { argb: 'FF55637A' } };

    const headerRow = ws.getRow(4);
    kolom.forEach((k, i) => {
        const cell = headerRow.getCell(i + 1);
        cell.value = k.header;
        cell.font = { bold: true, color: { argb: 'FFFFFFFF' }, size: 11 };
        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF13273F' } };
        cell.alignment = { vertical: 'middle', horizontal: i === 0 ? 'center' : 'left' };
        cell.border = {
            top: { style: 'thin', color: { argb: 'FF0B1A2B' } }, bottom: { style: 'thin', color: { argb: 'FF0B1A2B' } },
            left: { style: 'thin', color: { argb: 'FF0B1A2B' } }, right: { style: 'thin', color: { argb: 'FF0B1A2B' } },
        };
    });
    headerRow.height = 20;

    data.rows.forEach((r, i) => {
        const row = ws.getRow(5 + i);
        kolom.forEach((k, c) => {
            const cell = row.getCell(c + 1);
            cell.value = r[k.key] ?? '-';
            cell.font = { size: 10.5, color: { argb: 'FF13273F' } };
            cell.alignment = { vertical: 'middle', horizontal: c === 0 ? 'center' : 'left' };
            cell.border = {
                top: { style: 'thin', color: { argb: 'FFE5E2D9' } }, bottom: { style: 'thin', color: { argb: 'FFE5E2D9' } },
                left: { style: 'thin', color: { argb: 'FFE5E2D9' } }, right: { style: 'thin', color: { argb: 'FFE5E2D9' } },
            };
            if (i % 2 === 1) cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFAF8F4' } };
        });
        row.height = 18;
    });

    const totalRowIdx = 5 + data.rows.length + 1;
    ws.mergeCells(totalRowIdx, 1, totalRowIdx, jumlahKolom);
    const totalCell = ws.getCell(totalRowIdx, 1);
    totalCell.value = `Total: ${data.rows.length} data`;
    totalCell.font = { bold: true, size: 10.5, color: { argb: 'FF13273F' } };

    const buffer = await wb.xlsx.writeBuffer();
    const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    unduhBlob(blob, namaFileExport(data, 'xlsx'));
}

function exportPdf(data) {
    const { jsPDF } = window.jspdf;
    const kolom = kolomExport(data.mode);
    const doc = new jsPDF({ orientation: 'landscape', unit: 'pt', format: 'a4' });

    doc.setFont('helvetica', 'bold');
    doc.setFontSize(14);
    doc.setTextColor(19, 39, 63);
    doc.text(data.judul.toUpperCase(), 40, 40);

    doc.setFont('helvetica', 'italic');
    doc.setFontSize(9.5);
    doc.setTextColor(85, 99, 122);
    const tanggalExport = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
    doc.text(`Periode: ${data.periode}   |   Tanggal Export: ${tanggalExport}`, 40, 56);

    doc.autoTable({
        startY: 72,
        head: [kolom.map(k => k.header)],
        body: data.rows.map(r => kolom.map(k => String(r[k.key] ?? '-'))),
        styles: { font: 'helvetica', fontSize: 8.5, textColor: [19, 39, 63], lineColor: [229, 226, 217], lineWidth: 0.5 },
        headStyles: { fillColor: [19, 39, 63], textColor: [255, 255, 255], fontStyle: 'bold' },
        alternateRowStyles: { fillColor: [250, 248, 244] },
        margin: { left: 40, right: 40 },
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
