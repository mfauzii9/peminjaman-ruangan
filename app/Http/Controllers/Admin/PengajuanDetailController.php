<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Models\BorrowRequest;

use App\Notifications\BorrowApproved;
use App\Notifications\BorrowRejected;

class PengajuanDetailController extends Controller
{
    private $tz = '+07:00';

    /*
    |--------------------------------------------------------------------------
    | HALAMAN DETAIL
    |--------------------------------------------------------------------------
    */
    public function confirm($id)
    {
        $this->setDbTimezone();

        $data = BorrowRequest::with('room')->findOrFail($id);

        // Auto hangus jika masih menunggu dan sudah lewat waktu
        if (($data->status ?? 'menunggu') === 'menunggu') {
            if ($data->end_time && $data->end_time < now()) {
                $data->status = 'hangus';
                $data->save();
            }
        }

        return view('admin.pengajuan_confirm', compact('data'));
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVE ADMIN
    |--------------------------------------------------------------------------
    */
    public function approve(Request $request, $id)
    {
        $this->setDbTimezone();

        $req = BorrowRequest::with('room')->findOrFail($id);

        // Harus sudah disetujui Kemahasiswaan
        if (($req->kema_status ?? 'menunggu') !== 'disetujui') {
            return back()->with('err', 'Tidak bisa approve. Kemahasiswaan belum menyetujui pengajuan ini.');
        }

        // Sudah disetujui sebelumnya
        if (($req->status ?? 'menunggu') === 'disetujui') {
            return back()->with('err', 'Pengajuan sudah disetujui admin sebelumnya.');
        }

        // Jika sudah lewat waktu
        if ($req->end_time && $req->end_time < now()) {
            $req->status = 'hangus';
            $req->save();
            return back()->with('err', 'Pengajuan sudah lewat waktu (hangus).');
        }

        // Ambil catatan admin (opsional)
        $note = trim((string) $request->input('admin_note', ''));
        $note = $note !== '' ? $note : null;

        // ✅ Simpan status + admin_note
        $req->status      = 'disetujui';
        $req->approved_at = now();
        $req->admin_note  = $note; // <-- kolom tabel admin_note
        $req->save();

        // ==========================
        // ✅ KIRIM EMAIL KE MAHASISWA
        // ==========================
        if (!empty($req->email)) {

            $roomLabel = trim(
                ($req->room->floor ?? '-') . ' - ' . ($req->room->name ?? '-')
            );

            $startText = $req->start_time
                ? $req->start_time->format('d M Y H:i')
                : '-';

            $endText = $req->end_time
                ? $req->end_time->format('d M Y H:i')
                : '-';

            Notification::route('mail', $req->email)
                ->notify(new BorrowApproved(
                    $req->id,
                    $roomLabel,
                    $startText,
                    $endText,
                    $note
                ));
        }

        return back()->with('ok', 'Pengajuan disetujui oleh Admin dan email terkirim.');
    }

    /*
    |--------------------------------------------------------------------------
    | REJECT ADMIN
    |--------------------------------------------------------------------------
    */
    public function reject(Request $request, $id)
    {
        $this->setDbTimezone();

        $req = BorrowRequest::findOrFail($id);

        if (($req->status ?? 'menunggu') === 'disetujui') {
            return back()->with('err', 'Pengajuan sudah disetujui admin, tidak bisa ditolak.');
        }

        $note = trim((string) $request->input('admin_note', ''));
        if ($note === '') {
            $note = 'Ditolak oleh Admin.';
        }

        // ✅ Simpan status + admin_note
        $req->status = 'ditolak';
        $req->admin_note = $note; // <-- kolom tabel admin_note
        $req->save();

        // ==========================
        // ✅ EMAIL KE MAHASISWA
        // ==========================
        if (!empty($req->email)) {
            Notification::route('mail', $req->email)
                ->notify(new BorrowRejected($req->id, $note));
        }

        return back()->with('ok', 'Pengajuan ditolak oleh Admin.');
    }

    /*
    |--------------------------------------------------------------------------
    | SET TIMEZONE DATABASE
    |--------------------------------------------------------------------------
    */
    private function setDbTimezone()
    {
        DB::statement("SET time_zone = '{$this->tz}'");
    }
}