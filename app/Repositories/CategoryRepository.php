<?php

namespace App\Repositories;

use App\Http\Requests\CategoryRequest;
use App\Http\Traits\CategoryTrait;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryRepository
{
    protected $category = null;
    use CategoryTrait;



    //listing
    public function index()
    {
//              $value = "";
//              $categories =   Category::byTitle($value)
//                                        ->byName($value)
//                                        ->byDate($value)
//                                        ->get();
    }

    public function create(CategoryRequest $request)
    {
        $category = new Category();
        $data = $this->getFillderRequest($request);
        $fillable = $category->getFillable();
        foreach ($fillable as $field) {
            if (isset($data[$field])) {
                $category->{$field} = $data[$field];
            }
        }
        $category->save();
        return $category;
    }

    public function UpdateCategory(Request $request, $id)
    {
        $category = Category::find($id);
        $data = $this->getFillderRequest($request);
        $fillable = $category->getFillable();
        foreach ($fillable as $field) {
            if (isset($data[$field])) {
                $category->{$field} = $data[$field];
            }
        }
        $category->save();
        return $category;

    }

    public function getAllCategories()
    {
//        $category = Category::orderBy('title')->cursorPaginate(10);
//        return $category;

        $category = $this->querygetAllData(config('constant.cursorPaginate'), config('constant.orderDirection'), config('constant.orderBy'));
        return $category;
    }

    public function getCategoryById($id)
    {
        $category = Category::find($id);
        return $category;


    }



    public function deleteCategory($id)
    {

        $category = Category::find($id)->delete();
        return $category;
    }
}
