<?php

namespace App\Repositories;


use App\Events\NewNotificationEvent;
use App\Models\Role;
use App\Models\User;
use App\Notifications\RoleChangedNotification;

class RoleRepository
{
    /**
     * @param $id
     * @return mixed
     */
    public function setAdmin($id)
    {
        $user = User::findOrFail($id);
        $user->role_id = 2;
        $user->save();
        if ($user) {
            $newRole = Role::find(2);
            $user->notify(new RoleChangedNotification($newRole));
            event(new NewNotificationEvent("App\Notifications\RoleChangedNotification",$user->id,$user,null));

        }
        return $user;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function revokeAdmin($id)
    {
        $user = User::findOrFail($id);
        $user->role_id = 1;
        $user->save();
        if ($user) {
            $newRole = Role::find(1);
            $user->notify(new RoleChangedNotification($newRole));
            event(new NewNotificationEvent("App\Notifications\RoleChangedNotification",$user->id,$user,null));

        }
        return $user;
    }
}
