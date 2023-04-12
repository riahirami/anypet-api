<?php

namespace App\Http\Controllers\Web\Ad;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ad\AdRequest;
use App\Http\Traits\AdTrait;
use App\Models\Ad;
use App\Models\Category;
use App\Repositories\AdRepository;
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
            return response()->json(['ads' => $ads,], 200);
        } catch (Exception $exception) {
            return response()->json(['message' => trans('message.errorShowAllAds')], 500);
        }
    }


    public function store(AdRequest $request)
    {
        try {
            $validated = $request->validated();
            $ad = $this->adRepository->create($this->getFillderRequest($request));
            return response()->json([
                'ad' => $ad,
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
                    'message' => "no ad found",
                ], 500);

            }
            return response()->json([
                'ad' => $ad
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => trans('message.errorfindAd')], 500);
        }

    }


    public function update(AdRequest $request, $id)
    {
        try {
            $validated = $request->validated();

            $ad = $this->adRepository->update($this->getFillderRequest($request), $id);
            return response()->json([
                'message' => 'ad updated successfully',
                'ad' => $ad
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => trans('message.errorUpdateAd')], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $ad = $this->adRepository->delete($id);
            return response()->json([
                'message' => 'ad deleted successfully',

                'ad' => $ad
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => trans('message.errorDeleteAd')], 500);
        }

    }

    public function getByDate($date)
    {
        try {
            $ads = $this->adRepository->getAdsByDate($date);
            return response()->json([
                'ads' => $ads
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => "no data found for this date",], 500);
        }
    }

    public
    function getByCategory($categoryId)
    {
        $ads = $this->adRepository->getAdsByCategory($categoryId);
        return response()->json([
            'ad' => $ads
        ], 201);
    }
}
