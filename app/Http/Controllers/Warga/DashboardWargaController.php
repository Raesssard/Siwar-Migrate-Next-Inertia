<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\Pengumuman;
use App\Models\Tagihan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardWargaController extends Controller
{
    //

    public function index()
    {
        $title = 'Dashboard';

        $userRtId = Auth::user()->warga->kartuKeluarga->rukunTetangga->id ?? null;
        $userRwId = Auth::user()->warga->kartuKeluarga->rw->id ?? null;

        if (is_null($userRtId) && is_null($userRwId)) {
            abort(403, 'Anda tidak terhubung dengan RT atau RW manapun untuk melihat pengumuman. Harap hubungi administrator.');
        }

        $baseQuery = Pengumuman::query();

        $baseQuery->where(function ($query) use ($userRtId, $userRwId) {
            if ($userRtId) {
                $query->where('id_rt', $userRtId);
            }

            if ($userRwId) {
                $query->orWhere(function ($subQuery) use ($userRwId) {
                    $subQuery->where('id_rw', $userRwId)
                        ->whereNull('id_rt'); // Penting: Hanya pengumuman tingkat RW
                });
            }
        });

        $nik = Auth::user()->nik;
        $jumlah_pengumuman = (clone $baseQuery)->count();
        $jumlah_tagihan = Tagihan::where('status_bayar', 'belum_bayar')
            ->whereIn('no_kk', function ($kk) use ($nik) {
                $kk->select('no_kk')
                    ->from('warga')
                    ->where('nik', $nik);
            })->count();
        $total_tagihan = Tagihan::where('status_bayar', 'belum_bayar')
            ->whereIn('no_kk', function ($kk) use ($nik) {
                $kk->select('no_kk')
                    ->from('warga')
                    ->where('nik', $nik);
            })
            ->sum('nominal');

        $transaksi = Transaksi::where('rt', Auth::user()->warga->kartuKeluarga->rukunTetangga->rt);
        $pemasukan = (clone $transaksi)->where('jenis', 'pemasukan')->sum('nominal');
        $pengeluaran = (clone $transaksi)->where('jenis', 'pengeluaran')->sum('nominal');
        $jumlah_transaksi = (clone $transaksi)->count();
        $total_transaksi = $pemasukan - $pengeluaran;

        $pengaduan = Pengaduan::where('nik_warga', $nik)->count();

        $total_pemasukan_iuran = Tagihan::where('status_bayar', 'sudah_bayar')
            ->sum('nominal');

        $total_pemasukan_transaksi = Transaksi::where('jenis', 'pemasukan')->sum('nominal');
        $total_pengeluaran = Transaksi::where('jenis', 'pengeluaran')->sum('nominal');

        $total_pemasukan = $total_pemasukan_iuran + $total_pemasukan_transaksi;

        $total_saldo_akhir = $total_pemasukan - $total_pengeluaran;

        // return view('warga.dashboard.dashboard', compact(
        //     'title',
        //     'jumlah_pengumuman',
        //     'total_tagihan',
        //     'total_transaksi',
        //     'jumlah_tagihan',
        //     'jumlah_transaksi',
        //     'total_saldo_akhir',
        //     'pengaduan'
        // ));
        return Inertia::render('Dashboard', [
            'title' => $title,
            'jumlah_pengumuman' => $jumlah_pengumuman,
            'total_tagihan' => $total_tagihan,
            'total_transaksi' => $total_transaksi,
            'jumlah_tagihan' => $jumlah_tagihan,
            'jumlah_transaksi' => $jumlah_transaksi,
            'total_saldo_akhir' => $total_saldo_akhir,
            'pengaduan' => $pengaduan,
        ]);
    }
}
