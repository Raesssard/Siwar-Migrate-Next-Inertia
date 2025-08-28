<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;

use App\Models\Rt_transaksi;
use Illuminate\Http\Request;
use App\Models\Transaksi; // kalau pakai model transaksi

class Rt_transaksiController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi.
     * Tidak ada filter berdasarkan RT karena ini adalah akun RT.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Mendapatkan semua data transaksi, diurutkan dari yang terbaru,
        // tanpa memfilter berdasarkan RT.
        $transaksi = Transaksi::latest()->paginate(10);

        return view('rt_transaksi.index', compact('transaksi'));
    }

    /**
     * Menampilkan formulir untuk membuat transaksi baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('rt_transaksi.create');
    }

    /**
     * Menyimpan transaksi yang baru dibuat ke database.
     * Validasi tidak lagi menyertakan 'rt'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|string|in:Pemasukan,Pengeluaran',
            'keterangan' => 'required|string|max:255',
            'jumlah' => 'required|numeric',
        ]);

        Transaksi::create($validated);

        return redirect()->route('rt_transaksi.index')->with('success', 'Transaksi berhasil ditambahkan!');
    }

    /**
     * Menampilkan transaksi tertentu.
     *
     * @param  \App\Models\Rt_transaksi  $transaksi
     * @return \Illuminate\View\View
     */
    public function show(Transaksi $transaksi)
    {
        return view('rt_transaksi.show', compact('transaksi'));
    }

    /**
     * Menampilkan formulir untuk mengedit transaksi.
     *
     * @param  \App\Models\Rt_transaksi  $transaksi
     * @return \Illuminate\View\View
     */
    public function edit(Transaksi $transaksi)
    {
        return view('rt_transaksi.edit', compact('transaksi'));
    }

    /**
     * Memperbarui transaksi yang ada di database.
     * Validasi tidak lagi menyertakan 'rt'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rt_transaksi  $transaksi
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|string|in:Pemasukan,Pengeluaran',
            'keterangan' => 'required|string|max:255',
            'jumlah' => 'required|numeric',
        ]);

        $transaksi->update($validated);

        return redirect()->route('rt_transaksi.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    /**
     * Menghapus transaksi dari database.
     *
     * @param  \App\Models\Rt_transaksi  $transaksi
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();

        return redirect()->route('rt_transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
