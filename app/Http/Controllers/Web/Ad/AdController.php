<?php

namespace App\Http\Controllers\Web\Ad;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ad\AdRequest;
use App\Models\Ad;
use App\Models\Category;
use App\Repositories\AdRepository;
use Illuminate\Http\Request;

class AdController extends Controller
{
    protected $adRepository;

    public function __construct(AdRepository $adRepository)
    {
        $this->adRepository = $adRepository;
    }

    public function index()
    {
        $ads = $this->adRepository->all();
        if ($ads) {
            return response()->json(['ads' => $ads,], 201);
        }
        return response()->json(['message' => "no data found !",], 500);
    }


    public function store(AdRequest $request)
    {
        try {
            $validated = $request->validated();
            $ad = $this->adRepository->create($request->all());
            return response()->json([
                'message' => 'ad added successfully',
                'ad' => $ad,
            ], 201);
        } catch (Exception $exception) {
            return response()->json(['message' => __('message.error')], 500);
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
            return response()->json(['message' => "error",], 500);
        }

    }


    public function update(AdRequest $request, $id)
    {
        try {
            $validated = $request->validated();

            $ad = $this->adRepository->update($request->all(), $id);
            return response()->json([
                'message' => 'ad updated successfully',
                'ad' => $ad
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => "error to update your ad",], 500);
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
            return response()->json(['message' => "error",], 500);
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
