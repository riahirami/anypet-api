<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Ad\AdController;

Route::middleware(['auth.jwt', 'verified', 'owner'])->group(function () {
    Route::delete('/{id}', [AdController::class, 'destroy'])->name('ads.destroy');
    Route::post('/updateads/{id}', [AdController::class, 'update'])->name('ads.update');

});

Route::middleware(['auth.jwt', 'verified', 'role'])->group(function () {
    Route::get('/stats', [AdController::class, 'getAdsStats'])->name('ads.statsads');
    Route::get('/statsdate', [AdController::class, 'getCountAdsPerDate'])->name('ads.statsdata');
    Route::get('/status/{status}', [AdController::class, 'getAdsByStatus'])->name('ads.bystatus');
    Route::get('/requestad', [AdController::class, 'requestAds'])->name('ads.requestads');

});

Route::middleware(['auth.jwt', 'verified'])->group(function () {
    Route::get('/mark-as', [AdController::class, 'markAsAdoptedOrReserved'])->name('ads.adoptedOrReserved');
    Route::get('/listfavorite/{id}', [AdController::class, 'getlistFavoriteAds'])->name('favorite-ads.list');
    Route::get('/myads/{id}', [AdController::class, 'getlistUserAds'])->name('user-ads.list');
    Route::get('/allmedia', [AdController::class, 'getAllMedia'])->name('allmedia.list');
    Route::get('/', [AdController::class, 'index'])->name('ads.index');
    Route::post('/', [AdController::class, 'store'])->name('ads.store');
    Route::get('/media/{ad_id}', [AdController::class, 'getMediaPerAds'])->name('ads.media');
    Route::get('/{id}', [AdController::class, 'show'])->name('ads.find');
    Route::get('/date/{date}', [AdController::class, 'getByDate'])->name('ads.bydate');
    Route::get('/category/{id}', [AdController::class, 'getByCategory'])->name('ads.bycategory');
    Route::get('/search/{key}', [AdController::class, 'getAdsByString'])->name('ads.bykey');
    Route::post('/setfavorite/{ad}', [AdController::class, 'setFavorite'])->name('favorite-ads.add');
});


