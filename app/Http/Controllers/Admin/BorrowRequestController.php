<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowRequestController extends Controller
{
    public function show(int $id)
    {
        $this->setTimezone();
        $this->autofinishRequests();

        $data = DB::table('borrow_requests as b')
            ->join('rooms as r', 'r.id', '=', 'b.room_id')
            ->where('b.id', $id)
            ->select('b.*', 'r.name as room_name', 'r.floor as room_floor')
            ->first();

        abort_if(!$data, 404, 'Pengajuan tidak ditemukan');

        [$statusText, $statusClass] = $this->statusLabel((string) $data->status);
        [$kemaText, $kemaClass]     = $this->kemaStatusLabel((string) ($data->kema_status ?? 'menunggu'));

        // Admin boleh memproses hanya kalau:
        // - status masih menunggu
        // - kema_status sudah disetujui
        $adminCanProcess = $this->adminCanProcess($data);

        return view('admin.pengajuan_show', compact(
            'data',
            'statusText',
            'statusClass',
            'kemaText',
            'kemaClass',
            'adminCanProcess'
        ));
    }

public function approve(Request $request, int $id)
    {
        $this->setTimezone();
        $this->autofinishRequests();

        $data = DB::table('borrow_requests')->where('id', $id)->first();
        abort_if(!$data, 404, 'Pengajuan tidak ditemukan');

        // 1) Hanya boleh approve kalau masih menunggu
        if ($data->status !== 'menunggu') {
            return back()->with('err', "Pengajuan sudah diproses (status: {$data->status}).");
        }

        // 2) Admin wajib nunggu ACC Kemahasiswaan
        if ($this->mustWaitKema($data)) {
            return back()->with('err', 'Admin belum bisa approve. Menunggu ACC Kemahasiswaan.');
        }

        // 3) CEK BENTROK JADWAL (3 TABEL)
        $start  = $data->start_time;
        $end    = $data->end_time;
        $roomId = (int) $data->room_id;

        // A. Cek bentrok dengan pengajuan mahasiswa lain yang sudah ACC
        $bentrokPengajuan = DB::table('borrow_requests')
            ->where('room_id', $roomId)
            ->where('id', '!=', $id) // Abaikan ID pengajuan ini sendiri
            ->where('status', 'disetujui')
            ->whereRaw('start_time < ? AND end_time > ?', [$end, $start])
            ->exists();

        // B. Cek bentrok dengan jadwal harian PBM
        $bentrokPBM = DB::table('pbm_occurrences')
            ->where('room_id', $roomId)
            ->where('status', 'approved')
            ->whereRaw('start_time < ? AND end_time > ?', [$end, $start])
            ->exists();

        // C. Cek bentrok dengan Booking Cepat (Admin/Kemahasiswaan)
        $bentrokBooking = DB::table('room_blocks')
            ->where('room_id', $roomId)
            ->where('status', 'terbooking')
            ->whereRaw('start_time < ? AND end_time > ?', [$end, $start])
            ->exists();

        // Jika salah satu tabel ada yang bentrok, tolak!
        if ($bentrokPengajuan || $bentrokPBM || $bentrokBooking) {
            return back()->with('err', 'Gagal disetujui! Jadwal ini BENTROK dengan Peminjaman, Jadwal PBM, atau Booking Cepat yang sudah ada.');
        }

        // 4) Update approve
        DB::table('borrow_requests')
            ->where('id', $id)
            ->where('status', 'menunggu')
            ->update([
                'status'      => 'disetujui',
                'approved_at' => now(),
                // simpan catatan admin bila ada (opsional)
                'admin_note'  => $request->filled('admin_note') ? trim($request->input('admin_note')) : null,
            ]);

        return redirect()
            ->route('admin.pengajuan.show', $id)
            ->with('ok', 'Pengajuan berhasil disetujui.');
    }

    public function reject(Request $request, int $id)
    {
        $this->setTimezone();
        $this->autofinishRequests();

        $data = DB::table('borrow_requests')->where('id', $id)->first();
        abort_if(!$data, 404, 'Pengajuan tidak ditemukan');

        // 1) Hanya boleh reject kalau masih menunggu
        if ($data->status !== 'menunggu') {
            return back()->with('err', "Pengajuan sudah diproses (status: {$data->status}).");
        }

        // 2) Admin wajib nunggu ACC Kemahasiswaan
        if ($this->mustWaitKema($data)) {
            return back()->with('err', 'Admin belum bisa menolak. Menunggu keputusan Kemahasiswaan.');
        }

        // 3) Note wajib diisi agar jelas alasan penolakan (boleh kamu ubah jadi opsional)
        $request->validate([
            'admin_note' => ['required', 'string', 'max:500'],
        ], [
            'admin_note.required' => 'Alasan penolakan wajib diisi.',
            'admin_note.max'      => 'Alasan penolakan maksimal 500 karakter.',
        ]);

        $note = trim((string) $request->input('admin_note'));

        DB::table('borrow_requests')
            ->where('id', $id)
            ->where('status', 'menunggu')
            ->update([
                'status'     => 'ditolak',
                'admin_note' => $note,
            ]);

        return redirect()
            ->route('admin.pengajuan.show', $id)
            ->with('ok', 'Pengajuan ditolak.');
    }

    /**
     * Admin hanya boleh proses kalau:
     * - status masih menunggu
     * - kema_status sudah disetujui
     * - (opsional) kema_status bukan ditolak
     */
    private function adminCanProcess(object $data): bool
    {
        $kemaStatus = (string) ($data->kema_status ?? 'menunggu');

        return $data->status === 'menunggu'
            && $kemaStatus === 'disetujui';
    }

    /**
     * Admin harus menunggu jika kema_status belum disetujui.
     */
    private function mustWaitKema(object $data): bool
    {
        $kemaStatus = (string) ($data->kema_status ?? 'menunggu');

        // kema_status: menunggu / disetujui / ditolak
        return $kemaStatus !== 'disetujui';
    }

    private function autofinishRequests(): void
    {
        // jika sudah disetujui dan end_time lewat, otomatis selesai
        DB::table('borrow_requests')
            ->where('status', 'disetujui')
            ->whereRaw('end_time < NOW()')
            ->update([
                'status' => 'selesai',
            ]);
    }

    private function setTimezone(): void
    {
        DB::statement("SET time_zone = '+07:00'");
    }

    private function statusLabel(string $status): array
    {
        $map = [
            'menunggu'  => ['Menunggu',  'badge-wait'],
            'disetujui' => ['Disetujui', 'badge-ok'],
            'ditolak'   => ['Ditolak',   'badge-no'],
            'selesai'   => ['Selesai',   'badge-done'],
            'hangus'    => ['Hangus',    'badge-no'],
        ];

        return $map[$status] ?? ['-', 'badge-wait'];
    }

    private function kemaStatusLabel(string $kemaStatus): array
    {
        $map = [
            'menunggu'  => ['Menunggu Kema',  'badge-wait'],
            'disetujui' => ['ACC Kema',       'badge-ok'],
            'ditolak'   => ['Ditolak Kema',   'badge-no'],
        ];

        return $map[$kemaStatus] ?? ['-', 'badge-wait'];
    }
}
