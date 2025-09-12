<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaduanRwController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Daftar Pengaduan Warga';

        $pengaduanQuery = Pengaduan::with('warga');

        // Filter search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $pengaduanQuery->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhereHas('warga', function ($sub) use ($search) {
                      $sub->where('nama', 'like', "%$search%")
                          ->orWhere('nik', 'like', "%$search%");
                  });
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $pengaduanQuery->where('status', $request->status);
        }

        $pengaduan = $pengaduanQuery->orderBy('updated_at', 'desc')->paginate(10);
        $total_pengaduan = $pengaduan->total();

        return view('rw.pengaduan.pengaduan', compact('title', 'pengaduan', 'total_pengaduan'));
    }

    public function update(Request $request, $id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        $request->validate([
            'status' => 'required|in:belum,sudah',
            'bukti_selesai' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Jika RW upload bukti, hapus file lama lalu ganti
        if ($request->hasFile('bukti_selesai')) {
            // Hapus file lama kalau ada
            if ($pengaduan->file_path && Storage::disk('public')->exists($pengaduan->file_path)) {
                Storage::disk('public')->delete($pengaduan->file_path);
            }

            $file = $request->file('bukti_selesai');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $pathFile = $file->storeAs('pengaduan', $namaFile, 'public');

            // Update file di field yang sama
            $pengaduan->file_path = $pathFile;
            $pengaduan->file_name = $namaFile;
        }

        $pengaduan->status = $request->status;
        $pengaduan->save();

        return back()->with('success', 'Pengaduan berhasil diperbarui.');
    }

}
