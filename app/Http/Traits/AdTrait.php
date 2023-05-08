<?php

namespace App\Http\Traits;

use App\Http\Requests\Ad\AdRequest;
use App\Models\Ad;
use Illuminate\Http\Request;

trait AdTrait
{
    public function getFillerRequest(Request $request)
    {

        return [
            'title' => $request->title,
            'description' => $request->description,
            'country' => $request->country,
            'status' => 0,
            'state' => $request->state,
            'city' => $request->city,
            'street' => $request->street,
            'postal_code' => $request->postal_code,
            'category_id' => $request->category_id
        ];

    }

    public function getQueryParameters($request)
    {
        return [
            'perPage' => $request->input('perPage', config('constants.PER_PAGE')),
            'orderDirection' => $request->input('orderDirection', config('constants.ORDER_DIRECTION')),
            'orderBy' => $request->input('orderBy', config('constants.ORDER_BY')),
            'page' => $request->input('page', config('constants.PAGE')),
            'keyword' => $request->input('keyword'),
            'status' => $request->input('status'),
            'date' => $request->input('date'),

        ];
    }

    public function getStatusQueryParameters($request)
    {
        return [
            'id' => $request->input('id'),
            'status' => $request->input('status')
        ];
    }
}
