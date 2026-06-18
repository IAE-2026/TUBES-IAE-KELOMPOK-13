<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
    'nama',
    'kategori',
    'deskripsi',
    'stock',
    'harga',
    'satuan'
    ];
}
