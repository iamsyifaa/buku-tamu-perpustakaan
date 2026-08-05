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

        $visits = $query->paginate(15)->withQueryString();

        $stats = [
            'total_kunjungan'  => Visit::count(),
            'total_pengunjung' => Visitor::count(),
            'baca_buku'        => Visit::where('baca_buku', true)->count(),
            'pinjam_buku'      => Visit::where('pinjam_buku', true)->count(),
            'belajar_komputer' => Visit::where('belajar_komputer', true)->count(),
            'hari_ini'         => Visit::whereDate('visited_at', today())->count(),
        ];

        // Top pengunjung bulan ini atau semua waktu
        $topQuery = Visitor::withCount('visits')->orderBy('visits_count', 'desc')->limit(5);
        $topVisitors = $topQuery->get();

        return view('admin.dashboard', compact('visits', 'stats', 'filter', 'search', 'bulan', 'tahun', 'rw', 'rt', 'topVisitors'));
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
}
