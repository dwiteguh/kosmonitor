<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman form login.
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Memproses data login yang dikirim oleh pengguna.
     */
    public function authenticate(Request $request)
    {
        // 1. Validasi input form
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba melakukan proses login
        if (Auth::attempt($credentials)) {
            
            // 3. Jika berhasil, regenerate session untuk mencegah serangan Session Fixation
            $request->session()->regenerate();

            // 4. Cek role user dan arahkan ke dashboard yang tepat
            $role = Auth::user()->role;
            
            if ($role === 'owner') {
                return redirect()->intended(route('owner.dashboard'));
            } elseif ($role === 'tenant') {
                return redirect()->intended(route('tenant.dashboard'));
            }
        }

        // 5. Jika gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Memproses logout pengguna.
     */
    public function logout(Request $request)
    {
        // 1. Proses logout
        Auth::logout();

        // 2. Hapus semua data session
        $request->session()->invalidate();

        // 3. Buat ulang CSRF token untuk keamanan
        $request->session()->regenerateToken();

        // 4. Arahkan kembali ke halaman login
        return redirect('/login');
    }
}