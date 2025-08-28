<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nik' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // kalau user hanya punya 1 role → langsung redirect
            if (count($user->roles) === 1) {
                return $this->redirectByRole($user->roles[0], $user);
            }

            // kalau punya banyak role → tampilkan halaman pilih role
            return redirect()->route('choose-role');
        }

        // Jika gagal login
        return back()->withErrors([
            'nik' => 'NIK atau password salah.',
            'password' => 'NIK atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    private function redirectByRole(string $role, $user)
    {
        // Khusus warga, cek dulu apakah Kepala Keluarga
        if ($role === 'warga') {
            $warga = $user->warga;
            if (!$warga || strtolower($warga->status_hubungan_dalam_keluarga) !== 'kepala keluarga') {
                Auth::logout();
                return redirect('/login')->withErrors([
                    'nik' => 'Hanya Kepala Keluarga yang bisa login.',
                ]);
            }
            return redirect()->route('dashboard-main');
        }

        return match ($role) {
            'admin' => redirect()->route('dashboard-admin'),
            'rw'    => redirect()->route('dashboard-rw'),
            'rt'    => redirect()->route('dashboard-rt'),
            default => redirect('/login'),
        };
    }

    // Halaman pilih role
    public function chooseRole()
    {
        $user = Auth::user();
        return view('auth.choose-role', ['roles' => $user->roles]);
    }

    // Simpan role yang dipilih
    public function setRole(Request $request)
    {
        $user = Auth::user();
        $role = $request->input('role');

        if (!in_array($role, $user->roles)) {
            return redirect()->route('choose-role')->with('error', 'Role tidak valid.');
        }

        // bisa juga disimpan ke session kalau mau
        session(['active_role' => $role]);

        return $this->redirectByRole($role, $user);
    }
}
