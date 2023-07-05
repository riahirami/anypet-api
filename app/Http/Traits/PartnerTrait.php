<?php

namespace App\Http\Traits;


use Illuminate\Http\Request;

trait PartnerTrait
{

    /**
     * @param Request $request
     * @return array
     */
    public function getFillerRequest(Request $request)
    {
        return [
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'link' => $request->link,
            'contact' => $request->contact,
            'logo' => $request->logo,
            'media' => $request->media,
        ];
    }

}
