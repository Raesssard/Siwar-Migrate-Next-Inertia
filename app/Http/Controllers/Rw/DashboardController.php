<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use App\Models\Kartu_keluarga;
use App\Models\Pengumuman;
use App\Models\Rukun_tetangga;
use App\Models\Tagihan;
use App\Models\Transaksi;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $id_rw = Auth::user()->id_rw;
        $id_rt = Auth::user()->id_rt;

        $jumlah_warga = Warga::count();
        $jumlah_kk = Kartu_keluarga::count();

        $pengumuman_rw = Pengumuman::where('id_rw', $id_rw)
            ->whereNull('id_rt')
            ->count();

        $pengumuman_rt = Pengumuman::where('id_rw', $id_rw)
            ->where('id_rt', $id_rt)
            ->count();

        $jumlah_rt = Rukun_tetangga::count();

        // Total pemasukan dari iuran yang sudah dibayar
        $total_pemasukan_iuran = Tagihan::where('status_bayar', 'sudah_bayar')
            ->sum('nominal');

        // Total pemasukan & pengeluaran dari tabel transaksi
        $total_pemasukan_transaksi = Transaksi::where('jenis', 'pemasukan')->sum('nominal');
        $total_pengeluaran = Transaksi::where('jenis', 'pengeluaran')->sum('nominal');

        // Total pemasukan keseluruhan
        $total_pemasukan = $total_pemasukan_iuran + $total_pemasukan_transaksi;

        // Saldo akhir
        $total_saldo_akhir = $total_pemasukan - $total_pengeluaran;

        // Total iuran masuk bulan ini
        $total_iuran_bulan_ini = Tagihan::where('status_bayar', 'sudah_bayar')
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereYear('updated_at', Carbon::now()->year)
            ->sum('nominal');

        $title = 'Dashboard';
        $jumlah_warga_penduduk = Warga::where('status_warga', 'penduduk')->count();
        $jumlah_warga_pendatang = Warga::where('status_warga', 'pendatang')->count();

        return view('rw.dashboard.dashboard', compact(
            'title',
            'jumlah_warga',
            'jumlah_kk',
            'pengumuman_rw',
            'pengumuman_rt',
            'jumlah_warga_penduduk',
            'jumlah_warga_pendatang',
            'jumlah_rt',
            'total_pemasukan',
            'total_pengeluaran',
            'total_saldo_akhir',
            'total_iuran_bulan_ini'
        ));
    }
}
