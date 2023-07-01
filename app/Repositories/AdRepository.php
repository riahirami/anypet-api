<?php

namespace App\Repositories;

use App\Events\NewNotificationEvent;
use App\Http\Requests\Ad\AdRequest;
use App\Http\Traits\AdTrait;
use App\Models\Ad;
use App\Models\Category;
use App\Models\FavoriteAd;
use App\Models\Media;
use App\Models\User;
use App\Notifications\AdStatusUpdated;
use Illuminate\Http\Request;
use App\Http\Traits\CategoryTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        return Ad::query()->with('media', 'user')
            ->byKeyword($parameters['keyword'])
            ->byStatus($parameters['status'])
            ->byDate($parameters['date'])
            ->byState($parameters['state'])
            ->byCategories($parameters['category'])
            ->orderBy($parameters['orderBy'], $parameters['orderDirection'])
            ->paginate($parameters['perPage'], ['*'], null, $parameters['page']);
    }

    /**
     * @param $id
     * @return mixed
     */
    public
    function show($id)
    {
        return Ad::with('media', 'user')->findOrFail($id);
    }

    /**
     * @param array $data
     * @return Ad
     */
    public function create(array $data)
    {
        $user_id = auth()->id();
        $ad = new Ad();
        $ad->title = $data['title'];
        $ad->description = $data['description'];
        $ad->state = $data['state'];
        $ad->city = $data['city'];
        $ad->street = $data['street'];
        $ad->postal_code = $data['postal_code'];
        $ad->category_id = $data['category_id'];
        $ad->status = 0;
        $ad->user_id = $user_id;
        $ad->save();

        if (isset($data['media'])) {
            foreach ($data['media'] as $file) {
                $media = new Media();
                $media->file_name = $file->getClientOriginalName();
                $media->file_path = url(Storage::url($file->store('public/ads')));
                $media->mime_type = $file->getClientMimeType();
                $ad->media()->save($media);
            }
        }
        $users = User::all();
        foreach ($users as $user) {
            $user->notifyIfNewAdInFavoriteCategoryAdded($ad);
        }
        return $ad;
    }


    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public
    function update(array $data, $id)
    {
        $ad = Ad::findOrFail($id);
        $user = auth()->id();
        if ($ad->user_id == $user) {
//            $ad = AD::destroy($id);

            $ad->title = $data['title'];
            $ad->description = $data['description'];
            $ad->state = $data['state'];
            $ad->city = $data['city'];
            $ad->street = $data['street'];
            $ad->postal_code = $data['postal_code'];
            $ad->category_id = $data['category_id'];
            $ad->status = 0;
            $ad->save();
            if (isset($data['media'])) {
                foreach ($data['media'] as $file) {
                    $media = new Media();
                    $media->file_name = $file->getClientOriginalName();
                    $media->file_path = url(Storage::url($file->store('public/ads')));
                    $media->mime_type = $file->getClientMimeType();
                    $ad->media()->save($media);
                }
            }
            return $ad;
        } else
            return response()->json(['message' => trans('message.unauthorized')]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAllMedia()
    {
        $ads = Ad::query()->with('media')->get();
        return $ads;
    }

    /**
     * @param $ad_id
     * @return mixed
     */
    public function getMediaOfAds($ad_id)
    {
        $ad = Ad::findOrFail($ad_id);
        $media = $ad->media;
        return $media;
    }

    /**
     * @param $date
     * @return mixed
     */
    public
    function getAdsByDate($date)
    {
        $ads = Ad::byDate($date)->get();
        return $ads;
    }

    /**
     * @param $categoryId
     * @return mixed
     */
    public function getAdsByCategory($categoryId)
    {
        $ads = Ad::with('media', 'user')->byCategories($categoryId)
            ->byStatus(['4', '3', '2']) // Pass an array of status values
            ->paginate(10, ['*'], null, 1);
//        ->get();
        return $ads;
    }

    /**
     * @param $status
     * @return mixed
     */
    public function getAdsByStatus($status)
    {
        $ads = Ad::byStatus($status)->get();
        return $ads;
    }

    /**
     * @param $string
     * @return mixed
     */
    public function getAdsByString($string)
    {

        $ads = Ad::byString($string)->get();
        return $ads;
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function updateAdStatus(array $parameters)
    {
        $ad = Ad::findOrFail($parameters['id']);
        $ad->status = $parameters['status'];
        $ad->save();
        if ($ad) {
            $ad->user->notify(new AdStatusUpdated($ad));
//            $latestNotification = $ad->user->notifications()->latest()->first();
            event(new NewNotificationEvent("App\Notifications\AdStatusUpdated",$ad->user_id,$ad,null));
        }
        return $ad;
    }

    /**
     * @param $column
     * @return mixed
     */
    public function getStats($column)
    {
        $stats = Ad::AdsStats($column);
        return $stats;
    }

    /**
     * @return mixed
     */
    public function CountAdsPerDate()
    {
        $stats = Ad::CountAdsPerDate();
        return $stats;
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @param $id
     * @return mixed
     */
    public function listUserAds($id)
    {
        $ads = Ad::with('media', 'user')->UserAdList($id)->get();
        return $ads;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function listFavoriteAds($id)
    {
        $favoriteAds = FavoriteAd::favoriteList($id)->with('ad')->get();
        return $favoriteAds;
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse|int
     */
    public function delete($id)
    {
        $ad = Ad::destroy($id);
        return $ad;
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function markAsAdoptedOrReserved(array $parameters)
    {
        $ad = Ad::findOrFail($parameters['id']);
        $ad->status = $parameters['status'];
        $ad->save();
        return $ad;
    }

}


