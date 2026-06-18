<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    protected $fillable = [
        'doctor_name',
        'specialization',
        'schedule_date',
        'start_time',
        'end_time',
        'status'
    ];
}