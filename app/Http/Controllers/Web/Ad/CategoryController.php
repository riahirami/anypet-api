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
        $category = $this->category->getAllCategories();
        if($category) {
            return response()->json(['data' => $category,], 200);
        }
        return  response()->json(['message' => "no data found !",], 500);
    }

    public function store(CategoryRequest $request)
    {
        try {
            $validated = $request->validated();

            $category = $this->category->create($this->getFillderRequest($request));
            return response()->json(['category' => $category], 201);

        } catch (Exception $exception) {
            return response()->json(['message' => __('message.error')], 500);
        }
    }

    public function show($id)
    {
        $category = $this->category->getCategoryById($id);

        try {
            if (!$category) {
                return response()->json([
                    'message' => "no category found",
                ], 500);

            }
            return response()->json([
                'results' => $category
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => "error",], 500);
        }

    }

    public
    function update(CategoryRequest $request, $id)
    {
        $validated = $request->validated();

        $category = $this->category->UpdateCategory($request, $id);
        if ($category) {
            return response()->json([
                'message' => 'category updated successfully',
                'category' => $category,
            ]);
        }
        return response()->json(['message' => "error",], 500);

    }

    public
    function destroy($id)
    {
        $category = $this->category->deleteCategory($id);
        if ($category) {
            return response()->json([
                'message' => 'category deleted successfully',
                'category' => $category,
            ]);
        }
        return response()->json(['message' => "error",], 500);


    }
}
