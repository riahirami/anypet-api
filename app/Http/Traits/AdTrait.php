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
            'status' => $request->status,
            'state' => $request->state,
            'city' => $request->city,
            'street' => $request->street,
            'postal_code' => $request->postal_code,
            'category_id' => $request->category_id
        ];

    }

}
