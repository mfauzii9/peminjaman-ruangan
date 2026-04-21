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
        
        // EKSEKUSI PERBAIKAN DATABASE OTOMATIS DARI KODE
        try {
            // 1. Ubah kolom status ENUM untuk menghapus yang tidak terpakai dan menambahkan 'rescheduled'
            DB::statement("ALTER TABLE pbm_occurrences MODIFY COLUMN status ENUM('draft', 'approved', 'cancelled', 'rescheduled') NOT NULL DEFAULT 'draft'");
            
            // 2. Hapus Index Unik (uniq_pbm_date) agar satu mata kuliah bisa punya jadwal ganda di hari yang sama (jadwal rutin + jadwal tamu/pindahan)
            DB::statement("ALTER TABLE pbm_occurrences DROP INDEX uniq_pbm_date");
        } catch (\Throwable $e) {
            // Jika struktur sudah berhasil diubah sebelumnya, abaikan error ini
        }

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
                    'p.id', 'p.room_id', 'p.hari', 'p.start_time', 'p.end_time',
                    'p.mata_kuliah', 'p.kelas', 'p.dosen', 'p.semester', 'p.aktif',
                    'p.created_at', 'p.updated_at', 'r.name as room_name', 'r.floor as room_floor'
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
                ->orderByRaw("FIELD(LOWER(p.hari), 'senin','selasa','rabu','kamis','jumat','sabtu','minggu')")
                ->orderBy('p.room_id')->orderBy('p.start_time')->orderBy('p.id')->get();

            return response()->json(array('ok' => true, 'items' => $items));
        } catch (\Throwable $e) {
            Log::error('PBM templates error: ' . $e->getMessage());
            return response()->json(array('ok' => false, 'message' => 'Gagal memuat template'), 500);
        }
    }

    public function templateGet($id)
    {
        try {
            $item = DB::table('pbm_templates')->where('id', (int) $id)->first();
            if (!$item) {
                return response()->json(array('ok' => false, 'message' => 'Template tidak ditemukan'), 404);
            }
            return response()->json(array('ok' => true, 'item' => $item));
        } catch (\Throwable $e) {
            return response()->json(array('ok' => false, 'message' => 'Gagal memuat template'), 500);
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

            if (!$roomId || !$startNorm || !$endNorm) {
                DB::rollBack();
                return response()->json(array('ok' => false, 'message' => 'Data jadwal tidak lengkap'));
            }

            if (!in_array($hari, array('senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'), true)) {
                DB::rollBack();
                return response()->json(array('ok' => false, 'message' => 'Hari tidak valid'));
            }

            if (strtotime($endNorm) <= strtotime($startNorm)) {
                DB::rollBack();
                return response()->json(array('ok' => false, 'message' => 'Jam selesai harus setelah jam mulai'));
            }

            // CEK BENTROK TEMPLATE
            if ($this->checkTemplateConflict($roomId, $hari, $startNorm, $endNorm, $id)) {
                DB::rollBack();
                return response()->json(array('ok' => false, 'message' => 'Bentrok! Sudah ada jadwal PBM rutin lain di jam ini'));
            }

            $aktif = ($aktif !== 0) ? 1 : 0;

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

            if ($id > 0) {
                $data['updated_at'] = now();
                DB::table('pbm_templates')->where('id', $id)->update($data);
                DB::commit();
                return response()->json(array('ok' => true, 'message' => 'Template berhasil diupdate'));
            }

            $existing = DB::table('pbm_templates')
                ->where('room_id', $roomId)->whereRaw('LOWER(hari) = ?', array($hari))
                ->where('start_time', $startNorm)->where('end_time', $endNorm)->first();

            if ($existing) {
                $data['updated_at'] = now();
                DB::table('pbm_templates')->where('id', $existing->id)->update($data);
                DB::commit();
                return response()->json(array('ok' => true, 'message' => 'Template slot sama berhasil diupdate', 'id' => (int) $existing->id));
            }

            $data['created_at'] = now();
            $newId = DB::table('pbm_templates')->insertGetId($data);

            DB::commit();
            return response()->json(array('ok' => true, 'message' => 'Template berhasil ditambahkan', 'id' => (int) $newId));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PBM template save error: ' . $e->getMessage());
            return response()->json(array('ok' => false, 'message' => 'Gagal menyimpan template: ' . $e->getMessage()), 500);
        }
    }

    public function templateDelete($id)
    {
        try {
            DB::beginTransaction();
            DB::table('pbm_occurrences')->where('pbm_id', $id)->delete();
            DB::table('pbm_templates')->where('id', $id)->delete();
            DB::commit();
            return response()->json(array('ok' => true, 'message' => 'Template berhasil dihapus'));
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(array('ok' => false, 'message' => 'Gagal menghapus template'), 500);
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
            return response()->json(array('ok' => true, 'message' => 'Semua template dan occurrence PBM berhasil dihapus'));
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(array('ok' => false, 'message' => 'Gagal hapus semua template'), 500);
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
            $date      = trim((string) $request->input('date'));
            $hari      = strtolower(trim((string) $request->input('hari')));
            $month     = trim((string) $request->input('month'));
            $startDate = trim((string) $request->input('start_date'));
            $endDate   = trim((string) $request->input('end_date'));

            $allowedHari = array('senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu');

            if ($startDate !== '' && $endDate !== '') {
                $genStart = $startDate; $genEnd = $endDate;
            } elseif ($month !== '') {
                $genStart = $month . '-01'; $genEnd = date('Y-m-t', strtotime($genStart));
            } elseif ($date !== '') {
                $genStart = $date; $genEnd = $date;
            } else {
                $genStart = date('Y-m-01'); $genEnd = date('Y-m-t');
            }

            if ($this->isValidDate($genStart) && $this->isValidDate($genEnd)) {
                $curr = $genStart;
                while (strtotime($curr) <= strtotime($genEnd)) {
                    if ($hari !== '' && in_array($hari, $allowedHari, true)) {
                        if ($this->dayNameFromDate($curr) !== $hari) {
                            $curr = date('Y-m-d', strtotime($curr . ' +1 day'));
                            continue;
                        }
                    }
                    $this->ensureOccurrencesForDate($curr);
                    $curr = date('Y-m-d', strtotime($curr . ' +1 day'));
                }
            }

            $query = DB::table('pbm_occurrences as o')
                ->leftJoin('pbm_templates as p', 'p.id', '=', 'o.pbm_id')
                ->join('rooms as r', 'r.id', '=', 'o.room_id')
                ->select(
                    'o.id', 'o.pbm_id', 'o.room_id', 'o.occ_date', 'o.start_time', 'o.end_time', 'o.status',
                    'o.approved_by', 'o.approved_at', 'o.created_at',
                    'r.name as room_name', 'r.floor as room_floor',
                    DB::raw("COALESCE(p.hari, '') as hari_template"),
                    DB::raw("COALESCE(p.mata_kuliah, '-') as mata_kuliah"),
                    DB::raw("COALESCE(p.kelas, '-') as kelas"),
                    DB::raw("COALESCE(p.dosen, '-') as dosen"),
                    DB::raw("COALESCE(p.semester, '-') as semester")
                );

            if ($startDate !== '' && $endDate !== '') {
                $query->whereBetween('o.occ_date', [$startDate, $endDate]);
            } elseif ($month !== '') {
                $query->whereBetween('o.occ_date', [$month . '-01', date('Y-m-t', strtotime($month . '-01'))]);
            } elseif ($date !== '') {
                $query->whereDate('o.occ_date', $date);
            } else {
                $query->whereBetween('o.occ_date', [date('Y-m-01'), date('Y-m-t')]);
            }

            if ($hari !== '' && in_array($hari, $allowedHari, true)) {
                $query->whereRaw("LOWER(DAYNAME(o.occ_date)) = ?", array($this->englishDayNameFromIndo($hari)));
            }

            // HANYA TAMPILKAN approved, rescheduled, dan draft (SEMBUNYIKAN cancelled)
            if ($request->filled('status') && $request->input('status') !== 'all') {
                $query->where('o.status', $request->input('status'));
            } else {
                $query->whereIn('o.status', array('approved', 'rescheduled', 'draft'));
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
                        ->orWhere('r.name', 'like', $search);
                });
            }

            $items = $query->orderBy('o.occ_date')->orderBy('o.start_time')->orderBy('o.room_id')->get();

            // DINAMIS: UBAH KE RIWAYAT JIKA JAM MULAI SUDAH LEWAT
            $now = date('Y-m-d H:i:s');
            foreach ($items as $item) {
                if (in_array($item->status, ['approved', 'rescheduled']) && $item->start_time <= $now) {
                    $item->status = 'riwayat';
                }
            }

            $grouped = array(
                'senin' => array(), 'selasa' => array(), 'rabu' => array(),
                'kamis' => array(), 'jumat' => array(), 'sabtu' => array(), 'minggu' => array()
            );

            foreach ($items as $item) {
                $hariItem = $this->dayNameFromDate($item->occ_date);
                if (!isset($grouped[$hariItem])) $grouped[$hariItem] = array();
                $grouped[$hariItem][] = $item;
            }

            return response()->json(array('ok' => true, 'items' => $items, 'groups' => $grouped));
        } catch (\Throwable $e) {
            Log::error('PBM events error: ' . $e->getMessage());
            return response()->json(array('ok' => false, 'message' => 'Gagal memuat jadwal PBM'), 500);
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
                return response()->json(array('ok' => false, 'message' => 'Jadwal asal tidak ditemukan'), 404);
            }

            if (!in_array($old->status, array('approved', 'draft', 'rescheduled'), true)) {
                DB::rollBack();
                return response()->json(array('ok' => false, 'message' => 'Jadwal ini tidak bisa dipindahkan karena sudah riwayat.'));
            }

            $newDate    = trim((string) $request->input('date'));
            $newRoomId  = (int) $request->input('room_id', 0);
            $newStartHm = trim((string) $request->input('start_hm'));
            $newEndHm   = trim((string) $request->input('end_hm'));

            if (!$newDate || !$this->isValidDate($newDate)) {
                DB::rollBack();
                return response()->json(array('ok' => false, 'message' => 'Tanggal baru tidak valid'));
            }
            if (!$newRoomId) {
                DB::rollBack();
                return response()->json(array('ok' => false, 'message' => 'Ruangan baru wajib dipilih'));
            }
            $newStart = $this->normalizeHm($newStartHm);
            $newEnd   = $this->normalizeHm($newEndHm);
            
            if (!$newStart || !$newEnd) {
                DB::rollBack();
                return response()->json(array('ok' => false, 'message' => 'Format jam harus HH:MM'));
            }

            $newStartDateTime = $newDate . ' ' . $newStart . ':00';
            $newEndDateTime   = $newDate . ' ' . $newEnd . ':00';

            if (strtotime($newEndDateTime) <= strtotime($newStartDateTime)) {
                DB::rollBack();
                return response()->json(array('ok' => false, 'message' => 'Jam selesai harus setelah jam mulai'));
            }

            // CEK BENTROK KE JADWAL LAIN DI RUANGAN DAN JAM YANG SAMA
            $conflict = $this->getConflictSummary($newRoomId, $newDate, $newStartDateTime, $newEndDateTime, (int) $id, null);
            if ($conflict['has_conflict']) {
                DB::rollBack();
                return response()->json(array('ok' => false, 'message' => 'Gagal pindah! ' . $conflict['message']));
            }

            if ($old->occ_date === $newDate) {
                // PERBAIKAN 1: Pindah di hari yg sama, cukup ubah jam/ruangan, tapi JANGAN ubah flag 'is_rescheduled'
                DB::table('pbm_occurrences')
                    ->where('id', (int) $id)
                    ->update(array(
                        'room_id'    => $newRoomId,
                        'start_time' => $newStartDateTime,
                        'end_time'   => $newEndDateTime,
                        'status'     => 'rescheduled'
                    ));
            } else {
                // PERBAIKAN 2: Pindah ke HARI LAIN. Gunakan fitur is_rescheduled agar hari tujuannya tahu ini jadwal tamu.
                // 1. Matikan (cancel) jadwal di hari asal agar HILANG dari tampilan hari itu
                DB::table('pbm_occurrences')
                    ->where('id', (int) $id)
                    ->update(array(
                        'status' => 'cancelled',
                        'reschedule_note' => 'Dipindah ke ' . $newDate . ' ' . $newStart
                    ));

                // 2. Buat jadwal baru di hari tujuan dan set is_rescheduled = 1
                DB::table('pbm_occurrences')->insert(array(
                    'pbm_id'               => $old->pbm_id,
                    'source_occurrence_id' => $id, // Simpan riwayat ID asal
                    'is_rescheduled'       => 1,   // Tanda mutlak ini adalah jadwal tamu
                    'room_id'              => $newRoomId,
                    'occ_date'             => $newDate,
                    'start_time'           => $newStartDateTime,
                    'end_time'             => $newEndDateTime,
                    'status'               => 'rescheduled',
                    'approved_at'          => now(),
                    'created_at'           => now(),
                ));
            }

            DB::commit();
            return response()->json(array('ok' => true, 'message' => 'Jadwal berhasil dipindahkan.'));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PBM reschedule error: ' . $e->getMessage());
            return response()->json(array('ok' => false, 'message' => 'Gagal memindahkan jadwal: ' . $e->getMessage()), 500);
        }
    }

    public function eventDelete($id)
    {
        try {
            // Ubah jadi cancelled agar disembunyikan
            $updated = DB::table('pbm_occurrences')
                ->where('id', (int) $id)
                ->update(['status' => 'cancelled']);

            return response()->json(array(
                'ok' => (bool) $updated,
                'message' => $updated ? 'Jadwal berhasil disembunyikan' : 'Jadwal tidak ditemukan',
            ));
        } catch (\Throwable $e) {
            Log::error('PBM event delete error: ' . $e->getMessage());
            return response()->json(array('ok' => false, 'message' => 'Gagal menyembunyikan jadwal'), 500);
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
                if (!$this->isValidDate($date)) return response()->json(array('ok' => false, 'message' => 'Tanggal tidak valid'));
                $query->whereDate('occ_date', $date);
            } elseif ($hari !== '' && in_array($hari, $allowedHari, true)) {
                $query->whereRaw("LOWER(DAYNAME(occ_date)) = ?", array($this->englishDayNameFromIndo($hari)));
            } else {
                return response()->json(array('ok' => false, 'message' => 'Isi tanggal atau hari terlebih dahulu'));
            }

            $updated = $query->update(['status' => 'cancelled']);

            return response()->json(array(
                'ok' => true,
                'deleted' => (int) $updated,
                'message' => 'Semua jadwal PBM pada waktu terpilih berhasil dibatalkan',
            ));
        } catch (\Throwable $e) {
            Log::error('PBM delete all events error: ' . $e->getMessage());
            return response()->json(array('ok' => false, 'message' => 'Gagal membatalkan semua jadwal'), 500);
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
                return response()->download($path, 'pbm_templates_sample.csv', array('Content-Type' => 'text/csv; charset=UTF-8'));
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
            return response()->json(array('ok' => false, 'message' => 'Gagal download sample CSV'), 500);
        }
    }

    public function templatesImportCsv(Request $request)
    {
        try {
            $request->validate(array('file' => array('required', 'file', 'mimes:csv,txt', 'max:8192')));
            $deleteAllFirst = ((int) $request->input('delete_all_first', 0) === 1);

            $fh = fopen($request->file('file')->getRealPath(), 'r');
            if (!$fh) return response()->json(array('ok' => false, 'message' => 'Tidak bisa membaca file CSV'), 200);

            $firstLine = fgets($fh);
            if ($firstLine === false) { fclose($fh); return response()->json(array('ok' => false, 'message' => 'CSV kosong'), 200); }

            $firstLine = preg_replace('/^\xEF\xBB\xBF/', '', $firstLine);
            $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';
            rewind($fh);

            $header = fgetcsv($fh, 0, $delimiter);
            if (!$header) { fclose($fh); return response()->json(array('ok' => false, 'message' => 'Header CSV tidak valid'), 200); }

            $header = array_map(function ($h) {
                return str_replace(' ', '_', preg_replace('/^\xEF\xBB\xBF/', '', strtolower(trim((string) $h))));
            }, $header);

            $idx = function ($key) use ($header) {
                $pos = array_search($key, $header, true);
                return ($pos === false) ? false : (int) $pos;
            };

            $required = array('room_id', 'hari', 'start_time', 'end_time', 'mata_kuliah', 'kelas', 'dosen', 'semester', 'aktif');
            foreach ($required as $r) {
                if ($idx($r) === false) {
                    fclose($fh); return response()->json(array('ok' => false, 'message' => 'Kolom wajib tidak ada: ' . $r), 200);
                }
            }

            $rooms = DB::table('rooms')->select('id', 'name')->get();
            $roomIdSet = array();
            $roomNameToId = array();
            foreach ($rooms as $r) {
                $roomIdSet[(int) $r->id] = true;
                $roomNameToId[$this->normalizeRoomName($r->name)] = (int) $r->id;
            }

            $inserted = 0; $updated  = 0; $invalid  = 0; $errors   = array();
            DB::beginTransaction();

            if ($deleteAllFirst) {
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                DB::table('pbm_occurrences')->truncate();
                DB::table('pbm_templates')->truncate();
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            }

            $rowNum = 1;
            $allowedHari = array('senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu');

            while (($row = fgetcsv($fh, 0, $delimiter)) !== false) {
                $rowNum++;
                $hasValue = false;
                foreach ($row as $v) { if (trim((string) $v) !== '') { $hasValue = true; break; } }
                if (!$hasValue) continue;

                $roomKey  = trim((string) ($row[$idx('room_id')] ?? ''));
                $hari     = strtolower(trim((string) ($row[$idx('hari')] ?? '')));
                $start    = trim((string) ($row[$idx('start_time')] ?? ''));
                $end      = trim((string) ($row[$idx('end_time')] ?? ''));
                $mk       = trim((string) ($row[$idx('mata_kuliah')] ?? ''));
                $kelas    = trim((string) ($row[$idx('kelas')] ?? ''));
                $dosen    = trim((string) ($row[$idx('dosen')] ?? ''));
                $semester = trim((string) ($row[$idx('semester')] ?? ''));
                $aktif    = (trim((string) ($row[$idx('aktif')] ?? '1')) === '0') ? 0 : 1;

                if ($roomKey === '') { $errors[] = 'Baris ' . $rowNum . ': room_id kosong'; $invalid++; continue; }
                if (!in_array($hari, $allowedHari, true)) { $errors[] = 'Baris ' . $rowNum . ': hari tidak valid (' . $hari . ')'; $invalid++; continue; }

                $startNorm = $this->normalizeTime($start);
                $endNorm   = $this->normalizeTime($end);
                if (!$startNorm || !$endNorm) { $errors[] = 'Baris ' . $rowNum . ': format jam salah'; $invalid++; continue; }
                if (strtotime($endNorm) <= strtotime($startNorm)) { $errors[] = 'Baris ' . $rowNum . ': jam terbalik'; $invalid++; continue; }

                $roomId = 0;
                if (ctype_digit($roomKey)) {
                    $roomId = (int) $roomKey;
                    if (!isset($roomIdSet[$roomId])) { $errors[] = 'Baris ' . $rowNum . ': room_id tidak ada'; $invalid++; continue; }
                } else {
                    $roomNorm = $this->normalizeRoomName($roomKey);
                    $roomId = $roomNameToId[$roomNorm] ?? 0;
                    if (!$roomId) { $errors[] = 'Baris ' . $rowNum . ': ruangan tidak ada'; $invalid++; continue; }
                }

                $existing = DB::table('pbm_templates')->where('room_id', $roomId)->whereRaw('LOWER(hari) = ?', array($hari))
                    ->where('start_time', $startNorm)->where('end_time', $endNorm)->first();

                $data = array(
                    'mata_kuliah' => $mk, 'kelas' => $kelas, 'dosen' => $dosen, 'semester' => $semester, 'aktif' => $aktif, 'updated_at' => now(),
                );

                if ($existing) {
                    DB::table('pbm_templates')->where('id', $existing->id)->update($data);
                    $updated++;
                } else {
                    $data['room_id'] = $roomId; $data['hari'] = $hari; $data['start_time'] = $startNorm; $data['end_time'] = $endNorm;
                    $data['created_at'] = now(); $data['updated_at'] = null;
                    DB::table('pbm_templates')->insert($data);
                    $inserted++;
                }
            }

            fclose($fh);
            DB::commit();

            return response()->json(array(
                'ok' => true, 'inserted' => $inserted, 'updated' => $updated, 'invalid' => $invalid, 'errors' => $errors,
                'message' => 'Upload selesai. Occurrence otomatis dibentuk.',
            ), 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PBM upload CSV error: ' . $e->getMessage());
            return response()->json(array('ok' => false, 'message' => 'Gagal upload CSV: ' . $e->getMessage()), 500);
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
            ->get();

        foreach ($templates as $template) {
            // PERBAIKAN 3: Hanya cek apakah jadwal "Native" (is_rescheduled = 0) sudah ada.
            // Sebelumnya logika `!= rescheduled` membuat sistem malah membuat jadwal "Hantu" baru.
            $exists = DB::table('pbm_occurrences')
                ->where('pbm_id', (int) $template->id)
                ->whereDate('occ_date', $date)
                ->where('is_rescheduled', 0)
                ->exists();

            if ($exists) {
                continue;
            }

            try {
                DB::table('pbm_occurrences')->insert(array(
                    'pbm_id'         => (int) $template->id,
                    'room_id'        => (int) $template->room_id,
                    'occ_date'       => $date,
                    'start_time'     => $date . ' ' . $this->timeToHm($template->start_time) . ':00',
                    'end_time'       => $date . ' ' . $this->timeToHm($template->end_time) . ':00',
                    'is_rescheduled' => 0, // Ini jadwal asli (rutin)
                    'status'         => 'approved',
                    'approved_at'    => now(),
                    'created_at'     => now(),
                ));
            } catch (\Exception $e) {
                // Abaikan error constraint
            }
        }
    }

    private function dateFromIndoDayNameInCurrentWeek($hari)
    {
        $map = array('senin'=>'monday','selasa'=>'tuesday','rabu'=>'wednesday','kamis'=>'thursday','jumat'=>'friday','sabtu'=>'saturday','minggu'=>'sunday');
        $english = $map[$hari] ?? 'monday';
        return date('Y-m-d', strtotime($english . ' this week'));
    }

    private function getConflictSummary($roomId, $date, $startDateTime, $endDateTime, $excludeOccurrenceId = null, $excludeTemplateId = null) 
    {
        $sources = array();

        // PERBAIKAN 4: Merapikan query tanggal Overlap (Start baru < End Lama AND End Baru > Start Lama)
        $occQuery = DB::table('pbm_occurrences')
            ->where('room_id', $roomId)
            ->whereDate('occ_date', $date)
            ->whereIn('status', array('approved', 'rescheduled', 'draft'))
            ->where(function ($q) use ($startDateTime, $endDateTime) {
                $q->where('start_time', '<', $endDateTime)
                  ->where('end_time', '>', $startDateTime);
            });

        if ($excludeOccurrenceId) {
            $occQuery->where('id', '!=', (int) $excludeOccurrenceId);
        }
        if ($excludeTemplateId) {
            $occQuery->where(function ($q) use ($excludeTemplateId) {
                $q->whereNull('pbm_id')->orWhere('pbm_id', '!=', (int) $excludeTemplateId);
            });
        }
        if ($occQuery->exists()) $sources[] = 'jadwal PBM';

        $blockConflict = DB::table('room_blocks')
            ->where('room_id', $roomId)->where('status', 'terbooking')
            ->where(function ($q) use ($startDateTime, $endDateTime) {
                $q->where('start_time', '<', $endDateTime)->where('end_time', '>', $startDateTime);
            })->exists();
        if ($blockConflict) $sources[] = 'quick booking / room block';

        // Admin dilarang memindah jadwal jika sudah ditabrak mahasiswa (status menunggu/disetujui)
        $borrowConflict = DB::table('borrow_requests')
            ->where('room_id', $roomId)->whereIn('status', array('menunggu', 'disetujui')) 
            ->where(function ($q) use ($startDateTime, $endDateTime) {
                $q->where('start_time', '<', $endDateTime)->where('end_time', '>', $startDateTime);
            })->exists();
        if ($borrowConflict) $sources[] = 'booking mahasiswa';

        $has = !empty($sources);
        return array('has_conflict' => $has, 'sources' => $sources, 'message' => $has ? 'Ruangan terpakai jadwal lain pada jam tersebut.' : '');
    }

    private function checkTemplateConflict($roomId, $hari, $startTime, $endTime, $excludeId = 0)
    {
        $query = DB::table('pbm_templates')
            ->where('room_id', $roomId)->whereRaw('LOWER(hari) = ?', array($hari))->where('aktif', 1)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            });
        if ($excludeId > 0) $query->where('id', '!=', $excludeId);
        return $query->exists();
    }

    private function dayNameFromDate($date)
    {
        $map = array('Monday'=>'senin','Tuesday'=>'selasa','Wednesday'=>'rabu','Thursday'=>'kamis','Friday'=>'jumat','Saturday'=>'sabtu','Sunday'=>'minggu');
        return $map[date('l', strtotime($date))] ?? 'senin';
    }

    private function englishDayNameFromIndo($hari)
    {
        $map = array('senin'=>'monday','selasa'=>'tuesday','rabu'=>'wednesday','kamis'=>'thursday','jumat'=>'friday','sabtu'=>'saturday','minggu'=>'sunday');
        return $map[$hari] ?? '';
    }

    private function isValidDate($date)
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) return false;
        $parts = explode('-', $date);
        return checkdate((int)$parts[1], (int)$parts[2], (int)$parts[0]);
    }

    private function normalizeTime($t)
    {
        $t = trim((string) $t);
        if ($t === '') return null;
        if (preg_match('/^\d{2}:\d{2}$/', $t)) return $t . ':00';
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $t)) return $t;
        return null;
    }

    private function normalizeHm($t)
    {
        $t = trim((string) $t);
        if (preg_match('/^\d{2}:\d{2}$/', $t)) return $t;
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $t)) return substr($t, 0, 5);
        return null;
    }

    private function timeToHm($t) { return substr((string) $t, 0, 5); }

    private function normalizeRoomName($name)
    {
        return preg_replace('/\s+/', '', str_replace(['.', '-'], '', strtolower(trim((string) $name))));
    }
}