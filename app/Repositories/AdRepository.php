<?php

namespace App\Repositories;

use App\Models\Ad;
use App\Models\Category;

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

        return Ad::create($data);

    }

    public function update(array $data, $id)
    {

        $ad = Ad::findOrFail($id);
        $ad->update($data);
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

