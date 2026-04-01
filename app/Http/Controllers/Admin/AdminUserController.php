<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    // halaman 1 file: profil + kelola akun
    public function index()
    {
        $users = DB::table('admin_users')
            ->select('id','username','role','created_at')
            ->orderBy('id','desc')
            ->get();

        // profil dari session
        $me = [
            'id' => session('admin_id'),
            'username' => session('admin_username', session('username', 'admin')),
            'role' => session('role', 'admin'),
        ];

        return view('admin.accounts', compact('users','me'));
    }

    // ambil 1 user (untuk modal edit via AJAX)
    public function show($id)
    {
        $u = DB::table('admin_users')
            ->select('id','username','role','created_at')
            ->where('id', (int)$id)
            ->first();

        if (!$u) return response()->json(['message' => 'User tidak ditemukan'], 404);
        return response()->json($u);
    }

    // create akun (admin/kemahasiswaan)
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|max:40',
            'password' => 'required|string|min:4|max:80',
            'role'     => 'required|in:admin,kemahasiswaan',
        ]);

        $exists = DB::table('admin_users')->where('username', $request->username)->exists();
        if ($exists) {
            return response()->json(['message' => 'Username sudah dipakai'], 422);
        }

        DB::table('admin_users')->insert([
            'username'      => $request->username,
            'password_hash' => Hash::make($request->password),
            'role'          => $request->role,
            'created_at'    => now(),
        ]);

        return response()->json(['message' => 'Akun berhasil dibuat']);
    }

    // update akun (username/role, optional password)
    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|min:3|max:40',
            'role'     => 'required|in:admin,kemahasiswaan',
            'password' => 'nullable|string|min:4|max:80',
        ]);

        $id = (int)$id;

        $u = DB::table('admin_users')->where('id', $id)->first();
        if (!$u) return response()->json(['message' => 'User tidak ditemukan'], 404);

        $exists = DB::table('admin_users')
            ->where('username', $request->username)
            ->where('id', '<>', $id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Username sudah dipakai'], 422);
        }

        $data = [
            'username' => $request->username,
            'role'     => $request->role,
        ];

        if (!empty($request->password)) {
            $data['password_hash'] = Hash::make($request->password);
        }

        DB::table('admin_users')->where('id', $id)->update($data);

        return response()->json(['message' => 'Akun berhasil diupdate']);
    }

    // hapus akun
    public function destroy($id)
    {
        $id = (int)$id;

        // jangan izinkan hapus diri sendiri
        $meId = (int)session('admin_id');
        if ($meId && $id === $meId) {
            return response()->json(['message' => 'Tidak bisa menghapus akun yang sedang login'], 422);
        }

        $deleted = DB::table('admin_users')->where('id', $id)->delete();
        if (!$deleted) return response()->json(['message' => 'User tidak ditemukan'], 404);

        return response()->json(['message' => 'Akun berhasil dihapus']);
    }

    // update profil akun yang sedang login (username + password optional)
    public function updateProfile(Request $request)
    {
        $meId = (int)session('admin_id');
        if (!$meId) return response()->json(['message' => 'Session admin_id tidak ada'], 401);

        $request->validate([
            'username' => 'required|string|min:3|max:40',
            'password' => 'nullable|string|min:4|max:80',
        ]);

        $exists = DB::table('admin_users')
            ->where('username', $request->username)
            ->where('id', '<>', $meId)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Username sudah dipakai'], 422);
        }

        $data = ['username' => $request->username];

        if (!empty($request->password)) {
            $data['password_hash'] = Hash::make($request->password);
        }

        DB::table('admin_users')->where('id', $meId)->update($data);

        // update session biar sidebar ikut berubah
        session(['admin_username' => $request->username, 'username' => $request->username]);

        return response()->json(['message' => 'Profil berhasil disimpan']);
    }
}