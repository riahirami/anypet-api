<?php

namespace App\Http\Controllers\Web\Ad;

//use App\Repositories\CategoryRepositoryInterface;
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
        return response()->json(['data' => $category,], 200);

    }

    public function store(CategoryRequest $request)
    {
        try {
            $validated = $request->validated();

            $category = $this->category->create($this->getFillderRequest($request));
            return response()->json(['category' => $category], 201);

        } catch (Exception $exception) {
            return response()->json(['message' => __('message.error')], 400);
        }
    }

    public function show($id)
    {
        return $category = $this->category->getCategoryById($id);

    }

    public function update(CategoryRequest $request, $id)
    {
        $validated = $request->validated();

        return $category = $this->category->UpdateCategory($request, $id);
    }

    public function destroy($id)
    {
        return $category = $this->category->deleteCategory($id);


    }
}
