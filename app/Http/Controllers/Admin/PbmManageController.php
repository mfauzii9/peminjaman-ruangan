<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PbmManageController extends Controller
{
    public function index()
    {
        DB::statement("SET time_zone = '+07:00'");
        return view('admin.pbm.index');
    }

    public function rooms()
    {
        try {
            $items = DB::table('rooms')
                ->select('id', 'name', 'floor')
                ->orderBy('floor')
                ->orderBy('name')
                ->get();

            return response()->json(array(
                'ok' => true,
                'items' => $items,
            ));
        } catch (\Throwable $e) {
            Log::error('PBM rooms error: ' . $e->getMessage());

            return response()->json(array(
                'ok' => false,
                'message' => 'Gagal memuat ruangan',
            ), 500);
        }
    }

    /**
     * =========================================================
     * TEMPLATE
     * =========================================================
     */
    public function templates(Request $request)
    {
        try {
            $query = DB::table('pbm_templates as p')
                ->join('rooms as r', 'r.id', '=', 'p.room_id')
                ->select(
                    'p.id',
                    'p.room_id',
                    'p.hari',
                    'p.start_time',
                    'p.end_time',
                    'p.mata_kuliah',
                    'p.kelas',
                    'p.dosen',
                    'p.semester',
                    'p.aktif',
                    'p.created_at',
                    'p.updated_at',
                    'r.name as room_name',
                    'r.floor as room_floor'
                );

            if ($request->filled('room_id')) {
                $query->where('p.room_id', (int) $request->input('room_id'));
            }

            if ($request->filled('aktif')) {
                $query->where('p.aktif', (int) $request->input('aktif'));
            }

            if ($request->filled('q')) {
                $search = '%' . trim((string) $request->input('q')) . '%';
                $query->where(function ($q) use ($search) {
                    $q->where('r.name', 'like', $search)
                        ->orWhere('p.mata_kuliah', 'like', $search)
                        ->orWhere('p.kelas', 'like', $search)
                        ->orWhere('p.dosen', 'like', $search)
                        ->orWhere('p.semester', 'like', $search);
                });
            }

            $items = $query
                ->orderByRaw("
                    FIELD(LOWER(p.hari),
                    'senin','selasa','rabu','kamis','jumat','sabtu','minggu')
                ")
                ->orderBy('p.room_id')
                ->orderBy('p.start_time')
                ->orderBy('p.id')
                ->get();

            return response()->json(array(
                'ok' => true,
                'items' => $items,
            ));
        } catch (\Throwable $e) {
            Log::error('PBM templates error: ' . $e->getMessage());

            return response()->json(array(
                'ok' => false,
                'message' => 'Gagal memuat template',
            ), 500);
        }
    }

    public function templateGet($id)
    {
        try {
            $item = DB::table('pbm_templates')->where('id', (int) $id)->first();

            if (!$item) {
                return response()->json(array(
                    'ok' => false,
                    'message' => 'Template tidak ditemukan',
                ), 404);
            }

            return response()->json(array(
                'ok' => true,
                'item' => $item,
            ));
        } catch (\Throwable $e) {
            Log::error('PBM template get error: ' . $e->getMessage());

            return response()->json(array(
                'ok' => false,
                'message' => 'Gagal memuat template',
            ), 500);
        }
    }

    public function templateSave(Request $request)
    {
        try {
            DB::beginTransaction();

            $id        = (int) $request->input('id', 0);
            $roomId    = (int) $request->input('room_id', 0);
            $hari      = strtolower(trim((string) $request->input('hari')));
            $startNorm = $this->normalizeTime($request->input('start_time'));
            $endNorm   = $this->normalizeTime($request->input('end_time'));
            $aktif     = (int) $request->input('aktif', 1);

            if (!$roomId) {
                DB::rollBack();
                return response()->json(array(
                    'ok' => false,
                    'message' => 'Ruangan wajib dipilih',
                ));
            }

            if (!in_array($hari, array('senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'), true)) {
                DB::rollBack();
                return response()->json(array(
                    'ok' => false,
                    'message' => 'Hari tidak valid',
                ));
            }

            if (!$startNorm || !$endNorm) {
                DB::rollBack();
                return response()->json(array(
                    'ok' => false,
                    'message' => 'Format jam harus HH:MM atau HH:MM:SS',
                ));
            }

            if (strtotime($endNorm) <= strtotime($startNorm)) {
                DB::rollBack();
                return response()->json(array(
                    'ok' => false,
                    'message' => 'Jam selesai harus setelah jam mulai',
                ));
            }

            // === CEK BENTROK JADWAL RUTIN (TEMPLATE) ===
            if ($this->checkTemplateConflict($roomId, $hari, $startNorm, $endNorm, $id)) {
                DB::rollBack();
                return response()->json(array(
                    'ok' => false,
                    'message' => 'Bentrok! Sudah ada jadwal PBM rutin lain di ruangan dan jam tersebut pada hari ' . ucfirst($hari)
                ));
            }

            if ($aktif !== 0 && $aktif !== 1) {
                $aktif = 1;
            }

            $data = array(
                'room_id'     => $roomId,
                'hari'        => $hari,
                'start_time'  => $startNorm,
                'end_time'    => $endNorm,
                'mata_kuliah' => trim((string) $request->input('mata_kuliah')),
                'kelas'       => trim((string) $request->input('kelas')),
                'dosen'       => trim((string) $request->input('dosen')),
                'semester'    => trim((string) $request->input('semester')),
                'aktif'       => $aktif,
            );

            // UPDATE TEMPLATE LAMA
            if ($id > 0) {
                $data['updated_at'] = now();

                DB::table('pbm_templates')
                    ->where('id', $id)
                    ->update($data);

                DB::commit();

                return response()->json(array(
                    'ok' => true,
                    'message' => 'Template berhasil diupdate',
                ));
            }

            // Cek jika slot persis sama ada (untuk fallback update)
            $existing = DB::table('pbm_templates')
                ->where('room_id', $roomId)
                ->whereRaw('LOWER(hari) = ?', array($hari))
                ->where('start_time', $startNorm)
                ->where('end_time', $endNorm)
                ->first();

            if ($existing) {
                DB::table('pbm_templates')
                    ->where('id', $existing->id)
                    ->update(array(
                        'mata_kuliah' => $data['mata_kuliah'],
                        'kelas'       => $data['kelas'],
                        'dosen'       => $data['dosen'],
                        'semester'    => $data['semester'],
                        'aktif'       => $data['aktif'],
                        'updated_at'  => now(),
                    ));

                DB::commit();

                return response()->json(array(
                    'ok' => true,
                    'message' => 'Template slot sama berhasil diupdate',
                    'id' => (int) $existing->id,
                ));
            }

            // INSERT TEMPLATE BARU
            $data['created_at'] = now();
            $data['updated_at'] = null;

            $newId = DB::table('pbm_templates')->insertGetId($data);

            DB::commit();

            return response()->json(array(
                'ok' => true,
                'message' => 'Template berhasil ditambahkan',
                'id' => (int) $newId,
            ));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PBM template save error: ' . $e->getMessage());

            return response()->json(array(
                'ok' => false,
                'message' => 'Gagal menyimpan template: ' . $e->getMessage(),
            ), 500);
        }
    }

    public function templateDelete($id)
    {
        try {
            DB::beginTransaction();

            $id = (int) $id;
            $template = DB::table('pbm_templates')->where('id', $id)->first();

            if (!$template) {
                DB::rollBack();
                return response()->json(array(
                    'ok' => false,
                    'message' => 'Template tidak ditemukan',
                ), 404);
            }

            DB::table('pbm_occurrences')
                ->where('pbm_id', $id)
                ->delete();

            DB::table('pbm_templates')
                ->where('id', $id)
                ->delete();

            DB::commit();

            return response()->json(array(
                'ok' => true,
                'message' => 'Template berhasil dihapus',
            ));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PBM template delete error: ' . $e->getMessage());

            return response()->json(array(
                'ok' => false,
                'message' => 'Gagal menghapus template',
            ), 500);
        }
    }

    public function templateDeleteAll(Request $request)
    {
        try {
            DB::beginTransaction();

            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('pbm_occurrences')->truncate();
            DB::table('pbm_templates')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            DB::commit();

            return response()->json(array(
                'ok' => true,
                'message' => 'Semua template dan occurrence PBM berhasil dihapus',
            ));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PBM template delete all error: ' . $e->getMessage());

            return response()->json(array(
                'ok' => false,
                'message' => 'Gagal hapus semua template: ' . $e->getMessage(),
            ), 500);
        }
    }

    /**
     * =========================================================
     * EVENTS / OCCURRENCES
     * =========================================================
     */
    public function events(Request $request)
    {
        try {
            $date = trim((string) $request->input('date'));
            $hari = strtolower(trim((string) $request->input('hari')));

            $allowedHari = array('senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu');

            if ($date !== '') {
                if (!$this->isValidDate($date)) {
                    return response()->json(array(
                        'ok' => false,
                        'message' => 'Tanggal tidak valid',
                    ));
                }

                $this->ensureOccurrencesForDate($date);
            } elseif ($hari !== '' && in_array($hari, $allowedHari, true)) {
                $this->ensureOccurrencesForDayName($hari);
            } else {
                foreach ($allowedHari as $hariLoop) {
                    $this->ensureOccurrencesForDayName($hariLoop);
                }
            }

            $query = DB::table('pbm_occurrences as o')
                ->leftJoin('pbm_templates as p', 'p.id', '=', 'o.pbm_id')
                ->join('rooms as r', 'r.id', '=', 'o.room_id')
                ->select(
                    'o.id',
                    'o.pbm_id',
                    'o.room_id',
                    'o.occ_date',
                    'o.start_time',
                    'o.end_time',
                    'o.status',
                    'o.approved_by',
                    'o.approved_at',
                    'o.created_at',
                    'r.name as room_name',
                    'r.floor as room_floor',
                    DB::raw("COALESCE(p.hari, '') as hari_template"),
                    DB::raw("COALESCE(p.mata_kuliah, '-') as mata_kuliah"),
                    DB::raw("COALESCE(p.kelas, '-') as kelas"),
                    DB::raw("COALESCE(p.dosen, '-') as dosen"),
                    DB::raw("COALESCE(p.semester, '-') as semester")
                );

            if ($date !== '') {
                $query->whereDate('o.occ_date', $date);
            } elseif ($hari !== '' && in_array($hari, $allowedHari, true)) {
                $query->whereRaw("LOWER(DAYNAME(o.occ_date)) = ?", array($this->englishDayNameFromIndo($hari)));
            }

            if ($request->filled('status') && $request->input('status') !== 'all') {
                $query->where('o.status', $request->input('status'));
            } else {
                $query->whereIn('o.status', array('approved'));
            }

            if ($request->filled('room_id')) {
                $query->where('o.room_id', (int) $request->input('room_id'));
            }

            if ($request->filled('q')) {
                $search = '%' . trim((string) $request->input('q')) . '%';
                $query->where(function ($q) use ($search) {
                    $q->where('p.mata_kuliah', 'like', $search)
                        ->orWhere('p.kelas', 'like', $search)
                        ->orWhere('p.dosen', 'like', $search)
                        ->orWhere('p.semester', 'like', $search)
                        ->orWhere('r.name', 'like', $search);
                });
            }

            $items = $query
                ->orderByRaw("
                    FIELD(
                        LOWER(
                            CASE DAYNAME(o.occ_date)
                                WHEN 'Monday' THEN 'senin'
                                WHEN 'Tuesday' THEN 'selasa'
                                WHEN 'Wednesday' THEN 'rabu'
                                WHEN 'Thursday' THEN 'kamis'
                                WHEN 'Friday' THEN 'jumat'
                                WHEN 'Saturday' THEN 'sabtu'
                                WHEN 'Sunday' THEN 'minggu'
                                ELSE ''
                            END
                        ),
                        'senin','selasa','rabu','kamis','jumat','sabtu','minggu'
                    )
                ")
                ->orderBy('o.occ_date')
                ->orderBy('o.start_time')
                ->orderBy('o.room_id')
                ->orderBy('o.id')
                ->get();

            $grouped = array(
                'senin'  => array(),
                'selasa' => array(),
                'rabu'   => array(),
                'kamis'  => array(),
                'jumat'  => array(),
                'sabtu'  => array(),
                'minggu' => array(),
            );

            foreach ($items as $item) {
                $hariItem = $this->dayNameFromDate($item->occ_date);
                if (!isset($grouped[$hariItem])) {
                    $grouped[$hariItem] = array();
                }
                $grouped[$hariItem][] = $item;
            }

            return response()->json(array(
                'ok'     => true,
                'items'  => $items,
                'groups' => $grouped,
            ));
        } catch (\Throwable $e) {
            Log::error('PBM events error: ' . $e->getMessage());

            return response()->json(array(
                'ok' => false,
                'message' => 'Gagal memuat jadwal PBM: ' . $e->getMessage(),
            ), 500);
        }
    }

    public function reschedule(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            DB::statement("SET time_zone = '+07:00'");

            $old = DB::table('pbm_occurrences')->where('id', (int) $id)->first();

            if (!$old) {
                DB::rollBack();
                return response()->json(array(
                    'ok' => false,
                    'message' => 'Jadwal asal tidak ditemukan',
                ), 404);
            }

            if (!in_array($old->status, array('approved', 'draft'), true)) {
                DB::rollBack();
                return response()->json(array(
                    'ok' => false,
                    'message' => 'Jadwal ini tidak bisa dipindahkan',
                ));
            }

            $newDate    = trim((string) $request->input('date'));
            $newRoomId  = (int) $request->input('room_id', 0);
            $newStartHm = trim((string) $request->input('start_hm'));
            $newEndHm   = trim((string) $request->input('end_hm'));

            if (!$newDate || !$this->isValidDate($newDate)) {
                DB::rollBack();
                return response()->json(array(
                    'ok' => false,
                    'message' => 'Tanggal baru tidak valid',
                ));
            }

            if (!$newRoomId) {
                DB::rollBack();
                return response()->json(array(
                    'ok' => false,
                    'message' => 'Ruangan baru wajib dipilih',
                ));
            }

            $newStart = $this->normalizeHm($newStartHm);
            $newEnd   = $this->normalizeHm($newEndHm);

            if (!$newStart || !$newEnd) {
                DB::rollBack();
                return response()->json(array(
                    'ok' => false,
                    'message' => 'Format jam harus HH:MM',
                ));
            }

            $newStartDateTime = $newDate . ' ' . $newStart . ':00';
            $newEndDateTime   = $newDate . ' ' . $newEnd . ':00';

            if (strtotime($newEndDateTime) <= strtotime($newStartDateTime)) {
                DB::rollBack();
                return response()->json(array(
                    'ok' => false,
                    'message' => 'Jam selesai harus setelah jam mulai',
                ));
            }

            // === CEK BENTROK ===
            $conflict = $this->getConflictSummary(
                $newRoomId,
                $newDate,
                $newStartDateTime,
                $newEndDateTime,
                (int) $id,
                (int) $old->pbm_id
            );

            if ($conflict['has_conflict']) {
                DB::rollBack();
                return response()->json(array(
                    'ok' => false,
                    'message' => 'Gagal pindah jadwal! ' . $conflict['message'],
                    'conflicts' => $conflict['sources'],
                ));
            }

            DB::table('pbm_occurrences')
                ->where('id', (int) $id)
                ->update(array(
                    'status' => 'cancelled',
                ));

            DB::table('pbm_occurrences')->insert(array(
                'pbm_id'      => $old->pbm_id,
                'room_id'     => $newRoomId,
                'occ_date'    => $newDate,
                'start_time'  => $newStartDateTime,
                'end_time'    => $newEndDateTime,
                'status'      => 'approved',
                'approved_by' => null,
                'approved_at' => now(),
                'created_at'  => now(),
            ));

            DB::commit();

            return response()->json(array(
                'ok' => true,
                'message' => 'Jadwal berhasil dipindahkan.',
            ));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PBM reschedule error: ' . $e->getMessage());

            return response()->json(array(
                'ok' => false,
                'message' => 'Gagal memindahkan jadwal: ' . $e->getMessage(),
            ), 500);
        }
    }

    public function eventDelete($id)
    {
        try {
            $deleted = DB::table('pbm_occurrences')
                ->where('id', (int) $id)
                ->delete();

            return response()->json(array(
                'ok' => (bool) $deleted,
                'message' => $deleted ? 'Occurrence berhasil dihapus' : 'Occurrence tidak ditemukan',
            ));
        } catch (\Throwable $e) {
            Log::error('PBM event delete error: ' . $e->getMessage());

            return response()->json(array(
                'ok' => false,
                'message' => 'Gagal menghapus jadwal',
            ), 500);
        }
    }

    public function deleteAllEvents(Request $request)
    {
        try {
            $date = trim((string) $request->input('date'));
            $hari = strtolower(trim((string) $request->input('hari')));
            $allowedHari = array('senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu');

            $query = DB::table('pbm_occurrences');

            if ($date !== '') {
                if (!$this->isValidDate($date)) {
                    return response()->json(array(
                        'ok' => false,
                        'message' => 'Tanggal tidak valid',
                    ));
                }
                $query->whereDate('occ_date', $date);
            } elseif ($hari !== '' && in_array($hari, $allowedHari, true)) {
                $query->whereRaw("LOWER(DAYNAME(occ_date)) = ?", array($this->englishDayNameFromIndo($hari)));
            } else {
                return response()->json(array(
                    'ok' => false,
                    'message' => 'Isi tanggal atau hari terlebih dahulu',
                ));
            }

            $deleted = $query->delete();

            return response()->json(array(
                'ok' => true,
                'deleted' => (int) $deleted,
                'message' => 'Berhasil hapus occurrence PBM',
            ));
        } catch (\Throwable $e) {
            Log::error('PBM delete all events error: ' . $e->getMessage());

            return response()->json(array(
                'ok' => false,
                'message' => 'Gagal hapus semua jadwal',
            ), 500);
        }
    }

    /**
     * =========================================================
     * CSV UPLOAD
     * =========================================================
     */
    public function templatesSampleCsv()
    {
        try {
            $path = base_path('public/assets/pbm_templates_sample.csv');

            if (file_exists($path)) {
                return response()->download(
                    $path,
                    'pbm_templates_sample.csv',
                    array('Content-Type' => 'text/csv; charset=UTF-8')
                );
            }

            $sample = "room_id,hari,start_time,end_time,mata_kuliah,kelas,dosen,semester,aktif\n";
            $sample .= "1,senin,07:30,09:40,Pengantar Basis Data,1IF-A,Bapak A,1,1\n";
            $sample .= "1,senin,07:30,09:40,Pengantar Basis Data,1IF-B,Ibu B,1,1\n";
            $sample .= "2,selasa,09:50,12:00,Algoritma Pemrograman,1SI-A,Bapak C,1,1\n";

            return response($sample, 200, array(
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="pbm_templates_sample.csv"',
            ));
        } catch (\Throwable $e) {
            Log::error('PBM sample CSV error: ' . $e->getMessage());

            return response()->json(array(
                'ok' => false,
                'message' => 'Gagal download sample CSV',
            ), 500);
        }
    }

    public function templatesImportCsv(Request $request)
    {
        try {
            $request->validate(array(
                'file' => array('required', 'file', 'mimes:csv,txt', 'max:8192'),
            ));

            $deleteAllFirst = ((int) $request->input('delete_all_first', 0) === 1);

            $fh = fopen($request->file('file')->getRealPath(), 'r');
            if (!$fh) {
                return response()->json(array(
                    'ok' => false,
                    'message' => 'Tidak bisa membaca file CSV',
                ), 200);
            }

            $firstLine = fgets($fh);
            if ($firstLine === false) {
                fclose($fh);
                return response()->json(array(
                    'ok' => false,
                    'message' => 'CSV kosong',
                ), 200);
            }

            $firstLine = preg_replace('/^\xEF\xBB\xBF/', '', $firstLine);
            $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';
            rewind($fh);

            $header = fgetcsv($fh, 0, $delimiter);
            if (!$header) {
                fclose($fh);
                return response()->json(array(
                    'ok' => false,
                    'message' => 'Header CSV tidak valid',
                ), 200);
            }

            $header = array_map(function ($h) {
                $h = strtolower(trim((string) $h));
                $h = preg_replace('/^\xEF\xBB\xBF/', '', $h);
                $h = str_replace(' ', '_', $h);
                return $h;
            }, $header);

            $idx = function ($key) use ($header) {
                $pos = array_search($key, $header, true);
                return ($pos === false) ? false : (int) $pos;
            };

            $required = array('room_id', 'hari', 'start_time', 'end_time', 'mata_kuliah', 'kelas', 'dosen', 'semester', 'aktif');
            foreach ($required as $r) {
                if ($idx($r) === false) {
                    fclose($fh);
                    return response()->json(array(
                        'ok' => false,
                        'message' => 'Kolom wajib tidak ada: ' . $r,
                    ), 200);
                }
            }

            $iRoom     = $idx('room_id');
            $iHari     = $idx('hari');
            $iStart    = $idx('start_time');
            $iEnd      = $idx('end_time');
            $iMK       = $idx('mata_kuliah');
            $iKelas    = $idx('kelas');
            $iDosen    = $idx('dosen');
            $iSemester = $idx('semester');
            $iAktif    = $idx('aktif');

            $allowedHari = array('senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu');

            $rooms = DB::table('rooms')->select('id', 'name')->get();

            $roomIdSet = array();
            $roomNameToId = array();

            foreach ($rooms as $r) {
                $roomIdSet[(int) $r->id] = true;
                $roomNameToId[$this->normalizeRoomName($r->name)] = (int) $r->id;
            }

            $inserted = 0;
            $updated  = 0;
            $invalid  = 0;
            $errors   = array();

            DB::beginTransaction();

            if ($deleteAllFirst) {
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                DB::table('pbm_occurrences')->truncate();
                DB::table('pbm_templates')->truncate();
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            }

            $rowNum = 1;
            while (($row = fgetcsv($fh, 0, $delimiter)) !== false) {
                $rowNum++;

                $hasValue = false;
                foreach ($row as $v) {
                    if (trim((string) $v) !== '') {
                        $hasValue = true;
                        break;
                    }
                }

                if (!$hasValue) {
                    continue;
                }

                $roomKey  = trim((string) (isset($row[$iRoom]) ? $row[$iRoom] : ''));
                $hari     = strtolower(trim((string) (isset($row[$iHari]) ? $row[$iHari] : '')));
                $start    = trim((string) (isset($row[$iStart]) ? $row[$iStart] : ''));
                $end      = trim((string) (isset($row[$iEnd]) ? $row[$iEnd] : ''));
                $mk       = trim((string) (isset($row[$iMK]) ? $row[$iMK] : ''));
                $kelas    = trim((string) (isset($row[$iKelas]) ? $row[$iKelas] : ''));
                $dosen    = trim((string) (isset($row[$iDosen]) ? $row[$iDosen] : ''));
                $semester = trim((string) (isset($row[$iSemester]) ? $row[$iSemester] : ''));
                $aktifRaw = trim((string) (isset($row[$iAktif]) ? $row[$iAktif] : '1'));
                $aktif    = ($aktifRaw === '0') ? 0 : 1;

                if ($roomKey === '') {
                    $errors[] = 'Baris ' . $rowNum . ': room_id kosong';
                    $invalid++;
                    continue;
                }

                if (!in_array($hari, $allowedHari, true)) {
                    $errors[] = 'Baris ' . $rowNum . ': hari tidak valid (' . $hari . ')';
                    $invalid++;
                    continue;
                }

                $startNorm = $this->normalizeTime($start);
                $endNorm   = $this->normalizeTime($end);

                if (!$startNorm || !$endNorm) {
                    $errors[] = 'Baris ' . $rowNum . ': format jam salah (' . $start . ' - ' . $end . ')';
                    $invalid++;
                    continue;
                }

                if (strtotime($endNorm) <= strtotime($startNorm)) {
                    $errors[] = 'Baris ' . $rowNum . ': jam selesai harus setelah jam mulai';
                    $invalid++;
                    continue;
                }

                $roomId = 0;
                if (ctype_digit($roomKey)) {
                    $roomId = (int) $roomKey;
                    if (!isset($roomIdSet[$roomId])) {
                        $errors[] = 'Baris ' . $rowNum . ': room_id tidak ditemukan (id=' . $roomId . ')';
                        $invalid++;
                        continue;
                    }
                } else {
                    $roomNorm = $this->normalizeRoomName($roomKey);
                    $roomId = isset($roomNameToId[$roomNorm]) ? $roomNameToId[$roomNorm] : 0;
                    if (!$roomId) {
                        $errors[] = 'Baris ' . $rowNum . ': ruangan tidak ditemukan (name=' . $roomKey . ')';
                        $invalid++;
                        continue;
                    }
                }

                $existing = DB::table('pbm_templates')
                    ->where('room_id', $roomId)
                    ->whereRaw('LOWER(hari) = ?', array($hari))
                    ->where('start_time', $startNorm)
                    ->where('end_time', $endNorm)
                    ->first();

                if ($existing) {
                    DB::table('pbm_templates')
                        ->where('id', $existing->id)
                        ->update(array(
                            'mata_kuliah' => $mk,
                            'kelas'       => $kelas,
                            'dosen'       => $dosen,
                            'semester'    => $semester,
                            'aktif'       => $aktif,
                            'updated_at'  => now(),
                        ));

                    $updated++;
                    continue;
                }

                DB::table('pbm_templates')->insert(array(
                    'room_id'     => $roomId,
                    'hari'        => $hari,
                    'start_time'  => $startNorm,
                    'end_time'    => $endNorm,
                    'mata_kuliah' => $mk,
                    'kelas'       => $kelas,
                    'dosen'       => $dosen,
                    'semester'    => $semester,
                    'aktif'       => $aktif,
                    'created_at'  => now(),
                    'updated_at'  => null,
                ));

                $inserted++;
            }

            fclose($fh);
            DB::commit();

            return response()->json(array(
                'ok'       => true,
                'inserted' => $inserted,
                'updated'  => $updated,
                'skipped'  => 0,
                'invalid'  => $invalid,
                'errors'   => $errors,
                'message'  => 'Upload selesai. Template diperbarui, occurrence akan dibentuk otomatis saat hari / tanggal dibuka.',
            ), 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PBM upload CSV error: ' . $e->getMessage());

            return response()->json(array(
                'ok' => false,
                'message' => 'Gagal upload CSV: ' . $e->getMessage(),
            ), 500);
        }
    }

    /**
     * =========================================================
     * OCCURRENCE HELPERS
     * =========================================================
     */
    private function ensureOccurrencesForDate($date)
    {
        $dayName = $this->dayNameFromDate($date);

        $templates = DB::table('pbm_templates')
            ->whereRaw('LOWER(hari) = ?', array($dayName))
            ->where('aktif', 1)
            ->orderBy('start_time')
            ->orderBy('room_id')
            ->orderBy('id')
            ->get();

        foreach ($templates as $template) {
            $exists = DB::table('pbm_occurrences')
                ->where('pbm_id', (int) $template->id)
                ->whereDate('occ_date', $date)
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('pbm_occurrences')->insert(array(
                'pbm_id'      => (int) $template->id,
                'room_id'     => (int) $template->room_id,
                'occ_date'    => $date,
                'start_time'  => $date . ' ' . $this->timeToHm($template->start_time) . ':00',
                'end_time'    => $date . ' ' . $this->timeToHm($template->end_time) . ':00',
                'status'      => 'approved',
                'approved_by' => null,
                'approved_at' => now(),
                'created_at'  => now(),
            ));
        }
    }

    /**
     * Buat occurrence untuk semua template aktif pada nama hari tertentu
     * berdasarkan minggu berjalan.
     */
    private function ensureOccurrencesForDayName($hari)
    {
        $targetDate = $this->dateFromIndoDayNameInCurrentWeek($hari);
        $this->ensureOccurrencesForDate($targetDate);
    }

    private function getConflictSummary(
        $roomId,
        $date,
        $startDateTime,
        $endDateTime,
        $excludeOccurrenceId = null,
        $excludeTemplateId = null
    ) {
        $sources = array();

        $occQuery = DB::table('pbm_occurrences')
            ->where('room_id', $roomId)
            ->whereDate('occ_date', $date)
            ->whereIn('status', array('approved', 'draft'))
            ->where(function ($q) use ($startDateTime, $endDateTime) {
                $q->whereRaw('? < end_time', array($startDateTime))
                  ->whereRaw('? > start_time', array($endDateTime));
            });

        if ($excludeOccurrenceId) {
            $occQuery->where('id', '!=', (int) $excludeOccurrenceId);
        }

        if ($excludeTemplateId) {
            $occQuery->where(function ($q) use ($excludeTemplateId) {
                $q->whereNull('pbm_id')
                  ->orWhere('pbm_id', '!=', (int) $excludeTemplateId);
            });
        }

        if ($occQuery->exists()) {
            $sources[] = 'jadwal PBM';
        }

        $blockConflict = DB::table('room_blocks')
            ->where('room_id', $roomId)
            ->where('status', 'terbooking')
            ->where(function ($q) use ($startDateTime, $endDateTime) {
                $q->whereRaw('? < end_time', array($startDateTime))
                  ->whereRaw('? > start_time', array($endDateTime));
            })
            ->exists();

        if ($blockConflict) {
            $sources[] = 'quick booking / room block';
        }

        $borrowConflict = DB::table('borrow_requests')
            ->where('room_id', $roomId)
            // HANYA CEK YANG SUDAH DISETUJUI, JANGAN CEK YANG MASIH MENUNGGU
            ->whereIn('status', array('disetujui')) 
            ->where(function ($q) use ($startDateTime, $endDateTime) {
                $q->whereRaw('? < end_time', array($startDateTime))
                  ->whereRaw('? > start_time', array($endDateTime));
            })
            ->exists();

        if ($borrowConflict) {
            $sources[] = 'booking mahasiswa';
        }

        $has = !empty($sources);

        return array(
            'has_conflict' => $has,
            'sources'      => $sources,
            'message'      => $has ? 'Jadwal bentrok dengan: ' . implode(', ', $sources) . '.' : '',
        );
    }

    /**
     * Cek bentrok antar Template PBM rutin (Hari yang sama, Ruangan yang sama)
     */
    private function checkTemplateConflict($roomId, $hari, $startTime, $endTime, $excludeId = 0)
    {
        $query = DB::table('pbm_templates')
            ->where('room_id', $roomId)
            ->whereRaw('LOWER(hari) = ?', array($hari))
            ->where('aktif', 1)
            ->whereRaw('? < end_time', array($startTime)) // Logika irisan waktu
            ->whereRaw('? > start_time', array($endTime));

        if ($excludeId > 0) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * =========================================================
     * GENERAL HELPERS
     * =========================================================
     */
    private function dayNameFromDate($date)
    {
        $map = array(
            'Monday'    => 'senin',
            'Tuesday'   => 'selasa',
            'Wednesday' => 'rabu',
            'Thursday'  => 'kamis',
            'Friday'    => 'jumat',
            'Saturday'  => 'sabtu',
            'Sunday'    => 'minggu',
        );

        $day = date('l', strtotime($date));
        return isset($map[$day]) ? $map[$day] : 'senin';
    }

    private function englishDayNameFromIndo($hari)
    {
        $map = array(
            'senin'  => 'monday',
            'selasa' => 'tuesday',
            'rabu'   => 'wednesday',
            'kamis'  => 'thursday',
            'jumat'  => 'friday',
            'sabtu'  => 'saturday',
            'minggu' => 'sunday',
        );

        return isset($map[$hari]) ? $map[$hari] : '';
    }

    private function dateFromIndoDayNameInCurrentWeek($hari)
    {
        $map = array(
            'senin'  => 'monday',
            'selasa' => 'tuesday',
            'rabu'   => 'wednesday',
            'kamis'  => 'thursday',
            'jumat'  => 'friday',
            'sabtu'  => 'saturday',
            'minggu' => 'sunday',
        );

        $english = isset($map[$hari]) ? $map[$hari] : 'monday';
        return date('Y-m-d', strtotime($english . ' this week'));
    }

    private function isValidDate($date)
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return false;
        }

        $parts = explode('-', $date);
        $y = isset($parts[0]) ? (int) $parts[0] : 0;
        $m = isset($parts[1]) ? (int) $parts[1] : 0;
        $d = isset($parts[2]) ? (int) $parts[2] : 0;

        return checkdate($m, $d, $y);
    }

    private function normalizeTime($t)
    {
        $t = trim((string) $t);

        if ($t === '') {
            return null;
        }

        if (preg_match('/^\d{2}:\d{2}$/', $t)) {
            return $t . ':00';
        }

        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $t)) {
            return $t;
        }

        return null;
    }

    private function normalizeHm($t)
    {
        $t = trim((string) $t);

        if (preg_match('/^\d{2}:\d{2}$/', $t)) {
            return $t;
        }

        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $t)) {
            return substr($t, 0, 5);
        }

        return null;
    }

    private function timeToHm($t)
    {
        return substr((string) $t, 0, 5);
    }

    private function normalizeRoomName($name)
    {
        $s = strtolower(trim((string) $name));
        $s = str_replace('.', '', $s);
        $s = str_replace('-', '', $s);
        $s = preg_replace('/\s+/', '', $s);
        return $s;
    }
}