<?php
namespace App\Http\Traits;
use App\Http\Requests\Ad\AdRequest;
use App\Models\Ad;
use Illuminate\Http\Request;

trait AdTrait
{
    public function getFillerRequest(Request $request)
    {

//        return [
//            'title' => $request->title,
//            'description' => $request->description,
//            'country' => $request->country,
//            'state' => $request->state,
//            'city' => $request->city,
//            'street' => $request->street,
//            'postal_code' => $request->postal_code,
//            'category_id' => $request->category_id
//        ];


        $ad = new Ad();
        $fillable = $ad->getFillable();
        $adData = [];
        foreach ($fillable as $field) {
            if ($request->filled($field)) {
                $adData[$field] = $request->input($field);
            }
        }
        return new Request($adData);
    }

    public function querygetAllAdData($perPage = null, $orderDirection = 'asc', $orderBy = 'created_at')
    {
        $query = new Ad;

        if ($orderDirection) {
            $query = $query->orderBy($orderBy, $orderDirection);
        } else {
            $query = $query->orderBy($orderBy);
        }

        if ($perPage) {
            $result = $query->cursorPaginate($perPage);
        } else {
            $result = $query->get();
        }

        return $result;
    }
}
