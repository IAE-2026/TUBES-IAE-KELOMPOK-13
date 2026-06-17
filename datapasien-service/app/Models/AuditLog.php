<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'activity_name',
        'receipt_number',
        'log_data',
        'success',
    ];

    protected $casts = [
        'log_data' => 'array',
        'success'  => 'boolean',
    ];
}