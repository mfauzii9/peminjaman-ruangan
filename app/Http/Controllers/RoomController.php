<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        DB::statement("SET time_zone = '+07:00'");

        $filter = trim((string) $request->query('filter', 'all'));
        $q      = trim((string) $request->query('q', ''));

        $allowedFilter = array('all', 'segera_digunakan', 'sedang_digunakan', 'tidak_tersedia');
        if (!in_array($filter, $allowedFilter, true)) {
            $filter = 'all';
        }

        $soonMinutes = 30;

        $sql = "
        SELECT
          r.*,

          /* PBM aktif sekarang */
          EXISTS(
            SELECT 1
            FROM pbm_occurrences o
            WHERE o.room_id = r.id
              AND o.status = 'approved'
              AND NOW() BETWEEN o.start_time AND o.end_time
          ) AS pbm_now,

          /* Pengajuan mahasiswa ACC aktif sekarang */
          EXISTS(
            SELECT 1
            FROM borrow_requests b
            WHERE b.room_id = r.id
              AND b.status = 'disetujui'
              AND NOW() BETWEEN b.start_time AND b.end_time
          ) AS borrow_now,

          /* Booking cepat aktif sekarang */
          EXISTS(
            SELECT 1
            FROM room_blocks rb
            WHERE rb.room_id = r.id
              AND rb.status IN ('terbooking', 'approved')
              AND NOW() BETWEEN rb.start_time AND rb.end_time
          ) AS admin_now,

          /* Next PBM */
          (
            SELECT o.start_time
            FROM pbm_occurrences o
            WHERE o.room_id = r.id
              AND o.status = 'approved'
              AND o.start_time > NOW()
            ORDER BY o.start_time ASC
            LIMIT 1
          ) AS pbm_next_start_dt,

          (
            SELECT o.end_time
            FROM pbm_occurrences o
            WHERE o.room_id = r.id
              AND o.status = 'approved'
              AND o.start_time > NOW()
            ORDER BY o.start_time ASC
            LIMIT 1
          ) AS pbm_next_end_dt,

          (
            SELECT p.mata_kuliah
            FROM pbm_occurrences o
            JOIN pbm_templates p ON p.id = o.pbm_id
            WHERE o.room_id = r.id
              AND o.status = 'approved'
              AND o.start_time > NOW()
            ORDER BY o.start_time ASC
            LIMIT 1
          ) AS pbm_next_title,

          (
            SELECT TIMESTAMPDIFF(MINUTE, NOW(), o.start_time)
            FROM pbm_occurrences o
            WHERE o.room_id = r.id
              AND o.status = 'approved'
              AND o.start_time > NOW()
            ORDER BY o.start_time ASC
            LIMIT 1
          ) AS mins_to_next_pbm,

          /* Next Pengajuan mahasiswa ACC */
          (
            SELECT b.start_time
            FROM borrow_requests b
            WHERE b.room_id = r.id
              AND b.status = 'disetujui'
              AND b.start_time > NOW()
            ORDER BY b.start_time ASC
            LIMIT 1
          ) AS borrow_next_start_dt,

          (
            SELECT b.end_time
            FROM borrow_requests b
            WHERE b.room_id = r.id
              AND b.status = 'disetujui'
              AND b.start_time > NOW()
            ORDER BY b.start_time ASC
            LIMIT 1
          ) AS borrow_next_end_dt,

          (
            SELECT b.org_name
            FROM borrow_requests b
            WHERE b.room_id = r.id
              AND b.status = 'disetujui'
              AND b.start_time > NOW()
            ORDER BY b.start_time ASC
            LIMIT 1
          ) AS borrow_next_org,

          (
            SELECT TIMESTAMPDIFF(MINUTE, NOW(), b.start_time)
            FROM borrow_requests b
            WHERE b.room_id = r.id
              AND b.status = 'disetujui'
              AND b.start_time > NOW()
            ORDER BY b.start_time ASC
            LIMIT 1
          ) AS mins_to_next_borrow,

          /* Next booking cepat */
          (
            SELECT rb.start_time
            FROM room_blocks rb
            WHERE rb.room_id = r.id
              AND rb.status IN ('terbooking', 'approved')
              AND rb.start_time > NOW()
            ORDER BY rb.start_time ASC
            LIMIT 1
          ) AS admin_next_start_dt,

          (
            SELECT rb.end_time
            FROM room_blocks rb
            WHERE rb.room_id = r.id
              AND rb.status IN ('terbooking', 'approved')
              AND rb.start_time > NOW()
            ORDER BY rb.start_time ASC
            LIMIT 1
          ) AS admin_next_end_dt,

          (
            SELECT rb.title
            FROM room_blocks rb
            WHERE rb.room_id = r.id
              AND rb.status IN ('terbooking', 'approved')
              AND rb.start_time > NOW()
            ORDER BY rb.start_time ASC
            LIMIT 1
          ) AS admin_next_title,

          (
            SELECT TIMESTAMPDIFF(MINUTE, NOW(), rb.start_time)
            FROM room_blocks rb
            WHERE rb.room_id = r.id
              AND rb.status IN ('terbooking', 'approved')
              AND rb.start_time > NOW()
            ORDER BY rb.start_time ASC
            LIMIT 1
          ) AS mins_to_next_admin,

          /* sedang digunakan sekarang */
          CASE
            WHEN EXISTS(
              SELECT 1
              FROM pbm_occurrences o
              WHERE o.room_id = r.id
                AND o.status = 'approved'
                AND NOW() BETWEEN o.start_time AND o.end_time
            )
            OR EXISTS(
              SELECT 1
              FROM borrow_requests b
              WHERE b.room_id = r.id
                AND b.status = 'disetujui'
                AND NOW() BETWEEN b.start_time AND b.end_time
            )
            OR EXISTS(
              SELECT 1
              FROM room_blocks rb
              WHERE rb.room_id = r.id
                AND rb.status IN ('terbooking', 'approved')
                AND NOW() BETWEEN rb.start_time AND rb.end_time
            )
            THEN 1 ELSE 0
          END AS sedang_digunakan_flag,

          /* segera digunakan */
          CASE
            WHEN
              NOT EXISTS(
                SELECT 1
                FROM pbm_occurrences o
                WHERE o.room_id = r.id
                  AND o.status = 'approved'
                  AND NOW() BETWEEN o.start_time AND o.end_time
              )
              AND NOT EXISTS(
                SELECT 1
                FROM borrow_requests b
                WHERE b.room_id = r.id
                  AND b.status = 'disetujui'
                  AND NOW() BETWEEN b.start_time AND b.end_time
              )
              AND NOT EXISTS(
                SELECT 1
                FROM room_blocks rb
                WHERE rb.room_id = r.id
                  AND rb.status IN ('terbooking', 'approved')
                  AND NOW() BETWEEN rb.start_time AND rb.end_time
              )
              AND (
                EXISTS(
                  SELECT 1
                  FROM pbm_occurrences o
                  WHERE o.room_id = r.id
                    AND o.status = 'approved'
                    AND o.start_time > NOW()
                    AND o.start_time <= DATE_ADD(NOW(), INTERVAL {$soonMinutes} MINUTE)
                )
                OR EXISTS(
                  SELECT 1
                  FROM borrow_requests b
                  WHERE b.room_id = r.id
                    AND b.status = 'disetujui'
                    AND b.start_time > NOW()
                    AND b.start_time <= DATE_ADD(NOW(), INTERVAL {$soonMinutes} MINUTE)
                )
                OR EXISTS(
                  SELECT 1
                  FROM room_blocks rb
                  WHERE rb.room_id = r.id
                    AND rb.status IN ('terbooking', 'approved')
                    AND rb.start_time > NOW()
                    AND rb.start_time <= DATE_ADD(NOW(), INTERVAL {$soonMinutes} MINUTE)
                )
              )
            THEN 1 ELSE 0
          END AS segera_digunakan_flag

        FROM rooms r
        ";

        $conds = array();
        $binds = array();

        if ($filter === 'sedang_digunakan') {
            $conds[] = "(
              EXISTS(
                SELECT 1 FROM pbm_occurrences o
                WHERE o.room_id = r.id
                  AND o.status = 'approved'
                  AND NOW() BETWEEN o.start_time AND o.end_time
              )
              OR EXISTS(
                SELECT 1 FROM borrow_requests b
                WHERE b.room_id = r.id
                  AND b.status = 'disetujui'
                  AND NOW() BETWEEN b.start_time AND b.end_time
              )
              OR EXISTS(
                SELECT 1 FROM room_blocks rb
                WHERE rb.room_id = r.id
                  AND rb.status IN ('terbooking', 'approved')
                  AND NOW() BETWEEN rb.start_time AND rb.end_time
              )
            )";
        } elseif ($filter === 'segera_digunakan') {
            $conds[] = "(
              NOT EXISTS(
                SELECT 1 FROM pbm_occurrences o
                WHERE o.room_id = r.id
                  AND o.status = 'approved'
                  AND NOW() BETWEEN o.start_time AND o.end_time
              )
              AND NOT EXISTS(
                SELECT 1 FROM borrow_requests b
                WHERE b.room_id = r.id
                  AND b.status = 'disetujui'
                  AND NOW() BETWEEN b.start_time AND b.end_time
              )
              AND NOT EXISTS(
                SELECT 1 FROM room_blocks rb
                WHERE rb.room_id = r.id
                  AND rb.status IN ('terbooking', 'approved')
                  AND NOW() BETWEEN rb.start_time AND rb.end_time
              )
              AND (
                EXISTS(
                  SELECT 1 FROM pbm_occurrences o
                  WHERE o.room_id = r.id
                    AND o.status = 'approved'
                    AND o.start_time > NOW()
                    AND o.start_time <= DATE_ADD(NOW(), INTERVAL {$soonMinutes} MINUTE)
                )
                OR EXISTS(
                  SELECT 1 FROM borrow_requests b
                  WHERE b.room_id = r.id
                    AND b.status = 'disetujui'
                    AND b.start_time > NOW()
                    AND b.start_time <= DATE_ADD(NOW(), INTERVAL {$soonMinutes} MINUTE)
                )
                OR EXISTS(
                  SELECT 1 FROM room_blocks rb
                  WHERE rb.room_id = r.id
                    AND rb.status IN ('terbooking', 'approved')
                    AND rb.start_time > NOW()
                    AND rb.start_time <= DATE_ADD(NOW(), INTERVAL {$soonMinutes} MINUTE)
                )
              )
            )";
        } elseif ($filter === 'tidak_tersedia') {
            $conds[] = "(
              EXISTS(
                SELECT 1 FROM pbm_occurrences o
                WHERE o.room_id = r.id
                  AND o.status = 'approved'
                  AND NOW() BETWEEN o.start_time AND o.end_time
              )
              OR EXISTS(
                SELECT 1 FROM borrow_requests b
                WHERE b.room_id = r.id
                  AND b.status = 'disetujui'
                  AND NOW() BETWEEN b.start_time AND b.end_time
              )
              OR EXISTS(
                SELECT 1 FROM room_blocks rb
                WHERE rb.room_id = r.id
                  AND rb.status IN ('terbooking', 'approved')
                  AND NOW() BETWEEN rb.start_time AND rb.end_time
              )
              OR EXISTS(
                SELECT 1 FROM pbm_occurrences o
                WHERE o.room_id = r.id
                  AND o.status = 'approved'
                  AND o.start_time > NOW()
                  AND o.start_time <= DATE_ADD(NOW(), INTERVAL {$soonMinutes} MINUTE)
              )
              OR EXISTS(
                SELECT 1 FROM borrow_requests b
                WHERE b.room_id = r.id
                  AND b.status = 'disetujui'
                  AND b.start_time > NOW()
                  AND b.start_time <= DATE_ADD(NOW(), INTERVAL {$soonMinutes} MINUTE)
              )
              OR EXISTS(
                SELECT 1 FROM room_blocks rb
                WHERE rb.room_id = r.id
                  AND rb.status IN ('terbooking', 'approved')
                  AND rb.start_time > NOW()
                  AND rb.start_time <= DATE_ADD(NOW(), INTERVAL {$soonMinutes} MINUTE)
              )
            )";
        }

        if ($q !== '') {
            $conds[] = "(r.name LIKE ? OR r.floor LIKE ? OR r.facilities LIKE ?)";
            $like = '%' . $q . '%';
            $binds[] = $like;
            $binds[] = $like;
            $binds[] = $like;
        }

        if (!empty($conds)) {
            $sql .= " WHERE " . implode(" AND ", $conds);
        }

        $sql .= " ORDER BY r.floor, r.name";

        $all = collect(DB::select($sql, $binds))
            ->map(function ($r) {

                $r->photo_url = $this->roomPhotoUrl(isset($r->photo) ? $r->photo : null);

                $pbmNow    = ((int) $r->pbm_now === 1);
                $borrowNow = ((int) $r->borrow_now === 1);
                $adminNow  = ((int) $r->admin_now === 1);

                $sedangDigunakan = ((int) $r->sedang_digunakan_flag === 1);
                $segeraDigunakan = ((int) $r->segera_digunakan_flag === 1);
                $tidakTersedia   = ($sedangDigunakan || $segeraDigunakan);

                $minsToNextPbm    = ($r->mins_to_next_pbm === null) ? null : (int) $r->mins_to_next_pbm;
                $minsToNextBorrow = ($r->mins_to_next_borrow === null) ? null : (int) $r->mins_to_next_borrow;
                $minsToNextAdmin  = ($r->mins_to_next_admin === null) ? null : (int) $r->mins_to_next_admin;

                $candidates = array();
                if ($minsToNextPbm !== null && $minsToNextPbm >= 0) {
                    $candidates[] = array('mins' => $minsToNextPbm, 'type' => 'pbm');
                }
                if ($minsToNextBorrow !== null && $minsToNextBorrow >= 0) {
                    $candidates[] = array('mins' => $minsToNextBorrow, 'type' => 'borrow');
                }
                if ($minsToNextAdmin !== null && $minsToNextAdmin >= 0) {
                    $candidates[] = array('mins' => $minsToNextAdmin, 'type' => 'admin');
                }

                $nearestMins = null;
                $nearestType = null;

                if (!empty($candidates)) {
                    usort($candidates, function ($a, $b) {
                        if ($a['mins'] == $b['mins']) return 0;
                        return ($a['mins'] < $b['mins']) ? -1 : 1;
                    });
                    $nearestMins = $candidates[0]['mins'];
                    $nearestType = $candidates[0]['type'];
                }

                $chipClass = 'chip-tersedia';
                $chipText  = 'ruangan tersedia';
                $chipIcon  = 'fa-circle-check';

                if ($sedangDigunakan) {
                    $chipClass = 'chip-digunakan';
                    $chipText  = 'ruangan sedang digunakan';
                    $chipIcon  = 'fa-circle-xmark';
                } elseif ($segeraDigunakan) {
                    $chipClass = 'chip-segera';
                    $chipText  = 'ruangan segera digunakan';
                    $chipIcon  = 'fa-hourglass-half';
                } elseif ($tidakTersedia) {
                    $chipClass = 'chip-tidak-tersedia';
                    $chipText  = 'ruangan tidak tersedia';
                    $chipIcon  = 'fa-lock';
                }

                // DIUBAH: Tombol tidak pernah di-disable
                $disableAjukan = false;

                $warnMsg = '';
                if (!$sedangDigunakan && $segeraDigunakan && $nearestMins !== null) {
                    $warnType = ($nearestType === 'pbm')
                        ? 'PBM'
                        : (($nearestType === 'borrow') ? 'Pengajuan Mahasiswa' : 'Booking Cepat');

                    $jam   = (int) floor($nearestMins / 60);
                    $menit = (int) ($nearestMins % 60);
                    $sisa  = ($jam > 0) ? ($jam . ' jam ' . $menit . ' menit') : ($menit . ' menit');

                    $warnMsg = 'Ruangan akan digunakan untuk ' . $warnType . ' dalam ' . $sisa . '.';
                }

                $r->chipClass         = $chipClass;
                $r->chipText          = $chipText;
                $r->chipIcon          = $chipIcon;
                $r->sedangDigunakan   = $sedangDigunakan;
                $r->segeraDigunakan   = $segeraDigunakan;
                $r->tidakTersedia     = $tidakTersedia;
                $r->disableAjukan     = $disableAjukan;
                $r->warnMsg           = $warnMsg;
                $r->todaySchedules    = array();
                $r->nextSchedules     = array();

                return $r;
            });

        $perPage = 6;
        $page    = (int) $request->query('page', 1);
        if ($page < 1) $page = 1;

        $total = $all->count();
        $items = $all->slice(($page - 1) * $perPage, $perPage)->values();

        $rooms = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            array(
                'path'  => $request->url(),
                'query' => $request->query(),
            )
        );

        $roomIds = $items->pluck('id')->all();

        if (!empty($roomIds)) {
            $todayStart = Carbon::today();
            $todayEnd   = Carbon::tomorrow();
            $futureEnd  = Carbon::today()->addDays(7)->endOfDay();

            $rangeStart = $todayStart->toDateTimeString();
            $rangeEnd   = $futureEnd->toDateTimeString();

            $pbmRows = DB::table('pbm_occurrences as o')
                ->join('pbm_templates as p', 'p.id', '=', 'o.pbm_id')
                ->select('o.room_id', 'o.start_time', 'o.end_time', 'p.mata_kuliah')
                ->whereIn('o.room_id', $roomIds)
                ->where('o.status', 'approved')
                ->where('o.start_time', '<', $rangeEnd)
                ->where('o.end_time', '>', $rangeStart)
                ->orderBy('o.start_time', 'asc')
                ->get();

            $borRows = DB::table('borrow_requests as b')
                ->select('b.room_id', 'b.start_time', 'b.end_time', 'b.org_name')
                ->whereIn('b.room_id', $roomIds)
                ->where('b.status', 'disetujui')
                ->where('b.start_time', '<', $rangeEnd)
                ->where('b.end_time', '>', $rangeStart)
                ->orderBy('b.start_time', 'asc')
                ->get();

            $admRows = DB::table('room_blocks as rb')
                ->select('rb.room_id', 'rb.start_time', 'rb.end_time', 'rb.title')
                ->whereIn('rb.room_id', $roomIds)
                ->whereIn('rb.status', array('terbooking', 'approved'))
                ->where('rb.start_time', '<', $rangeEnd)
                ->where('rb.end_time', '>', $rangeStart)
                ->orderBy('rb.start_time', 'asc')
                ->get();

            $sched = array();

            $pushSched = function ($roomId, $start, $end, $label) use (&$sched, $todayStart, $todayEnd) {
                if (!isset($sched[$roomId])) {
                    $sched[$roomId] = array('today' => array(), 'next' => array());
                }

                $st = Carbon::parse($start);
                $en = Carbon::parse($end);

                $text = $st->format('d M') . ' • ' . $st->format('H:i') . '-' . $en->format('H:i') . ' • ' . $label;

                if ($st->greaterThanOrEqualTo($todayStart) && $st->lessThan($todayEnd)) {
                    $sched[$roomId]['today'][] = $text;
                } else {
                    $sched[$roomId]['next'][]  = $text;
                }
            };

            foreach ($pbmRows as $x) {
                $pushSched($x->room_id, $x->start_time, $x->end_time, 'PBM: ' . $x->mata_kuliah);
            }
            foreach ($borRows as $x) {
                $org = trim((string) $x->org_name);
                if ($org === '') $org = 'Mahasiswa';
                $pushSched($x->room_id, $x->start_time, $x->end_time, 'Pengajuan: ' . $org);
            }
            foreach ($admRows as $x) {
                $title = trim((string) $x->title);
                if ($title === '') $title = 'Admin';
                $pushSched($x->room_id, $x->start_time, $x->end_time, 'Booking Cepat: ' . $title);
            }

            $items = $items->map(function ($r) use ($sched) {
                $rid = (int) $r->id;
                if (isset($sched[$rid])) {
                    $r->todaySchedules = $sched[$rid]['today'];
                    $r->nextSchedules  = $sched[$rid]['next'];
                } else {
                    $r->todaySchedules = array();
                    $r->nextSchedules  = array();
                }
                return $r;
            });

            $rooms->setCollection($items);
        }

        return view('rooms.index', array(
            'rooms'  => $rooms,
            'filter' => $filter,
            'q'      => $q,
        ));
    }

    private function roomPhotoUrl($photo)
    {
        $p = trim((string) $photo);
        if ($p === '') return null;

        if (preg_match('#^https?://#i', $p)) return $p;

        $p = ltrim($p, '/');

        if (preg_match('#^(assets|uploads|storage)/#i', $p)) {
            return asset($p);
        }

        return asset('assets/foto/' . $p);
    }
}