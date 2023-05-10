<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;



Route::controller(CommentController::class)->group(function () {
    Route::get('/{ad_id}', 'index')->middleware(['auth','verified']);
    Route::post('/{ad_id}', 'create')->middleware(['auth','verified']);
    Route::post('/{ad_id}/{parent_id}', 'replyComment')->middleware(['auth','verified']);
    Route::delete('/{id}', 'destroy')->middleware(['auth','verified']);
//    Route::get('/show/{id}', 'show')->middleware(['auth','verified']);
//    Route::put('/{id}', 'update')->middleware(['auth','verified']);
});
