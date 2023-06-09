<?php

namespace App\Http\Controllers;

use App\Http\Traits\GlobalTrait;
use App\Repositories\PartnersRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PartnerController extends Controller
{
    protected $partnerRepository;
    use GlobalTrait;

    public function __construct(PartnersRepository $partnerRepository)
    {
        $this->partnerRepository = $partnerRepository;
    }

    public function index()
    {
        try {
            $partners = $this->partnerRepository->getAllPartners();
            return $this->returnSuccessResponse(Response::HTTP_CREATED, ['data' => $partners]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorFindAd'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }

    }
    public function show($id)
    {
        try {
            $partner = $this->partnerRepository->getPartnerById($id);
            return $this->returnSuccessResponse(Response::HTTP_CREATED, ['data' => $partner]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorFindAd'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }

    }
    public function showAll($perpage)
    {


    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        try {
            $partner = $this->partnerRepository->addPartner($request);
            return $this->returnSuccessResponse(Response::HTTP_OK, $partner);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorListAds'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans($e->getMessage()));
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
            $partner = $this->partnerRepository->updatePartner($request,$id);
            return $this->returnSuccessResponse(Response::HTTP_OK, $partner);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorListAds'));
        } catch (\Exception $e) {
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
            $partner = $this->partnerRepository->deletePartner($id);
            return $this->returnSuccessResponse(Response::HTTP_CREATED, ['data' => $partner->original]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorFindAd'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }

    }
}
