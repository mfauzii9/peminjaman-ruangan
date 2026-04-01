<?php

namespace App\Http\Controllers;

use App\Models\BorrowRequest;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * CEK PENGAJUAN berdasarkan KODE BOOKING (public_code)
     * URL: /riwayat?code=LPKIA-XXXXXX
     * View: resources/views/history/index.blade.php
     */
    public function index(Request $request)
    {
        $code = strtoupper(trim((string) $request->query('code', '')));

        $searched = $code !== '';
        $detail   = null;
        $errorMsg = null;
        $badge    = 'wait';

        if ($searched) {
            $detail = BorrowRequest::query()
                ->leftJoin('rooms as r', 'r.id', '=', 'borrow_requests.room_id')
                ->where('borrow_requests.public_code', $code)
                ->first([
                    'borrow_requests.*',
                    'r.name as room_name',
                    'r.floor as room_floor',
                ]);

            if (!$detail) {
                $errorMsg = 'Data tidak ditemukan. Pastikan kode booking benar.';
            } else {
                $status = strtolower((string) ($detail->status ?? 'menunggu'));
                $kema   = strtolower((string) ($detail->kema_status ?? 'menunggu'));

                if ($status === 'ditolak' || $kema === 'ditolak') $badge = 'err';
                elseif ($status === 'disetujui') $badge = 'ok';
                elseif ($status === 'selesai') $badge = 'ok';
                elseif ($status === 'hangus') $badge = 'err';
                else $badge = 'wait';
            }
        }

        return view('history.index', [
            'code'     => $code,
            'searched' => $searched,
            'detail'   => $detail,
            'errorMsg' => $errorMsg,
            'badge'    => $badge,
        ]);
    }

    /**
     * OPTIONAL: /riwayat/{code} redirect ke /riwayat?code=...
     */
    public function show(Request $request, string $code)
    {
        return redirect()->route('history.index', ['code' => $code]);
    }
}