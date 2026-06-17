<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'name',
        'nik',
        'phone',
        'birth_date',
        'address',
        'allergies'
    ];
}
