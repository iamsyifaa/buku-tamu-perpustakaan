<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Visit;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Halaman login admin
    public function showLogin()
    {
        if (session('admin')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    // Proses login admin
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->withErrors(['login' => 'Username atau password salah.'])->withInput();
        }

        session(['admin' => $admin]);
        return redirect()->route('admin.dashboard');
    }

    // Logout admin
    public function logout()
    {
        session()->forget('admin');
        return redirect()->route('admin.login');
    }

    // Dashboard admin
    public function dashboard(Request $request)
    {
        if (!session('admin')) {
            return redirect()->route('admin.login');
        }

        $filter = $request->get('filter', 'semua'); // semua, baca_buku, pinjam_buku, belajar_komputer
        $search = $request->get('search', '');
        $perPage = 15;

        $query = Visit::with('visitor')
            ->orderBy('visited_at', 'desc');

        // Filter berdasarkan aktivitas
        if ($filter === 'baca_buku') {
            $query->where('baca_buku', true);
        } elseif ($filter === 'pinjam_buku') {
            $query->where('pinjam_buku', true);
        } elseif ($filter === 'belajar_komputer') {
            $query->where('belajar_komputer', true);
        }

        // Search berdasarkan nama atau visitor_id
        if ($search) {
            $query->whereHas('visitor', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('visitor_id', 'like', "%{$search}%");
            });
        }

        $visits = $query->paginate($perPage)->withQueryString();

        // Statistik ringkasan
        $stats = [
            'total_kunjungan'   => Visit::count(),
            'total_pengunjung'  => Visitor::count(),
            'baca_buku'         => Visit::where('baca_buku', true)->count(),
            'pinjam_buku'       => Visit::where('pinjam_buku', true)->count(),
            'belajar_komputer'  => Visit::where('belajar_komputer', true)->count(),
            'hari_ini'          => Visit::whereDate('visited_at', today())->count(),
        ];

        return view('admin.dashboard', compact('visits', 'stats', 'filter', 'search'));
    }
}
