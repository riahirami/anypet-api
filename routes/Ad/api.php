<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Ad\AdController;

Route::put('/{id}', [AdController::class, 'update'])->name('ads.update');

Route::middleware(['auth.jwt','verified'])->group(function(){
    Route::get('/', [AdController::class, 'index'])->name('ads.index');
    Route::post('/', [AdController::class, 'store'])->name('ads.store');
    Route::get('/requestad', [AdController::class, 'requestAds'])->name('ads.requestads');
    Route::get('/stats', [AdController::class, 'getAdsStats'])->name('ads.statsads');
    Route::get('/statsdate', [AdController::class, 'getCountAdsPerDate'])->name('ads.statsdata');
    Route::delete('/{id}', [AdController::class, 'destroy'])->name('ads.destroy');
    Route::get('/{id}', [AdController::class, 'show'])->name('ads.find');
    Route::get('/date/{date}', [AdController::class, 'getByDate'])->name('ads.bydate');
    Route::get('/category/{id}', [AdController::class, 'getByCategory'])->name('ads.bycategory');
    Route::get('/status/{status}', [AdController::class, 'getAdsByStatus'])->name('ads.bystatus');
    Route::get('/search/{key}', [AdController::class, 'getAdsByString'])->name('ads.bykey');
});
