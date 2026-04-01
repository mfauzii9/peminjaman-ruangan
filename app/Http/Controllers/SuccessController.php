<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuccessController extends Controller
{
    public function show(Request $request)
    {
        $code  = (string) $request->query('code', '');
        $token = (string) $request->query('token', '');

        $found = false;
        $req = null;
        $errorMsg = '';

        // wajib ada code & token
        if ($code === '' || $token === '') {
            $errorMsg = 'Kode/Token tidak ditemukan.';
            return view('success', compact('found', 'req', 'errorMsg', 'token', 'code'));
        }

        // ambil data request + nama ruangan
        $req = DB::table('borrow_requests as b')
            ->leftJoin('rooms as r', 'r.id', '=', 'b.room_id')
            ->where('b.public_code', $code)
            ->select(
                'b.*',
                'r.name as room_name',
                'r.floor as room_floor'
            )
            ->first();

        if (!$req) {
            $errorMsg = 'Pengajuan tidak ditemukan.';
            return view('success', compact('found', 'req', 'errorMsg', 'token', 'code'));
        }

        // validasi token (hash sha256)
        $tokenHash = hash('sha256', $token);
        if (!hash_equals((string) $req->token_hash, $tokenHash)) {
            $errorMsg = 'Token tidak valid / tidak cocok.';
            return view('success', compact('found', 'req', 'errorMsg', 'token', 'code'));
        }

        // sukses
        $found = true;

        return view('success', compact('found', 'req', 'errorMsg', 'token', 'code'));
    }
}
