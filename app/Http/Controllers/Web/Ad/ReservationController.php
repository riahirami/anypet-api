<?php

namespace App\Http\Controllers\Web\Ad;

use App\Http\Traits\GlobalTrait;
use App\Repositories\ReservationRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReservationController extends \App\Http\Controllers\Controller
{
    use GlobalTrait;

    private $reservation;


    public function __construct(ReservationRepository $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $reservation = $this->reservation->store($request);
            return $this->returnSuccessResponse(Response::HTTP_CREATED, ['data' => $reservation]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorStoreReservation'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans($e->getMessage()));
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ResponseReservation(Request $request)
    {
        try {
            $reservation = $this->reservation->responseOnReservation($request);
            return $this->returnSuccessResponse(Response::HTTP_CREATED, ['data' => $reservation]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorUpdateReservation'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans($e->getMessage()));
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyreservation()
    {
        try {
            $reservations = $this->reservation->getAuthenticatedUserReservations();
            return $this->returnSuccessResponse(Response::HTTP_OK, ['data' => $reservations]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorFindReservation'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans($e->getMessage()));
        }
    }

    /**
     * @param $adId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAdreservation($adId)
    {
        try {
            $reservations = $this->reservation->getAdReservations($adId);
            return $this->returnSuccessResponse(Response::HTTP_OK, ['data' => $reservations]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorFindReservation'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans($e->getMessage()));
        }
    }

}
