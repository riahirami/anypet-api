<?php

namespace App\Http\Controllers\Web\Ad;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ad\AdRequest;
use App\Http\Traits\AdTrait;
use App\Http\Traits\CategoryTrait;
use App\Http\Traits\GlobalTrait;
use App\Repositories\AdRepository;
use Exception;
use Illuminate\Http\Request;


class AdController extends Controller
{
    protected $adRepository;
    use AdTrait;
    use CategoryTrait;
    use GlobalTrait;

    public function __construct(AdRepository $adRepository)
    {
        $this->adRepository = $adRepository;
    }

    public function index()
    {
        try {
            $ads = $this->adRepository->all();
            return $this->returnSuccessResponse(200, ['data' => $ads]);

        } catch (Exception $exception) {
            return $this->returnErrorResponse(400, trans('message.errorListAds'));
        }
    }


    public function store(Request $request)
    {
        try {
            $ad = $this->adRepository->create($this->getFillerRequest($request));
            return $this->returnSuccessResponse(201, ['data' => $ad]);

        } catch (Exception $exception) {
            return $this->returnErrorResponse(400, trans('message.errorCreateAd'));

        }
    }

    public function show($id)
    {

        try {
            $ad = $this->adRepository->getAdById($id);
            if (!$ad) {
                return $this->returnErrorResponse(404, trans('message.adNotFound'));
            }
            return $this->returnSuccessResponse(200, ['data' => $ad]);

        } catch (\Exception $e) {
            return $this->returnErrorResponse(400, trans('message.errorfindAd'));

        }

    }


    public function update(Request $request, $id)
    {
        try {
            $ad = $this->adRepository->update($this->getFillerRequest($request), $id);
            return $this->returnSuccessResponse(201, ['data' => $ad]);

        } catch (\Exception $e) {
            return $this->returnErrorResponse(400, trans('message.errorUpdateAd'));

        }
    }

    public function destroy($id)
    {
        try {
            $ad = $this->adRepository->delete($id);
            return $this->returnSuccessResponse(200,
                trans('message.adDeleted')
            );
        } catch (\Exception $e) {
            return $this->returnErrorResponse(400, trans('message.errorDeleteAd'));

        }

    }

    public function getByDate($date)
    {
        try {
            $ads = $this->adRepository->getAdsByDate($date);
            return $this->returnSuccessResponse(200, ['data' => $ads]);

        } catch (\Exception $e) {

            return $this->returnErrorResponse(400, trans('message.adNotFoundForDate'));

        }
    }

    public
    function getByCategory($categoryId)
    {
        try {
            $ads = $this->adRepository->getAdsByCategory($categoryId);
            return $this->returnSuccessResponse(200, ['data' => $ads]);

        } catch (\Exception $e) {
            return $this->returnErrorResponse(400, trans('message.adNotFoundForCategory'));

        }
    }
}
