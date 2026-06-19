<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DoctorScheduleController;

Route::prefix('v1')->group(function () {
    Route::get('/schedules', [DoctorScheduleController::class, 'index']);
    Route::get('/schedules/{id}', [DoctorScheduleController::class, 'show']);
    Route::post('/schedules', [DoctorScheduleController::class, 'store']);
});