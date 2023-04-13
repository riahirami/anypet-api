<?php

namespace App\Http\Traits;

use App\Http\Requests\CategoryRequest;
use App\Models\Ad;
use App\Models\Category;
use Illuminate\Http\Request;

trait CategoryTrait
{

    public function getFillderRequest(Request $request)
    {
        $category = new Category();
        $fillable = $category->getFillable();
        $adData = [];
        foreach ($fillable as $field) {
            if ($request->filled($field)) {
                $adData[$field] = $request->input($field);
            }
        }
        return new Request($adData);
    }


    public function queryData($perPage = null, $orderDirection = 'asc', $orderBy = 'created_at')
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
