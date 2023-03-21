<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Auth\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/users', [UserController::class, 'showUsers'])->name('user.list');
Route::get('/users/{id}', [UserController::class, 'getUser'])->name('user.byid');
// Route::get('/user/add/{name}/{login}/{email}/{password}/{phone}/{address}', [UserController::class,'createUser'])->name('user.create');
Route::get('/user/registre', [UserController::class,'registreUser'])->name('user.create');
Route::post('/user/create', [UserController::class,'saveUser']);
Route::get('/user/edit/{id}', [UserController::class,'getUser'])->name('user.edit');
Route::put('/user/edit/{id}', [UserController::class,'saveUser'])->name('user.update');
Route::get('/user/delete/{id}', [UserController::class,'deleteUser'])->name('user.delete');

Route::get('/anypet', function () {
    return view('welcome');
});

