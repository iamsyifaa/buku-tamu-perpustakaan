<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Perpustakaan Desa</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f0f2f5; min-height: 100vh; }

        /* Navbar */
        .navbar { background: #2c7a3f; color: #fff; padding: 14px 24px; display: flex; align-items: center; justify-content: space-between; }
        .navbar-title { font-size: 1.1rem; font-weight: bold; }
        .navbar-logout { color: #fff; text-decoration: none; font-size: 0.88rem; background: rgba(255,255,255,0.15); padding: 6px 14px; border-radius: 4px; }
        .navbar-logout:hover { background: rgba(255,255,255,0.25); }

        /* Container */
        .container { max-width: 1100px; margin: 24px auto; padding: 0 16px; }

        /* Stat cards */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 14px; margin-bottom: 24px; }
        .stat-card { background: #fff; border-radius: 8px; padding: 18px 16px; text-align: center; box-shadow: 0 1px 6px rgba(0,0,0,0.08); }
        .stat-num { font-size: 2rem; font-weight: bold; color: #2c7a3f; }
        .stat-label { font-size: 0.82rem; color: #666; margin-top: 4px; }

        /* Filter + Search */
        .toolbar { background: #fff; border-radius: 8px; padding: 16px 20px; margin-bottom: 16px; box-shadow: 0 1px 6px rgba(0,0,0,0.08); display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
        .filter-btn { padding: 8px 16px; border-radius: 20px; border: 2px solid #ddd; background: #fff; font-size: 0.88rem; cursor: pointer; text-decoration: none; color: #333; transition: all 0.2s; }
        .filter-btn:hover { border-color: #2c7a3f; color: #2c7a3f; }
        .filter-btn.active { background: #2c7a3f; color: #fff; border-color: #2c7a3f; }
        .search-box { margin-left: auto; display: flex; gap: 8px; }
        .search-box input { padding: 8px 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 0.88rem; outline: none; width: 200px; }
        .search-box input:focus { border-color: #2c7a3f; }
        .search-box button { padding: 8px 16px; background: #2c7a3f; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 0.88rem; }

        /* Table */
        .table-wrap { background: #fff; border-radius: 8px; box-shadow: 0 1px 6px rgba(0,0,0,0.08); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #2c7a3f; color: #fff; }
        th { padding: 12px 14px; text-align: left; font-size: 0.88rem; font-weight: 600; }
        td { padding: 11px 14px; font-size: 0.88rem; color: #333; border-bottom: 1px solid #f0f0f0; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f9fef9; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 10px; font-size: 0.78rem; font-weight: 600; }
        .badge-yes { background: #d4edda; color: #155724; }
        .badge-no  { background: #f0f0f0; color: #999; }

        /* Pagination */
        .pagination-wrap { padding: 16px 20px; display: flex; justify-content: flex-end; gap: 6px; }
        .page-btn { padding: 6px 12px; border-radius: 4px; border: 1px solid #ddd; background: #fff; font-size: 0.85rem; text-decoration: none; color: #333; }
        .page-btn:hover { border-color: #2c7a3f; color: #2c7a3f; }
        .page-btn.active { background: #2c7a3f; color: #fff; border-color: #2c7a3f; }

        .empty-msg { text-align: center; padding: 40px; color: #aaa; }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="navbar-title">📚 Admin Perpustakaan Desa</div>
    <a href="{{ route('admin.logout') }}" class="navbar-logout">Keluar</a>
</nav>

<div class="container">

    <!-- Statistik -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-num">{{ $stats['total_kunjungan'] }}</div>
            <div class="stat-label">Total Kunjungan</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">{{ $stats['total_pengunjung'] }}</div>
            <div class="stat-label">Pengunjung Terdaftar</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">{{ $stats['hari_ini'] }}</div>
            <div class="stat-label">Kunjungan Hari Ini</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">{{ $stats['baca_buku'] }}</div>
            <div class="stat-label">Baca Buku</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">{{ $stats['pinjam_buku'] }}</div>
            <div class="stat-label">Pinjam Buku</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">{{ $stats['belajar_komputer'] }}</div>
            <div class="stat-label">Belajar Komputer</div>
        </div>
    </div>

    <!-- Filter + Search -->
    <div class="toolbar">
        @php
            $filters = [
                'semua'           => 'Semua Kunjungan',
                'baca_buku'       => '📖 Baca Buku',
                'pinjam_buku'     => '📋 Pinjam Buku',
                'belajar_komputer'=> '💻 Belajar Komputer',
            ];
        @endphp

        @foreach($filters as $key => $label)
            <a href="{{ route('admin.dashboard', ['filter' => $key, 'search' => $search]) }}"
               class="filter-btn {{ $filter === $key ? 'active' : '' }}">
                {{ $label }}
            </a>
        @endforeach

        <form class="search-box" method="GET" action="{{ route('admin.dashboard') }}">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama / ID...">
            <button type="submit">Cari</button>
        </form>
    </div>

    <!-- Tabel Data -->
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Waktu Kunjungan</th>
                    <th>ID Pengunjung</th>
                    <th>Nama</th>
                    <th>Baca Buku</th>
                    <th>Pinjam Buku</th>
                    <th>Belajar Komputer</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visits as $i => $visit)
                <tr>
                    <td>{{ $visits->firstItem() + $i }}</td>
                    <td>{{ $visit->visited_at->format('d/m/Y H:i') }}</td>
                    <td><strong>{{ $visit->visitor->visitor_id ?? '-' }}</strong></td>
                    <td>{{ $visit->visitor->name ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $visit->baca_buku ? 'badge-yes' : 'badge-no' }}">
                            {{ $visit->baca_buku ? 'Ya' : 'Tidak' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $visit->pinjam_buku ? 'badge-yes' : 'badge-no' }}">
                            {{ $visit->pinjam_buku ? 'Ya' : 'Tidak' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $visit->belajar_komputer ? 'badge-yes' : 'badge-no' }}">
                            {{ $visit->belajar_komputer ? 'Ya' : 'Tidak' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty-msg">Belum ada data kunjungan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($visits->hasPages())
        <div class="pagination-wrap">
            @foreach($visits->links()->elements[0] as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $visits->currentPage() == $page ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
        </div>
        @endif
    </div>

</div>
</body>
</html>
