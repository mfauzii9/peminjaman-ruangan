<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class QuickBookingController extends Controller
{
    private function setTz(): void
    {
        DB::statement("SET time_zone = '+07:00'");
    }

    private function dayRange(string $date): array
    {
        $start = $date . ' 00:00:00';
        $end   = $date . ' 23:59:59';
        return [$start, $end];
    }

    private function buildNote(?string $kelas, ?string $dosen): string
    {
        $kelas = trim((string) $kelas);
        $dosen = trim((string) $dosen);

        $noteParts = [];
        if ($kelas !== '') $noteParts[] = 'Kelas: ' . $kelas;
        if ($dosen !== '') $noteParts[] = 'Dosen: ' . $dosen;

        return implode(' | ', $noteParts);
    }

    /**
     * Ambil jadwal 1 hari dari:
     * - room_blocks
     * - pbm_occurrences (join pbm_templates untuk mata_kuliah/kelas/dosen)
     * - borrow_requests (status menunggu/disetujui)
     */
    private function getCombinedByDate(int $roomId, string $date)
    {
        [$start, $end] = $this->dayRange($date);

        // 1) ROOM BLOCKS
        $blocks = DB::table('room_blocks')
            ->selectRaw("
                id,
                room_id,
                title,
                note,
                start_time,
                end_time,
                source as source,
                status as status,
                'room_blocks' as source_table
            ")
            ->where('room_id', $roomId)
            ->where('status', '<>', 'cancel')
            // overlap dengan 1 hari
            ->where('start_time', '<=', $end)
            ->where('end_time', '>=', $start);

        // 2) PBM OCCURRENCES (approved/draft) + join template untuk judul & note
        $pbm = DB::table('pbm_occurrences as o')
            ->leftJoin('pbm_templates as t', 't.id', '=', 'o.pbm_id')
            ->selectRaw("
                o.id as id,
                o.room_id as room_id,
                COALESCE(t.mata_kuliah, 'PBM') as title,
                CONCAT(
                    CASE WHEN t.kelas IS NULL OR t.kelas = '' THEN '' ELSE CONCAT('Kelas: ', t.kelas) END,
                    CASE
                        WHEN (t.kelas IS NULL OR t.kelas = '') AND (t.dosen IS NULL OR t.dosen = '') THEN ''
                        WHEN (t.kelas IS NULL OR t.kelas = '') OR (t.dosen IS NULL OR t.dosen = '') THEN ''
                        ELSE ' | '
                    END,
                    CASE WHEN t.dosen IS NULL OR t.dosen = '' THEN '' ELSE CONCAT('Dosen: ', t.dosen) END
                ) as note,
                o.start_time as start_time,
                o.end_time as end_time,
                'jadwal' as source,
                o.status as status,
                'pbm_occurrences' as source_table
            ")
            ->where('o.room_id', $roomId)
            ->whereIn('o.status', ['draft', 'approved'])
            ->where('o.start_time', '<=', $end)
            ->where('o.end_time', '>=', $start);

        // 3) BORROW REQUESTS (tampilkan yang menunggu & disetujui untuk info di kalender)
        $borrow = DB::table('borrow_requests')
            ->selectRaw("
                id,
                room_id,
                CONCAT('Peminjaman: ', COALESCE(org_name, responsible_name)) as title,
                CONCAT('PJ: ', responsible_name, ' | Email: ', email, ' | Telp: ', phone) as note,
                start_time,
                end_time,
                'mahasiswa' as source,
                status as status,
                'borrow_requests' as source_table
            ")
            ->where('room_id', $roomId)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->where('start_time', '<=', $end)
            ->where('end_time', '>=', $start);

        // UNION ALL jadi 1 list, urut start_time
        $union = $blocks
            ->unionAll($pbm)
            ->unionAll($borrow);

        return DB::query()
            ->fromSub($union, 'u')
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Riwayat jadwal mulai hari ini (dan seterusnya) dari 3 sumber.
     * Bisa batasi dengan ?days=30 (default 30 hari).
     */
    private function getUpcomingHistory(int $roomId, int $days = 30)
    {
        $from = Carbon::now('Asia/Jakarta')->startOfDay();
        $to   = (clone $from)->addDays(max(1, $days))->endOfDay();

        $fromDT = $from->format('Y-m-d H:i:s');
        $toDT   = $to->format('Y-m-d H:i:s');

        $blocks = DB::table('room_blocks')
            ->selectRaw("
                id,
                room_id,
                title,
                note,
                start_time,
                end_time,
                source as source,
                status as status,
                'room_blocks' as source_table
            ")
            ->where('room_id', $roomId)
            ->where('status', '<>', 'cancel')
            ->where('start_time', '>=', $fromDT)
            ->where('start_time', '<=', $toDT);

        $pbm = DB::table('pbm_occurrences as o')
            ->leftJoin('pbm_templates as t', 't.id', '=', 'o.pbm_id')
            ->selectRaw("
                o.id as id,
                o.room_id as room_id,
                COALESCE(t.mata_kuliah, 'PBM') as title,
                CONCAT(
                    CASE WHEN t.kelas IS NULL OR t.kelas = '' THEN '' ELSE CONCAT('Kelas: ', t.kelas) END,
                    CASE
                        WHEN (t.kelas IS NULL OR t.kelas = '') AND (t.dosen IS NULL OR t.dosen = '') THEN ''
                        WHEN (t.kelas IS NULL OR t.kelas = '') OR (t.dosen IS NULL OR t.dosen = '') THEN ''
                        ELSE ' | '
                    END,
                    CASE WHEN t.dosen IS NULL OR t.dosen = '' THEN '' ELSE CONCAT('Dosen: ', t.dosen) END
                ) as note,
                o.start_time as start_time,
                o.end_time as end_time,
                'jadwal' as source,
                o.status as status,
                'pbm_occurrences' as source_table
            ")
            ->where('o.room_id', $roomId)
            ->whereIn('o.status', ['draft', 'approved'])
            ->where('o.start_time', '>=', $fromDT)
            ->where('o.start_time', '<=', $toDT);

        $borrow = DB::table('borrow_requests')
            ->selectRaw("
                id,
                room_id,
                CONCAT('Peminjaman: ', COALESCE(org_name, responsible_name)) as title,
                CONCAT('PJ: ', responsible_name, ' | Email: ', email, ' | Telp: ', phone) as note,
                start_time,
                end_time,
                'mahasiswa' as source,
                status as status,
                'borrow_requests' as source_table
            ")
            ->where('room_id', $roomId)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->where('start_time', '>=', $fromDT)
            ->where('start_time', '<=', $toDT);

        $union = $blocks
            ->unionAll($pbm)
            ->unionAll($borrow);

        return DB::query()
            ->fromSub($union, 'u')
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Cek bentrok ke 3 sumber data.
     * $excludeRoomBlockId dipakai saat update booking room_blocks.
     */
    private function hasConflict(int $roomId, string $startDT, string $endDT, ?int $excludeRoomBlockId = null): bool
    {
        // 1) room_blocks
        $q1 = DB::table('room_blocks')
            ->where('room_id', $roomId)
            ->where('status', '<>', 'cancel')
            ->where('start_time', '<', $endDT)
            ->where('end_time', '>', $startDT);

        if ($excludeRoomBlockId) {
            $q1->where('id', '<>', $excludeRoomBlockId);
        }

        if ($q1->exists()) return true;

        // 2) pbm_occurrences
        $q2 = DB::table('pbm_occurrences')
            ->where('room_id', $roomId)
            ->whereIn('status', ['draft', 'approved'])
            ->where('start_time', '<', $endDT)
            ->where('end_time', '>', $startDT);

        if ($q2->exists()) return true;

        // 3) borrow_requests (PERBAIKAN: Hanya cek yang sudah 'disetujui')
        $q3 = DB::table('borrow_requests')
            ->where('room_id', $roomId)
            ->whereIn('status', ['disetujui']) 
            ->where('start_time', '<', $endDT)
            ->where('end_time', '>', $startDT);

        return $q3->exists();
    }

    public function index()
    {
        $this->setTz();

        $rooms = DB::table('rooms')
            ->orderBy('floor')
            ->orderBy('name')
            ->get();

        return view('admin.quick-booking', compact('rooms'));
    }

    /**
     * LIST BOOKING PER HARI (gabungan 3 sumber)
     * GET /admin/quick-booking/list?room_id=2&date=2026-03-03
     */
    public function list(Request $request)
    {
        $this->setTz();

        $roomId = (int) $request->query('room_id');
        $date   = $request->query('date');

        if (!$roomId || !$date) {
            return response()->json([
                'ok' => false,
                'message' => 'Parameter tidak valid'
            ]);
        }

        $items = $this->getCombinedByDate($roomId, $date);

        return response()->json([
            'ok' => true,
            'items' => $items
        ]);
    }

    /**
     * RIWAYAT JADWAL (hari ini dan seterusnya) gabungan 3 sumber
     * GET /admin/quick-booking/history?room_id=2&days=30
     */
    public function history(Request $request)
    {
        $this->setTz();

        $roomId = (int) $request->query('room_id');
        $days   = (int) ($request->query('days', 30));

        if (!$roomId) {
            return response()->json([
                'ok' => false,
                'message' => 'Parameter tidak valid'
            ]);
        }

        $items = $this->getUpcomingHistory($roomId, $days);

        return response()->json([
            'ok' => true,
            'from' => Carbon::now('Asia/Jakarta')->startOfDay()->format('Y-m-d'),
            'days' => $days,
            'items' => $items
        ]);
    }

    /**
     * CREATE BOOKING (room_blocks) + cek bentrok ke 3 sumber
     */
    public function create(Request $request)
    {
        $this->setTz();

        try {
            $roomId = (int) $request->room_id;
            $date   = $request->date;
            $start  = $request->start;
            $end    = $request->end;
            $title  = trim((string) $request->title);
            $kelas  = trim((string) $request->kelas);
            $dosen  = trim((string) $request->dosen);
            $source = $request->source ?? 'admin';

            if (!$roomId || !$date || !$start || !$end || $title === '') {
                return response()->json([
                    'ok' => false,
                    'message' => 'Data tidak lengkap'
                ]);
            }

            $startDT = $date . ' ' . $start . ':00';
            $endDT   = $date . ' ' . $end . ':00';

            if (strtotime($endDT) <= strtotime($startDT)) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Jam selesai harus lebih besar dari jam mulai'
                ]);
            }

            $note = $this->buildNote($kelas, $dosen);

            // Cek bentrok (3 sumber)
            if ($this->hasConflict($roomId, $startDT, $endDT, null)) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Bentrok dengan jadwal lain (PBM / Peminjaman ACC / Booking Admin)'
                ]);
            }

            DB::table('room_blocks')->insert([
                'room_id'    => $roomId,
                'title'      => $title,
                'note'       => $note,
                'source'     => $source,
                'status'     => 'terbooking',
                'start_time' => $startDT,
                'end_time'   => $endDT,
            ]);

            return response()->json([
                'ok' => true,
                'message' => 'Booking berhasil dibuat'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * UPDATE BOOKING (room_blocks) + cek bentrok ke 3 sumber (exclude id sendiri)
     */
    public function update(Request $request, $id)
    {
        $this->setTz();

        try {
            $id     = (int) $id;
            $roomId = (int) $request->room_id;
            $date   = $request->date;
            $start  = $request->start;
            $end    = $request->end;
            $title  = trim((string) $request->title);
            $kelas  = trim((string) $request->kelas);
            $dosen  = trim((string) $request->dosen);
            $source = $request->source ?? 'admin';

            if (!$id || !$roomId || !$date || !$start || !$end || $title === '') {
                return response()->json([
                    'ok' => false,
                    'message' => 'Data tidak lengkap'
                ]);
            }

            $startDT = $date . ' ' . $start . ':00';
            $endDT   = $date . ' ' . $end . ':00';

            if (strtotime($endDT) <= strtotime($startDT)) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Jam tidak valid'
                ]);
            }

            $note = $this->buildNote($kelas, $dosen);

            // Cek bentrok (exclude room_blocks id sendiri)
            if ($this->hasConflict($roomId, $startDT, $endDT, $id)) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Bentrok jadwal (PBM / Peminjaman ACC / Booking Admin)'
                ]);
            }

            $updated = DB::table('room_blocks')
                ->where('id', $id)
                ->update([
                    'room_id'    => $roomId,
                    'title'      => $title,
                    'note'       => $note,
                    'source'     => $source,
                    'status'     => 'terbooking',
                    'start_time' => $startDT,
                    'end_time'   => $endDT,
                ]);

            if (!$updated) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }

            return response()->json([
                'ok' => true,
                'message' => 'Booking berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * DELETE BOOKING (room_blocks)
     */
    public function delete($id)
    {
        $this->setTz();

        $deleted = DB::table('room_blocks')
            ->where('id', (int) $id)
            ->delete();

        if (!$deleted) {
            return response()->json([
                'ok' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Jadwal berhasil dihapus'
        ]);
    }
}