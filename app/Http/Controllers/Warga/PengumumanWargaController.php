<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\Rukun_tetangga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PengumumanWargaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');
        $kategori = $request->input('kategori');

        $userRtId = Auth::user()->warga->kartuKeluarga->rukunTetangga->id ?? null;
        $userRwId = Auth::user()->warga->kartuKeluarga->rw->id ?? null;

        if (is_null($userRtId) && is_null($userRwId)) {
            abort(403, 'Anda tidak terhubung dengan RT atau RW manapun untuk melihat pengumuman. Harap hubungi administrator.');
        }

        $baseQuery = Pengumuman::query()->with(['rukunTetangga', 'rw']);

        $baseQuery->where(function ($query) use ($userRtId, $userRwId) {
            if ($userRtId) {
                $query->where('id_rt', $userRtId);
            }
            if ($userRwId) {
                $query->orWhere(function ($subQuery) use ($userRwId) {
                    $subQuery->where('id_rw', $userRwId)
                        ->whereNull('id_rt');
                });
            }
            $query->orWhere(function ($subQuery) {
                $subQuery->whereNull('id_rt')
                    ->whereNull('id_rw');
            });
        });

        $total_pengumuman = (clone $baseQuery)->count();

        $pengumuman = (clone $baseQuery)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%$search%")
                        ->orWhere('isi', 'like', "%$search%");
                    $searchLower = strtolower($search);
                    $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
                    if (in_array($searchLower, $hariList)) {
                        $q->orWhereRaw("DAYNAME(tanggal) = ?", [$this->indoToEnglishDay($searchLower)]);
                    }
                    $bulanList = [
                        'januari',
                        'februari',
                        'maret',
                        'april',
                        'mei',
                        'juni',
                        'juli',
                        'agustus',
                        'september',
                        'oktober',
                        'november',
                        'desember'
                    ];
                    if (in_array($searchLower, $bulanList)) {
                        $bulanAngka = array_search($searchLower, $bulanList) + 1;
                        $q->orWhereMonth('tanggal', $bulanAngka);
                    }
                });
            })
            ->when($tahun, fn($q) => $q->whereYear('tanggal', $tahun))
            ->when($bulan, fn($q) => $q->whereMonth('tanggal', $bulan))
            ->when($kategori, fn($q) => $q->where('kategori', $kategori))
            ->orderByDesc('tanggal')
            ->paginate(5)
            ->through(function ($item) {
                $item->tanggal = \Carbon\Carbon::parse($item->tanggal)
                    ->translatedFormat('d F Y'); // contoh: 24 September 2025
                $item->dokumen_url = $item->dokumen_path
                    ? Storage::url($item->dokumen_path)
                    : null;
                return $item;
            })
            ->withQueryString();

        $daftar_tahun = (clone $baseQuery)
            ->selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        $daftar_kategori = (clone $baseQuery)
            ->select('kategori')
            ->distinct()
            ->pluck('kategori');

        $list_bulan = [
            'januari',
            'februari',
            'maret',
            'april',
            'mei',
            'juni',
            'juli',
            'agustus',
            'september',
            'oktober',
            'november',
            'desember'
        ];

        $daftar_bulan = range(1, 12);

        $rukun_tetangga = $userRtId ? Rukun_tetangga::find($userRtId) : null;
        $title = 'Pengumuman';

        // return view('warga.pengumuman.pengumuman', compact(
        //     'pengumuman',
        //     'rukun_tetangga',
        //     'title',
        //     'daftar_tahun',
        //     'daftar_bulan',
        //     'daftar_kategori',
        //     'total_pengumuman'
        // ));

        return Inertia::render('Pengumuman', [
            'pengumuman' => $pengumuman,
            'rukun_tetangga' => $rukun_tetangga,
            'title' => $title,
            'daftar_tahun' => $daftar_tahun,
            'daftar_bulan' => $daftar_bulan,
            'daftar_kategori' => $daftar_kategori,
            'total_pengumuman' => $total_pengumuman,
            'list_bulan' => $list_bulan,
            'filters' => $request->only(['search', 'tahun', 'bulan', 'kategori']),
        ]);
    }

    /**
     * Helper method untuk mengkonversi nama hari dalam Bahasa Indonesia ke Bahasa Inggris.
     * Metode ini diasumsikan ada di dalam controller yang sama.
     *
     * @param string $indoDay
     * @return string|null
     */
    protected function indoToEnglishDay(string $indoDay): ?string
    {
        $map = [
            'senin' => 'Monday',
            'selasa' => 'Tuesday',
            'rabu' => 'Wednesday',
            'kamis' => 'Thursday',
            'jumat' => 'Friday',
            'sabtu' => 'Saturday',
            'minggu' => 'Sunday',
        ];
        return $map[strtolower($indoDay)] ?? null;
    }
}
