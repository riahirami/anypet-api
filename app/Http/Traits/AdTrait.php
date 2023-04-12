<?php
namespace App\Http\Traits;
use Illuminate\Http\Request;

Trait AdTrait
{

    public function getFillderRequest(Request $request)
    {
        return [
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'street' => $request->street,
            'postal_code' => $request->postal_code
        ];
    }

}
