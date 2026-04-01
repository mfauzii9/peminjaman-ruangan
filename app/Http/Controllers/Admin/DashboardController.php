<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private $soonMinutes = 30;

    public function index(Request $request)
    {
        DB::statement("SET time_zone = '+07:00'");
        $this->autohangusRequests();

        $roomsTotal = (int) DB::table('rooms')->count();
        $occupied   = $this->countRoomsOccupied();
        $soonFree   = $this->countRoomsSoonFree($this->soonMinutes);
        $emptyRooms = max(0, $roomsTotal - $occupied);

        $pending  = (int) DB::table('borrow_requests')->where('status', 'menunggu')->count();
        $approved = (int) DB::table('borrow_requests')->where('status', 'disetujui')->count();
        $rejected = (int) DB::table('borrow_requests')->where('status', 'ditolak')->count();
        $finished = (int) DB::table('borrow_requests')->where('status', 'selesai')->count();
        $expired  = (int) DB::table('borrow_requests')->where('status', 'hangus')->count();

        $blocksTotal = (int) DB::table('room_blocks')->count();
        $pbmOccTotal = (int) DB::table('pbm_occurrences')->where('status', 'approved')->count();

        $historyTotal =
            (int) DB::table('borrow_requests')->count()
            + (int) DB::table('room_blocks')->count()
            + (int) DB::table('pbm_occurrences')->where('status', 'approved')->count();

        $totalReq = max(1, (int) DB::table('borrow_requests')->count());
        $donut = [
            'pending'  => (int) round(($pending / $totalReq) * 100),
            'approved' => (int) round(($approved / $totalReq) * 100),
            'others'   => (int) max(
                0,
                100
                - (int) round(($pending / $totalReq) * 100)
                - (int) round(($approved / $totalReq) * 100)
            ),
        ];

        // Chart 12 bulan terakhir
        $months = $this->last12Months();
        $countsByMonth = DB::table('borrow_requests')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as c")
            ->where('created_at', '>=', date('Y-m-01 00:00:00', strtotime('-11 months')))
            ->groupBy('ym')
            ->pluck('c', 'ym');

        $labels = [];
        $series = [];
        foreach ($months as $ym) {
            $labels[] = $ym;
            $series[] = (int) ($countsByMonth[$ym] ?? 0);
        }

        $recentActivities = $this->recentActivities(3);

        $earningsThisMonth = 0;
        $soonMinutes = (int) $this->soonMinutes;

        return view('admin.dashboard', compact(
            'roomsTotal',
            'occupied',
            'soonFree',
            'emptyRooms',
            'pending',
            'approved',
            'rejected',
            'finished',
            'expired',
            'blocksTotal',
            'pbmOccTotal',
            'historyTotal',
            'labels',
            'series',
            'donut',
            'recentActivities',
            'earningsThisMonth',
            'soonMinutes'
        ));
    }

    private function autohangusRequests()
    {
        DB::table('borrow_requests')
            ->where('status', 'menunggu')
            ->whereRaw('end_time < NOW()')
            ->update(['status' => 'hangus']);
    }

    private function countRoomsOccupied()
    {
        $row = DB::selectOne("
            SELECT COUNT(DISTINCT t.room_id) AS c
            FROM (
                SELECT b.room_id
                FROM borrow_requests b
                WHERE b.status = 'disetujui'
                  AND NOW() BETWEEN b.start_time AND b.end_time

                UNION ALL

                SELECT rb.room_id
                FROM room_blocks rb
                WHERE NOW() BETWEEN rb.start_time AND rb.end_time

                UNION ALL

                SELECT o.room_id
                FROM pbm_occurrences o
                WHERE o.status = 'approved'
                  AND NOW() BETWEEN o.start_time AND o.end_time
            ) t
        ");

        return (int) ($row ? $row->c : 0);
    }

    private function countRoomsSoonFree($soonMinutes)
    {
        $m = (int) $soonMinutes;

        $row = DB::selectOne("
            SELECT COUNT(DISTINCT t.room_id) AS c
            FROM (
                SELECT b.room_id, b.end_time
                FROM borrow_requests b
                WHERE b.status = 'disetujui'
                  AND NOW() BETWEEN b.start_time AND b.end_time

                UNION ALL

                SELECT rb.room_id, rb.end_time
                FROM room_blocks rb
                WHERE NOW() BETWEEN rb.start_time AND rb.end_time

                UNION ALL

                SELECT o.room_id, o.end_time
                FROM pbm_occurrences o
                WHERE o.status = 'approved'
                  AND NOW() BETWEEN o.start_time AND o.end_time
            ) t
            WHERE t.end_time <= DATE_ADD(NOW(), INTERVAL {$m} MINUTE)
        ");

        return (int) ($row ? $row->c : 0);
    }

    private function last12Months()
    {
        $out = [];
        for ($i = 11; $i >= 0; $i--) {
            $out[] = date('Y-m', strtotime("-{$i} months"));
        }
        return $out;
    }

    private function recentActivities($limit)
    {
        $limit = (int) $limit;

        $sql = "
            SELECT * FROM (
                SELECT
                    'request' AS kind,
                    b.id AS id,
                    b.responsible_name AS title,
                    b.email AS meta,
                    b.created_at AS created_at
                FROM borrow_requests b

                UNION ALL

                SELECT
                    'block' AS kind,
                    rb.id AS id,
                    rb.title AS title,
                    CONCAT('Room #', rb.room_id) AS meta,
                    IFNULL(rb.created_at, rb.start_time) AS created_at
                FROM room_blocks rb

                UNION ALL

                SELECT
                    'pbm' AS kind,
                    o.id AS id,
                    CONCAT(IFNULL(p.mata_kuliah, 'PBM'), ' (', IFNULL(p.kelas, '-'), ')') AS title,
                    CONCAT('Approved by: ', IFNULL(o.approved_by, '-')) AS meta,
                    IFNULL(o.approved_at, o.created_at) AS created_at
                FROM pbm_occurrences o
                JOIN pbm_templates p ON p.id = o.pbm_id
                WHERE o.status = 'approved'
            ) t
            ORDER BY t.created_at DESC
            LIMIT {$limit}
        ";

        return DB::select($sql);
    }
}