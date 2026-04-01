<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowRequest extends Model
{
    protected $table = 'borrow_requests';

    protected $fillable = [
        'public_code',
        'token_hash',
        'token_created_at',

        'room_id',
        'responsible_name',
        'org_name',
        'email',
        'phone',
        'start_time',
        'end_time',
        'letter_file',

        'status',
        'admin_note',
        'approved_at',

        'kema_status',
        'kema_note',
        'kema_approved_at',
        'kema_approved_by',
    ];

    protected $casts = [
        'approved_at'      => 'datetime',
        'kema_approved_at' => 'datetime',
        'start_time'       => 'datetime',
        'end_time'         => 'datetime',
        'token_created_at' => 'datetime',
    ];

    /**
     * Relasi ke Room (WAJIB kalau kamu pakai with('room') di controller Kema/Admin)
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    /**
     * Helper status Kemahasiswaan
     * (kema_status: menunggu / disetujui / ditolak)
     */
    public function isKemaApproved(): bool
    {
        return strtolower((string) $this->kema_status) === 'disetujui';
    }

    public function isWaitingKema(): bool
    {
        return strtolower((string) $this->kema_status) === 'menunggu';
    }

    public function isKemaRejected(): bool
    {
        return strtolower((string) $this->kema_status) === 'ditolak';
    }
}
