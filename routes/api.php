<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Auth\AuthController;
use App\Http\Controllers\Web\Ad\CategoryController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\ForgotPasswordController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->name('login');
    Route::post('register', 'register');
    Route::post('auth/logout', 'logout')->middleware(['auth']);
    Route::post('auth/refresh', 'refresh')->middleware(['auth']);
    Route::post('profile', 'profile')->middleware(['auth','verified']);
//    Route::get('email/verify', 'me')->middleware('auth')->name('verification.notice');
});
Route::get('/email/verify',[AuthController::class,'profile']) ->middleware('auth')->name('verification.notice');



Route::get('email/verify/{id}/{hash}', [AuthController::class,'SendEmailVerification'])->name('verification.verify');
Route::post('email/resend-verification', [AuthController::class,'ResendEmailVerification'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'reset'])->name('password.reset');

Route::controller(CategoryController::class)->group(function () {
    Route::get('categories', 'index')->middleware(['auth','verified']);
    Route::post('addcategory', 'store')->middleware(['auth','verified']);
    Route::get('show/categories/{id}', 'show')->middleware(['auth','verified']);
    Route::put('update/categories/{id}', 'update')->middleware(['auth','verified']);
    Route::delete('delete/categories/{id}', 'destroy')->middleware(['auth','verified']);
});
//Auth::routes([
//    'verify' => true
//]);
