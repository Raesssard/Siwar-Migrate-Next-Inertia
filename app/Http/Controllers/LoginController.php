<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // return view('login.login'); ini yg blade
        return Inertia::render('Login');
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
            if ($user->roles->count() === 1) {
                return $this->redirectByRole($user->roles->first()->name, $user);
            }

            // kalau punya banyak role → tampilkan halaman pilih role
            // return redirect()->route('choose-role');
            return Inertia::location(route('choose-role'));
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
            return redirect()->route('warga.dashboard');
        }

        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'rw'    => redirect()->route('rw.dashboard'),
            'rt'    => redirect()->route('rt.dashboard'),
            default => redirect('/login'),
        };
    }

    // Halaman pilih role
    public function chooseRole()
    {
        /** @var User $user */
        $user = Auth::user();
        $roles = $user->getRoleNames();
        // return view('auth.choose-role', ['roles' => $user->roles]);
        return Inertia::render('ChooseRole', compact('user', 'roles'));
    }

    // Simpan role yang dipilih
    public function setRole(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $role = $request->input('role');

        if (!$user->hasRole($role)) {
            return redirect()->route('choose-role')->with('error', 'Role tidak valid.');
        }

        session(['active_role' => $role]);

        return $this->redirectByRole($role, $user);
    }
}
