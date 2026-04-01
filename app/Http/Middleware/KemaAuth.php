<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KemaAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('is_admin_logged_in')) {
            return redirect()->route('admin.login')->with('err', 'Silakan login dulu.');
        }

        if (session('admin_role') !== 'kemahasiswaan') {
            return redirect()->route('admin.pengajuan')->with('err', 'Akses khusus kemahasiswaan.');
        }

        return $next($request);
    }
}
