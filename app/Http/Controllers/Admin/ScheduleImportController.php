<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\ScheduleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ScheduleImportController extends Controller
{
    public function create()
    {
        return view('admin.jadwal.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        DB::beginTransaction();

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());

            $sheetNames = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU'];

            $rowsToInsert = [];

            foreach ($sheetNames as $sheetName) {
                $worksheet = $spreadsheet->getSheetByName($sheetName);

                if (!$worksheet) {
                    continue;
                }

                $highestColumn = $worksheet->getHighestColumn();
                $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

                $roomMap = [];

                // header room: row 2, alias optional: row 1
                for ($col = 2; $col <= $highestColumnIndex; $col++) {
                    $roomHeader = trim((string) $worksheet->getCellByColumnAndRow($col, 2)->getValue());
                    $roomAlias  = trim((string) $worksheet->getCellByColumnAndRow($col, 1)->getValue());

                    if ($roomHeader === '') {
                        continue;
                    }

                    $room = $this->findRoom($roomHeader, $roomAlias);

                    if ($room) {
                        $roomMap[$col] = $room->id;
                    }
                }

                // format file kamu: 1 slot = 3 baris
                for ($row = 5; $row <= 41; $row += 3) {
                    $timeRange = trim((string) $worksheet->getCellByColumnAndRow(1, $row)->getValue());

                    if ($timeRange === '') {
                        continue;
                    }

                    list($timeStart, $timeEnd) = $this->parseTimeRange($timeRange);

                    for ($col = 2; $col <= $highestColumnIndex; $col++) {
                        if (!isset($roomMap[$col])) {
                            continue;
                        }

                        $courseName = trim((string) $worksheet->getCellByColumnAndRow($col, $row)->getValue());
                        $className  = trim((string) $worksheet->getCellByColumnAndRow($col, $row + 1)->getValue());
                        $lecturer   = trim((string) $worksheet->getCellByColumnAndRow($col, $row + 2)->getValue());

                        if ($courseName === '' && $className === '' && $lecturer === '') {
                            continue;
                        }

                        $rowsToInsert[] = [
                            'room_id'     => $roomMap[$col],
                            'day'         => $sheetName,
                            'time_start'  => $timeStart,
                            'time_end'    => $timeEnd,
                            'course_name' => $courseName ?: null,
                            'class_name'  => $className ?: null,
                            'lecturer'    => $lecturer ?: null,
                            'raw_text'    => trim($courseName . ' | ' . $className . ' | ' . $lecturer, ' |'),
                            'created_at'  => now(),
                            'updated_at'  => now(),
                        ];
                    }
                }
            }

            // hapus jadwal lama, lalu isi jadwal baru
            ScheduleDetail::truncate();

            if (!empty($rowsToInsert)) {
                foreach (array_chunk($rowsToInsert, 500) as $chunk) {
                    ScheduleDetail::insert($chunk);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.jadwal.import')
                ->with('success', 'Jadwal berhasil diimport dan jadwal lama otomatis diganti.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    private function parseTimeRange($value)
    {
        $value = str_replace(' ', '', $value);
        $value = str_replace('.', ':', $value);

        $parts = explode('-', $value);

        $start = isset($parts[0]) ? trim($parts[0]) : null;
        $end   = isset($parts[1]) ? trim($parts[1]) : null;

        return [$start, $end];
    }

    private function findRoom($roomHeader, $roomAlias = null)
    {
        $room = Room::where('name', $roomHeader)->first();
        if ($room) {
            return $room;
        }

        if ($roomAlias) {
            $alias = trim($roomAlias, '() ');
            $room = Room::where('name', $alias)->first();
            if ($room) {
                return $room;
            }
        }

        $room = Room::where('name', 'like', '%' . $roomHeader . '%')->first();
        if ($room) {
            return $room;
        }

        if ($roomAlias) {
            $alias = trim($roomAlias, '() ');
            $room = Room::where('name', 'like', '%' . $alias . '%')->first();
            if ($room) {
                return $room;
            }
        }

        return null;
    }
}