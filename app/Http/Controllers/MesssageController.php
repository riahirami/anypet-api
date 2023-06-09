<?php

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use App\Http\Traits\GlobalTrait;
use App\Models\Messages;
use App\Models\User;
use App\Notifications\MessageNotification;
use App\Repositories\MessageRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class MesssageController extends Controller
{
    use GlobalTrait;
    protected $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }
    public function getConversationMsg(Request $request)
    {
        try {
            $conversationMsg = $this->messageRepository->getConversationMsg($request);
            return $this->returnSuccessResponse(Response::HTTP_OK, $conversationMsg);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorGetConversation'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }

    public function sendMessage(Request $request)
    {
        try {
            $response = $this->messageRepository->sendMessage($request);
            return $this->returnSuccessResponse(Response::HTTP_CREATED, $response->original);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorSendMessage'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans($e->getMessage()));
        }
    }

    public function conversations(Request $request)
    {
        try {
            $conversations = $this->messageRepository->getConversations();
            return $this->returnSuccessResponse(Response::HTTP_OK, $conversations);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorGetConversation'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }

    public function contactAdmin(Request $request)
    {
        try {
            $response = $this->messageRepository->ContactAdmin($request);
            return $this->returnSuccessResponse(Response::HTTP_CREATED, $response->original);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorSendMessage'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans($e->getMessage()));
        }
    }
}
