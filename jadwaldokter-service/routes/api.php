<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DoctorScheduleController;

Route::prefix('v1')->group(function () {
    Route::get('/doctor-schedules', [DoctorScheduleController::class, 'index']);
    Route::get('/doctor-schedules/{id}', [DoctorScheduleController::class, 'show']);
    Route::post('/doctor-schedules', [DoctorScheduleController::class, 'store']);
});