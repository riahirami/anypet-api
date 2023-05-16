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

        $perPage = $data['perpage'] ?? config('constants.PER_PAGE');
        $orderBy = $data['orderBy'] ?? config('constants.ORDER_BY');
        $orderDirection = $data['order_direction'] ?? config('constants.ORDER_DIRECTION');
        $page = $data['page'] ?? config('constants.PAGE');
        return Category::query()
            ->orderBy($orderBy, $orderDirection)
            ->paginate($perPage, ['*'], $page);
    }

    function create(array $data)
    {
        $category = new Category();
        $category->title = $data['title'];
        $category->save();
        return $category;
    }

    public function Update(array $data, $id)
    {
        $category = Category::find($id);
        $category->title = $data['title'];
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
