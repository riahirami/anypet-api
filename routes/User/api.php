<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\User\UserController;


Route::get('/', [UserController::class, 'index'])->middleware('role')->name('user.index');

Route::middleware(['auth.jwt','verified'])->group(function(){
    Route::get('/verified', [UserController::class, 'showVerifiedUsers'])->name('users.verifiedUserscompt');
    Route::get('/{id}', [UserController::class, 'show'])->name('user.show');
 });
