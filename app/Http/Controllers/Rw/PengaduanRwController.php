<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaduanRwController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Pengaduan RT Saya';
        $user = Auth::user();

        $pengaduan_rw = $user->rw->nomor_rw;

        $pengaduan_rw_saya = Pengaduan::WhereHas('warga.kartuKeluarga.rw', function ($aduan) use ($pengaduan_rw) {
            $aduan->where('nomor_rw', $pengaduan_rw);
        });

        if ($request->filled('search')) {
            $hasil = $request->input('search');
            $pengaduan_rw_saya->where(function ($item) use ($hasil) {
                $item->where('judul', 'like', "%$hasil%");
            });
        }

        $rw_pengaduan = $pengaduan_rw_saya->orderBy('updated_at', 'desc')
            ->paginate(10);

        $total_pengaduan_rw = $rw_pengaduan->count();

        return view('rw.pengaduan.pengaduan', compact('title', 'rw_pengaduan', 'total_pengaduan_rw'));
    }

    public function baca($id)
    {
        $rw_user = Auth::user()->warga->kartuKeluarga->rw->nomor_rw;

        $pengaduan = Pengaduan::whereHas('warga.kartuKeluarga.rw', function ($q) use ($rw_user) {
            $q->where('nomor_rw', $rw_user);
        })->where('id', $id)->firstOrFail();

        if ($pengaduan->status === 'belum') {
            $pengaduan->update(['status' => 'sudah']);
        }

        return response()->json(['success' => true]);
    }
}
