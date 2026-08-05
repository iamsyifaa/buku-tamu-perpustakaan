<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Perpustakaan Desa</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; min-height: 100vh; }

        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, #1a5c2e, #2c7a3f);
            color: #fff; padding: 0 28px;
            display: flex; align-items: center; justify-content: space-between;
            height: 60px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .navbar-brand { display: flex; align-items: center; gap: 10px; font-size: 1.05rem; font-weight: 700; }
        .navbar-brand span { font-size: 1.4rem; }
        .navbar-right { display: flex; align-items: center; gap: 12px; }
        .navbar-user { font-size: 0.85rem; opacity: 0.85; }
        .btn-logout {
            color: #fff; text-decoration: none; font-size: 0.82rem;
            background: rgba(255,255,255,0.15); padding: 7px 16px;
            border-radius: 20px; border: 1px solid rgba(255,255,255,0.3);
            transition: all 0.2s;
        }
        .btn-logout:hover { background: rgba(255,255,255,0.25); }

        .container { max-width: 1200px; margin: 0 auto; padding: 24px 20px; }

        /* Page header */
        .page-header { margin-bottom: 20px; }
        .page-header h1 { font-size: 1.25rem; color: #1a3c2e; font-weight: 700; }
        .page-header p { color: #777; font-size: 0.85rem; margin-top: 2px; }

        /* Stats */
        .stats-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 12px; margin-bottom: 20px; }
        @media(max-width:900px) { .stats-grid { grid-template-columns: repeat(3, 1fr); } }
        .stat-card {
            background: #fff; border-radius: 10px; padding: 16px 14px;
            text-align: center; box-shadow: 0 1px 4px rgba(0,0,0,0.07);
            border-top: 3px solid #2c7a3f; transition: transform 0.15s;
        }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-card.blue { border-top-color: #3498db; }
        .stat-card.orange { border-top-color: #e67e22; }
        .stat-card.purple { border-top-color: #9b59b6; }
        .stat-card.teal { border-top-color: #1abc9c; }
        .stat-card.red { border-top-color: #e74c3c; }
        .stat-num { font-size: 1.9rem; font-weight: 800; color: #2c7a3f; line-height: 1; }
        .stat-card.blue .stat-num { color: #3498db; }
        .stat-card.orange .stat-num { color: #e67e22; }
        .stat-card.purple .stat-num { color: #9b59b6; }
        .stat-card.teal .stat-num { color: #1abc9c; }
        .stat-card.red .stat-num { color: #e74c3c; }
        .stat-label { font-size: 0.75rem; color: #888; margin-top: 5px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px; }

        /* Filter panel */
        .filter-panel {
            background: #fff; border-radius: 10px; padding: 16px 20px;
            margin-bottom: 16px; box-shadow: 0 1px 4px rgba(0,0,0,0.07);
        }
        .filter-panel-title { font-size: 0.78rem; font-weight: 700; color: #2c7a3f; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; }
        .filter-row { display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end; }
        .filter-group { display: flex; flex-direction: column; gap: 4px; }
        .filter-group label { font-size: 0.75rem; color: #666; font-weight: 600; }
        .filter-group select, .filter-group input {
            padding: 7px 10px; border: 1px solid #ddd; border-radius: 6px;
            font-size: 0.85rem; outline: none; background: #fff; min-width: 110px;
        }
        .filter-group select:focus, .filter-group input:focus { border-color: #2c7a3f; }
        .filter-group input { min-width: 180px; }
        .btn-filter {
            padding: 7px 18px; background: #2c7a3f; color: #fff;
            border: none; border-radius: 6px; font-size: 0.85rem;
            cursor: pointer; font-weight: 600; align-self: flex-end;
        }
        .btn-filter:hover { background: #235f31; }
        .btn-reset {
            padding: 7px 14px; background: #fff; color: #666;
            border: 1px solid #ddd; border-radius: 6px; font-size: 0.85rem;
            cursor: pointer; text-decoration: none; align-self: flex-end;
        }
        .btn-reset:hover { border-color: #999; color: #333; }

        /* Aktivitas filter pills */
        .pills { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px; }
        .pill {
            padding: 7px 18px; border-radius: 20px; border: 2px solid #ddd;
            background: #fff; font-size: 0.85rem; cursor: pointer;
            text-decoration: none; color: #555; font-weight: 500; transition: all 0.2s;
            display: flex; align-items: center; gap: 6px;
        }
        .pill:hover { border-color: #2c7a3f; color: #2c7a3f; }
        .pill.active { background: #2c7a3f; color: #fff; border-color: #2c7a3f; }

        /* Table */
        .table-card {
            background: #fff; border-radius: 10px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.07); overflow: hidden;
        }
        .table-header {
            padding: 14px 20px; border-bottom: 1px solid #f0f0f0;
            display: flex; justify-content: space-between; align-items: center;
        }
        .table-header-title { font-size: 0.9rem; font-weight: 700; color: #333; }
        .table-header-count { font-size: 0.8rem; color: #888; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8fdf9; }
        th {
            padding: 11px 14px; text-align: left; font-size: 0.78rem;
            font-weight: 700; color: #2c7a3f; text-transform: uppercase;
            letter-spacing: 0.4px; border-bottom: 2px solid #e8f5ec;
        }
        td { padding: 12px 14px; font-size: 0.85rem; color: #444; border-bottom: 1px solid #f5f5f5; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fafffe; }
        .visitor-id { font-weight: 700; color: #2c7a3f; font-family: monospace; font-size: 0.88rem; }
        .visitor-name { font-weight: 600; color: #222; }
        .time-text { color: #888; font-size: 0.82rem; }
        .badge {
            display: inline-flex; align-items: center; gap: 3px;
            padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;
        }
        .badge-yes { background: #e8f5ec; color: #1a6e30; }
        .badge-no  { background: #f5f5f5; color: #bbb; }
        .btn-detail {
            padding: 5px 14px; background: #eef7f0; color: #2c7a3f;
            border: 1px solid #c3e6cb; border-radius: 6px; font-size: 0.78rem;
            cursor: pointer; font-weight: 600; transition: all 0.2s; white-space: nowrap;
        }
        .btn-detail:hover { background: #2c7a3f; color: #fff; }
        .empty-msg { text-align: center; padding: 48px; color: #bbb; font-size: 0.9rem; }
        .empty-msg .empty-icon { font-size: 2.5rem; margin-bottom: 8px; }

        /* Pagination */
        .pagination { padding: 14px 20px; display: flex; justify-content: flex-end; gap: 5px; border-top: 1px solid #f0f0f0; }
        .page-btn {
            padding: 6px 12px; border-radius: 6px; border: 1px solid #e0e0e0;
            background: #fff; font-size: 0.82rem; text-decoration: none; color: #555;
            transition: all 0.15s;
        }
        .page-btn:hover { border-color: #2c7a3f; color: #2c7a3f; }
        .page-btn.active { background: #2c7a3f; color: #fff; border-color: #2c7a3f; font-weight: 700; }

        /* Top Visitors */
        .top-section { margin-top: 20px; }
        .top-card { background: #fff; border-radius: 10px; padding: 16px 20px; box-shadow: 0 1px 4px rgba(0,0,0,0.07); }
        .top-card-title { font-size: 0.9rem; font-weight: 700; color: #333; margin-bottom: 14px; display: flex; align-items: center; gap: 6px; }
        .top-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 0; border-bottom: 1px solid #f5f5f5;
        }
        .top-item:last-child { border-bottom: none; }
        .top-rank {
            width: 28px; height: 28px; border-radius: 50%; background: #e8f5ec;
            color: #2c7a3f; font-weight: 800; font-size: 0.85rem;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .top-rank.gold { background: #fff3cd; color: #856404; }
        .top-rank.silver { background: #e2e3e5; color: #555; }
        .top-rank.bronze { background: #fde8d8; color: #8b4513; }
        .top-info { flex: 1; }
        .top-name { font-weight: 600; font-size: 0.88rem; color: #222; }
        .top-id { font-size: 0.75rem; color: #999; font-family: monospace; }
        .top-count { font-weight: 800; font-size: 1rem; color: #2c7a3f; }
        .top-count-label { font-size: 0.72rem; color: #aaa; }

        /* Modal */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 999; align-items: center; justify-content: center; }
        .modal-overlay.active { display: flex; }
        .modal-box {
            background: #fff; border-radius: 12px; width: 90%; max-width: 480px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18); overflow: hidden;
        }
        .modal-head {
            background: linear-gradient(135deg, #1a5c2e, #2c7a3f);
            color: #fff; padding: 18px 22px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .modal-head-title { font-size: 1rem; font-weight: 700; }
        .modal-close {
            background: rgba(255,255,255,0.15); border: none; color: #fff;
            width: 28px; height: 28px; border-radius: 50%; cursor: pointer;
            font-size: 1rem; display: flex; align-items: center; justify-content: center;
        }
        .modal-close:hover { background: rgba(255,255,255,0.3); }
        .modal-body { padding: 20px 22px; }
        .modal-id-badge {
            background: #f0f9f3; border: 1px solid #c3e6cb; border-radius: 8px;
            padding: 10px 16px; margin-bottom: 16px; display: flex; align-items: center; gap: 10px;
        }
        .modal-id-text { font-family: monospace; font-size: 1.1rem; font-weight: 800; color: #2c7a3f; }
        .modal-id-name { font-size: 0.88rem; color: #555; }
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px; }
        .detail-item { background: #f8f9fa; border-radius: 7px; padding: 10px 12px; }
        .detail-item-label { font-size: 0.72rem; color: #999; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px; }
        .detail-item-value { font-size: 0.92rem; color: #222; font-weight: 600; margin-top: 2px; }
        .stat-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin-bottom: 6px; }
        .stat-mini { background: #f0f9f3; border-radius: 7px; padding: 10px 8px; text-align: center; }
        .stat-mini-num { font-size: 1.4rem; font-weight: 800; color: #2c7a3f; }
        .stat-mini-label { font-size: 0.68rem; color: #888; font-weight: 600; text-transform: uppercase; margin-top: 2px; }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="navbar-brand">
        <span>📚</span> Admin Perpustakaan Desa
    </div>
    <div class="navbar-right">
        <span class="navbar-user">👤 {{ session('admin')->username }}</span>
        <a href="{{ route('admin.logout') }}" class="btn-logout">Keluar</a>
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
            <div class="stat-num">{{ $stats['total_kunjungan'] }}</div>
            <div class="stat-label">Total Kunjungan</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-num">{{ $stats['total_pengunjung'] }}</div>
            <div class="stat-label">Terdaftar</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-num">{{ $stats['hari_ini'] }}</div>
            <div class="stat-label">Hari Ini</div>
        </div>
        <div class="stat-card teal">
            <div class="stat-num">{{ $stats['baca_buku'] }}</div>
            <div class="stat-label">Baca Buku</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-num">{{ $stats['pinjam_buku'] }}</div>
            <div class="stat-label">Pinjam Buku</div>
        </div>
        <div class="stat-card red">
            <div class="stat-num">{{ $stats['belajar_komputer'] }}</div>
            <div class="stat-label">Belajar Komputer</div>
        </div>
    </div>

    <!-- Filter Panel -->
    <div class="filter-panel">
        <div class="filter-panel-title">🔍 Filter Data</div>
        <form method="GET" action="{{ route('admin.dashboard') }}">
            <input type="hidden" name="filter" value="{{ $filter }}">
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
                    <label>Cari</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Nama atau ID...">
                </div>
                <button type="submit" class="btn-filter">Terapkan</button>
                <a href="{{ route('admin.dashboard') }}" class="btn-reset">Reset</a>
            </div>
        </form>
    </div>

    <!-- Pills aktivitas -->
    <div class="pills">
        @php
            $filterOptions = [
                'semua'            => ['label' => 'Semua Kunjungan', 'icon' => '📋'],
                'baca_buku'        => ['label' => 'Baca Buku', 'icon' => '📖'],
                'pinjam_buku'      => ['label' => 'Pinjam Buku', 'icon' => '📚'],
                'belajar_komputer' => ['label' => 'Belajar Komputer', 'icon' => '💻'],
            ];
        @endphp
        @foreach($filterOptions as $key => $opt)
            <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['filter' => $key])) }}"
               class="pill {{ $filter === $key ? 'active' : '' }}">
                {{ $opt['icon'] }} {{ $opt['label'] }}
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
                    <td><span class="badge {{ $visit->baca_buku ? 'badge-yes' : 'badge-no' }}">{{ $visit->baca_buku ? '✓ Ya' : '— Tidak' }}</span></td>
                    <td><span class="badge {{ $visit->pinjam_buku ? 'badge-yes' : 'badge-no' }}">{{ $visit->pinjam_buku ? '✓ Ya' : '— Tidak' }}</span></td>
                    <td><span class="badge {{ $visit->belajar_komputer ? 'badge-yes' : 'badge-no' }}">{{ $visit->belajar_komputer ? '✓ Ya' : '— Tidak' }}</span></td>
                    <td>
                        @if($visit->visitor)
                        <button class="btn-detail" onclick="showDetail({{ $visit->visitor->id }})">Lihat Detail</button>
                        @else
                        <span style="color:#ccc;font-size:0.8rem">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-msg">
                            <div class="empty-icon">📭</div>
                            Belum ada data kunjungan.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($visits->hasPages())
        <div class="pagination">
            @foreach($visits->links()->elements[0] as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $visits->currentPage() == $page ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Top Pengunjung -->
    @if($topVisitors->count())
    <div class="top-section">
        <div class="top-card">
            <div class="top-card-title">🏆 Pengunjung Terbanyak</div>
            @foreach($topVisitors as $idx => $top)
            <div class="top-item">
                <div class="top-rank {{ $idx === 0 ? 'gold' : ($idx === 1 ? 'silver' : ($idx === 2 ? 'bronze' : '')) }}">
                    {{ $idx + 1 }}
                </div>
                <div class="top-info">
                    <div class="top-name">{{ $top->name }}</div>
                    <div class="top-id">{{ $top->visitor_id }} &bull; RW{{ $top->rw }}/RT{{ $top->rt }}</div>
                </div>
                <div style="text-align:right">
                    <div class="top-count">{{ $top->visits_count }}</div>
                    <div class="top-count-label">kunjungan</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

<!-- Modal Detail -->
<div class="modal-overlay" id="modal-detail">
    <div class="modal-box">
        <div class="modal-head">
            <div class="modal-head-title">👤 Detail Pengunjung</div>
            <button class="modal-close" onclick="closeDetail()">✕</button>
        </div>
        <div class="modal-body" id="modal-content">
            <div style="text-align:center;padding:24px;color:#aaa;">Memuat data...</div>
        </div>
    </div>
</div>

<script>
const detailUrl = "{{ url('admin/visitor') }}";

function showDetail(id) {
    document.getElementById('modal-detail').classList.add('active');
    document.getElementById('modal-content').innerHTML = '<div style="text-align:center;padding:24px;color:#aaa;">⏳ Memuat data...</div>';

    fetch(detailUrl + '/' + id)
        .then(r => r.json())
        .then(d => {
            const v = d.visitor;
            document.getElementById('modal-content').innerHTML = `
                <div class="modal-id-badge">
                    <div>
                        <div class="modal-id-text">${v.visitor_id}</div>
                        <div class="modal-id-name">${v.name}</div>
                    </div>
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
                <div style="font-size:0.78rem;font-weight:700;color:#2c7a3f;text-transform:uppercase;letter-spacing:0.4px;margin-bottom:8px;">📊 Statistik Kunjungan</div>
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
                <div style="margin-top:10px;font-size:0.8rem;color:#aaa;">Kunjungan terakhir: ${d.kunjungan_terakhir}</div>
            `;
        })
        .catch(() => {
            document.getElementById('modal-content').innerHTML = '<div style="text-align:center;padding:24px;color:#e74c3c;">Gagal memuat data.</div>';
        });
}

function closeDetail() {
    document.getElementById('modal-detail').classList.remove('active');
}

document.getElementById('modal-detail').addEventListener('click', function(e) {
    if (e.target === this) closeDetail();
});
</script>
</body>
</html>
