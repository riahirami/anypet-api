<?php

namespace App\Repositories ;


use Illuminate\Http\Request;

interface CategoryRepositoryInterface

{
    public function getAllCategories();

    public function createCategory(Request $request);
    public function getCategoryById($id);

    public function UpdateCategory( Request $request, $id );

    public function deleteCategory($id);

}
