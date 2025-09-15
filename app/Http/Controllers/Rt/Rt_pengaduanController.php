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

        $title = ' Daftar Pengaduan Warga';
        $user = Auth::user();

        $pengaduan_rt = $user->rukunTetangga->rt;

        $pengaduan_rt_saya = Pengaduan::WhereHas('warga.kartuKeluarga.rukunTetangga', function ($aduan) use ($pengaduan_rt) {
            $aduan->where('rt', $pengaduan_rt);
        });

        if ($request->filled('search')) {
            $hasil = $request->input('search');
            $pengaduan_rt_saya->where(function ($item) use ($hasil) {
                $item->where('judul', 'like', "%$hasil%");
            });
        }

        $rt_pengaduan = $pengaduan_rt_saya->orderBy('created_at', 'desc')
            ->paginate(10);

        $total_pengaduan_rt = $rt_pengaduan->count();

        return view('rt.pengaduan.pengaduan', compact('title', 'rt_pengaduan', 'total_pengaduan_rt'));
      
    }

    public function show($id)
    {

        $rt_user = Auth::user()->rukunTetangga->rt;

        $pengaduan_rw_saya = Pengaduan::whereHas('warga.kartuKeluarga.rukunTetangga', function ($aduan) use ($rt_user) {
            $aduan->where('rt', $rt_user);
        })->findOrFail($id);

        if ($pengaduan_rw_saya->status === 'belum') {
            $pengaduan_rw_saya->update([
                'status' => 'sudah'
            ]);
        }

        $title = 'Detail Pengaduan';
        $pengaduan = Pengaduan::with('warga')->findOrFail($id);


        return view('rt.pengaduan.komponen.detail_pengaduan', compact('title', 'pengaduan'));
    }
}
