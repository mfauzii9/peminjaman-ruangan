<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleDetail extends Model
{
    protected $fillable = [
        'room_id',
        'day',
        'time_start',
        'time_end',
        'course_name',
        'class_name',
        'lecturer',
        'raw_text',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}