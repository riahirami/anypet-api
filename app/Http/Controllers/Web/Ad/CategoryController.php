<?php

namespace App\Http\Controllers\Web\Ad;

use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends \App\Http\Controllers\Controller
{
    private $category;

    public function __construct(CategoryRepositoryInterface $category)
    {
        $this->middleware('auth:api');
        $this->category = $category;

    }

    public function index()
    {
        return $category = $this->category->getAllCategories();

    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:12|max:255'
        ]);

        return $category = Category::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);


    }

    public function show($id)
    {
        return $category = $this->category->getCategoryById($id);

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:12|max:255'
        ]);

        return $category = $this->category->UpdateCategory($request, $id);
    }

    public function destroy($id)
    {
        return $category = $this->category->deleteCategory($id);


    }
}
