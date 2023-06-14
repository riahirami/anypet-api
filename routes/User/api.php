<?php

use App\Http\Controllers\MesssageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\User\UserController;


Route::get('/', [UserController::class, 'index'])->middleware('role')->name('user.index');

Route::middleware(['auth.jwt', 'verified'])->group(function () {
    Route::get('/verified', [UserController::class, 'showVerifiedUsers'])->name('users.verifiedUserscompt');
    Route::get('/{id}', [UserController::class, 'show'])->name('user.show');
});


Route::get('notifications/{id}', [UserController::class, 'userNotifications'])->middleware('auth.jwt', 'verified')->name('user.userNotifications');
Route::get('notifications/unread/{id}', [UserController::class, 'userUnreadNotifications'])->middleware('auth.jwt', 'verified')->name('user.userUnreadNotifications');
Route::post('notifications/readone/{id}', [UserController::class, 'markOneNotificationAsRead'])->middleware('auth.jwt', 'verified')->name('user.notificationreadone');
Route::post('notifications/readall', [UserController::class, 'markAllNotificationsAsRead'])->middleware('auth.jwt', 'verified')->name('user.notificationreadall');


Route::post('message/{receiver_id}', [MesssageController::class, 'sendMessage'])->name('user.SendMessage');
Route::post('contact-us', [MesssageController::class, 'contactAdmin'])->name('user.contactAdmin');
Route::get('conversation/{user_id}', [MesssageController::class, 'getConversationMsg'])->name('user.conversationMsg');
Route::get('conversations/list', [MesssageController::class, 'conversations'])->name('user.getConversations');
