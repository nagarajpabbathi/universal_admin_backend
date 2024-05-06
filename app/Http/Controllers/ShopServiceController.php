<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\ShopService;
use Illuminate\Http\Request;

class ShopServiceController extends Controller
{
    public function index(Request $request)
    {
        try {
            $shop_id  = $request->input('shop_id');
            $shopServices = ShopService::where('shop_id', $shop_id)->get();
            return ApiResponse::success("Shop services retrieved successfully", $shopServices);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve shop services: " . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'shop_id' => 'required|exists:shops,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'cost' => 'nullable|numeric',
                'image' => 'nullable|string',
                'rating' => 'nullable|integer|min:1|max:5',
            ];
            $request->validate($rules);
            $shopService = ShopService::create($request->all());
            return ApiResponse::success("Shop service created successfully", $shopService, 201);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to create shop service: " . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $shopService = ShopService::findOrFail($id);
            return ApiResponse::success("Shop service retrieved successfully", $shopService);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve shop service: " . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $shopService = ShopService::findOrFail($id);
            $shopService->update($request->all());
            return ApiResponse::success("Shop service updated successfully", $shopService);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to update shop service: " . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $shopService = ShopService::findOrFail($id);
            $shopService->delete();
            return ApiResponse::success("Shop service deleted successfully", null, 200);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to delete shop service: " . $e->getMessage());
        }
    }
}
