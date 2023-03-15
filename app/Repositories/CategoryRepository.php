<?php

namespace  App\Repositories;

use App\Models\Category ;
use Illuminate\Http\Request;
class CategoryRepository implements CategoryRepositoryInterface {
    protected $category = null;

    public function getAllCategories()
    {
        $category = Category::all();
        return response()->json([
            'status' => 'success',
            'categories' => $category,
        ]);
    }

    public function createCategory(Request $request)
    {

        try {

            $newCategory = new Category();
            $newCategory->title = $request->title;
            $newCategory->description =$request->description;
            $newCategory->save();

            return response()->json([
                'message' => 'User created',
                'code' => 200,
                'error' => false,
                'results' => $newCategory
            ], 201);
        } catch(\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'error' => true,
                'code' => 500
            ], 500);
        }
    }

    public function getCategoryById($id)
    {
        try {
            $category = Category::find($id);
            if($category) {

                return response()->json([
                    'message' => 'category find',
                    'code' => 200,
                    'error' => false,
                    'results' => $category
                ], 201);
            }
            else {
                return response()->json([
                    'message' => "no category found",
                    'error' => true,
                    'code' => 500
                ], 500);
            }

        } catch(\Exception $e) {
            return response()->json([
                'message' => "error",
                'error' => true,
                'code' => 500
            ], 500);
        }

    }

    public function UpdateCategory(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $category = Category::find($id);
        $category->title = $request->title;
        $category->description = $request->description;
        $category->save();

        return response()->json([
            'status' => 'success',
            'message' => 'category updated successfully',
            'todo' => $category,
        ]);
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id)->delete();
        return     response()->json([
        'status' => 'success',
        'message' => 'category deleted successfully',
        'todo' => $category,
    ]);
    }
}
