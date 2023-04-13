<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Ad\AdController;


Route::get('/', [AdController::class, 'index'])->name('ads.index')->middleware(['auth','verified']);
Route::get('/{id}', [AdController::class, 'show'])->name('ads.find')->middleware(['auth','verified']);
Route::get('/date/{date}', [AdController::class, 'getByDate'])->name('ads.bydate')->middleware(['auth','verified']);
Route::get('/category/{id}', [AdController::class, 'getByCategory'])->name('ads.bycategory')->middleware(['auth','verified']);
Route::post('/', [AdController::class, 'store'])->name('ads.store')->middleware(['auth','verified']);
Route::put('/{id}', [AdController::class, 'update'])->name('ads.update');
Route::delete('/{id}', [AdController::class, 'destroy'])->name('ads.destroy')->middleware(['auth','verified']);
