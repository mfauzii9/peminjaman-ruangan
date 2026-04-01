<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class ShareStatPending
{
    public function handle($request, Closure $next)
    {
        DB::statement("SET time_zone = '+07:00'");

        // ✅ Pending untuk Admin (dari kolom status)
        $stat_pending = (int) DB::table('borrow_requests')
            ->where('status', 'menunggu')
            ->count();

        // ✅ Pending untuk Kemahasiswaan (dari kolom kema_status)
        $stat_pending_kema = (int) DB::table('borrow_requests')
            ->where('kema_status', 'menunggu')
            ->count();

        // Share ke semua view
        view()->share('stat_pending', $stat_pending);
        view()->share('stat_pending_kema', $stat_pending_kema);

        return $next($request);
    }
}