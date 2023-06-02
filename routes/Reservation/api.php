<?php

use App\Http\Controllers\Web\Ad\ReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/create', [ReservationController::class, 'store'])->middleware('auth', 'verified');
Route::get('/show/myreservation', [ReservationController::class, 'getMyreservation'])->middleware('auth', 'verified');
Route::get('/show/ad/{id}', [ReservationController::class, 'getAdreservation'])->middleware('auth', 'verified');
Route::put('/response/{id}', [ReservationController::class, 'ResponseReservation'])->middleware('auth', 'verified');


