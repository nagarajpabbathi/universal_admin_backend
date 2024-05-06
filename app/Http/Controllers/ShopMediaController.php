<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\ShopMedia;
use Illuminate\Http\Request;

class ShopMediaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $shop_id  = $request->input('shop_id');
            $shopMedia = ShopMedia::where('shop_id', $shop_id)->get();
            return ApiResponse::success("Shop media retrieved successfully", $shopMedia);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve shop media: " . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {

            $rules = [
                'shop_id' => 'required|exists:shops,id',
                'media_type' => 'required|string|in:youtube_url,video,image',
                'section' => 'nullable',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4048',
            ];

            $request->validate($rules);
            $shopMediaData = $request->only('shop_id', 'media_type', "section");

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('shop_images');
                $shopMediaData['media_url'] = $imagePath;
            }
            else {
                $shopMediaData['media_url'] = $request->input('media_url');
            }

            $shopMedia = ShopMedia::create($shopMediaData);
            return ApiResponse::success("Shop media created successfully", $shopMedia, 201);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to create shop media: " . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $shopMedia = ShopMedia::findOrFail($id);
            return ApiResponse::success("Shop media retrieved successfully", $shopMedia);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve shop media: " . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $shopMedia = ShopMedia::findOrFail($id);
            $shopMedia->update($request->all());
            return ApiResponse::success("Shop media updated successfully", $shopMedia);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to update shop media: " . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $shopMedia = ShopMedia::findOrFail($id);
            $shopMedia->delete();
            return ApiResponse::success("Shop media deleted successfully", $shopMedia, 200);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to delete shop media: " . $e->getMessage());
        }
    }
}
