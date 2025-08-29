<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WargatagihanController extends Controller
{
    
    /**
     * Menampilkan daftar tagihan untuk warga yang sedang login.
     */
        public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        

        $title = 'Tagihan Saya';
        if (!$user || !$user->hasRole('warga') || !$user->warga) {
            Log::warning("Akses tidak sah ke halaman tagihan warga atau data warga tidak ditemukan.", ['user_id' => $user->id ?? 'guest']);
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini atau data profil Anda tidak lengkap.');
        }

        $no_kk_warga = $user->warga->no_kk;

        if (!$no_kk_warga) {
            Log::warning("Nomor Kartu Keluarga tidak ditemukan untuk warga yang login.", ['user_id' => $user->id, 'nik' => $user->nik]);
            return redirect('/')->with('error', 'Data Kartu Keluarga Anda tidak ditemukan. Silakan hubungi RT/RW Anda.');
        }

        $query = Tagihan::where('no_kk', $no_kk_warga);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                ->orWhere('nominal', 'like', '%' . $search . '%');
            });
        }

        $tagihan = $query->orderBy('tgl_tagih', 'desc')->paginate(10);

        return view('warga.iuran.tagihan', compact('title', 'tagihan'));
    }
}
