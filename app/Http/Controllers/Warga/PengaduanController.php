<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $title = 'Pengaduan Saya';
        if (!$user || !$user->hasRole('warga') || !$user->warga) {
            Log::warning("Akses tidak sah ke halaman tagihan warga atau data warga tidak ditemukan.", ['user_id' => $user->id ?? 'guest']);
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini atau data profil Anda tidak lengkap.');
        }

        $nik_warga = $user->warga->nik;

        if (!$nik_warga) {
            Log::warning("Nomor Induk Kependudukan tidak ditemukan untuk warga yang login.", ['user_id' => $user->id, 'nik' => $user->nik]);
            return redirect('/')->with('error', 'Data Kartu Keluarga Anda tidak ditemukan. Silakan hubungi RT/RW Anda.');
        }

        $pengaduanSaya = Pengaduan::where('nik_warga', $nik_warga);

        if ($request->filled('search')) {
            $hasil = $request->input('search');
            $pengaduanSaya->where(function ($item) use ($hasil) {
                $item->where('judul', 'like', "%$hasil%");
            });
        }

        $pengaduan = $pengaduanSaya->orderBy('created_at', 'desc')->paginate(10);

        $total_pengaduan = $pengaduan->count();

        return view('warga.pengaduan.pengaduan', compact('pengaduan', 'title', 'total_pengaduan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'file' => 'required|file|mimes:jpg,jpeg,JPG,PNG,png,gif,mp4,mov,avi,mkv,doc,docx,pdf|max:20480',
            'level' => 'required|in:rt,rw',
        ]);

        $nik_user = Auth::user()->warga->nik;
        $filePath = null;
        $fileName = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            // Simpan ke folder 'documents/pengumuman-rt' di disk 'public'
            $filePath = $file->storeAs('file', $fileName, 'public');
        }

        Pengaduan::create([
            'nik_warga' => $nik_user,
            'judul' => $request->judul,
            'isi' => $request->isi,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'foto_bukti' => null,
            'status' => 'belum',
            'level' => $request->level,
        ]);

        return back()->with('success', 'Pengaduan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $nik_user = Auth::user()->warga->nik;

        $pengaduan = Pengaduan::where(function ($q) use ($nik_user) {
            $q->whereHas('warga', function ($q2) use ($nik_user) {
                $q2->where('nik', $nik_user);
            });
        })->findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'level' => 'required|in:rt,rw',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,mkv,doc,docx,pdf|max:20480',
            'hapus_dokumen_lama' => 'nullable|boolean',
        ]);

        $dataYangDiUpdate = [
            'judul' => $request->judul,
            'isi' => $request->isi,
            'level' => $request->level,
        ];

        if ($request->hasFile('file')) {
            if ($pengaduan->file_path && Storage::disk('public')->exists($pengaduan->file_path)) {
                Storage::disk('public')->delete($pengaduan->file_path);
            }
            $file = $request->file('file');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $pathFile = $file->storeAs('file', $namaFile, 'public');
            $dataYangDiUpdate['file_path'] = $pathFile;
            $dataYangDiUpdate['file_name'] = $namaFile;
        } elseif ($request->boolean('hapus_dokumen_lama')) {
            if ($pengaduan->file_path && Storage::disk('public')->exists($pengaduan->file_path)) {
                Storage::disk('public')->delete($pengaduan->file_path);
            }
            $dataYangDiUpdate['file_path'] = null;
            $dataYangDiUpdate['file_name'] = null;
        }

        $pengaduan->update($dataYangDiUpdate);

        return redirect()->route('warga.pengaduan.index')
            ->with('success', 'Pengaduan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $nik_user = Auth::user()->warga->nik;

        $pengaduan = Pengaduan::where(function ($q) use ($nik_user) {
            $q->whereHas('warga', function ($q2) use ($nik_user) {
                $q2->where('nik', $nik_user);
            });
        })->findOrFail($id);

        if ($pengaduan->file_path && Storage::disk('public')->exists($pengaduan->file_path)) {
            Storage::disk('public')->delete($pengaduan->file_path);
        }

        $pengaduan->delete();

        return redirect()->route('warga.pengaduan.index')
            ->with('success', 'Pengaduan berhasil diperbarui.');
    }
}
