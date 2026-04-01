<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ScheduleDetail;

class Room extends Model
{
    protected $table = 'rooms';

    // ✅ karena tabel rooms tidak punya created_at & updated_at
    public $timestamps = false;

    protected $fillable = [
        'floor',
        'name',
        'capacity',
        'facilities',
        'photo',
    ];
    public function scheduleDetails()
{
    return $this->hasMany(ScheduleDetail::class);
}
}
