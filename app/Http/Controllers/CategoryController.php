<?php
namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::with("type")->get();
            return ApiResponse::success("Categories retrieved successfully", $categories);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve categories: " . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'title' => 'required|string|max:255',
                'type_id' => 'required|exists:category_types,id',
            ];
            $request->validate($rules);
            $category = Category::create($request->all());
            return ApiResponse::success("Category created successfully", $category, 201);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to create category: " . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            return ApiResponse::success("Category retrieved successfully", $category);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve category: " . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->update($request->all());
            return ApiResponse::success("Category updated successfully", $category);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to update category: " . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return ApiResponse::success("Category deleted successfully", null, 204);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to delete category: " . $e->getMessage());
        }
    }
}
