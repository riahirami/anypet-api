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

    static function index(array $data)
    {
        $perPage = $data['perpage'] ?? config('constant.per_page');
        $orderBy = $data['orderBy'] ?? config('constant.orderBy');
        $orderDirection = $data['order_direction'] ?? config('constant.orderDirection');
        $page = $data['page'] ?? config('constant.page');

        return Ad::query()
            ->orderBy($orderBy, $orderDirection)
            ->paginate($perPage, ['*'], $page);

// $ads = $this->querygetAllAdData(config('constant.cursorPaginate'), config('constant.orderDirection'), config('constant.orderBy'));

    }

    public
    function show($id)
    {
        $ad = Ad::find($id);
        return $ad;


    }

    public
    function create(array $data)
    {
        $ad = new Ad();
        $ad->title = $data['title'];
        $ad->description = $data['description'];
        $ad->country = $data['country'];
        $ad->status = 0;
        $ad->state = $data['state'];
        $ad->city = $data['city'];
        $ad->street = $data['street'];
        $ad->postal_code = $data['postal_code'];
        $ad->category_id = $data['category_id'];
        $ad->save();
        return $ad;
    }

    public
    function update(array $data, $id)
    {
        $ad = Ad::find($id);
        $ad->title = $data['title'];
        $ad->description = $data['description'];
        $ad->status = $data['status'];
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
}

