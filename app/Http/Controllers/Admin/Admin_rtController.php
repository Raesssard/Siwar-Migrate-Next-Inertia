<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rukun_tetangga;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Jabatan;

class Admin_rtController extends Controller
{
    public function index()
    {
        $rukun_tetangga = Rukun_tetangga::paginate(5);
        $title = 'Rukun Tetangga';
        $jabatans = Jabatan::where('level', 'rt')->get();

        return view('admin.data-rt.rt', compact('rukun_tetangga', 'title', 'jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:rukun_tetangga,nik',
            'rt' => ['required', 'regex:/^[0-9]{2}$/'],
            'nama' => 'required|string|max:255',
            'jabatan_id' => 'required|exists:jabatan,id',
            'mulai_menjabat' => 'required|date',
            'akhir_jabatan' => 'required|date|after:mulai_menjabat',
        ]);

        $warga = Warga::where('nik', $request->nik)->first();
        if (!$warga) {
            return back()->withErrors(['nik' => 'NIK tidak ditemukan di data warga.'])->withInput();
        }

        if ($warga->nama !== $request->nama) {
            return back()->withErrors(['nama' => 'Nama tidak sesuai dengan NIK yang dipilih.'])->withInput();
        }

        // Pastikan jabatan unik per RT
        $jabatan = Jabatan::find($request->jabatan_id);
        $sudahAda = Rukun_tetangga::where('rt', $request->rt)
            ->where('jabatan_id', $jabatan->id)
            ->exists();

        if ($sudahAda) {
            return back()->withErrors([
                'jabatan_id' => "RT {$request->rt} sudah punya {$jabatan->nama_jabatan}."
            ])->withInput();
        }

        // Buat record RT
        $rt = Rukun_tetangga::create([
            'nik' => $request->nik,
            'no_kk' => $warga->no_kk,
            'rt' => $request->rt,
            'nama' => $request->nama,
            'jabatan_id' => $request->jabatan_id,
            'mulai_menjabat' => $request->mulai_menjabat,
            'akhir_jabatan' => $request->akhir_jabatan,
            'id_rw' => 1,
        ]);

        // User RT
        $user = User::where('nik', $request->nik)->first();
        if ($user) {
            $user->update([
                'id_rt' => $rt->id,
                'id_rw' => 1,
                'password' => Hash::make('password'),
            ]);

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

            $user->assignRole('rt');
        }

        return redirect()->route('admin.rt.index')->with('success', 'Rukun Tetangga berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $rukun_tetangga = Rukun_tetangga::findOrFail($id);
        $jabatans = Jabatan::where('level', 'rt')->get();

        return view('admin.rt.edit', compact('rukun_tetangga', 'jabatans'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nik' => 'required|exists:warga,nik',
            'rt' => ['required', 'regex:/^[0-9]{2}$/'],
            'nama' => 'required|string|max:255',
            'jabatan_id' => 'required|exists:jabatan,id',
            'mulai_menjabat' => 'required|date',
            'akhir_jabatan' => 'required|date|after:mulai_menjabat',
        ]);

        $warga = Warga::where('nik', $request->nik)->first();
        if (!$warga) {
            return back()->withErrors(['nik' => 'NIK tidak ditemukan di data warga.'])->withInput();
        }

        if ($warga->nama !== $request->nama) {
            return back()->withErrors(['nama' => 'Nama tidak sesuai dengan NIK yang dipilih.'])->withInput();
        }

        // Pastikan jabatan unik
        $jabatan = Jabatan::find($request->jabatan_id);
        $sudahAda = Rukun_tetangga::where('rt', $request->rt)
            ->where('jabatan_id', $jabatan->id)
            ->where('id', '!=', $id)
            ->exists();

        if ($sudahAda) {
            return back()->withErrors([
                'jabatan_id' => "RT {$request->rt} sudah punya {$jabatan->nama_jabatan}."
            ])->withInput();
        }

        $rukun_tetangga = Rukun_tetangga::findOrFail($id);
        $rukun_tetangga->update([
            'nik' => $request->nik,
            'no_kk' => $warga->no_kk,
            'rt'  => $request->rt,
            'nama' => $request->nama,
            'jabatan_id' => $request->jabatan_id,
            'mulai_menjabat' => $request->mulai_menjabat,
            'akhir_jabatan'  => $request->akhir_jabatan,
        ]);

        // Update user
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
            $user = User::where('id_rt', $rt->id)->first();

            if ($user) {
                if ($user->roles()->count() > 1) {
                    if ($user->hasRole('rt')) {
                        $user->removeRole('rt');
                    }
                    $user->update(['id_rt' => null]);
                } else {
                    $user->delete();
                }
            }

            $rt->delete();

            return redirect()->back()->with('success', 'RT berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus RT karena masih digunakan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
