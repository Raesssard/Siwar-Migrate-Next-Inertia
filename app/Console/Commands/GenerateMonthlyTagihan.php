<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use App\Models\Iuran;
use App\Models\IuranGolongan;
use App\Models\Kartu_Keluarga;
use App\Models\Tagihan;
use Carbon\Carbon;

class GenerateMonthlyTagihan extends Command
{
    protected $signature = 'tagihan:generate';
    protected $description = 'Generate tagihan otomatis setiap awal bulan berdasarkan iuran dan golongan';

    public function handle()
    {
        $today = Carbon::today();
        $this->info("=== Mulai generate tagihan untuk {$today->format('F Y')} ===");

        // ambil semua iuran
        $iuranList = Iuran::all();
        $this->info("Total iuran: " . $iuranList->count());

        foreach ($iuranList as $iuran) {
            $this->info("Iuran: {$iuran->id} - {$iuran->nama}");

            // ambil semua mapping golongan untuk iuran ini
            $iuranGolonganList = IuranGolongan::where('id_iuran', $iuran->id)->get();
            $this->info("  Jumlah golongan terkait: " . $iuranGolonganList->count());

            foreach ($iuranGolonganList as $gol) {
                $this->info("    Golongan: {$gol->id_golongan} - Nominal: {$gol->nominal}");

                // ambil semua keluarga yang sesuai dengan golongan
                $keluargaList = Kartu_Keluarga::where('kategori_iuran', $gol->id_golongan)->get();
                $this->info("      Jumlah KK ditemukan: " . $keluargaList->count());

                foreach ($keluargaList as $kk) {
                    $this->info("        Proses KK: {$kk->no_kk}");

                    if ($gol->nominal <= 0) {
                        $this->warn("        Nominal 0, skip!");
                        continue;
                    }

                    // cek apakah tagihan bulan ini sudah ada
                    $sudahAda = Tagihan::where('id_iuran', $iuran->id)
                        ->where('no_kk', $kk->no_kk)
                        ->whereMonth('tgl_tagih', $today->month)
                        ->whereYear('tgl_tagih', $today->year)
                        ->exists();

                    if ($sudahAda) {
                        $this->warn("        Sudah ada tagihan bulan ini, skip!");
                        continue;
                    }

                    // buat tagihan baru
                    Tagihan::create([
                        'nama' => $iuran->nama,
                        'nominal' => $gol->nominal,
                        'tgl_tagih' => $today,
                        'tgl_tempo' => $today->copy()->endOfMonth(),
                        'jenis' => 'otomatis',
                        'no_kk' => $kk->no_kk,
                        'status_bayar' => 'belum_bayar',
                        'id_iuran' => $iuran->id,
                        'kategori_pembayaran' => null,
                        'tercatat_transaksi' => false,
                    ]);

                    $this->info("        ✅ Tagihan dibuat untuk KK {$kk->no_kk}");
                }
            }
        }

        $this->info("=== Selesai generate tagihan {$today->format('F Y')} ===");
    }

    public function schedule(Schedule $schedule): void
    {
        $schedule->command(static::class)->monthlyOn(1, '00:00');
    }
}
