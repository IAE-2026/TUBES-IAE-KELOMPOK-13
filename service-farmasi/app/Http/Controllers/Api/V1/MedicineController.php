<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Medicine;

class MedicineController extends Controller
{
    public function index()
    {
        $daftarObat = Medicine::all();
        return response()->json([
            'status'  => 'success',
            'message' => 'Data berhasil diambil',
            'data'    => $daftarObat,
            'meta'    => ['nama_service' => 'pharmacy-service', 'versi_api' => 'v1']
        ]);
    }

    public function show($id)
    {
        $obat = Medicine::find($id);
        if (!$obat) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Obat tidak ditemukan',
                'errors'  => null
            ], 404);
        }
        return response()->json([
            'status'  => 'success',
            'message' => 'Data berhasil diambil',
            'data'    => $obat,
            'meta'    => ['nama_service' => 'pharmacy-service', 'versi_api' => 'v1']
        ]);
    }
}
