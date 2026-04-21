<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Pastikan timezone server sudah WIB agar tidak meleset
        DB::statement("SET time_zone = '+07:00'");
        $now = Carbon::now('Asia/Jakarta');

        $selectedTanggal = (string) $request->get('tanggal', date('Y-m-d'));

        if (!$this->isValidDate($selectedTanggal)) {
            $selectedTanggal = date('Y-m-d');
        }

        $lantai = (string) $request->get('lantai', 'all');

        $dateObj = Carbon::parse($selectedTanggal);
        $selectedHari = $this->mapEnglishDayToHari(strtolower($dateObj->englishDayOfWeek));
        $hariLabel = $this->hariLabel($selectedHari);
        $tanggalLabel = $hariLabel . ', ' . $dateObj->format('d-m-Y');

        // 1. Ambil daftar lantai untuk filter
        $floors = DB::table('rooms')
            ->select('floor')
            ->whereNotNull('floor')
            ->distinct()
            ->orderBy('floor')
            ->pluck('floor');

        // 2. Ambil data ruangan
        $roomsData = DB::table('rooms')
            ->select('id', 'name', 'floor', 'capacity', 'photo')
            ->get()
            ->keyBy('id');

        $roomsQuery = DB::table('rooms')
            ->select('id', 'name', 'floor', 'capacity', 'photo')
            ->whereNotNull('name');

        if ($lantai !== 'all') {
            $roomsQuery->where('floor', $lantai);
        }

        $rooms = $roomsQuery
            ->orderBy('floor')
            ->orderByRaw("
                CASE
                    WHEN name REGEXP '^R\\.[0-9]+' THEN CAST(REPLACE(name, 'R.', '') AS UNSIGNED)
                    ELSE 999999
                END ASC
            ")
            ->orderBy('name')
            ->get();

        // ---------------------------------------------------------------------
        // 3. QUERY JADWAL PBM: Gabungan Template Rutin & Reschedule Dinamis
        // ---------------------------------------------------------------------
        
        // Ambil Template Rutin untuk hari ini
        $templates = DB::table('pbm_templates')
            ->where('hari', $selectedHari)
            ->where('aktif', 1)
            ->get();

        // Ambil data Occurrence (Perubahan/Reschedule/Batal) khusus di tanggal ini
        $occurrences = DB::table('pbm_occurrences')
            ->whereDate('occ_date', $selectedTanggal)
            ->get();

        $pbmRows = collect();
        $processedOccIds = [];

        // Proses 1: Masukkan Jadwal Rutin Asli di hari ini
        foreach ($templates as $t) {
            $nativeOcc = $occurrences->where('pbm_id', $t->id)->where('is_rescheduled', 0)->first();

            if ($nativeOcc) {
                $processedOccIds[] = $nativeOcc->id;
                
                // Jika dibatalkan/ditolak, jangan tampilkan
                if (in_array($nativeOcc->status, ['cancelled', 'rejected'])) {
                    continue;
                }

                // Filter waktu: Sembunyikan jika jam selesai sudah lewat
                $absoluteEndTime = Carbon::parse($selectedTanggal . ' ' . Carbon::parse($nativeOcc->end_time)->format('H:i:s'));
                if ($absoluteEndTime->isPast()) {
                    continue;
                }
                
                $pbmRows->push((object)[
                    'pbm_id'         => $t->id,
                    'room_id'        => $nativeOcc->room_id,
                    'start_time'     => $nativeOcc->start_time,
                    'end_time'       => $nativeOcc->end_time,
                    'mata_kuliah'    => $t->mata_kuliah ?: 'PBM',
                    'kelas'          => $t->kelas ?: '-',
                    'dosen'          => $t->dosen ?: '-',
                    'room_name'      => $roomsData->has($nativeOcc->room_id) ? $roomsData->get($nativeOcc->room_id)->name : 'Unknown',
                    'floor'          => $roomsData->has($nativeOcc->room_id) ? $roomsData->get($nativeOcc->room_id)->floor : '',
                    'is_rescheduled' => false
                ]);
            } else {
                // Filter waktu: Sembunyikan jika jam selesai sudah lewat (dari template)
                $absoluteEndTime = Carbon::parse($selectedTanggal . ' ' . Carbon::parse($t->end_time)->format('H:i:s'));
                if ($absoluteEndTime->isPast()) {
                    continue;
                }

                $pbmRows->push((object)[
                    'pbm_id'         => $t->id,
                    'room_id'        => $t->room_id,
                    'start_time'     => $selectedTanggal . ' ' . $t->start_time,
                    'end_time'       => $selectedTanggal . ' ' . $t->end_time,
                    'mata_kuliah'    => $t->mata_kuliah ?: 'PBM',
                    'kelas'          => $t->kelas ?: '-',
                    'dosen'          => $t->dosen ?: '-',
                    'room_name'      => $roomsData->has($t->room_id) ? $roomsData->get($t->room_id)->name : 'Unknown',
                    'floor'          => $roomsData->has($t->room_id) ? $roomsData->get($t->room_id)->floor : '',
                    'is_rescheduled' => false
                ]);
            }
        }

        // Proses 2: Masukkan Jadwal TAMU (Jadwal pindahan dari hari lain)
        foreach ($occurrences as $occ) {
            if (in_array($occ->id, $processedOccIds)) continue;
            if (in_array($occ->status, ['cancelled', 'rejected'])) continue;

            // Filter waktu: Sembunyikan jika jam selesai sudah lewat
            $absoluteEndTime = Carbon::parse($selectedTanggal . ' ' . Carbon::parse($occ->end_time)->format('H:i:s'));
            if ($absoluteEndTime->isPast()) {
                continue;
            }

            $tmpl = DB::table('pbm_templates')->where('id', $occ->pbm_id)->first();
            if ($tmpl) {
                $pbmRows->push((object)[
                    'pbm_id'         => $occ->pbm_id,
                    'room_id'        => $occ->room_id,
                    'start_time'     => $occ->start_time,
                    'end_time'       => $occ->end_time,
                    'mata_kuliah'    => $tmpl->mata_kuliah ?: 'PBM',
                    'kelas'          => $tmpl->kelas ?: '-',
                    'dosen'          => $tmpl->dosen ?: '-',
                    'room_name'      => $roomsData->has($occ->room_id) ? $roomsData->get($occ->room_id)->name : 'Unknown',
                    'floor'          => $roomsData->has($occ->room_id) ? $roomsData->get($occ->room_id)->floor : '',
                    'is_rescheduled' => true // Tandai sebagai jadwal tamu
                ]);
            }
        }

        // Filter PBM berdasarkan lantai (jika ada)
        if ($lantai !== 'all') {
            $pbmRows = $pbmRows->filter(function ($item) use ($lantai) {
                return (string) $item->floor === (string) $lantai;
            });
        }
        
        $pbmRows = $pbmRows->sortBy('start_time')->values();

        // ---------------------------------------------------------------------
        // 4. QUERY JADWAL: Pengajuan Mahasiswa - HANYA 1 HARI
        // ---------------------------------------------------------------------
        $mahasiswaRows = DB::table('borrow_requests as br')
            ->join('rooms as r', 'r.id', '=', 'br.room_id')
            ->select(
                'br.room_id',
                'br.org_name',
                'br.responsible_name',
                'br.start_time',
                'br.end_time',
                'br.status',
                'r.name as room_name',
                'r.floor'
            )
            ->whereDate('br.start_time', '=', $selectedTanggal)
            ->whereIn('br.status', ['disetujui', 'menunggu']) 
            ->when($lantai !== 'all', function ($q) use ($lantai) {
                $q->where('r.floor', $lantai);
            })
            ->orderBy('br.start_time')
            ->get();

        // Filter jadwal Mahasiswa yang sudah lewat (Absolute time check)
        $mahasiswaRows = $mahasiswaRows->filter(function ($item) use ($selectedTanggal) {
            $absoluteEndTime = Carbon::parse($selectedTanggal . ' ' . Carbon::parse($item->end_time)->format('H:i:s'));
            return !$absoluteEndTime->isPast();
        })->values();

        // ---------------------------------------------------------------------
        // 5. QUERY JADWAL: Booking Cepat (Admin/Kemahasiswaan) - HANYA 1 HARI
        // ---------------------------------------------------------------------
        $bookingCepatRows = DB::table('room_blocks as rb')
            ->join('rooms as r', 'r.id', '=', 'rb.room_id')
            ->select(
                'rb.room_id',
                'rb.title',
                'rb.note',
                'rb.source',
                'rb.start_time',
                'rb.end_time',
                'rb.status',
                'r.name as room_name',
                'r.floor'
            )
            ->whereDate('rb.start_time', '=', $selectedTanggal)
            ->where('rb.status', 'terbooking') 
            ->when($lantai !== 'all', function ($q) use ($lantai) {
                $q->where('r.floor', $lantai);
            })
            ->orderBy('rb.start_time')
            ->get();

        // Filter Booking Cepat yang sudah lewat (Absolute time check)
        $bookingCepatRows = $bookingCepatRows->filter(function ($item) use ($selectedTanggal) {
            $absoluteEndTime = Carbon::parse($selectedTanggal . ' ' . Carbon::parse($item->end_time)->format('H:i:s'));
            return !$absoluteEndTime->isPast();
        })->values();

        // ---------------------------------------------------------------------
        // 6. GENERATE TIME SLOTS (Sumbu Y/Jam di Kalender)
        // ---------------------------------------------------------------------
        $timeSlots = array();
        $slotSeen = array();

        // Kumpulkan semua jadwal jadi satu array
        $allSchedules = array_merge($pbmRows->toArray(), $mahasiswaRows->toArray(), $bookingCepatRows->toArray());

        foreach ($allSchedules as $row) {
            $start = Carbon::parse($row->start_time)->format('H:i:s');
            $end = Carbon::parse($row->end_time)->format('H:i:s');
            $slotKey = $start . '|' . $end;

            if (!isset($slotSeen[$slotKey])) {
                $slotSeen[$slotKey] = true;
                $timeSlots[] = array(
                    'label' => substr($start, 0, 5) . ' - ' . substr($end, 0, 5),
                    'start' => $start,
                    'end'   => $end,
                );
            }
        }

        // Fallback: Jika tidak ada jadwal sama sekali hari itu, pakai jam dari template yang aktif di hari tsb
        // (Pastikan time slot dari template juga difilter jika sudah lewat)
        if (empty($timeSlots)) {
            foreach ($templates as $slot) {
                $absoluteEndTime = Carbon::parse($selectedTanggal . ' ' . Carbon::parse($slot->end_time)->format('H:i:s'));
                if ($absoluteEndTime->isPast()) {
                    continue;
                }

                $start = Carbon::parse($slot->start_time)->format('H:i:s');
                $end = Carbon::parse($slot->end_time)->format('H:i:s');
                $slotKey = $start . '|' . $end;

                if (!isset($slotSeen[$slotKey])) {
                    $slotSeen[$slotKey] = true;
                    $timeSlots[] = array(
                        'label' => substr($start, 0, 5) . ' - ' . substr($end, 0, 5),
                        'start' => $start,
                        'end'   => $end,
                    );
                }
            }
        }

        // Urutkan slot waktu dari pagi ke malam
        usort($timeSlots, function ($a, $b) {
            return strcmp($a['start'], $b['start']);
        });

        // ---------------------------------------------------------------------
        // 7. MAPPING JADWAL KE DALAM GRID KALENDER
        // ---------------------------------------------------------------------
        $scheduleMap = array();

        // Map PBM
        foreach ($pbmRows as $item) {
            $itemStart = Carbon::parse($item->start_time)->format('H:i:s');
            $itemEnd = Carbon::parse($item->end_time)->format('H:i:s');

            foreach ($timeSlots as $slot) {
                if ($this->isTimeOverlap($slot['start'], $slot['end'], $itemStart, $itemEnd)) {
                    $cellKey = $item->room_id . '|' . $slot['start'] . '|' . $slot['end'];

                    if (!isset($scheduleMap[$cellKey])) {
                        $scheduleMap[$cellKey] = array();
                    }

                    $title = $item->mata_kuliah;
                    if ($item->is_rescheduled) {
                        $title .= ' [PINDAHAN]'; // Menandakan ini jadwal tamu
                    }

                    $scheduleMap[$cellKey][] = array(
                        'type'       => 'pbm',
                        'title'      => $title,
                        'subtitle'   => 'Kelas: ' . $item->kelas,
                        'meta'       => 'Dosen: ' . $item->dosen,
                        'start_time' => $itemStart,
                        'end_time'   => $itemEnd,
                    );
                }
            }
        }

        // Map Pengajuan Mahasiswa
        foreach ($mahasiswaRows as $item) {
            $itemStart = Carbon::parse($item->start_time)->format('H:i:s');
            $itemEnd = Carbon::parse($item->end_time)->format('H:i:s');

            foreach ($timeSlots as $slot) {
                if ($this->isTimeOverlap($slot['start'], $slot['end'], $itemStart, $itemEnd)) {
                    $cellKey = $item->room_id . '|' . $slot['start'] . '|' . $slot['end'];

                    if (!isset($scheduleMap[$cellKey])) {
                        $scheduleMap[$cellKey] = array();
                    }

                    $scheduleMap[$cellKey][] = array(
                        'type'       => 'mahasiswa',
                        'title'      => $item->status === 'menunggu' ? 'Sedang Diproses/Menunggu' : 'Peminjaman Ruangan',
                        'subtitle'   => $item->org_name ? $item->org_name : 'Mahasiswa',
                        'meta'       => 'PJ: ' . ($item->responsible_name ? $item->responsible_name : '-'),
                        'start_time' => $itemStart,
                        'end_time'   => $itemEnd,
                    );
                }
            }
        }

        // Map Booking Cepat
        foreach ($bookingCepatRows as $item) {
            $itemStart = Carbon::parse($item->start_time)->format('H:i:s');
            $itemEnd = Carbon::parse($item->end_time)->format('H:i:s');

            foreach ($timeSlots as $slot) {
                if ($this->isTimeOverlap($slot['start'], $slot['end'], $itemStart, $itemEnd)) {
                    $cellKey = $item->room_id . '|' . $slot['start'] . '|' . $slot['end'];

                    if (!isset($scheduleMap[$cellKey])) {
                        $scheduleMap[$cellKey] = array();
                    }

                    $scheduleMap[$cellKey][] = array(
                        'type'       => 'quick_booking',
                        'title'      => ($item->title ? $item->title : 'Booking Ruangan'),
                        'subtitle'   => 'Oleh: ' . ucfirst($item->source),
                        'meta'       => $item->note ? $item->note : '-',
                        'start_time' => $itemStart,
                        'end_time'   => $itemEnd,
                    );
                }
            }
        }

        // Return Data
        if ($request->ajax()) {
            return response()->json(array(
                'rooms'            => $rooms,
                'timeSlots'        => $timeSlots,
                'scheduleMap'      => $scheduleMap,
                'selectedTanggal'  => $selectedTanggal,
                'selectedHari'     => $selectedHari,
                'selectedLantai'   => $lantai,
                'tanggalLabel'     => $tanggalLabel,
                'totalRooms'       => count($rooms),
            ));
        }

        return view('home', array(
            'rooms'            => $rooms,
            'timeSlots'        => $timeSlots,
            'scheduleMap'      => $scheduleMap,
            'selectedTanggal'  => $selectedTanggal,
            'selectedHari'     => $selectedHari,
            'selectedLantai'   => $lantai,
            'floors'           => $floors,
            'tanggalLabel'     => $tanggalLabel,
        ));
    }

    private function isValidDate($date)
    {
        if (!$date) {
            return false;
        }

        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    private function mapEnglishDayToHari($day)
    {
        switch ($day) {
            case 'monday':    return 'senin';
            case 'tuesday':   return 'selasa';
            case 'wednesday': return 'rabu';
            case 'thursday':  return 'kamis';
            case 'friday':    return 'jumat';
            case 'saturday':  return 'sabtu';
            default:          return 'minggu';
        }
    }

    private function hariLabel($hari)
    {
        switch ($hari) {
            case 'senin':  return 'Senin';
            case 'selasa': return 'Selasa';
            case 'rabu':   return 'Rabu';
            case 'kamis':  return 'Kamis';
            case 'jumat':  return 'Jumat';
            case 'sabtu':  return 'Sabtu';
            case 'minggu': return 'Minggu';
            default:       return ucfirst($hari);
        }
    }

    private function isTimeOverlap($slotStart, $slotEnd, $itemStart, $itemEnd)
    {
        return ($itemStart < $slotEnd) && ($itemEnd > $slotStart);
    }
}