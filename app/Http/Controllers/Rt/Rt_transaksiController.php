<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Rukun_tetangga;
use Illuminate\Http\Request;

class Rt_transaksiController extends Controller
{
    public function index(Request $request)
    {
        $title = "Data Transaksi RT";

        // filter data transaksi khusus RT yang sedang login
        $query = Transaksi::query();

        if ($request->filled('search')) {
            $query->where('nama_transaksi', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        $transaksi = (clone $query)->orderBy('tanggal', 'desc')->get();
        $paginatedTransaksi = $query->orderBy('tanggal', 'desc')->paginate(10);

        // daftar tahun unik dari data transaksi
        $daftar_tahun = Transaksi::selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // daftar RT untuk dropdown
        $rukun_tetangga = Rukun_tetangga::orderBy('rt')->pluck('rt', 'id');

        // pemasukan otomatis (sementara 0, bisa diisi logic iuran)
        $totalPemasukanBelumTercatat = 0;

        return view('rt.iuran.transaksi', compact(
            'title',
            'transaksi',
            'paginatedTransaksi',
            'daftar_tahun',
            'rukun_tetangga',
            'totalPemasukanBelumTercatat'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rt' => 'required|exists:rukun_tetangga,id',
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string|max:255',
            'pengeluaran' => 'nullable|numeric|min:0',
            'pemasukan' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        Transaksi::create($validated);

        return redirect()->route('rt_transaksi.index')->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function update(Request $request, Transaksi $rt_transaksi)
    {
        $validated = $request->validate([
            'rt' => 'required|exists:rukun_tetangga,id',
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string|max:255',
            'pengeluaran' => 'nullable|numeric|min:0',
            'pemasukan' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $rt_transaksi->update($validated);

        return redirect()->route('rt_transaksi.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy(Transaksi $rt_transaksi)
    {
        $rt_transaksi->delete();

        return redirect()->route('rt_transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
