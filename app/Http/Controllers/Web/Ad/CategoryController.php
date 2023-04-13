<?php

namespace App\Http\Controllers\Web\Ad;

use App\Http\Requests\CategoryRequest;
use App\Http\Traits\GlobalTrait;
use App\Repositories\CategoryRepository;
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

    public function index()
    {
        try {
            $category = $this->category->getAllCategories();
            return $this->returnSuccessResponse(200, ['data' => $category]);
        } catch (Exception $exception) {
            return $this->returnErrorResponse(400, trans('message.errorShowAllCategory'));
        }
    }

    public function store(Request $request)
    {
        try {
            $category = $this->category->create($this->getFillderRequest($request));
            return $this->returnSuccessResponse(201, ['data' => $category]);

        } catch (Exception $exception) {
            return $this->returnErrorResponse(400, trans('message.errorCreateCategory'));
        }
    }

    public function show($id)
    {
        $category = $this->category->getCategoryById($id);

        try {
            return $this->returnSuccessResponse(200, ['data' => $category]);

        } catch (\Exception $e) {
            return $this->returnErrorResponse(400, trans('message.errorfindCategory'));
        }

    }

    public function update(Request $request, $id)
    {
        $category = $this->category->UpdateCategory($request, $id);
        if ($category) {
            return $this->returnSuccessResponse(201, ['data' => $category]);

        }
        return $this->returnErrorResponse(400, trans('message.errorUpdatecategory'));

    }

    public
    function destroy($id)
    {
        $category = $this->category->deleteCategory($id);
        if ($category) {
            return $this->returnSuccessResponse(200,
                trans('message.successDeletedcategory')
            );
        }
        return $this->returnErrorResponse(400, trans('message.errorDeletecategory'));


    }
}
