<?php

namespace App\Http\Controllers\Web\Ad;

use App\Http\Requests\CategoryRequest;
use App\Http\Traits\GlobalTrait;
use App\Repositories\CategoryRepository;
use Exception;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Traits\CategoryTrait;

class CategoryController extends \App\Http\Controllers\Controller
{
    private $category;
    use CategoryTrait;
    use GlobalTrait;


    public function __construct(CategoryRepository $category)
    {
        $this->category = $category;

    }

    public function index(array $data = [])
    {
        try {
            $category = $this->category->index($data);
            return $this->returnSuccessResponse(200, $category);
        } catch (Exception $exception) {
            return $this->returnErrorResponse(400, trans('message.errorShowAllCategory'));
        }
    }

    public function show($id)
    {
        $category = $this->category->show($id);

        try {
            return $this->returnSuccessResponse(200,['data'=>$category]);

        } catch (\Exception $e) {
            return $this->returnErrorResponse(400, trans('message.errorfindCategory'));
        }

    }

    public function store(Request $request)
    {
        try {
            $attribute = $this->getFillderRequest($request);
            $category = $this->category->create($attribute);
            return $this->returnSuccessResponse(201, ['data'=>$category]);

        } catch (Exception $exception) {
            return $this->returnErrorResponse(400, trans('message.errorCreateCategory'));
        }
    }


    public function update(CategoryRequest $request, $id)
    {
        try {
            $attribute = $this->getFillderRequest($request);
            $category = $this->category->Update( $attribute, $id);
            return $this->returnSuccessResponse(201, ['data'=>$category]);
        } catch (Exception $exception) {
            return $this->returnErrorResponse(400, trans('message.errorUpdatecategory'));
        }
    }

    public
    function destroy($id)
    {
        $category = $this->category->delete($id);
        if ($category) {
            return $this->returnSuccessResponse(200,
                trans('message.successDeletedcategory')
            );
        }
        return $this->returnErrorResponse(400, trans('message.errorDeletecategory'));


    }
}
