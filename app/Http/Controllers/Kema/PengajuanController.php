<?php

namespace App\Http\Controllers\Kema;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Models\BorrowRequest;

use App\Notifications\KemaApproved;
use App\Notifications\KemaRejected;

class PengajuanController extends Controller
{
    private $tz = '+07:00';
    private const ADMIN_EMAIL = 'rizkyfauzi040507@gmail.com';

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD / INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $this->setDbTimezone();
        $this->autoExpireKema();

        return view('kema.pengajuan.index');
    }

    /*
    |--------------------------------------------------------------------------
    | ✅ RIWAYAT (TAMBAHAN)
    |--------------------------------------------------------------------------
    */

    public function riwayat()
    {
        $this->setDbTimezone();
        $this->autoExpireKema();

        return view('kema.pengajuan.riwayat');
    }

    public function riwayatList(Request $request)
    {
        $this->setDbTimezone();
        $this->autoExpireKema();

        $date = (string) $request->get('date', '');
        $q    = trim((string) $request->get('q', ''));

        $query = BorrowRequest::query()
            ->with('room')
            ->whereIn('kema_status', ['disetujui', 'ditolak', 'hangus']);

        if ($date !== '') {
            $query->whereDate('start_time', $date);
        }

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('responsible_name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('org_name', 'like', "%{$q}%")
                  ->orWhere('public_code', 'like', "%{$q}%");
            });
        }

        $rows = $query->orderByDesc('created_at')->limit(300)->get();

        $items = $rows->map(function ($r) {
            return array(
                'id' => $r->id,
                'public_code' => $r->public_code,

                'room_floor' => optional($r->room)->floor,
                'room_name'  => optional($r->room)->name,

                'responsible_name' => $r->responsible_name,
                'email' => $r->email,
                'org_name' => $r->org_name,

                'start_time' => $r->start_time ? $r->start_time->format('Y-m-d H:i:s') : null,
                'end_time'   => $r->end_time ? $r->end_time->format('Y-m-d H:i:s') : null,

                'status'      => $r->status,
                'kema_status' => $r->kema_status,
            );
        });

        return response()->json(array(
            'ok' => true,
            'items' => $items,
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | LIST (VERIFIKASI)
    |--------------------------------------------------------------------------
    */

    public function list(Request $request)
    {
        $this->setDbTimezone();
        $this->autoExpireKema();

        $status = (string) $request->get('status', 'menunggu');
        $date   = (string) $request->get('date', '');
        $q      = trim((string) $request->get('q', ''));

        $query = BorrowRequest::query()->with('room');

        if ($status !== 'all') {
            $query->where('kema_status', $status);
        }

        if ($date !== '') {
            $query->whereDate('start_time', $date);
        }

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('responsible_name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('org_name', 'like', "%{$q}%")
                    ->orWhere('public_code', 'like', "%{$q}%");
            });
        }

        $rows = $query->orderByDesc('created_at')->limit(300)->get();

        $items = $rows->map(function ($r) {
            return array(
                'id' => $r->id,
                'public_code' => $r->public_code,
                'room_floor' => optional($r->room)->floor,
                'room_name'  => optional($r->room)->name,
                'responsible_name' => $r->responsible_name,
                'email' => $r->email,
                'org_name' => $r->org_name,
                'start_time' => $r->start_time ? $r->start_time->format('Y-m-d H:i:s') : null,
                'end_time'   => $r->end_time ? $r->end_time->format('Y-m-d H:i:s') : null,
                'status'      => $r->status,
                'kema_status' => $r->kema_status,
            );
        });

        $countsRaw = BorrowRequest::query()
            ->selectRaw("
                SUM(kema_status='menunggu')  AS menunggu,
                SUM(kema_status='disetujui') AS disetujui,
                SUM(kema_status='ditolak')   AS ditolak,
                SUM(kema_status='hangus')    AS hangus
            ")
            ->first();

        $counts = array(
            'menunggu'  => (int) ($countsRaw->menunggu ?? 0),
            'disetujui' => (int) ($countsRaw->disetujui ?? 0),
            'ditolak'   => (int) ($countsRaw->ditolak ?? 0),
            'hangus'    => (int) ($countsRaw->hangus ?? 0),
        );

        return response()->json(array(
            'ok' => true,
            'items' => $items,
            'counts' => $counts,
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW DETAIL
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $this->setDbTimezone();
        $this->autoExpireKema();

        $data = BorrowRequest::with('room')->findOrFail($id);
        return view('kema.pengajuan.show', compact('data'));
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function approve(Request $request, $id)
    {
        $this->setDbTimezone();
        $this->autoExpireKema();

        $req = BorrowRequest::with('room')->findOrFail($id);

        if ((string) ($req->kema_status ?? 'menunggu') !== 'menunggu') {
            return $this->respondFail($request, 'Pengajuan sudah diproses.');
        }

        if ($req->end_time && $req->end_time->lt(now())) {
            $req->kema_status = 'hangus';
            $req->save();
            return $this->respondFail($request, 'Pengajuan sudah lewat waktu (hangus).');
        }

        $note = trim((string) $request->input('kema_note', ''));

        $req->kema_status = 'disetujui';
        $req->kema_note = ($note !== '') ? $note : null;
        $req->kema_approved_at = now();
        $req->kema_approved_by = session('kema_name')
            ?? session('admin_name')
            ?? session('kema_username')
            ?? 'kema';

        $req->save();

        $roomLabel = trim(
            (optional($req->room)->floor ?? '-') . ' - ' .
            (optional($req->room)->name ?? '-')
        );

        $startText = $req->start_time ? $req->start_time->format('d M Y H:i') : '-';
        $endText   = $req->end_time ? $req->end_time->format('d M Y H:i') : '-';

        if (!empty($req->email)) {
            Notification::route('mail', $req->email)
                ->notify(new KemaApproved($req->id, $roomLabel, $startText, $endText));
        }

        Notification::route('mail', self::ADMIN_EMAIL)
            ->notify(new KemaApproved($req->id, $roomLabel, $startText, $endText));

        return $this->respondOk($request, 'Pengajuan disetujui Kemahasiswaan.');
    }

    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    public function reject(Request $request, $id)
    {
        $this->setDbTimezone();
        $this->autoExpireKema();

        $req = BorrowRequest::with('room')->findOrFail($id);

        if ((string) ($req->kema_status ?? 'menunggu') !== 'menunggu') {
            return $this->respondFail($request, 'Pengajuan sudah diproses.');
        }

        if ($req->end_time && $req->end_time->lt(now())) {
            $req->kema_status = 'hangus';
            $req->save();
            return $this->respondFail($request, 'Pengajuan sudah lewat waktu (hangus).');
        }

        $note = trim((string) $request->input('kema_note', ''));
        if ($note === '') {
            $note = 'Ditolak oleh Kemahasiswaan.';
        }

        $req->kema_status = 'ditolak';
        $req->kema_note = $note;
        $req->kema_approved_at = now();
        $req->kema_approved_by = session('kema_name')
            ?? session('admin_name')
            ?? session('kema_username')
            ?? 'kema';

        $req->save();

        return $this->respondOk($request, 'Pengajuan ditolak oleh Kemahasiswaan.');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    private function autoExpireKema()
    {
        DB::table('borrow_requests')
            ->where('kema_status', 'menunggu')
            ->whereNotNull('end_time')
            ->whereRaw('end_time < NOW()')
            ->update(['kema_status' => 'hangus']);
    }

    private function setDbTimezone()
    {
        DB::statement("SET time_zone = '{$this->tz}'");
    }

    private function respondOk(Request $request, $message)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'message' => $message]);
        }
        return back()->with('ok', $message);
    }

    private function respondFail(Request $request, $message)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => false, 'message' => $message], 422);
        }
        return back()->with('err', $message);
    }
}