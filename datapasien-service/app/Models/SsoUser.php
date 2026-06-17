<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SsoUser extends Model
{
    protected $fillable = [
        'sub',
        'email',
        'name',
        'local_role',
        'last_token',
        'last_login_at',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];
}