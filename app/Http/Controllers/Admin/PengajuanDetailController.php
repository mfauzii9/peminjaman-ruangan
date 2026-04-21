<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\BorrowRequest;

// Menggunakan Mailable yang baru dibuat untuk Antrean (Queue)
use App\Mail\EmailUntukMahasiswa;

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

        // Auto hangus jika masih menunggu namun sudah lewat waktu (end_time)
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
    | APPROVE ADMIN (AJAX SUPPORTED)
    |--------------------------------------------------------------------------
    */
    public function approve(Request $request, $id)
    {
        $this->setDbTimezone();

        $req = BorrowRequest::with('room')->findOrFail($id);

        if (($req->kema_status ?? 'menunggu') !== 'disetujui') {
            return $this->ajaxResponse($request, false, 'Tidak bisa approve. Kemahasiswaan belum menyetujui pengajuan ini.');
        }

        if (($req->status ?? 'menunggu') === 'disetujui') {
            return $this->ajaxResponse($request, false, 'Pengajuan sudah disetujui admin sebelumnya.');
        }

        if ($req->end_time && $req->end_time < now()) {
            $req->status = 'hangus';
            $req->save();
            return $this->ajaxResponse($request, false, 'Pengajuan sudah lewat waktu (hangus).');
        }

        $note = trim((string) $request->input('admin_note', ''));
        $note = $note !== '' ? $note : null;

        $req->status      = 'disetujui';
        $req->approved_at = now();
        $req->admin_note  = $note;
        $req->save();

        // PERBAIKAN: Gunakan queue() agar Admin tidak loading lama
        if (!empty($req->email)) {
            Mail::to($req->email)->queue(new EmailUntukMahasiswa($req, 'status_akhir'));
        }

        return $this->ajaxResponse($request, true, 'Pengajuan disetujui oleh Admin.');
    }

    /*
    |--------------------------------------------------------------------------
    | REJECT ADMIN (AJAX SUPPORTED)
    |--------------------------------------------------------------------------
    */
    public function reject(Request $request, $id)
    {
        $this->setDbTimezone();

        $req = BorrowRequest::findOrFail($id);

        if (($req->status ?? 'menunggu') === 'disetujui') {
            return $this->ajaxResponse($request, false, 'Pengajuan sudah disetujui admin, tidak bisa ditolak.');
        }

        $note = trim((string) $request->input('admin_note', ''));
        if ($note === '') {
            $note = 'Ditolak oleh Admin.';
        }

        $req->status = 'ditolak';
        $req->admin_note = $note;
        $req->save();

        // PERBAIKAN: Gunakan queue() agar Admin tidak loading lama
        if (!empty($req->email)) {
            Mail::to($req->email)->queue(new EmailUntukMahasiswa($req, 'status_akhir'));
        }

        return $this->ajaxResponse($request, true, 'Pengajuan berhasil ditolak oleh Admin.');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */
    private function setDbTimezone()
    {
        DB::statement("SET time_zone = '{$this->tz}'");
    }

    private function ajaxResponse($request, $success, $message)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => $success, 'message' => $message]);
        }

        // Fallback jika tidak menggunakan AJAX
        return $success ? back()->with('ok', $message) : back()->with('err', $message);
    }
}