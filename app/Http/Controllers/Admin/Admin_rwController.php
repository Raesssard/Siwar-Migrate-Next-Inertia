<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rw;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Jabatan;
use Illuminate\Support\Facades\Validator;

class Admin_rwController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Data RW';
        $rw = Rw::with('jabatan')->paginate(10);
        $jabatan = Jabatan::where('level', 'rw')->get();

        return view('admin.data-rw.rw', compact('title', 'rw', 'jabatan'));
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
            'nik' => 'required|unique:rw,nik',
            'nomor_rw' => 'required|string',
            'nama_ketua_rw' => 'required|string|max:255',
            'jabatan_id' => 'required|exists:jabatan,id',
            'mulai_menjabat'=> 'required',
            'akhir_jabatan' => 'required',
        ], [
            'nik.required' => 'NIK harus diisi.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'nomor_rw.required' => 'Nomor Rukun Warga harus diisi.',
            'nama_ketua_rw.required' => 'Nama Ketua Rukun Warga harus diisi.',
            'mulai_menjabat.required' => 'Mulai Menjabat harus diisi.',
            'akhir_jabatan.required' => 'Akhir Menjabat harus diisi.',
        ]);

        // Validasi jabatan unik per nomor_rw
        $jabatan = Jabatan::findOrFail($request->jabatan_id);
        $exists = Rw::where('nomor_rw', $request->nomor_rw)
            ->where('jabatan_id', $jabatan->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors([
                'jabatan_id' => "RW {$request->nomor_rw} sudah memiliki {$jabatan->nama_jabatan}.",
            ])->withInput();
        }

        $rw = Rw::create($request->only([
            'nik','nomor_rw','nama_ketua_rw','jabatan_id','mulai_menjabat','akhir_jabatan'
        ]));

        $user = User::create([
            'nik' => $request->nik,
            'nama' => $request->nama_ketua_rw,
            'password' => bcrypt('password'),
            'id_rw' => $rw->id,
        ]);

        $user->assignRole('rw');

        return redirect()->route('admin.rw.index')->with('success', 'Rukun Warga berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rw = Rw::findOrFail($id);
        return view('admin.data-rw.show', compact('rw'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rw = Rw::findOrFail($id);
        return view('admin.data-rw.edit', compact('rw'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // gunakan validator manual biar bisa tambahin edit_id ke session
        $validator = Validator::make($request->all(), [
            'nik' => [
                'required',
                Rule::unique('rw')->ignore($id),
            ],
            'nomor_rw' => 'required|string',
            'nama_ketua_rw' => 'required|string|max:255',
            'jabatan_id' => 'required|exists:jabatan,id',
            'mulai_menjabat' => 'required',
            'akhir_jabatan' => 'required',
        ], [
            'nik.required' => 'NIK harus diisi.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'nomor_rw.required' => 'Nomor Rukun Warga harus diisi.',
            'nama_ketua_rw.required' => 'Nama Ketua Rukun Warga harus diisi.',
            'mulai_menjabat.required' => 'Mulai Menjabat harus diisi.',
            'akhir_jabatan.required' => 'Akhir Menjabat harus diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('edit_id', $id); // 🔑 biar view tau modal mana yg harus dibuka
        }

        $rw = Rw::findOrFail($id);

        // Cek jabatan unik saat update
        $jabatan = Jabatan::findOrFail($request->jabatan_id);
        $exists = Rw::where('nomor_rw', $request->nomor_rw)
            ->where('jabatan_id', $jabatan->id)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors([
                'jabatan_id' => "RW {$request->nomor_rw} sudah memiliki {$jabatan->nama_jabatan}.",
            ])->withInput()->with('edit_id', $id);
        }

        // Simpan NIK lama sebelum update
        $oldNik = $rw->nik;

        // Update data RW
        $rw->update($request->only([
            'nik','nomor_rw','nama_ketua_rw','jabatan_id','mulai_menjabat','akhir_jabatan'
        ]));

        // Update user yang terhubung dengan RW ini
        $user = User::where('id_rw', $rw->id)->first();
        if ($user) {
            $user->update([
                'nik'  => $request->nik,
                'nama' => $request->nama_ketua_rw,
            ]);
        } else {
            // fallback kalau user hilang
            User::where('nik', $oldNik)->update([
                'nik'  => $request->nik,
                'nama' => $request->nama_ketua_rw,
            ]);
        }

        return redirect()->route('admin.rw.index')->with('success', 'Rukun Warga berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $rw = Rw::findOrFail($id);

            // Hapus user terkait
            User::where('id_rw', $rw->id)->delete();

            // Hapus data RW
            $rw->delete();

            return redirect()->back()->with('success', 'RW berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus RW karena masih digunakan.');
        }
    }
}
