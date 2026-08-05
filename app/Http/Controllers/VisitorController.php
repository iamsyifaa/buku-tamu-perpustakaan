<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Visit;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function index()
    {
        return view('visitor.login');
    }

    public function showRegister()
    {
        return view('visitor.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:100',
            'rw'     => 'required|string|max:3',
            'rt'     => 'required|string|max:3',
            'alamat' => 'nullable|string|max:200',
            'umur'   => 'required|integer|min:1|max:120',
            'desa'   => 'required|string|max:50',
        ]);

        $visitorId = Visitor::generateVisitorId($request->name);

        Visitor::create([
            'visitor_id' => $visitorId,
            'name'       => $request->name,
            'rw'         => $request->rw,
            'rt'         => $request->rt,
            'alamat'     => $request->alamat,
            'umur'       => $request->umur,
            'desa'       => $request->desa,
        ]);

        return response()->json([
            'success'    => true,
            'visitor_id' => $visitorId,
            'name'       => $request->name,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate(['visitor_id' => 'required|string']);

        $visitor = Visitor::where('visitor_id', $request->visitor_id)->first();

        if (!$visitor) {
            return response()->json(['success' => false, 'message' => 'ID pengunjung tidak ditemukan.'], 404);
        }

        session(['visitor' => $visitor]);

        return response()->json(['success' => true, 'name' => $visitor->name]);
    }

    public function aktivitas()
    {
        if (!session('visitor')) {
            return redirect()->route('visitor.login');
        }
        return view('visitor.aktivitas', ['visitor' => session('visitor')]);
    }

    public function simpanAktivitas(Request $request)
    {
        $request->validate([
            'aktivitas'   => 'required|array|min:1',
            'aktivitas.*' => 'in:baca_buku,pinjam_buku,belajar_komputer',
        ]);

        $visitor = session('visitor');

        if (!$visitor) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak valid.'], 401);
        }

        Visit::create([
            'visitor_id'       => $visitor->id,
            'baca_buku'        => in_array('baca_buku', $request->aktivitas),
            'pinjam_buku'      => in_array('pinjam_buku', $request->aktivitas),
            'belajar_komputer' => in_array('belajar_komputer', $request->aktivitas),
        ]);

        session()->forget('visitor');

        return response()->json(['success' => true]);
    }
}
