<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Auth\AuthController;
use App\Http\Controllers\Web\Ad\CategoryController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Web\Ad\AdController;

Route::get('/ads', [AdController::class, 'index'])->name('ads.index')->middleware(['auth','verified']);
Route::get('/ads/create', [AdController::class, 'create'])->name('ads.create')->middleware(['auth','verified']);
Route::post('/ads', [AdController::class, 'store'])->name('ads.store')->middleware(['auth','verified']);
Route::get('/ads/{id}/edit', [AdController::class, 'edit'])->name('ads.edit')->middleware(['auth','verified']);
Route::put('/ads/{id}', [AdController::class, 'update'])->name('ads.update')->middleware(['auth','verified']);
Route::delete('/ads/{id}', [AdController::class, 'destroy'])->name('ads.destroy')->middleware(['auth','verified']);
