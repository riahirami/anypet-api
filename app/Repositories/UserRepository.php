<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{

    protected $user = null;

    static function index(array $data)
    {
        $perPage = $data['perpage'] ?? config('constants.PER_PAGE');
        $orderBy = $data['orderBy'] ?? config('constants.ORDER_BY');
        $orderDirection = $data['order_direction'] ?? config('constants.ORDER_DIRECTION');
        $page = $data['page'] ?? config('constants.PAGE');
        return User::query()
            ->orderBy($orderBy, $orderDirection)
            ->paginate($perPage, ['*'], $page);
    }

    public function show($id){
        $user = User::find($id);
        return $user ;
    }

    public function verifiedUsers(){
        $users= User::VerifiedEmail()->get() ;

        return $users ;
    }
}
