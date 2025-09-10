<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\Kartu_keluarga;
use App\Models\Rukun_tetangga;
use App\Models\User;
use App\Models\Warga;
use App\Models\Iuran;
use App\Models\IuranGolongan;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class Rt_wargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $title = 'Manajemen Warga';
        $search = $request->search;
        $rt_id = Auth::user()->rukunTetangga->id;
        $jenis_kelamin = $request->jenis_kelamin;
        $total_warga = Warga::whereHas('kartuKeluarga', function ($query) use ($rt_id) {
            $query->where('id_rt', $rt_id);
        })->count();
        $warga = Warga::with('kartuKeluarga')
            ->whereHas('kartuKeluarga', function ($query) {
                $query->where('id_rt', Auth::user()->id_rt);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('nik', 'like', '%' . $search . '%')
                    ->orWhere('no_kk', 'like', '%' . $search . '%');
            })
            ->when($jenis_kelamin, function ($kelamin) use ($jenis_kelamin) {
                $kelamin->where('jenis_kelamin', $jenis_kelamin);
            })
            ->paginate(5)
            ->withQueryString();


        return view('rt.warga.warga', compact('title', 'warga', 'search', 'total_warga'));
    }

    public function generateMonthlyTagihan()
    {
        $today = now()->startOfDay();

        $iurans = Iuran::where('jenis', 'otomatis')
            ->whereDay('tgl_tagih', $today->day)
            ->get();

        foreach ($iurans as $iuran) {
            $iuranNominals = IuranGolongan::where('id_iuran', $iuran->id)
                ->pluck('nominal', 'golongan');

            $kkList = Kartu_keluarga::all();

            foreach ($kkList as $kk) {
                $nominalTagihan = $iuranNominals[$kk->golongan] ?? 0;

                $exists = Tagihan::where('id_iuran', $iuran->id)
                    ->where('no_kk', $kk->no_kk)
                    ->whereMonth('tgl_tagih', $today->month)
                    ->whereYear('tgl_tagih', $today->year)
                    ->exists();

                if (!$exists) {
                    Tagihan::create([
                        'nama' => $iuran->nama,
                        'tgl_tagih' => $today,
                        'tgl_tempo' => $iuran->tgl_tempo ?? $today->copy()->addDays(10),
                        'jenis' => 'otomatis',
                        'nominal' => $nominalTagihan,
                        'no_kk' => $kk->no_kk,
                        'status_bayar' => 'belum_bayar',
                        'tgl_bayar' => null,
                        'id_iuran' => $iuran->id,
                        'kategori_pembayaran' => null,
                        'bukti_transfer' => null,
                    ]);
                }
            }
        }

        return redirect()->route('rt_iuran.index')->with('success', 'Tagihan bulanan berhasil dibuat.');
    }
}
