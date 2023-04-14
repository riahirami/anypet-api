<?php

namespace App\Http\Traits;

use App\Http\Requests\CategoryRequest;
use App\Models\Ad;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait GlobalTrait
{

    /**
     * @param $successCode
     * @param $data
     * @param $message
     * @return JsonResponse
     */
    public function returnSuccessResponse($successCode, $data = null, $message = null): JsonResponse
    {
        $responseData = $data ? $data : ['message' => $message];
        return response()->json($responseData, $successCode);
    }


    /**
     * @param $errorCode
     * @param $message
     * @return JsonResponse
     */
    public function returnErrorResponse($errorCode, $message): JsonResponse
    {
        return response()->json(
            [
                'message' => $message,
            ],
            $errorCode);
    }
}
