<?php

namespace App\Http\Controllers\Kema;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        DB::statement("SET time_zone = '+07:00'");
        $now = Carbon::now('Asia/Jakarta');

        // ===== STATS berdasarkan KEMA =====
        $today = (int) DB::table('borrow_requests')
            ->whereDate('created_at', $now->toDateString())
            ->count();

        $pending = (int) DB::table('borrow_requests')
            ->where('kema_status', 'menunggu')
            ->count();

        $approved = (int) DB::table('borrow_requests')
            ->where('kema_status', 'disetujui')
            ->count();

        $rejected = (int) DB::table('borrow_requests')
            ->where('kema_status', 'ditolak')
            ->count();

        $stats = [
            'today'    => $today,
            'pending'  => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
        ];

        // ===== RECENT (prioritas: menunggu + jadwal terdekat) =====
        $recent = DB::table('borrow_requests as br')
            ->leftJoin('rooms as r', 'r.id', '=', 'br.room_id')
            ->select([
                'br.id',
                'br.created_at',
                'br.start_time',
                'br.end_time',
                'br.responsible_name',
                'br.org_name',
                'br.status as admin_status',
                'br.kema_status',
                'br.public_code',
                'br.letter_file',
                'r.name as room_name',
            ])
            ->orderByRaw("CASE WHEN br.kema_status='menunggu' THEN 0 ELSE 1 END ASC")
            ->orderByRaw("CASE WHEN br.start_time IS NULL THEN 1 ELSE 0 END ASC")
            ->orderBy('br.start_time', 'asc')
            ->orderBy('br.created_at', 'desc')
            ->limit(12)
            ->get()
            ->map(function ($row) use ($now) {
                $created = $row->created_at ? Carbon::parse($row->created_at)->timezone('Asia/Jakarta') : null;
                $start   = $row->start_time ? Carbon::parse($row->start_time)->timezone('Asia/Jakarta') : null;
                $end     = $row->end_time ? Carbon::parse($row->end_time)->timezone('Asia/Jakarta') : null;

                $hoursToStart = $start ? $now->diffInHours($start, false) : null;

                return [
                    'id'           => $row->id,
                    'created'      => $created ? $created->translatedFormat('d M Y H:i') : '-',
                    'room'         => $row->room_name ?? '—',
                    'name'         => $row->responsible_name ?? '-',
                    'org'          => $row->org_name ?? '-',
                    'kema_status'  => $row->kema_status ?? 'menunggu',
                    'admin_status' => $row->admin_status ?? '-',
                    'schedule'     => ($start && $end)
                        ? ($start->translatedFormat('d M Y H:i') . ' - ' . $end->translatedFormat('H:i'))
                        : (($start) ? $start->translatedFormat('d M Y H:i') : '—'),
                    'hoursToStart' => $hoursToStart,
                    'public_code'  => $row->public_code,
                    'letter_file'  => $row->letter_file,
                ];
            })
            ->toArray();

        return view('kema.dashboard', compact('stats', 'recent'));
    }
}