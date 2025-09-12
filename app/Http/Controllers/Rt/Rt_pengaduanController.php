<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Rt_pengaduanController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Pengaduan RT Saya';
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

        $rt_pengaduan = $pengaduan_rt_saya->orderBy('updated_at', 'desc')
            ->paginate(10);

        $total_pengaduan_rt = $rt_pengaduan->count();

        return view('rt.pengaduan.pengaduan', compact('title', 'rt_pengaduan',));
    }

    public function baca($id)
    {
        $rt_user = Auth::user()->warga->kartuKeluarga->rukunTetangga->rt;

        $pengaduan = Pengaduan::whereHas('warga.kartuKeluarga.rukunTetangga', function ($q) use ($rt_user) {
            $q->where('rt', $rt_user);
        })->where('id', $id)->firstOrFail();

        $pengaduan->update(['status' => 'sudah']);

        return response()->json(['success' => true]);
    }
}
