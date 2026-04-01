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
        // 3. QUERY JADWAL: PBM (Termasuk Reschedule)
        // ---------------------------------------------------------------------
        $pbmRows = DB::table('pbm_occurrences as po')
            ->leftJoin('pbm_templates as pt', 'pt.id', '=', 'po.pbm_id')
            ->join('rooms as r', 'r.id', '=', 'po.room_id')
            ->select(
                'po.room_id',
                'po.start_time',
                'po.end_time',
                'po.occ_date', // Tambahan untuk membedakan tanggal
                DB::raw("COALESCE(pt.mata_kuliah, 'PBM') as mata_kuliah"),
                DB::raw("COALESCE(pt.kelas, '-') as kelas"),
                DB::raw("COALESCE(pt.dosen, '-') as dosen"),
                'r.name as room_name',
                'r.floor'
            )
            ->whereDate('po.occ_date', '>=', $selectedTanggal) // Menampilkan tanggal yg dipilih dan hari selanjutnya
            ->where('po.status', 'approved') 
            ->when($lantai !== 'all', function ($q) use ($lantai) {
                $q->where('r.floor', $lantai);
            })
            ->orderBy('po.start_time')
            ->get();

        // ---------------------------------------------------------------------
        // 4. QUERY JADWAL: Pengajuan Mahasiswa
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
            ->whereDate('br.start_time', '>=', $selectedTanggal) // Menampilkan tanggal yg dipilih dan hari selanjutnya
            ->where('br.status', 'disetujui')
            ->when($lantai !== 'all', function ($q) use ($lantai) {
                $q->where('r.floor', $lantai);
            })
            ->orderBy('br.start_time')
            ->get();

        // ---------------------------------------------------------------------
        // 5. QUERY JADWAL: Booking Cepat (Admin/Kemahasiswaan)
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
            ->whereDate('rb.start_time', '>=', $selectedTanggal) // Menampilkan tanggal yg dipilih dan hari selanjutnya
            ->where('rb.status', 'terbooking') 
            ->when($lantai !== 'all', function ($q) use ($lantai) {
                $q->where('r.floor', $lantai);
            })
            ->orderBy('rb.start_time')
            ->get();

        // ---------------------------------------------------------------------
        // 6. GENERATE TIME SLOTS (Sumbu Y/Jam di Kalender)
        // ---------------------------------------------------------------------
        $timeSlots = array();
        $slotSeen = array();

        // Kumpulkan semua jadwal jadi satu array untuk diekstrak jamnya
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

        // Fallback: Jika tidak ada jadwal sama sekali hari itu, pakai jam dari template
        if (empty($timeSlots)) {
            $fallbackSlots = DB::table('pbm_templates')
                ->select('start_time', 'end_time')
                ->where('aktif', 1)
                ->distinct()
                ->orderBy('start_time')
                ->get();

            foreach ($fallbackSlots as $slot) {
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
            $tglPbm = Carbon::parse($item->occ_date)->format('d/m/Y'); // Tanggal kegiatan

            foreach ($timeSlots as $slot) {
                if ($this->isTimeOverlap($slot['start'], $slot['end'], $itemStart, $itemEnd)) {
                    $cellKey = $item->room_id . '|' . $slot['start'] . '|' . $slot['end'];

                    if (!isset($scheduleMap[$cellKey])) {
                        $scheduleMap[$cellKey] = array();
                    }

                    $scheduleMap[$cellKey][] = array(
                        'type'       => 'pbm',
                        'title'      => $item->mata_kuliah . ' (' . $tglPbm . ')',
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
            $tglMhs = Carbon::parse($item->start_time)->format('d/m/Y'); // Tanggal kegiatan

            foreach ($timeSlots as $slot) {
                if ($this->isTimeOverlap($slot['start'], $slot['end'], $itemStart, $itemEnd)) {
                    $cellKey = $item->room_id . '|' . $slot['start'] . '|' . $slot['end'];

                    if (!isset($scheduleMap[$cellKey])) {
                        $scheduleMap[$cellKey] = array();
                    }

                    $scheduleMap[$cellKey][] = array(
                        'type'       => 'mahasiswa',
                        'title'      => 'Peminjaman Ruangan (' . $tglMhs . ')',
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
            $tglBooking = Carbon::parse($item->start_time)->format('d/m/Y'); // Tanggal kegiatan

            foreach ($timeSlots as $slot) {
                if ($this->isTimeOverlap($slot['start'], $slot['end'], $itemStart, $itemEnd)) {
                    $cellKey = $item->room_id . '|' . $slot['start'] . '|' . $slot['end'];

                    if (!isset($scheduleMap[$cellKey])) {
                        $scheduleMap[$cellKey] = array();
                    }

                    $scheduleMap[$cellKey][] = array(
                        'type'       => 'quick_booking',
                        'title'      => ($item->title ? $item->title : 'Booking Ruangan') . ' (' . $tglBooking . ')',
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