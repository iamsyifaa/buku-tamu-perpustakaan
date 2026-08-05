<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Visit;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    // Halaman utama: form login + link ke register
    public function index()
    {
        return view('visitor.login');
    }

    // Halaman register
    public function showRegister()
    {
        return view('visitor.register');
    }

    // Proses register
    public function register(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'address' => 'nullable|string|max:200',
            'phone'   => 'nullable|string|max:20',
        ]);

        $visitorId = Visitor::generateVisitorId($request->name);

        Visitor::create([
            'visitor_id' => $visitorId,
            'name'       => $request->name,
            'address'    => $request->address,
            'phone'      => $request->phone,
        ]);

        return response()->json([
            'success'    => true,
            'visitor_id' => $visitorId,
            'name'       => $request->name,
        ]);
    }

    // Proses login dengan visitor_id
    public function login(Request $request)
    {
        $request->validate([
            'visitor_id' => 'required|string',
        ]);

        $visitor = Visitor::where('visitor_id', $request->visitor_id)->first();

        if (!$visitor) {
            return response()->json([
                'success' => false,
                'message' => 'ID pengunjung tidak ditemukan.',
            ], 404);
        }

        // Simpan ke session
        session(['visitor' => $visitor]);

        return response()->json([
            'success' => true,
            'name'    => $visitor->name,
        ]);
    }

    // Halaman aktivitas (setelah login)
    public function aktivitas()
    {
        if (!session('visitor')) {
            return redirect()->route('visitor.login');
        }

        return view('visitor.aktivitas', ['visitor' => session('visitor')]);
    }

    // Proses simpan aktivitas
    public function simpanAktivitas(Request $request)
    {
        $request->validate([
            'aktivitas' => 'required|array|min:1',
            'aktivitas.*' => 'in:baca_buku,pinjam_buku,belajar_komputer',
        ]);

        $visitor = session('visitor');

        if (!$visitor) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak valid.'], 401);
        }

        Visit::create([
            'visitor_id'      => $visitor->id,
            'baca_buku'       => in_array('baca_buku', $request->aktivitas),
            'pinjam_buku'     => in_array('pinjam_buku', $request->aktivitas),
            'belajar_komputer' => in_array('belajar_komputer', $request->aktivitas),
        ]);

        // Hapus session setelah selesai
        session()->forget('visitor');

        return response()->json(['success' => true]);
    }
}
