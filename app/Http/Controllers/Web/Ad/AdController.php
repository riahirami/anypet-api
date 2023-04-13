<?php

namespace App\Http\Controllers\Web\Ad;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ad\AdRequest;
use App\Http\Traits\AdTrait;
use App\Repositories\AdRepository;
use Exception;
use Illuminate\Http\Request;

class AdController extends Controller
{
    protected $adRepository;
    use AdTrait;

    public function __construct(AdRepository $adRepository)
    {
        $this->adRepository = $adRepository;
    }

    public function index()
    {
        try {
            $ads = $this->adRepository->all();
            return response()->json(['data' => $ads,], 200);
        } catch (Exception $exception) {
            return response()->json(['message' => trans('message.errorShowAllAds')], 500);
        }
    }


    public function store(Request $request)
    {
        try {
            $ad = $this->adRepository->create($this->getFillerRequest($request));
            return response()->json([
                'data' => $ad,
            ], 201);
        } catch (Exception $exception) {
            return response()->json(['message' => trans('message.errorCreateAd')], 500);
        }
    }

    public function show($id)
    {
        $ad = $this->adRepository->getAdById($id);

        try {
            if (!$ad) {
                return response()->json([
                    'message' => trans('message.adNotFound')],
                    404);

            }
            return response()->json([
                'data' => $ad
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => trans('message.errorfindAd')], 500);
        }

    }


    public function update(Request $request, $id)
    {
        try {
            $ad = $this->adRepository->update($request, $id);
            return response()->json(['data' => $ad],
                201);
        } catch (\Exception $e) {
            return response()->json(['message' => trans('message.errorUpdateAd')], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $ad = $this->adRepository->delete($id);
            return response()->json([
                'data' => $ad
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => trans('message.errorDeleteAd')], 500);
        }

    }

    public function getByDate($date)
    {
        try {
            $ads = $this->adRepository->getAdsByDate($date);
            return response()->json([
                'data' => $ads
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message'=>trans('message.adNotFoundForDate')], 500);
        }
    }

    public
    function getByCategory($categoryId)
    {
        try {
            $ads = $this->adRepository->getAdsByCategory($categoryId);
            return response()->json([
                'ad' => $ads
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message'=>trans('message.adNotFoundForCategory')], 500);
        }
    }
}
