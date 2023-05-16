<?php

namespace App\Http\Traits;

use App\Http\Requests\CategoryRequest;
use App\Models\Ad;
use App\Models\Category;
use Illuminate\Http\Request;

trait CategoryTrait
{

    /**
     * @param Request $request
     * @return array
     */
    public function getFillderRequest(Request $request)
    {
        return [
            'title' => $request->title,
        ];
    }

}
