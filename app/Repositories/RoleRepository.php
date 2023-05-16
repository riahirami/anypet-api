<?php

namespace App\Repositories;


use App\Models\User;

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
        return $user;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function revokeAdmin($id){
        $user = USer::findOrFail($id);
        $user->role_id = 1;
        $user->save();
        return $user ;
    }
}
