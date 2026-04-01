<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('is_admin_logged_in')) {
            return redirect()->route('admin.login')->with('err', 'Silakan login dulu.');
        }

        // hanya role admin yang boleh masuk /admin/*
        if (session('admin_role') !== 'admin') {
            return redirect()->route('admin.login')->with('err', 'Akses khusus admin.');
        }

        return $next($request);
    }
}
