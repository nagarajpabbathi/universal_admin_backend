<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Models\CategoryType;

class CategoryTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $categoryTypesQuery = CategoryType::query();

            if ($request->has('populate')) {
                $categoryTypesQuery->with('categories');
            }
            $categoryTypes = $categoryTypesQuery->get();
            return ApiResponse::success("Category types retrieved successfully", $categoryTypes);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve category types: " . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'title' => 'required|string|max:255',
            ];
            $request->validate($rules);
            $categoryType = CategoryType::create($request->all());
            return ApiResponse::success("Category type created successfully", $categoryType, 201);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to create category type: " . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $categoryType = CategoryType::findOrFail($id);
            return ApiResponse::success("Category type retrieved successfully", $categoryType);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve category type: " . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $categoryType = CategoryType::findOrFail($id);
            $categoryType->update($request->all());
            return ApiResponse::success("Category type updated successfully", $categoryType);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to update category type: " . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $categoryType = CategoryType::findOrFail($id);
            $categoryType->delete();
            return ApiResponse::success("Category type deleted successfully", null, 204);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to delete category type: " . $e->getMessage());
        }
    }
}
