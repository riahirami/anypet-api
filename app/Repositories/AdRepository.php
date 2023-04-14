<?php

namespace App\Repositories;

use App\Http\Requests\Ad\AdRequest;
use App\Http\Traits\AdTrait;
use App\Models\Ad;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Traits\CategoryTrait;

class AdRepository
{
    use CategoryTrait;
    use AdTrait;

    public function all()
    {
        $ads = $this->querygetAllAdData(config('constant.cursorPaginate'), config('constant.orderDirection'), config('constant.orderBy'));
        return $ads;

    }

    public function getAdById($id)
    {
        $ad = Ad::find($id);
        return $ad;


    }

    public function create(Request $request)
    {
//        $ad = new Ad();
//        $ad->title = $data['title'];
//        $ad->description = $data['description'];
//        $ad->country = $data['country'];
//        $ad->state = $data['state'];
//        $ad->city = $data['city'];
//        $ad->street = $data['street'];
//        $ad->postal_code = $data['postal_code'];
//        $ad->category_id = $data['category_id'];
//        $ad->save();
        $ad = new Ad();
        $data = $this->getFillerRequest($request);
        $fillable = $ad->getFillable();
        foreach ($fillable as $field) {
            if (isset($data[$field])) {
                $ad->{$field} = $data[$field];
            }
        }
        $ad->save();
        return $ad;

    }

    public function update(Request $request, $id)
    {
        $ad = Ad::find($id);
        $data = $this->getFillerRequest($request);
        $fillable = $ad->getFillable();
        foreach ($fillable as $field) {
            if (isset($data[$field])) {
                $ad->{$field} = $data[$field];
            }
        }
        $ad->save();
        return $ad;
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

    public function getAdsByCategory($categoryId)
    {

        $ads = Ad::byCategory($categoryId)->get();
        return $ads;

    }
}

