<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    private $soonMinutes = 30;

    public function index(Request $request)
    {
        DB::statement("SET time_zone = '+07:00'");
        $this->autohangusRequests();

        $stat_rooms_total    = $this->countRoomsTotal();
        $stat_occupied       = $this->countRoomsOccupied();
        $stat_soon_free      = $this->countRoomsSoonFree($this->soonMinutes);
        $stat_empty          = max(0, $stat_rooms_total - $stat_occupied);

        $stat_history_total  = $this->countHistoryTotal();
        $stat_history_today  = $this->countHistoryToday();
        $stat_history_month  = $this->countHistoryMonth();
        
        $stat_pending        = $this->countRequestsStatus('menunggu');
        $stat_blocks_total   = $this->countBlocksTotal();
        $stat_pbm_occ_total  = $this->countPbmOccurrencesTotal();

        $defaultView = (string) $request->query('view', 'history');
        if (!in_array($defaultView, array('history', 'rooms'), true)) {
            $defaultView = 'history';
        }

        $soonMinutes = (int) $this->soonMinutes;

        return view('admin.pengajuan', compact(
            'stat_rooms_total',
            'stat_occupied',
            'stat_soon_free',
            'stat_empty',
            'stat_history_total',
            'stat_history_today',
            'stat_history_month',
            'stat_pending',
            'stat_blocks_total',
            'stat_pbm_occ_total',
            'defaultView',
            'soonMinutes'
        ));
    }

    public function stats()
    {
        DB::statement("SET time_zone = '+07:00'");
        $this->autohangusRequests();

        $roomsTotal = $this->countRoomsTotal();
        $occupied   = $this->countRoomsOccupied();
        $soonFree   = $this->countRoomsSoonFree($this->soonMinutes);
        $emptyRooms = max(0, $roomsTotal - $occupied);

        return response()->json(array(
            'ok' => true,

            'occupied_rooms'  => $occupied,
            'soon_free_rooms' => $soonFree,
            'empty_rooms'     => $emptyRooms,
            'rooms_total'     => $roomsTotal,

            'history_total'   => $this->countHistoryTotal(),
            'history_today'   => $this->countHistoryToday(),
            'history_month'   => $this->countHistoryMonth(),
            
            'blocks_total'    => $this->countBlocksTotal(),
            'pbm_occ_total'   => $this->countPbmOccurrencesTotal(),

            'pending'         => $this->countRequestsStatus('menunggu'),
            'approved'        => $this->countRequestsStatus('disetujui'),
            'rejected'        => $this->countRequestsStatus('ditolak'),
            'finished'        => $this->countRequestsStatus('selesai'),
            'expired'         => $this->countRequestsStatus('hangus'),

            'server_time'     => now()->format('Y-m-d H:i:s'),
        ));
    }

    public function history(Request $request)
    {
        DB::statement("SET time_zone = '+07:00'");

        $status   = (string) $request->query('status', 'all');
        $source   = (string) $request->query('source', 'all');
        $dateFrom = (string) $request->query('date_from', '');
        $dateTo   = (string) $request->query('date_to', '');
        $q        = trim((string) $request->query('q', ''));

        $page    = max(1, (int) $request->query('page', 1));
        $perPage = (int) $request->query('per_page', 6);
        if ($perPage <= 0) $perPage = 6;
        if ($perPage > 50) $perPage = 50;

        $allowedStatus = array('all', 'menunggu', 'disetujui', 'ditolak', 'selesai', 'hangus', 'block', 'pbm');
        if (!in_array($status, $allowedStatus, true)) $status = 'all';

        $allowedSource = array('all', 'request', 'block', 'pbm');
        if (!in_array($source, $allowedSource, true)) $source = 'all';

        if ($dateFrom && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom)) $dateFrom = '';
        if ($dateTo && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo)) $dateTo = '';

        if ($dateFrom && !$dateTo) $dateTo = $dateFrom;
        if ($dateTo && !$dateFrom) $dateFrom = $dateTo;

        $items = $this->listHistory($status, $source, $dateFrom, $dateTo);

        if ($q !== '') {
            $items = $this->filterItemsByQuery($items, $q, array(
                'id',
                'room_name',
                'room_floor',
                'title',
                'org_name',
                'email',
                'phone',
                'status',
                'note',
                'created_at',
                'start_time',
                'end_time',
                'kind'
            ));
        }

        $paged = $this->paginateArray($items, $page, $perPage);

        return response()->json(array(
            'ok' => true,
            'items' => $paged['items'],
            'meta' => $paged['meta'],

            'status' => $status,
            'source' => $source,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'q' => $q,
        ));
    }

    public function rooms(Request $request)
    {
        DB::statement("SET time_zone = '+07:00'");

        $type = (string) $request->query('type', 'all');
        $type = in_array($type, array('all', 'occupied', 'soon', 'empty'), true) ? $type : 'all';

        $q       = trim((string) $request->query('q', ''));
        $page    = max(1, (int) $request->query('page', 1));
        $perPage = (int) $request->query('per_page', 6);
        if ($perPage <= 0) $perPage = 6;
        if ($perPage > 50) $perPage = 50;

        $items = $this->listRooms($type, $this->soonMinutes);

        if ($q !== '') {
            $items = $this->filterItemsByQuery($items, $q, array(
                'id',
                'name',
                'floor',
                'capacity',
                'src',
                'title',
                'note',
                'room_status',
                'end_time'
            ));
        }

        $paged = $this->paginateArray($items, $page, $perPage);

        return response()->json(array(
            'ok' => true,
            'type' => $type,
            'items' => $paged['items'],
            'meta' => $paged['meta'],
            'q' => $q,
        ));
    }

    public function roomsFree(Request $request)
    {
        DB::statement("SET time_zone = '+07:00'");

        $date  = trim((string) $request->query('date', ''));
        $start = trim((string) $request->query('start', ''));
        $end   = trim((string) $request->query('end', ''));

        $q       = trim((string) $request->query('q', ''));
        $page    = max(1, (int) $request->query('page', 1));
        $perPage = (int) $request->query('per_page', 6);
        if ($perPage <= 0) $perPage = 6;
        if ($perPage > 50) $perPage = 50;

        if (
            !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) ||
            !preg_match('/^\d{2}:\d{2}$/', $start) ||
            !preg_match('/^\d{2}:\d{2}$/', $end)
        ) {
            return response()->json(array('ok' => false, 'message' => 'Format date/time tidak valid'));
        }

        if (strtotime("$date $end:00") <= strtotime("$date $start:00")) {
            return response()->json(array('ok' => false, 'message' => 'Jam selesai harus lebih besar dari jam mulai'));
        }

        $items = $this->listFreeRooms($date, $start, $end);

        if ($q !== '') {
            $items = $this->filterItemsByQuery($items, $q, array('id', 'name', 'floor', 'capacity'));
        }

        $paged = $this->paginateArray($items, $page, $perPage);

        return response()->json(array(
            'ok' => true,
            'items' => $paged['items'],
            'meta' => $paged['meta'],
            'range' => array('date' => $date, 'start' => $start, 'end' => $end),
            'q' => $q,
        ));
    }

    public function checkRoomAvailability(Request $request)
    {
        DB::statement("SET time_zone = '+07:00'");

        $roomId = (int) $request->query('room_id', 0);
        $date   = trim((string) $request->query('date', ''));
        $start  = trim((string) $request->query('start', ''));
        $end    = trim((string) $request->query('end', ''));

        if ($roomId <= 0) {
            return response()->json(array(
                'ok' => false,
                'message' => 'room_id tidak valid'
            ));
        }

        if (
            !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) ||
            !preg_match('/^\d{2}:\d{2}$/', $start) ||
            !preg_match('/^\d{2}:\d{2}$/', $end)
        ) {
            return response()->json(array(
                'ok' => false,
                'message' => 'Format date/time tidak valid'
            ));
        }

        if (strtotime("$date $end:00") <= strtotime("$date $start:00")) {
            return response()->json(array(
                'ok' => false,
                'message' => 'Jam selesai harus lebih besar dari jam mulai'
            ));
        }

        $conflict = $this->findRoomConflict($roomId, $date, $start, $end);

        if ($conflict) {
            return response()->json(array(
                'ok' => true,
                'available' => false,
                'message' => 'Ruangan tidak tersedia pada jam tersebut',
                'conflict' => $conflict,
            ));
        }

        return response()->json(array(
            'ok' => true,
            'available' => true,
            'message' => 'Ruangan tersedia pada jam tersebut',
            'conflict' => null,
        ));
    }

    private function paginateArray(array $items, int $page, int $perPage): array
    {
        $total = count($items);
        $totalPages = (int) ceil($total / max(1, $perPage));
        if ($totalPages <= 0) $totalPages = 1;
        if ($page > $totalPages) $page = $totalPages;

        $offset = ($page - 1) * $perPage;
        $slice = array_slice($items, $offset, $perPage);

        return array(
            'items' => array_values($slice),
            'meta' => array(
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => $totalPages,
            ),
        );
    }

    private function filterItemsByQuery(array $items, string $q, array $fields): array
    {
        $needle = mb_strtolower($q);

        return array_values(array_filter($items, function ($row) use ($needle, $fields) {
            if (!is_array($row)) $row = (array) $row;

            $hay = '';
            foreach ($fields as $f) {
                $v = $row[$f] ?? '';
                if (is_scalar($v) || $v === null) {
                    $hay .= ' ' . (string) $v;
                } else {
                    $hay .= ' ' . json_encode($v);
                }
            }

            $hay = mb_strtolower($hay);
            return mb_strpos($hay, $needle) !== false;
        }));
    }

    private function autohangusRequests()
    {
        DB::table('borrow_requests')
            ->where('status', 'menunggu')
            ->whereRaw('end_time < NOW()')
            ->update(array('status' => 'hangus'));
    }

    private function countRoomsTotal()
    {
        return (int) DB::table('rooms')->count();
    }

    private function countBlocksTotal()
    {
        return (int) DB::table('room_blocks')
            ->where('status', 'terbooking')
            ->count();
    }

    private function countPbmOccurrencesTotal()
    {
        // Hitung juga jadwal yang sudah pernah di-reschedule
        return (int) DB::table('pbm_occurrences')
            ->whereIn('status', array('approved', 'cancelled', 'rejected'))
            ->count();
    }

    private function countRequestsStatus($status = null)
    {
        $q = DB::table('borrow_requests');
        if ($status) $q->where('status', $status);
        return (int) $q->count();
    }

    private function countHistoryTotal()
    {
        // Total riwayat pengajuan, block, dan semua histori PBM
        return
            (int) DB::table('borrow_requests')->count()
            + (int) DB::table('room_blocks')->where('status', 'terbooking')->count()
            + (int) DB::table('pbm_occurrences')->whereIn('status', array('approved', 'cancelled', 'rejected'))->count();
    }

    private function countHistoryToday()
    {
        // Total riwayat yang kegiatan (start_time) nya berlangsung pada hari ini
        return
            (int) DB::table('borrow_requests')
                ->whereRaw('DATE(start_time) = CURDATE()')
                ->count()
            + (int) DB::table('room_blocks')
                ->where('status', 'terbooking')
                ->whereRaw('DATE(start_time) = CURDATE()')
                ->count()
            + (int) DB::table('pbm_occurrences')
                ->whereIn('status', array('approved', 'cancelled', 'rejected'))
                ->whereRaw('DATE(start_time) = CURDATE()')
                ->count();
    }

    private function countHistoryMonth()
    {
        // Total riwayat yang kegiatan (start_time) nya berlangsung pada bulan dan tahun ini
        return
            (int) DB::table('borrow_requests')
                ->whereRaw('MONTH(start_time) = MONTH(CURDATE()) AND YEAR(start_time) = YEAR(CURDATE())')
                ->count()
            + (int) DB::table('room_blocks')
                ->where('status', 'terbooking')
                ->whereRaw('MONTH(start_time) = MONTH(CURDATE()) AND YEAR(start_time) = YEAR(CURDATE())')
                ->count()
            + (int) DB::table('pbm_occurrences')
                ->whereIn('status', array('approved', 'cancelled', 'rejected'))
                ->whereRaw('MONTH(start_time) = MONTH(CURDATE()) AND YEAR(start_time) = YEAR(CURDATE())')
                ->count();
    }

    private function countRoomsOccupied()
    {
        $row = DB::selectOne("
            SELECT COUNT(DISTINCT t.room_id) AS c
            FROM (
                SELECT b.room_id
                FROM borrow_requests b
                WHERE b.status='disetujui'
                  AND NOW() BETWEEN b.start_time AND b.end_time

                UNION ALL

                SELECT rb.room_id
                FROM room_blocks rb
                WHERE rb.status='terbooking'
                  AND NOW() BETWEEN rb.start_time AND rb.end_time

                UNION ALL

                SELECT o.room_id
                FROM pbm_occurrences o
                WHERE o.status='approved'
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
                WHERE b.status='disetujui'
                  AND NOW() BETWEEN b.start_time AND b.end_time

                UNION ALL

                SELECT rb.room_id, rb.end_time
                FROM room_blocks rb
                WHERE rb.status='terbooking'
                  AND NOW() BETWEEN rb.start_time AND rb.end_time

                UNION ALL

                SELECT o.room_id, o.end_time
                FROM pbm_occurrences o
                WHERE o.status='approved'
                  AND NOW() BETWEEN o.start_time AND o.end_time
            ) t
            WHERE t.end_time <= DATE_ADD(NOW(), INTERVAL {$m} MINUTE)
        ");

        return (int) ($row ? $row->c : 0);
    }

    private function buildWhere($conds)
    {
        return $conds ? ('WHERE ' . implode(' AND ', $conds)) : '';
    }

    private function listHistory($status, $source, $dateFrom, $dateTo)
    {
        $condsReq = array();
        $bindReq = array();
        $condsBlk = array();
        $bindBlk = array();
        $condsPbm = array();
        $bindPbm = array();

        if ($source === 'request') {
            $condsBlk[] = "1=0";
            $condsPbm[] = "1=0";
        } elseif ($source === 'block') {
            $condsReq[] = "1=0";
            $condsPbm[] = "1=0";
        } elseif ($source === 'pbm') {
            $condsReq[] = "1=0";
            $condsBlk[] = "1=0";
        }

        if ($status !== 'all') {
            if ($status === 'block') {
                $condsReq[] = "1=0";
                $condsPbm[] = "1=0";
            } elseif ($status === 'pbm') {
                $condsReq[] = "1=0";
                $condsBlk[] = "1=0";
            } else {
                $condsReq[] = "b.status = ?";
                $bindReq[] = $status;
                
                // Jika difilter Ditolak/Hangus, tampilkan juga histori PBM yg direschedule (cancelled)
                if ($status === 'ditolak') {
                    $condsPbm[] = "o.status IN ('cancelled', 'rejected')";
                    $condsBlk[] = "1=0";
                } else {
                    $condsBlk[] = "1=0";
                    $condsPbm[] = "1=0";
                }
            }
        }

        if ($dateFrom !== '' && $dateTo !== '') {
            $condsReq[] = "DATE(b.start_time) BETWEEN ? AND ?";
            $bindReq[] = $dateFrom;
            $bindReq[] = $dateTo;

            $condsBlk[] = "DATE(rb.start_time) BETWEEN ? AND ?";
            $bindBlk[] = $dateFrom;
            $bindBlk[] = $dateTo;

            $condsPbm[] = "DATE(o.start_time) BETWEEN ? AND ?";
            $bindPbm[] = $dateFrom;
            $bindPbm[] = $dateTo;
        }

        $whereReq = $this->buildWhere($condsReq);
        $whereBlk = $this->buildWhere($condsBlk);
        $wherePbm = $this->buildWhere($condsPbm);

        $bindings = array_merge($bindReq, $bindBlk, $bindPbm);

        $pbmExtra = '';
        if ($wherePbm !== '') {
            $pbmExtra = ' AND ' . preg_replace('/^WHERE\s+/i', '', $wherePbm);
        }

        $blkExtra = '';
        if ($whereBlk !== '') {
            $blkExtra = ' AND ' . preg_replace('/^WHERE\s+/i', '', $whereBlk);
        }

        $sql = "
            SELECT
                t.kind, t.id, t.room_id,
                r.name AS room_name, r.floor AS room_floor,
                t.title, t.org_name, t.email, t.phone,
                t.start_time, t.end_time, t.status, t.created_at, t.note
            FROM (
                SELECT
                    'request' AS kind, b.id, b.room_id,
                    b.responsible_name AS title,
                    IFNULL(b.org_name,'') AS org_name,
                    IFNULL(b.email,'') AS email,
                    IFNULL(b.phone,'') AS phone,
                    b.start_time, b.end_time, b.status, b.created_at,
                    '' AS note
                FROM borrow_requests b
                {$whereReq}

                UNION ALL

                SELECT
                    'block' AS kind, rb.id, rb.room_id,
                    rb.title AS title,
                    'BOOKING CEPAT' AS org_name,
                    '' AS email, '' AS phone,
                    rb.start_time, rb.end_time,
                    'block' AS status,
                    IFNULL(rb.created_at, rb.start_time) AS created_at,
                    IFNULL(rb.note,'') AS note
                FROM room_blocks rb
                WHERE rb.status='terbooking'
                {$blkExtra}

                UNION ALL

                SELECT
                    'pbm' AS kind, o.id, o.room_id,
                    CONCAT(
                        IFNULL(p.mata_kuliah,'PBM'), ' (', IFNULL(p.kelas,'-'), ')',
                        CASE WHEN o.status IN ('cancelled', 'rejected') THEN ' [RESCHEDULE/BATAL]' ELSE '' END
                    ) AS title,
                    CONCAT('PBM • ', IFNULL(o.approved_by,'-')) AS org_name,
                    '' AS email, '' AS phone,
                    o.start_time, o.end_time,
                    CASE WHEN o.status IN ('cancelled', 'rejected') THEN 'ditolak' ELSE 'pbm' END AS status,
                    IFNULL(o.approved_at, o.created_at) AS created_at,
                    CONCAT('Dosen: ', IFNULL(p.dosen,'-'), ' | Semester: ', IFNULL(p.semester,'-')) AS note
                FROM pbm_occurrences o
                JOIN pbm_templates p ON p.id = o.pbm_id
                WHERE o.status IN ('approved', 'cancelled', 'rejected')
                {$pbmExtra}
            ) t
            JOIN rooms r ON r.id = t.room_id
            ORDER BY t.created_at DESC
        ";

        $rows = DB::select($sql, $bindings);
        $out = array();

        foreach ($rows as $x) {
            $out[] = (array) $x;
        }

        return $out;
    }

    private function listRooms($type, $soonMinutes)
    {
        $m = (int) $soonMinutes;

        $activeEventSql = "
            SELECT t.*
            FROM (
                SELECT
                    'request' AS src,
                    b.id AS src_id,
                    b.room_id,
                    b.start_time,
                    b.end_time,
                    b.responsible_name AS title,
                    CONCAT('Email: ', IFNULL(b.email,''), ' | Phone: ', IFNULL(b.phone,'')) AS note
                FROM borrow_requests b
                WHERE b.status='disetujui'
                  AND NOW() BETWEEN b.start_time AND b.end_time

                UNION ALL

                SELECT
                    'block' AS src,
                    rb.id AS src_id,
                    rb.room_id,
                    rb.start_time,
                    rb.end_time,
                    rb.title,
                    rb.note
                FROM room_blocks rb
                WHERE rb.status='terbooking'
                  AND NOW() BETWEEN rb.start_time AND rb.end_time

                UNION ALL

                SELECT
                    'pbm' AS src,
                    o.id AS src_id,
                    o.room_id,
                    o.start_time,
                    o.end_time,
                    CONCAT(IFNULL(p.mata_kuliah,'PBM'), ' (', IFNULL(p.kelas,'-'), ')') AS title,
                    CONCAT('Dosen: ', IFNULL(p.dosen,'-'), ' | Semester: ', IFNULL(p.semester,'-'), ' | Approved by: ', IFNULL(o.approved_by,'-')) AS note
                FROM pbm_occurrences o
                JOIN pbm_templates p ON p.id=o.pbm_id
                WHERE o.status='approved'
                  AND NOW() BETWEEN o.start_time AND o.end_time
            ) t
        ";

        $sql = "
            SELECT
                r.id,
                r.name,
                r.floor,
                r.capacity,
                e.src,
                e.src_id,
                e.start_time,
                e.end_time,
                e.title,
                e.note,
                CASE
                    WHEN e.room_id IS NULL THEN 'kosong'
                    ELSE 'terisi'
                END AS room_status,
                CASE
                    WHEN e.room_id IS NOT NULL AND e.end_time <= DATE_ADD(NOW(), INTERVAL {$m} MINUTE) THEN 1
                    ELSE 0
                END AS soon_flag,
                0 AS disabled
            FROM rooms r
            LEFT JOIN (
                SELECT x.*
                FROM ({$activeEventSql}) x
                JOIN (
                    SELECT room_id, MIN(end_time) AS min_end
                    FROM ({$activeEventSql}) z
                    GROUP BY room_id
                ) pick ON pick.room_id = x.room_id AND pick.min_end = x.end_time
            ) e ON e.room_id = r.id
        ";

        if ($type === 'occupied') {
            $sql .= " WHERE e.room_id IS NOT NULL";
        } elseif ($type === 'empty') {
            $sql .= " WHERE e.room_id IS NULL";
        } elseif ($type === 'soon') {
            $sql .= " WHERE e.room_id IS NOT NULL AND e.end_time <= DATE_ADD(NOW(), INTERVAL {$m} MINUTE)";
        }

        $sql .= " ORDER BY r.floor, r.name";

        $rows = DB::select($sql);
        $out = array();

        foreach ($rows as $x) {
            $item = (array) $x;
            $item['can_submit'] = true; // selalu bisa diajukan
            $out[] = $item;
        }

        return $out;
    }

    private function listFreeRooms($date, $start, $end)
    {
        $startDT = "$date $start:00";
        $endDT   = "$date $end:00";

        $sql = "
            SELECT r.id, r.name, r.floor, r.capacity
            FROM rooms r
            WHERE NOT EXISTS (
                SELECT 1 FROM borrow_requests b
                WHERE b.room_id = r.id
                  AND b.status = 'disetujui'
                  AND b.start_time < ?
                  AND b.end_time > ?
            )
            AND NOT EXISTS (
                SELECT 1 FROM room_blocks rb
                WHERE rb.room_id = r.id
                  AND rb.status = 'terbooking'
                  AND rb.start_time < ?
                  AND rb.end_time > ?
            )
            AND NOT EXISTS (
                SELECT 1 FROM pbm_occurrences o
                WHERE o.room_id = r.id
                  AND o.status = 'approved'
                  AND o.start_time < ?
                  AND o.end_time > ?
                  AND o.occ_date = DATE(?)
            )
            ORDER BY r.floor, r.name
        ";

        $rows = DB::select($sql, array(
            $endDT, $startDT,
            $endDT, $startDT,
            $endDT, $startDT,
            $date
        ));

        $out = array();
        foreach ($rows as $x) {
            $out[] = (array) $x;
        }

        return $out;
    }

    private function findRoomConflict($roomId, $date, $start, $end)
    {
        $startDT = "$date $start:00";
        $endDT   = "$date $end:00";

        $sql = "
            SELECT *
            FROM (
                SELECT
                    'request' AS kind,
                    b.id,
                    b.room_id,
                    b.responsible_name AS title,
                    b.start_time,
                    b.end_time,
                    b.status,
                    CONCAT('Email: ', IFNULL(b.email,''), ' | Phone: ', IFNULL(b.phone,'')) AS note
                FROM borrow_requests b
                WHERE b.room_id = ?
                  AND b.status = 'disetujui'
                  AND b.start_time < ?
                  AND b.end_time > ?

                UNION ALL

                SELECT
                    'block' AS kind,
                    rb.id,
                    rb.room_id,
                    rb.title AS title,
                    rb.start_time,
                    rb.end_time,
                    'block' AS status,
                    IFNULL(rb.note,'') AS note
                FROM room_blocks rb
                WHERE rb.room_id = ?
                  AND rb.status = 'terbooking'
                  AND rb.start_time < ?
                  AND rb.end_time > ?

                UNION ALL

                SELECT
                    'pbm' AS kind,
                    o.id,
                    o.room_id,
                    CONCAT(IFNULL(p.mata_kuliah,'PBM'), ' (', IFNULL(p.kelas,'-'), ')') AS title,
                    o.start_time,
                    o.end_time,
                    'pbm' AS status,
                    CONCAT('Dosen: ', IFNULL(p.dosen,'-'), ' | Semester: ', IFNULL(p.semester,'-')) AS note
                FROM pbm_occurrences o
                JOIN pbm_templates p ON p.id = o.pbm_id
                WHERE o.room_id = ?
                  AND o.status = 'approved'
                  AND o.start_time < ?
                  AND o.end_time > ?
                  AND o.occ_date = DATE(?)
            ) x
            ORDER BY x.start_time ASC
            LIMIT 1
        ";

        $rows = DB::select($sql, array(
            $roomId, $endDT, $startDT,
            $roomId, $endDT, $startDT,
            $roomId, $endDT, $startDT, $date
        ));

        if (!$rows || !isset($rows[0])) {
            return null;
        }

        return (array) $rows[0];
    }
}