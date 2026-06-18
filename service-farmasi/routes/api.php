<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\MedicineController;
use App\Http\Controllers\Api\V1\PrescriptionController;

Route::prefix('v1')->group(function () {

    // Protected Endpoints (SSO Authentication)
    Route::middleware('auth.sso')->group(function () {
        Route::post('/prescriptions', [PrescriptionController::class, 'store']);
        Route::get('/prescriptions', [PrescriptionController::class, 'index']);
        Route::get('/prescriptions/{id}', [PrescriptionController::class, 'show']);
    });

});