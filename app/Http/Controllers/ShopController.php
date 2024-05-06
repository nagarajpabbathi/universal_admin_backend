<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Shop;
use App\Models\ShopService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ShopController extends Controller
{
    public function index()
    {
        try {
            // $shops = Shop::all();
            $shops = Shop::with("categories")->get();
            return ApiResponse::success("Shops retrieved successfully", $shops);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve shops: " . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|string|unique:shops|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'category_ids' => 'required|string',
                'facebook' => 'nullable|string',
                'twitter' => 'nullable|string',
                'instagram' => 'nullable|string',
            ];
            $request->validate($rules);

            $shopData = $request->only('name', 'description', 'facebook', 'twitter', 'instagram');

            // Upload image if provided
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('shop_images');
                $shopData['image'] = $imagePath;
            }

            $shop = Shop::create($shopData);
            $categoryIds = $request->input('category_ids');
            $categoryIds = json_decode($categoryIds, true);
            $shop->categories()->attach($categoryIds);


            return ApiResponse::success("Shop created successfully", $shop, 201);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to create shop: " . $e->getMessage(), $e);
        }
    }

    public function show($id)
    {
        try {

            $shop = Shop::with(['services', 'media', 'categories'])->where("id", $id)->first();
            return ApiResponse::success("Shop retrieved successfully", $shop);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve shop: " . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $rules = [
                'name' => 'required|string',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'category_ids' => 'required|string',
                'facebook' => 'nullable|string',
                'twitter' => 'nullable|string',
                'instagram' => 'nullable|string',

            ];

            $request->validate($rules);
            $shop = Shop::findOrFail($id);
            $shopData = $request->only('name', 'description', 'facebook', 'twitter', 'instagram');

            // Check if image is provided and shop has an existing image
            if ($request->hasFile('image') && $shop->image && Storage::exists($shop->image)) {
                // Delete the existing image file from storage
                Storage::delete($shop->image);
            }

            // Upload the new image if provided
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('shop_images');
                $shopData['image'] = $imagePath;
            }

            $shop->update($shopData);
            $categoryIds = $request->input('category_ids');
            $categoryIds = json_decode($categoryIds, true);
            $shop->categories()->sync($categoryIds);

            // $serviceIds = explode(',', $request->input('service_ids'));
            // $shop->services()->attach($serviceIds);

            return ApiResponse::success("Shop updated successfully", $shop);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to update shop: " . $e->getMessage(), $e);
        }
    }

    public function destroy($id)
    {
        try {
            $shop = Shop::findOrFail($id);

            $shop->delete();
            return ApiResponse::success("Shop deleted successfully", $shop, 200);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to delete shop: " . $e->getMessage());
        }
    }
}
