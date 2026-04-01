<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminUser;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // Kalau sudah login, lempar sesuai role
        if (session('is_admin_logged_in')) {
            if (session('admin_role') === 'kemahasiswaan') {
                return redirect()->route('kema.dashboard');
            }
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = trim((string) $request->input('username'));
        $password = (string) $request->input('password');

        // Ambil user dari tabel admin_users
        $user = AdminUser::where('username', $username)->first();

        if (!$user) {
            return back()
                ->with('err', 'Username / password salah.')
                ->withInput($request->only('username'));
        }

        // password_hash berisi bcrypt
        if (!Hash::check($password, (string) $user->password_hash)) {
            return back()
                ->with('err', 'Username / password salah.')
                ->withInput($request->only('username'));
        }

        // AMAN: regenerate session id (anti session fixation)
        $request->session()->regenerate();

        // set session login (ADMIN)
        session([
            'is_admin_logged_in' => true,
            'admin_id'           => $user->id,
            'admin_username'     => $user->username,
            'admin_name'         => $user->name ?? $user->username, // kalau ada kolom name
            'admin_role'         => $user->role, // admin | kemahasiswaan
        ]);

        /**
         * Kalau role kemahasiswaan:
         * - set juga flag khusus kema supaya middleware kema.auth gampang
         * - dan controller Kema bisa pakai session('kema_name')
         */
        if ((string) $user->role === 'kemahasiswaan') {
            session([
                'is_kema_logged_in' => true,
                'kema_id'           => $user->id,
                'kema_username'     => $user->username,
                'kema_name'         => $user->name ?? $user->username,
            ]);

            return redirect()->route('kema.dashboard');
        }

        // default role admin biasa
        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        // hapus semua session
        $request->session()->flush();

        // invalidate + rotate token (aman untuk logout)
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
