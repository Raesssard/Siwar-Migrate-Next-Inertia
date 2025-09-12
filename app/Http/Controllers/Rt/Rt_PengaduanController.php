<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Rt_PengaduanController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Daftar Pengaduan Warga';

        $nik = Auth::user()->nik;
        $total_pengaduan = Pengaduan::where('nik_warga', $nik)->count();

        $pengaduan = Pengaduan::with('warga')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->input('search');
                $q->where('judul', 'like', "%$search%")
                  ->orWhereHas('warga', function ($sub) use ($search) {
                      $sub->where('nama', 'like', "%$search%");
                  });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('rt.pengaduan.pengaduan', compact('title', 'pengaduan', 'total_pengaduan'));
    }

    public function show($id)
    {
        $title = 'Detail Pengaduan';
        $pengaduan = Pengaduan::with('warga')->findOrFail($id);

        return view('rt.pengaduan.komponen.detail_pengaduan', compact('title', 'pengaduan'));
    }
}
