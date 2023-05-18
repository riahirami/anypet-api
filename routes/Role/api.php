<?php

use App\Http\Controllers\RoleController;
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

Route::middleware(['auth.jwt', 'verified', 'role'])->group(function () {
    Route::put('setadmin/{id}',[RoleController::class, 'setAdmin'])->name('set_Admin');
    Route::put('revokeadmin/{id}',[RoleController::class, 'revokeAdmin'])->name('revoke_Admin');
});
