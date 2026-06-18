<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrescriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_resep',
        'id_obat',
        'jumlah',
        'dosis'
    ];

    public function resep()
    {
        return $this->belongsTo(Prescription::class, 'id_resep');
    }

    public function obat()
    {
        return $this->belongsTo(Medicine::class, 'id_obat');
    }
}
