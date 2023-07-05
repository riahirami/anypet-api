<?php

use App\Http\Controllers\PartnerController;
use Illuminate\Support\Facades\Route;

Route::controller(PartnerController::class)->group(function () {
Route::get('/', 'index')->middleware(['auth','verified']);
Route::post('/', 'create')->middleware(['auth','verified','role']);
Route::get('/{id}', 'show')->middleware(['auth','verified']);
Route::post('/update/{id}', 'update')->middleware(['auth','verified','role']);
Route::delete('/{id}', 'destroy')->middleware(['auth','verified','role']);
});
