<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pasien', 
        'id_kunjungan', 
        'nama_dokter', 
        'status',
        'receipt_number'
    ];

    public function items()
    {
        return $this->hasMany(PrescriptionItem::class, 'id_resep');
    }
}
