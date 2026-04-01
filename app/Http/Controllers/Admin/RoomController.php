<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $rooms = Room::orderBy('id','desc')->get();

        return view('admin.rooms.index', [
            'rooms' => $rooms,
        ]);
    }

    // optional: masih ada kalau route butuh, tapi kita arahkan balik ke index
    public function edit(Room $room)
    {
        return redirect()->route('admin.rooms.index');
    }

    public function store(Request $request)
    {
        $data = $this->validateRoom($request);

        $photoPath = $this->saveUploadPhoto($request);
        if ($photoPath) $data['photo'] = $photoPath;

        Room::create($data);

        return redirect()->route('admin.rooms.index')->with('msg', 'Ruangan berhasil ditambahkan.');
    }

    public function update(Request $request, Room $room)
    {
        $data = $this->validateRoom($request);

        $photoPath = $this->saveUploadPhoto($request);
        if ($photoPath) $data['photo'] = $photoPath;

        $room->update($data);

        return redirect()->route('admin.rooms.index')->with('msg', 'Ruangan berhasil diupdate.');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('admin.rooms.index')->with('msg', 'Ruangan berhasil dihapus.');
    }

    public function templateCsv()
    {
        $csv = "floor,name,capacity,facilities,photo\n";
        $csv .= "1,R.101,30,\"Proyektor, AC\",assets/sample.jpg\n";

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename=rooms_template.csv');
    }

    public function importCsv(Request $request)
    {
        if (!$request->hasFile('csv_file')) {
            return redirect()->route('admin.rooms.index')->with('csvErr', 'CSV belum dipilih / gagal upload.');
        }

        $file = $request->file('csv_file');
        $ext = strtolower($file->getClientOriginalExtension());
        if ($ext !== 'csv') {
            return redirect()->route('admin.rooms.index')->with('csvErr', 'File harus .csv');
        }

        $path = $file->getRealPath();
        $fp = fopen($path, 'r');
        if (!$fp) {
            return redirect()->route('admin.rooms.index')->with('csvErr', 'Gagal membaca file CSV.');
        }

        $header = fgetcsv($fp);
        if (!$header) {
            fclose($fp);
            return redirect()->route('admin.rooms.index')->with('csvErr', 'CSV kosong.');
        }

        $normalized = [];
        foreach ($header as $h) $normalized[] = strtolower(trim((string)$h));

        $hasHeader = in_array('name', $normalized, true) && in_array('floor', $normalized, true);

        $idx = [
            'floor' => $hasHeader ? array_search('floor', $normalized, true) : 0,
            'name'  => $hasHeader ? array_search('name', $normalized, true) : 1,
            'capacity' => $hasHeader ? array_search('capacity', $normalized, true) : 2,
            'facilities' => $hasHeader ? array_search('facilities', $normalized, true) : 3,
            'photo' => $hasHeader ? array_search('photo', $normalized, true) : 4,
        ];

        $inserted = 0; $skipped = 0;

        DB::beginTransaction();
        try{
            while (($row = fgetcsv($fp)) !== false) {
                $allEmpty = true;
                foreach ($row as $c) {
                    if (trim((string)$c) !== '') { $allEmpty = false; break; }
                }
                if ($allEmpty) { $skipped++; continue; }

                $floor = trim((string)($row[$idx['floor']] ?? ''));
                $name  = trim((string)($row[$idx['name']] ?? ''));
                $capacityRaw = trim((string)($row[$idx['capacity']] ?? '0'));
                $facilities  = trim((string)($row[$idx['facilities']] ?? ''));
                $photo = trim((string)($row[$idx['photo']] ?? ''));

                if ($floor === '' || $name === '') { $skipped++; continue; }

                $capacity = is_numeric($capacityRaw) ? (int)$capacityRaw : 0;
                if ($capacity < 0) $capacity = 0;

                Room::create([
                    'floor' => $floor,
                    'name' => $name,
                    'capacity' => $capacity,
                    'facilities' => $facilities,
                    'photo' => $photo !== '' ? $photo : null,
                ]);
                $inserted++;
            }
            DB::commit();
        } catch (\Throwable $e){
            DB::rollBack();
            fclose($fp);
            return redirect()->route('admin.rooms.index')->with('csvErr', 'Import gagal: ' . $e->getMessage());
        }

        fclose($fp);
        return redirect()->route('admin.rooms.index')->with('csvMsg', "Import selesai. Ditambahkan: {$inserted}, Dilewati: {$skipped}.");
    }

    private function validateRoom(Request $request)
    {
        $validated = $request->validate([
            'floor' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'facilities' => 'nullable|string',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if (!isset($validated['capacity'])) $validated['capacity'] = 0;
        return $validated;
    }

    private function saveUploadPhoto(Request $request)
    {
        if (!$request->hasFile('photo')) return null;

        $file = $request->file('photo');
        if (!$file || !$file->isValid()) return null;

        $mime = $file->getMimeType();
        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
        ];
        if (!isset($allowed[$mime])) return null;

        $ext = $allowed[$mime];
        $name = 'room_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

        $destDir = public_path('assets');
        if (!is_dir($destDir)) @mkdir($destDir, 0775, true);

        $file->move($destDir, $name);

        return 'assets/' . $name;
    }
}