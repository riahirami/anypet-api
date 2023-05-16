<?php

namespace App\Http\Controllers;

use App\Http\Traits\GlobalTrait;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{

    protected $commentsRepository;
    use GlobalTrait;

    public function __construct(CommentRepository $commentsRepository)
    {
        $this->commentsRepository = $commentsRepository;
    }

    /**
     * @param $ad_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($ad_id)
    {
        try {
            $comments = $this->commentsRepository->listAdComments($ad_id);
            return $this->returnSuccessResponse(Response::HTTP_OK, $comments);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorListAds'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }

    /**
     * @param $ad_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create($ad_id, Request $request)
    {
        $user_id = auth()->id();
        try {
            $comment = $this->commentsRepository->addComment($user_id, $ad_id, $request);
            return $this->returnSuccessResponse(Response::HTTP_CREATED, ['data' => $comment]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorFindAd'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }


    /**
     * store a new comment as a response on an existing one.
     */
    public function store()
    {

    }

    /**
     * @param $ad_id
     * @param $parent_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function replyComment($ad_id, $parent_id, Request $request)
    {
        $user_id = auth()->id();
        try {
            $comment = $this->commentsRepository->replyComment($user_id, $ad_id, $parent_id, $request);
            return $this->returnSuccessResponse(Response::HTTP_CREATED, ['data' => $comment]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorFindAd'));
        } catch (Exception $exception) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $exception->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $comment = $this->commentsRepository->delete($id);
            return $this->returnSuccessResponse(Response::HTTP_CREATED, ['data' => $comment]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorFindAd'));
        } catch (Exception $exception) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $exception->getMessage());
        }

    }
}
