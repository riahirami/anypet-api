<?php

namespace App\Repositories;

use App\Http\Requests\Ad\AdRequest;
use App\Http\Traits\AdTrait;
use App\Models\Ad;
use App\Models\Category;
use App\Models\FavoriteAd;
use Illuminate\Http\Request;
use App\Http\Traits\CategoryTrait;
use Illuminate\Support\Facades\Log;

class AdRepository
{
    use CategoryTrait;
    use AdTrait;

    /**
     * @param array $parameters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    static function index(array $parameters)
    {
        return Ad::query()
            ->byKeyword($parameters['keyword'])
            ->byStatus($parameters['status'])
            ->byDate($parameters['date'])
            ->orderBy($parameters['orderBy'], $parameters['orderDirection'])
            ->paginate($parameters['perPage'], ['*'], null, $parameters['page']);
    }

    public
    function show($id)
    {
        return Ad::findOrFail($id);
    }

    public
    function create(array $data)
    {
        $ad = new Ad();
        $ad->title = $data['title'];
        $ad->description = $data['description'];
        $ad->country = $data['country'];
        $ad->state = $data['state'];
        $ad->city = $data['city'];
        $ad->street = $data['street'];
        $ad->postal_code = $data['postal_code'];
        $ad->category_id = $data['category_id'];
        $ad->status = 0;
        $ad->save();
        return $ad;
    }

    public
    function update(array $data, $id)
    {
        $ad = Ad::find($id);
        $ad->title = $data['title'];
        $ad->description = $data['description'];
        $ad->country = $data['country'];
        $ad->state = $data['state'];
        $ad->city = $data['city'];
        $ad->street = $data['street'];
        $ad->postal_code = $data['postal_code'];
        $ad->category_id = $data['category_id'];
        $ad->save();
        return $ad;
    }

    public
    function delete($id)
    {

        $ad = Ad::findOrFail($id);
        $ad->delete();
        return $ad;

    }

    public
    function getAdsByDate($date)
    {

        $ads = Ad::byDate($date)->get();
        return $ads;

    }

    public function getAdsByCategory($categoryId)
    {
        $ads = Ad::byCategory($categoryId)->get();
        return $ads;
    }

    public function getAdsByStatus($status)
    {
        $ads = Ad::byStatus($status)->get();
        return $ads;
    }

    public function getAdsByString($string)
    {

        $ads = Ad::byString($string)->get();
        return $ads;
    }

    public function updateAdStatus(array $parameters)
    {
        $ad = Ad::findOrFail($parameters['id']);
        $ad->status = $parameters['status'];
        $ad->save();
        return $ad;
    }

    public function getStats($column)
    {
        $stats = Ad::AdsStats($column);
        return $stats;
    }

    public function CountAdsPerDate()
    {
        $stats = Ad::CountAdsPerDate();
        return $stats;
    }

    public function setToFavorite($id)
    {
        $ad = Ad::findOrFail($id);
        if (auth()->user()->favoriteAds()->where('ad_id', $id)->exists()) {
            auth()->user()->favoriteAds()->detach($ad);
            return response()->json(['message' => trans('message.successUnsetFavorite')]);
        } else {
            auth()->user()->favoriteAds()->attach($ad);
            return response()->json(['message' => trans('message.successSetFavorite')]);
        }

    }

    public function listFavoriteAds($id)
    {
        $favoriteAds = FavoriteAd::favoriteList($id)->with('ad')->get();
        return $favoriteAds;
    }
}


