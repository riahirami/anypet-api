<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\GlobalTrait;
use App\Http\Traits\UserTrait;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    protected $useRepository;
    use UserTrait;
    use GlobalTrait;

    public function __construct(UserRepository $useRepository)
    {
        $this->userRepository = $useRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $parameters = $this->getQueryParameters($request);
        try {
            $user = $this->userRepository->index($parameters);
            return $this->returnSuccessResponse(Response::HTTP_OK, $user);
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
            $user = $this->userRepository->show($id);
            return $this->returnSuccessResponse(Response::HTTP_OK, ['data' => $user]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorfindAd'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function showVerifiedUsers()
    {
        try {
            $users = $this->userRepository->verifiedUsers();
            return $this->returnSuccessResponse(Response::HTTP_OK, ['data' => $users]);
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }

    /**
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function userNotifications($user_id)
    {
        try {
            $notifications = $this->userRepository->getNotifications($user_id);
            return $this->returnSuccessResponse(Response::HTTP_OK, ['data' => $notifications]);
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }

    /**
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function userUnreadNotifications($user_id)
    {
        try {
            $notifications = $this->userRepository->unreadNotifications($user_id);
            return $this->returnSuccessResponse(Response::HTTP_OK, ['data' => $notifications]);
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }
    public function userUnreadMessages($user_id)
    {
        try {
            $notifications = $this->userRepository->unreadMessage($user_id);
            return $this->returnSuccessResponse(Response::HTTP_OK, ['data' => $notifications]);
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }

    /**
     * @param $notification_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markOneNotificationAsRead($notification_id)
    {
        try {
            $notification = $this->userRepository->markOneNotificationsAsRead($notification_id);

            return $this->returnSuccessResponse(Response::HTTP_OK, ['data' => $notification]);
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans($e->getMessage()));
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllNotificationsAsRead()
    {
        try {
            $notifications = $this->userRepository->markAllNotificationsAsRead();
            return $this->returnSuccessResponse(Response::HTTP_OK, ['data' => $notifications]);
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }
}
