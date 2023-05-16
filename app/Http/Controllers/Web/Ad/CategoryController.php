<?php

namespace App\Http\Controllers\Web\Ad;

use App\Http\Requests\CategoryRequest;
use App\Http\Traits\GlobalTrait;
use App\Repositories\CategoryRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Traits\CategoryTrait;
use Illuminate\Http\Response;

class CategoryController extends \App\Http\Controllers\Controller
{
    private $category;
    use CategoryTrait;
    use GlobalTrait;


    public function __construct(CategoryRepository $category)
    {
        $this->category = $category;

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $category = $this->category->index([
                'perpage' => $request->perpage,
                'orderBy' => $request->orderBy,
                'order_direction' => $request->order_direction,
                'page' => 'page',
            ]);
            return $this->returnSuccessResponse(Response::HTTP_OK, $category);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorShowAllCategory'));
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
        $category = $this->category->show($id);
            return $this->returnSuccessResponse(Response::HTTP_OK, ['data' => $category]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorfindCategory'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $attribute = $this->getFillderRequest($request);
            $category = $this->category->create($attribute);
            return $this->returnSuccessResponse(Response::HTTP_CREATED, ['data' => $category]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorCreateCategory'));
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
            $attribute = $this->getFillderRequest($request);
            $category = $this->category->Update($attribute, $id);
            return $this->returnSuccessResponse(Response::HTTP_CREATED, ['data' => $category]);
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorUpdatecategory'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function destroy($id)
    {
        try {
            $category = $this->category->delete($id);
            return $this->returnSuccessResponse(Response::HTTP_OK,
                trans('message.successDeletedcategory')
            );
        } catch (ModelNotFoundException) {
            return $this->returnErrorResponse(Response::HTTP_NOT_FOUND, trans('message.errorDeletecategory'));
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('message.ERROR'));
        }

    }
}
