<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Ad\CategoryController;



Route::controller(CategoryController::class)->group(function () {
Route::get('/', 'index')->middleware(['auth','verified']);
Route::post('/', 'store')->middleware(['auth','verified']);
Route::get('/{id}', 'show')->middleware(['auth','verified']);
Route::put('/{id}', 'update')->middleware(['auth','verified']);
Route::delete('/{id}', 'destroy')->middleware(['auth','verified']);
});
