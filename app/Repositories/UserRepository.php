<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

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
        $user = User::with([
            'ads' => function ($query) {
                $query->whereIn('status', [2, 3, 4])->with('media');
            },
            'comments.user'
        ])->find($id);

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

        $notificationTypes = [
            'ReservationNotification' => [],
            'MessageNotification' => [],
            'AdMatchingInterrestNotification' => [],
            'RespondOnReservationNotification' => [],
            'AdCommented' => [],
            'AdStatusUpdated' => [],
        ];

        foreach ($notifications as $notification) {
            $type = class_basename($notification->type);
            if (array_key_exists($type, $notificationTypes)) {
                $notificationTypes[$type][] = $notification;
            }
        }

        return $notificationTypes;
    }


    /**
     * @param $user_id
     * @return mixed
     */
    public function unreadNotifications($user_id)
    {
        $user = User::find($user_id);
        $notifications = $user->userNotifications()
            ->where('type', '!=', 'App\\Notifications\\MessageNotification')
            ->unread()
            ->get();

        return $notifications;
    }

    public function unreadMessage($user_id)
    {
        $user = User::find($user_id);
        $notifications = $user->userNotifications()
            ->where('type', '=', 'App\\Notifications\\MessageNotification')
            ->where('data->senderId', '!=', $user->id)
            ->unread()
            ->get();

        return $notifications;
    }

    /**
     * @param $notificationId
     * @return mixed
     */
    public function markOneNotificationsAsRead($notificationId)
    {
        $user = Auth::user();

        $notification = DatabaseNotification::where('notifiable_id', $user->id)
            ->where('data->messageId', $notificationId)
            ->first();
        if ($notification) {
            $notification->markAsRead();
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
