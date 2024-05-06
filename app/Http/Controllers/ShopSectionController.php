<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\ShopSection;
use Illuminate\Http\Request;

class ShopSectionController extends Controller
{
    public function index()
    {
        try {
            $shopSections = ShopSection::all();
            return ApiResponse::success("Shop sections retrieved successfully", $shopSections);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve shop sections: " . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'title' => 'required|string|unique:shop_sections|max:255',
            ];
            $request->validate($rules);
            $shopSection = ShopSection::create($request->all());
            return ApiResponse::success("Shop section created successfully", $shopSection, 201);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to create shop section: " . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $shopSection = ShopSection::findOrFail($id);
            return ApiResponse::success("Shop section retrieved successfully", $shopSection);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve shop section: " . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $shopSection = ShopSection::findOrFail($id);
            $shopSection->update($request->all());
            return ApiResponse::success("Shop section updated successfully", $shopSection);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to update shop section: " . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $shopSection = ShopSection::findOrFail($id);
            $shopSection->delete();
            return ApiResponse::success("Shop section deleted successfully", null, 204);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to delete shop section: " . $e->getMessage());
        }
    }
}
