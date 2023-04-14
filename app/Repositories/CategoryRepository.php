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
    static function index(array $data)
    {
//              $value = "";
//              $categories =   Category::byTitle($value)
//                                        ->byName($value)
//                                        ->byDate($value)
//                                        ->get();
        $perPage = $data['perpage'] ?? config('constant.per_page');
        $orderBy = $data['orderBy'] ?? config('constant.orderBy');
        $orderDirection = $data['order_direction'] ?? config('constant.orderDirection');
        $page = $data['page'] ?? config('constant.page');

        return Category::query()
            ->orderBy($orderBy, $orderDirection)
            ->paginate($perPage, ['*'], $page);
    }

    function create(array $data)
    {
        $category = new Category();
        $category->title = $data['title'];
        $category->description = $data['description'];
        $category->save();
        return $category;
    }

    public function Update(array $data, $id)
    {
        $category = Category::find($id);
        $category->title = $data['title'];
        $category->description = $data['description'];
        $category->save();
        return $category;

    }

    public function show($id)
    {
        $category = Category::find($id);
        return $category;


    }



    public function delete($id)
    {

        $category = Category::find($id)->delete();
        return $category;
    }
}
