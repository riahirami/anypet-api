<?php

namespace App\Http\Controllers\Web\Ad;

use App\Http\Controllers\Controller;
use App\Models\Ad;
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
            return response()->json(['data' => $ads,], 200);
        }
        return response()->json(['message' => "no data found !",], 500);
    }


    public function store(Request $request)
    {
        try {
            $ad = $this->adRepository->create($request->all());
            return response()->json(['ad' => $ad], 201);
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

    public function edit($id)
    {
        try {

            $ad = Ad::findOrFail($id);
            return response()->json([
                'ad' => $ad
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => "error",], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $ad = $this->adRepository->update($request->all(), $id);
            return response()->json([
                'ad' => $ad
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => "error",], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $ad = $this->adRepository->delete($id);
            return response()->json([
                'ad' => $ad
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => "error",], 500);
        }

    }
}
