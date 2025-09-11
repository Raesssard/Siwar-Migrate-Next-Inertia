<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rukun_tetangga;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Admin_rtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $rukun_tetangga = Rukun_tetangga::paginate(5);
        $title = 'Rukun Tetangga';
        return view('admin.data-rt.rt', compact('rukun_tetangga', 'title'));
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
    // ✅ Validasi dasar
    $request->validate([
        'nik' => 'required|unique:rukun_tetangga,nik',
        'rt' => ['required', 'regex:/^[0-9]{2}$/', 'unique:rukun_tetangga,rt'],
        'nama' => 'required|string|max:255',
        'mulai_menjabat' => 'required|date',
        'akhir_jabatan' => 'required|date|after:mulai_menjabat',
    ], [
        'nik.required' => 'NIK harus diisi.',
        'nik.unique' => 'NIK sudah terdaftar.',
        'rt.required' => 'Nomor RT harus diisi.',
        'rt.regex' => 'Nomor RT harus terdiri dari 2 digit (contoh: 01, 02, 10).',
        'rt.unique' => 'Nomor RT sudah digunakan.',
        'nama.required' => 'Nama Ketua RT harus diisi.',
        'mulai_menjabat.required' => 'Mulai menjabat harus diisi.',
        'akhir_jabatan.required' => 'Akhir menjabat harus diisi.',
        'akhir_jabatan.after' => 'Akhir menjabat harus setelah mulai menjabat.',
    ]);

    // ✅ Cek apakah NIK ada di tabel warga
    $warga = DB::table('warga')->where('nik', $request->nik)->first();

    if (!$warga) {
        return back()->withErrors(['nik' => 'NIK tidak ditemukan di data warga.'])->withInput();
    }

    // ✅ Cek apakah nama sesuai dengan NIK
    if ($warga->nama !== $request->nama) {
        return back()->withErrors(['nama' => 'Nama tidak sesuai dengan NIK yang dipilih.'])->withInput();
    }

    // ✅ Buat record RT
    $rt = Rukun_tetangga::create([
        'nik' => $request->nik,
        'no_kk' => $warga->no_kk,
        'rt' => $request->rt,
        'nama' => $request->nama,
        'mulai_menjabat' => $request->mulai_menjabat,
        'akhir_jabatan' => $request->akhir_jabatan,
        'id_rw' => 1, // default sementara
    ]);

    // ✅ Buat atau update user untuk RT
    // ✅ Buat atau update user untuk RT
    $user = User::where('nik', $request->nik)->first();

    if ($user) {
        $user->update([
            'id_rt' => $rt->id,
            'id_rw' => 1,
            'password' => Hash::make('password'), // reset password default
        ]);

        // Tambah role 'rt' kalau belum ada
        if (!$user->hasRole('rt')) {
            $user->assignRole('rt');
        }
    } else {
        $user = User::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'password' => bcrypt('password'),
            'id_rt' => $rt->id,
            'id_rw' => 1,
        ]);

        // Set role RT
        $user->assignRole('rt');
    }

    return redirect()->route('admin.rt.index')->with('success', 'Rukun Tetangga berhasil ditambahkan.');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $rukun_tetangga = Rukun_tetangga::findOrFail($id);
        return view('admin.rt.show', compact('rukun_tetangga'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $rukun_tetangga = Rukun_tetangga::findOrFail($id);
        return view('admin.rt.edit', compact('rukun_tetangga'));
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, string $id)
{
    // ✅ Validasi dasar
    $request->validate([
        'nik' => 'required|exists:warga,nik',
        'rt' => ['required', 'regex:/^[0-9]{2}$/', 'unique:rukun_tetangga,rt,' . $id],
        'nama' => 'required|string|max:255',
        'mulai_menjabat' => 'required|date',
        'akhir_jabatan' => 'required|date|after:mulai_menjabat',
    ], [
        'nik.required' => 'NIK harus diisi.',
        'nik.exists' => 'NIK tidak ditemukan di data warga.',
        'rt.required' => 'Nomor RT harus diisi.',
        'rt.regex' => 'Nomor RT harus terdiri dari 2 digit (contoh: 01, 02, 10).',
        'rt.unique' => 'Nomor RT sudah digunakan.',
        'nama.required' => 'Nama Ketua RT harus diisi.',
        'mulai_menjabat.required' => 'Mulai menjabat harus diisi.',
        'akhir_jabatan.required' => 'Akhir menjabat harus diisi.',
        'akhir_jabatan.after' => 'Akhir menjabat harus setelah mulai menjabat.',
    ]);

    // ✅ Ambil data warga
    $warga = DB::table('warga')->where('nik', $request->nik)->first();

    if (!$warga) {
        return back()
            ->withErrors(['nik' => 'NIK tidak ditemukan di data warga.'])
            ->withInput($request->all() + ['id' => $id, '_method' => 'PUT']);
    }

    // ✅ Cek nama sesuai
    if ($warga->nama !== $request->nama) {
        return back()
            ->withErrors(['nama' => 'Nama tidak sesuai dengan NIK yang dipilih.'])
            ->withInput($request->all() + ['id' => $id, '_method' => 'PUT']);
    }

    // ✅ Update data RT
    $rukun_tetangga = Rukun_tetangga::findOrFail($id);
    $rukun_tetangga->update([
        'nik' => $request->nik,
        'no_kk' => $warga->no_kk,
        'rt'  => $request->rt,
        'nama' => $request->nama,
        'mulai_menjabat' => $request->mulai_menjabat,
        'akhir_jabatan'  => $request->akhir_jabatan,
    ]);

    // ✅ Update juga data user RT
    $user = User::where('nik', $request->nik)->first();

    if ($user) {
        $user->update([
            'id_rt' => $rukun_tetangga->id,
            'id_rw' => 1,
        ]);

        if (!$user->hasRole('rt')) {
            $user->assignRole('rt');
        }
    } else {
        $user = User::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'password' => bcrypt('password'),
            'id_rt' => $rukun_tetangga->id,
            'id_rw' => 1,
        ]);

        $user->assignRole('rt');
    }

    return redirect()->route('admin.rt.index')->with('success', 'Rukun Tetangga berhasil diperbarui.');
}
    

    public function destroy(string $id)
    {
        try {
            $rt = Rukun_tetangga::findOrFail($id);

            // Cari user terkait RT
            $user = User::where('id_rt', $rt->id)->first();

            if ($user) {
                // Jika user punya lebih dari 1 role, hapus role 'rt' saja
                if ($user->roles()->count() > 1) {
                    $user->removeRole('rt');
                    $user->id_rt = null; // reset id_rt
                    $user->save();
                } else {
                    // Jika hanya punya 1 role (yaitu 'rt'), hapus user
                    $user->delete();
                }
            }

            // Hapus data RT dari tabel rukun_tetangga
            $rt->delete();

            return redirect()->back()->with('success', 'RT berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus RT karena masih digunakan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
