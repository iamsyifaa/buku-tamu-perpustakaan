<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Visit;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function showLogin()
    {
        if (session('admin')) return redirect()->route('admin.dashboard');
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate(['username' => 'required', 'password' => 'required']);

        $admin = Admin::where('username', $request->username)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->withErrors(['login' => 'Username atau password salah.'])->withInput();
        }

        session(['admin' => $admin]);
        return redirect()->route('admin.dashboard');
    }

    public function logout()
    {
        session()->forget('admin');
        return redirect()->route('admin.login');
    }

    public function dashboard(Request $request)
    {
        if (!session('admin')) return redirect()->route('admin.login');

        $filter    = $request->get('filter', 'semua');       // semua | baca_buku | pinjam_buku | belajar_komputer | pengunjung_terbanyak
        $search    = $request->get('search', '');
        $bulan     = $request->get('bulan', '');
        $tahun     = $request->get('tahun', date('Y'));
        $rw        = $request->get('rw', '');
        $rt        = $request->get('rt', '');
        // Dipakai hanya saat filter = pengunjung_terbanyak, buat nentuin
        // urutan ranking-nya berdasar apa: semua | baca_buku | pinjam_buku | belajar_komputer
        $peringkat = $request->get('peringkat', 'semua');

        $stats = [
            'total_kunjungan'  => Visit::count(),
            'total_pengunjung' => Visitor::count(),
            'baca_buku'        => Visit::where('baca_buku', true)->count(),
            'pinjam_buku'      => Visit::where('pinjam_buku', true)->count(),
            'belajar_komputer' => Visit::where('belajar_komputer', true)->count(),
            'hari_ini'         => Visit::whereDate('visited_at', today())->count(),
        ];

        $isTopMode = $filter === 'pengunjung_terbanyak';

        $visits      = null;
        $topVisitors = null;

        if ($isTopMode) {
            [$kolomPeringkat, $labelPeringkat] = $this->peringkatInfo($peringkat);
            $topVisitors = $this->topVisitorsQuery($peringkat, $bulan, $tahun, $rw, $rt, $search);
        } else {
            $visits = $this->applyVisitFilters($request)->paginate(15)->withQueryString();
            $kolomPeringkat = 'visits_count';
            $labelPeringkat = 'Semua Kunjungan';
        }

        return view('admin.dashboard', compact(
            'visits', 'stats', 'filter', 'search', 'bulan', 'tahun', 'rw', 'rt',
            'peringkat', 'topVisitors', 'isTopMode', 'kolomPeringkat', 'labelPeringkat'
        ));
    }

    public function detailVisitor($id)
    {
        if (!session('admin')) return response()->json(['error' => 'Unauthorized'], 401);

        $visitor = Visitor::with('visits')->findOrFail($id);

        $totalKunjungan   = $visitor->visits->count();
        $totalBaca        = $visitor->visits->where('baca_buku', true)->count();
        $totalPinjam      = $visitor->visits->where('pinjam_buku', true)->count();
        $totalKomputer    = $visitor->visits->where('belajar_komputer', true)->count();
        $kunjunganTerakhir = $visitor->visits->sortByDesc('visited_at')->first();

        return response()->json([
            'visitor'           => $visitor,
            'total_kunjungan'   => $totalKunjungan,
            'total_baca'        => $totalBaca,
            'total_pinjam'      => $totalPinjam,
            'total_komputer'    => $totalKomputer,
            'kunjungan_terakhir'=> $kunjunganTerakhir?->visited_at?->format('d/m/Y H:i') ?? '-',
        ]);
    }

    /**
     * Endpoint JSON buat export (Excel/PDF dibikin di sisi browser).
     * Selalu mengikuti filter yang lagi aktif di dashboard, termasuk
     * mode-nya (data kunjungan biasa atau pengunjung terbanyak),
     * biar yang di-export = persis yang lagi diliat admin di tabel.
     */
    public function exportData(Request $request)
    {
        if (!session('admin')) return response()->json(['error' => 'Unauthorized'], 401);

        $filter    = $request->get('filter', 'semua');
        $bulan     = $request->get('bulan', '');
        $tahun     = $request->get('tahun', date('Y'));
        $rw        = $request->get('rw', '');
        $rt        = $request->get('rt', '');
        $search    = $request->get('search', '');
        $peringkat = $request->get('peringkat', 'semua');
        $periode   = $bulan ? ($this->namaBulan($bulan) . ' ' . $tahun) : 'Semua Periode';

        if ($filter === 'pengunjung_terbanyak') {
            [$kolomPeringkat, $labelPeringkat] = $this->peringkatInfo($peringkat);
            $data = $this->topVisitorsQuery($peringkat, $bulan, $tahun, $rw, $rt, $search);

            return response()->json([
                'mode'    => 'pengunjung_terbanyak',
                'judul'   => 'Pengunjung Terbanyak - ' . $labelPeringkat,
                'periode' => $periode,
                'rows'    => $data->values()->map(function ($v, $i) use ($kolomPeringkat) {
                    return [
                        'no'                => $i + 1,
                        'id'                => $v->visitor_id,
                        'nama'              => $v->name,
                        'desa'              => $v->desa ?? '-',
                        'rw'                => $v->rw ?? '-',
                        'rt'                => $v->rt ?? '-',
                        'total_kunjungan'   => $v->visits_count,
                        'baca_buku'         => $v->baca_buku_count,
                        'pinjam_buku'       => $v->pinjam_buku_count,
                        'belajar_komputer'  => $v->belajar_komputer_count,
                        'nilai_peringkat'   => $v->{$kolomPeringkat},
                    ];
                })->values(),
            ]);
        }

        $visits = $this->applyVisitFilters($request)->get();

        return response()->json([
            'mode'    => 'kunjungan',
            'judul'   => 'Data Kunjungan Perpustakaan Desa',
            'periode' => $periode,
            'rows'    => $visits->values()->map(function ($visit, $i) {
                $v = $visit->visitor;
                return [
                    'no'                => $i + 1,
                    'waktu'             => $visit->visited_at->format('d/m/Y H:i'),
                    'id'                => $v->visitor_id ?? '-',
                    'nama'              => $v->name ?? '-',
                    'desa'              => $v->desa ?? '-',
                    'rw'                => $v->rw ?? '-',
                    'rt'                => $v->rt ?? '-',
                    'alamat'            => $v->alamat ?? '-',
                    'umur'              => $v->umur ?? '-',
                    'baca_buku'         => $visit->baca_buku ? 'Ya' : 'Tidak',
                    'pinjam_buku'       => $visit->pinjam_buku ? 'Ya' : 'Tidak',
                    'belajar_komputer'  => $visit->belajar_komputer ? 'Ya' : 'Tidak',
                ];
            })->values(),
        ]);
    }

    /**
     * Label & kolom hitungan buat kategori peringkat "Pengunjung Terbanyak".
     */
    private function peringkatInfo(string $peringkat): array
    {
        return match ($peringkat) {
            'baca_buku'        => ['baca_buku_count', 'Baca Buku'],
            'pinjam_buku'      => ['pinjam_buku_count', 'Pinjam Buku'],
            'belajar_komputer' => ['belajar_komputer_count', 'Belajar Komputer'],
            default            => ['visits_count', 'Semua Kunjungan'],
        };
    }

    private function namaBulan(string $bulan): string
    {
        $map = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni',
                '07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
        return $map[$bulan] ?? '';
    }

    /**
     * Ranking "Pengunjung Terbanyak", ikut filter bulan/tahun/rw/rt/search
     * yang sama dengan tabel kunjungan biasa, supaya konsisten.
     * Filter ">0" & pengurutan dikerjakan di PHP (bukan HAVING SQL) karena
     * Postgres tidak mengizinkan alias kolom SELECT dipakai di HAVING.
     */
    private function topVisitorsQuery(string $peringkat, string $bulan, string $tahun, string $rw, string $rt, string $search)
    {
        [$kolomPeringkat] = $this->peringkatInfo($peringkat);

        $scopeBulan = function ($q) use ($bulan, $tahun) {
            if ($bulan) $q->whereMonth('visited_at', $bulan)->whereYear('visited_at', $tahun);
        };

        $query = Visitor::withCount([
            'visits' => $scopeBulan,
            'visits as baca_buku_count' => function ($q) use ($scopeBulan) {
                $scopeBulan($q);
                $q->where('baca_buku', true);
            },
            'visits as pinjam_buku_count' => function ($q) use ($scopeBulan) {
                $scopeBulan($q);
                $q->where('pinjam_buku', true);
            },
            'visits as belajar_komputer_count' => function ($q) use ($scopeBulan) {
                $scopeBulan($q);
                $q->where('belajar_komputer', true);
            },
        ]);

        if ($rw) $query->where('rw', $rw);
        if ($rt) $query->where('rt', $rt);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('visitor_id', 'like', "%{$search}%");
            });
        }

        return $query->get()
            ->filter(fn ($v) => $v->{$kolomPeringkat} > 0)
            ->sortByDesc($kolomPeringkat)
            ->values();
    }

    /**
     * Filter tabel kunjungan biasa (bulan/tahun/rw/rt/search/aktivitas).
     * Dipakai bareng oleh tabel dashboard maupun export, biar hasilnya konsisten.
     */
    private function applyVisitFilters(Request $request)
    {
        $filter = $request->get('filter', 'semua');
        $search = $request->get('search', '');
        $bulan  = $request->get('bulan', '');
        $tahun  = $request->get('tahun', date('Y'));
        $rw     = $request->get('rw', '');
        $rt     = $request->get('rt', '');

        $query = Visit::with('visitor')->orderBy('visited_at', 'desc');

        if ($filter === 'baca_buku')        $query->where('baca_buku', true);
        elseif ($filter === 'pinjam_buku')  $query->where('pinjam_buku', true);
        elseif ($filter === 'belajar_komputer') $query->where('belajar_komputer', true);

        if ($bulan) $query->whereMonth('visited_at', $bulan)->whereYear('visited_at', $tahun);

        if ($rw || $rt) {
            $query->whereHas('visitor', function ($q) use ($rw, $rt) {
                if ($rw) $q->where('rw', $rw);
                if ($rt) $q->where('rt', $rt);
            });
        }

        if ($search) {
            $query->whereHas('visitor', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('visitor_id', 'like', "%{$search}%");
            });
        }

        return $query;
    }
}
