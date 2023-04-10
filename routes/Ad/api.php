<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Ad\AdController;

Route::get('/list', [AdController::class, 'index'])->name('ads.index')->middleware(['auth','verified']);
Route::get('/find/{id}', [AdController::class, 'show'])->name('ads.find')->middleware(['auth','verified']);
Route::get('/find/bycategory/{id}', [AdController::class, 'getByCategory'])->name('ads.bycategory')->middleware(['auth','verified']);
Route::get('/find/bydate/{date}', [AdController::class, 'getByDate'])->name('ads.bydate')->middleware(['auth','verified']);
Route::post('/add', [AdController::class, 'store'])->name('ads.store')->middleware(['auth','verified']);
Route::put('/update/{id}', [AdController::class, 'update'])->name('ads.update');
Route::delete('/delete/{id}', [AdController::class, 'destroy'])->name('ads.destroy')->middleware(['auth','verified']);
