<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaduanRwController extends Controller
{
    public function index(Request $request)
    {
        $title = ' Daftar Pengaduan Warga';
        $user = Auth::user();

        $pengaduan_rw = $user->rw->nomor_rw;

        $pengaduan_rw_saya = Pengaduan::WhereHas('warga.kartuKeluarga.rw', function ($aduan) use ($pengaduan_rw) {
            $aduan->where('level', 'rw')->where('nomor_rw', $pengaduan_rw);
        });

        if ($request->filled('search')) {
            $hasil = $request->input('search');
            $pengaduan_rw_saya->where(function ($item) use ($hasil) {
                $item->where('judul', 'like', "%$hasil%");
            });
        }

        $rw_pengaduan = $pengaduan_rw_saya->orderBy('created_at', 'desc')
            ->paginate(10);

        $total_pengaduan_rw = $rw_pengaduan->count();

        return view('rw.pengaduan.pengaduan', compact('title', 'rw_pengaduan', 'total_pengaduan_rw'));
    }

    public function baca(Request $request, $id)
    {

        $rw_user = Auth::user()->rw->nomor_rw;

        $pengaduan_rw_saya = Pengaduan::whereHas('warga.kartuKeluarga.rw', function ($aduan) use ($rw_user) {
            $aduan->where('level', 'rw')->where('nomor_rw', $rw_user);
        })->findOrFail($id);

        if ($pengaduan_rw_saya->status === 'belum') {
            $pengaduan_rw_saya->update([
                'status' => 'sudah'
            ]);
        }

        if ($request->boolean('selesai')) {
            $request->validate([
                'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,mkv,doc,docx,pdf|max:20480',
            ]);

            $filePath = null;
            $fileName = null;

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('bukti_selesai', $fileName, 'public');
            }

            $dataUpdate = [
                'status' => 'selesai',
                'foto_bukti' => $filePath,
            ];

            $pengaduan_rw_saya->update($dataUpdate);
        }

        return back()->with('success', 'Pengaduan telah selesai.');
    }
}
