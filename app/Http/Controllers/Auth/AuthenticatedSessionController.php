<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Validasi dan Autentikasi User (Menggunakan Email & Password)
        $request->authenticate();

        // 2. Regenerate session untuk mencegah Session Fixation attack
        $request->session()->regenerate();

        // 3. Tentukan arah redirect berdasarkan Role
        $user = Auth::user();
        $url = '/'; // Default fallback

        if ($user->role === 'admin') {
            $url = '/dashboard'; // Tambahkan '/' di awal agar absolut
        } elseif ($user->role === 'kasir') {
            $url = '/dashboard'; // Tambahkan '/' di awal agar absolut
        }

        // Redirect ke intended URL (halaman yang coba diakses sebelum login)
        // atau ke halaman dashboard sesuai role.
        return redirect()->intended($url);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
