<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\User\UserController;


Route::middleware(['auth.jwt','verified'])->group(function(){
    Route::get('/verified', [UserController::class, 'showVerifiedUsers'])->name('users.verifiedUserscompt');
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::get('/{id}', [UserController::class, 'show'])->name('user.show');
 });
