<?php

namespace App\Http\Traits;

use App\Http\Requests\Ad\AdRequest;
use App\Models\User;
use Illuminate\Http\Request;

trait UserTrait
{
    /**
     * @param $request
     * @return array
     */
    public function getQueryParameters($request)
    {
        return [
            'perPage' => $request->input('perPage', config('constants.PER_PAGE')),
            'orderDirection' => $request->input('orderDirection', config('constants.ORDER_DIRECTION')),
            'orderBy' => $request->input('orderBy', config('constants.ORDER_BY')),
            'page' => $request->input('page', config('constants.PAGE')),
            'id' => $request->input('id'),
            'login' => $request->input('login'),
            'email' => $request->input('email'),
        ];
    }

}
