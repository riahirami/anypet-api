<?php

namespace App\Repositories;

use App\Http\Requests\Ad\AdRequest;
use App\Models\Ad;
use App\Models\Category;
use Illuminate\Http\Request;

class AdRepository
{
    public function all()
    {
        $ads = Ad::orderBy('created_at')->cursorPaginate(10);;
        return $ads;

    }

    public function getAdById($id)
    {
        $ad = Ad::find($id);
        return $ad;


    }

    public function create(array $data)
    {

        $ad = new Ad();
        $ad->title = $data['title'];
        $ad->description = $data['description'];
        $ad->country = $data['country'];
        $ad->state = $data['state'];
        $ad->city = $data['city'];
        $ad->street = $data['street'];
        $ad->postal_code = $data['postal_code'];
        $ad->save();
        return $ad;

    }

    public function update(Request $request, $id)
    {

        try {
            $ad = Ad::find($id);
            $ad->title = $request->title;
            $ad->description = $request->description;
            $ad->status = $request->status;
            $ad->country = $request->country;
            $ad->state = $request->state;
            $ad->city = $request->city;
            $ad->street = $request->street;
            $ad->postal_code = $request->postal_code;
            $ad->save();
            return $ad;
        } catch
        (\Exception $e) {
            return response()->json(['message' => "error",], 500);
        }

    }

    public function delete($id)
    {

        $ad = Ad::findOrFail($id);
        $ad->delete();
        return $ad;

    }

    public function getAdsByDate($date)
    {

        $ads = Ad::byDate($date)->get();
        return $ads;

    }


}

