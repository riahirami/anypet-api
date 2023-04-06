<?php

namespace App\Repositories;

use App\Models\Ad;

class AdRepository
{
    public function all()
    {
        return Ad::all();
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
    }
}

