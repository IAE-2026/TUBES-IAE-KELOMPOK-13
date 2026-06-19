<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\MedicineController;
use App\Http\Controllers\Api\V1\PrescriptionController;

    // Protected Endpoints (SSO Authentication)
Route::prefix('v1')->group(function () {
    Route::middleware('auth.sso')->group(function () {
        Route::post('/prescriptions', [PrescriptionController::class, 'store']);
        Route::get('/prescriptions', [PrescriptionController::class, 'index']);
        Route::get('/prescriptions/{id}', [PrescriptionController::class, 'show']);

        Route::get('/medicines', [MedicineController::class, 'index']);
        Route::get('/medicines/{id}', [MedicineController::class, 'show']);
    });
});