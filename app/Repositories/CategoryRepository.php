<?php

namespace App\Repositories;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryRepository
{
    protected $category = null;


    //listing
    public function index()
    {
//              $value = "";
//              $categories =   Category::byTitle($value)
//                                        ->byName($value)
//                                        ->byDate($value)
//                                        ->get();
    }

    public function create(array $data)
    {
        $category = new Category();
        $category->title = $data['title'];
        $category->description = $data['description'];
        $category->save();
        return $category;
    }

    public function getAllCategories()
    {
        $category = Category::All();
        return $category;

    }

    public function getCategoryById($id)
    {
        $category = Category::find($id);
        return $category;


    }

    public function UpdateCategory(CategoryRequest $request, $id)
    {

        $validated = $request->validated();

        $category = Category::find($id);
        $category->title = $request->title;
        $category->description = $request->description;
        $category->save();

        return $category;
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id)->delete();
        return $category;
    }
}
