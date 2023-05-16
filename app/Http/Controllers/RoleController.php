<?php

namespace App\Http\Controllers;

use App\Http\Traits\GlobalTrait;
use App\Repositories\RoleRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends Controller
{

    private $role ;
    use GlobalTrait;

    public function __construct(RoleRepository $role)
    {
        $this->role = $role;
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setAdmin($id){

        try {
            $user = $this->role->setAdmin($id);
            return $this->returnSuccessResponse(Response::HTTP_CREATED, ['message'=>trans('message.successSetAdmin')]);
        }
        catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, ['message'=>trans('message.userNotFound')]);
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.errorSetAdmin'));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function revokeAdmin($id){

        try {
            $user = $this->role->revokeAdmin($id);
            return $this->returnSuccessResponse(Response::HTTP_CREATED,['message'=> trans('message.SuccessRevokeAdmin')]);
        }
        catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND,['message'=> trans('message.userNotFound')]);
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.errorRevokeAdmin'));
        }
    }
}
