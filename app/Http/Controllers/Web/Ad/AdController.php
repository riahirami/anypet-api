<?php

namespace App\Http\Controllers\Web\Ad;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ad\AdRequest;
use App\Http\Traits\AdTrait;
use App\Http\Traits\CategoryTrait;
use App\Http\Traits\GlobalTrait;
use App\Models\Ad;
use App\Repositories\AdRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\isNull;

class AdController extends Controller
{
    protected $adRepository;
    use AdTrait;
    use GlobalTrait;

    public function __construct(AdRepository $adRepository)
    {
        $this->adRepository = $adRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $parameters = $this->getQueryParameters($request);
        $ads = $this->adRepository->index($parameters);
        try {
            return $this->returnSuccessResponse(Response::HTTP_OK, $ads);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorListAds'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $ad = $this->adRepository->show($id);
            return $this->returnSuccessResponse(Response::HTTP_OK, ['data' => $ad]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorfindAd'));
        } catch (\Exception $e) {
//            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans($e->getMessage()));
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $attribute = $this->getFillerRequest($request);
//        $validator = Validator::make($attribute, [
//            'media' => 'required|mimes:jpeg,png,jpg,gif'
//        ]);
//
//        if ($validator->fails()) {
//            return $this->returnErrorResponse(Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
//        }
        try {
            $ad = $this->adRepository->create($attribute);
            return $this->returnSuccessResponse(Response::HTTP_CREATED, ['data' => $ad]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorCreateAd'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $attribute = $this->getFillerUpdateRequest($request);
            $ad = $this->adRepository->update($attribute, $id);
            return $this->returnSuccessResponse(Response::HTTP_CREATED, ['data' => $ad]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorUpdateAd'));
        } catch (\Exception $e) {
//            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans($e->getMessage()));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $ad = Ad::findOrfail($id);
            $user = auth()->id();
            if ($ad->user_id == $user) {

                $this->adRepository->delete($id);

                return $this->returnSuccessResponse(Response::HTTP_OK,
                    trans('message.adDeleted')
                );
            }
            else
                return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.unauthorized'));

        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorDeleteAd'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllMedia()
    {
        try {
            $media = $this->adRepository->getAllMedia();
            return $this->returnSuccessResponse(Response::HTTP_OK, ['data' => $media]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.adNotFoundForDate'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }

    /**
     * @param $ad_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMediaPerAds($ad_id)
    {
        try {
            $media = $this->adRepository->getMediaOfAds($ad_id);
            return $this->returnSuccessResponse(Response::HTTP_OK, ['data' => $media]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.adNotFoundForDate'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }

    /**
     * @param $date
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByDate($date)
    {
        try {
            $ads = $this->adRepository->getAdsByDate($date);
            return $this->returnSuccessResponse(Response::HTTP_OK, ['data' => $ads]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.adNotFoundForDate'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }

    /**
     * @param $categoryId
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function getByCategory($categoryId)
    {
        try {
            $ads = $this->adRepository->getAdsByCategory($categoryId);
            return $this->returnSuccessResponse(Response::HTTP_OK, ['data' => $ads]);

        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.adNotFoundForCategory'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }

    /**
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function getAdsByStatus($status)
    {
        try {
            $ads = $this->adRepository->getAdsByStatus($status);
            return $this->returnSuccessResponse(Response::HTTP_OK, ['data' => $ads]);

        } catch (\Exception $e) {
            return $this->returnErrorResponse(400, trans('message.adNotFoundForStatus'));
        }
    }

    /**
     * @param $string
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAdsByString($string)
    {
        try {
            $ads = $this->adRepository->getAdsByString($string);
            return $this->returnSuccessResponse(Response::HTTP_OK, ['data' => $ads]);
        } catch (\Exception $e) {
            return $this->returnErrorResponse(400, trans('message.adNotFoundForKey'));
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function requestAds(Request $request)
    {

        $parameters = $this->getStatusQueryParameters($request);
        try {

//            if(isNull($parameters["id"])){
//                return $this->returnSuccessResponse(Response::HTTP_NOT_MODIFIED, );
//            }
            $ad = $this->adRepository->updateAdStatus($parameters);
            return $this->returnSuccessResponse(Response::HTTP_CREATED, ['message' => trans('message.errorUpdateAd'), 'data' => $ad]);
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAdsStats(Request $request)
    {
        $column = $request->column;
        try {
            $ads = $this->adRepository->getStats($column);
            return $this->returnSuccessResponse(Response::HTTP_OK, ['data' => $ads]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorFindAd'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCountAdsPerDate()
    {
        try {
            $stats = $this->adRepository->CountAdsPerDate();
            return $this->returnSuccessResponse(Response::HTTP_OK, ['data' => $stats]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorFindAd'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }

    /**
     * @param $ad
     * @return \Illuminate\Http\JsonResponse
     */
    public function setFavorite($ad)
    {
        try {
            $response = $this->adRepository->setToFavorite($ad);
            $message = $response->getData()->message;
            return $this->returnSuccessResponse(Response::HTTP_OK, ['message' => $message]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorFindAd'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getlistUserAds()
    {
        $id = auth()->id();
        try {
            $ads = $this->adRepository->listUserAds($id);
            $count = $this->adRepository->listUserAds($id)->count();
            return $this->returnSuccessResponse(Response::HTTP_OK, ['data' => $ads,
                'count' => $count]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorFindAd'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans($e->getMessage()));
//            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getlistFavoriteAds()
    {
        $id = auth()->id();
        try {
            $ads = $this->adRepository->listFavoriteAds($id);
            $count = $this->adRepository->listFavoriteAds($id)->count();
            return $this->returnSuccessResponse(Response::HTTP_OK, ['data' => $ads,
                'count' => $count]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorFindAd'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }
}
