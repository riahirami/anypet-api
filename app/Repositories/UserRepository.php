<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class UserRepository
{

    protected $user = null;

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    static function index(array $data)
    {
        $perPage = $data['perpage'] ?? config('constants.PER_PAGE');
        $orderBy = $data['orderBy'] ?? config('constants.ORDER_BY');
        $orderDirection = $data['order_direction'] ?? config('constants.ORDER_DIRECTION');
        $page = $data['page'] ?? config('constants.PAGE');
        $connectedUserId = auth()->user()->id;
        return User::query()
            ->where('id', '!=', $connectedUserId)
            ->orderBy($orderBy, $orderDirection)
            ->paginate($perPage, ['*'], $page);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function show($id)
    {
//        $user = User::with('ads', 'comments')->find($id);
        $user = User::with('ads.media', 'comments.user')->find($id);

        return $user;
    }

    /**
     * @return mixed
     */
    public function verifiedUsers()
    {
        $users = User::VerifiedEmail()->get();

        return $users;
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function getNotifications($user_id)
    {
        $user = User::find($user_id);
        $notifications = $user->userNotifications()->get();
//        if ($notifications && $notifications->notifiable_id !== $user->id) {
//            return response()->json(['error' => 'Unauthorized'], 401);
//        }
        return $notifications;
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function unreadNotifications($user_id)
    {
        $user = User::find($user_id);
        $notifications = $user->userNotifications()->unread()->get();
//        if ($notifications && $notifications->notifiable_id !== $user->id) {
//            return response()->json(['error' => 'Unauthorized'], 401);
//
//        }
            return $notifications;

    }

    /**
     * @param $notificationId
     * @return mixed
     */
    public function markOneNotificationsAsRead($notificationId)
    {
        $notification = DatabaseNotification::findOrFail($notificationId);

        $user = Auth::user();
        if ($notification && $notification->notifiable_id === $user->id) {
            $notification->markAsRead();
            return response()->json(['error' => 'Unauthorized'], 401);

        }
        return $notification;

    }

    /**
     * @return mixed
     */
    public function markAllNotificationsAsRead()
    {
        $user = Auth::user();
        $notifications = $user->unreadNotifications($user->id)->get();
        $notifications->markAsRead();
        return $notifications;

    }
}
