<?php

namespace App\Http\Controllers\Web\Ad;

use App\Http\Requests\CategoryRequest;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Traits\CategoryTrait;

class CategoryController extends \App\Http\Controllers\Controller
{
    private $category;
    use CategoryTrait;

    public function __construct(CategoryRepository $category)
    {
        $this->category = $category;

    }

    public function index()
    {
        try {
            $category = $this->category->getAllCategories();
            return response()->json(['data' => $category,], 200);
        } catch (Exception $exception) {
            return response()->json(['message' => trans('message.errorShowAllCategory')], 500);
        }
    }

    public function store(CategoryRequest $request)
    {
        try {
            $category = $this->category->create($this->getFillderRequest($request));
            return response()->json(['data' => $category], 201);

        } catch (Exception $exception) {
            return response()->json(['message' => trans('message.errorCreateCategory')], 500);
        }
    }

    public function show($id)
    {
        $category = $this->category->getCategoryById($id);

        try {
            return response()->json([
                'data' => $category
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => trans('message.errorfindCategory')], 500);
        }

    }

    public
    function update(CategoryRequest $request, $id)
    {
        $category = $this->category->UpdateCategory($request, $id);
        if ($category) {
            return response()->json([
                'data' => $category,
            ], 201);
        }
        return response()->json(['message' => trans('message.errorUpdatecategory')], 500);

    }

    public
    function destroy($id)
    {
        $category = $this->category->deleteCategory($id);
        if ($category) {
            return response()->json([
                'message' => trans('message.successDeletedcategory')
            ], 200);
        }
        return response()->json(['message' => trans('message.errorDeletecategory')], 500);


    }
}
