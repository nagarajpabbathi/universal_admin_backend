<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has(['related_entity_type', 'related_entity_id'])) {
            $request->validate([
                'related_entity_type' => 'required|string|in:shop_services,brands,shops',
                'related_entity_id' => 'required|exists:' . $request->related_entity_type . ',id',
            ]);
        }
        try {
            // Check if request parameters are provided
            if ($request->has(['related_entity_type', 'related_entity_id'])) {
                $type = $request->related_entity_type;
                $relatedEntityId = $request->related_entity_id;

                $promotions = Promotion::with("category")
                    ->where('related_entity_type', $type)
                    ->where('related_entity_id', $relatedEntityId)
                    ->get();
            } else {
                // If no parameters are provided, return all promotions
                $promotions = Promotion::with("category")->get();
            }

            return ApiResponse::success("Promotions retrieved successfully", $promotions);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve promotions: " . $e->getMessage(), $e);
        }
    }


    public function store(Request $request)
    {

        $rules = [
            'title' => 'required|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bonus_amount' => 'nullable|numeric',
            'is_free' => 'required|in:true,false',
            'description' => 'nullable|string',
            'question' => 'nullable|string',
            'answer1' => 'nullable|string',
            'answer2' => 'nullable|string',
            'correct_answer' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'is_button_allowed' => 'required|in:true,false',
            'button_label' => 'nullable|string|max:255',
            'button_link' => 'nullable|url',
            'related_entity_id' => 'nullable|integer',
            'related_entity_type' => 'nullable|string',
        ];
        $request->validate($rules);
        try {
            $promotionData = $request->only(array_keys($rules));

            // Get user ID from authenticated user
            $promotionData['user_id'] = Auth::id();

            $promotionData['is_free'] = filter_var($request->input('is_free'), FILTER_VALIDATE_BOOLEAN);
            $promotionData['is_button_allowed'] = filter_var($request->input('is_button_allowed'), FILTER_VALIDATE_BOOLEAN);

            // Upload image if provided
            if ($request->hasFile('featured_image')) {
                $imagePath = $request->file('featured_image')->store('promotion_images');
                $promotionData['featured_image'] = $imagePath;
            }

            $promotion = Promotion::create($promotionData);

            return ApiResponse::success("Promotion created successfully", $promotion, 201);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to create promotion: " . $e->getMessage(), $e);
        }
    }

    public function show($id)
    {
        try {
            $promotion = Promotion::with(['category'])->findOrFail($id);
            return ApiResponse::success("Promotion retrieved successfully", $promotion);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve promotion: " . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bonus_amount' => 'nullable|numeric',
            'is_free' => 'required|in:true,false',
            'description' => 'nullable|string',
            'question' => 'nullable|string',
            'answer1' => 'nullable|string',
            'answer2' => 'nullable|string',
            'correct_answer' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'is_button_allowed' => 'required|in:true,false',
            'button_label' => 'nullable|string|max:255',
            'button_link' => 'nullable|url',
            'related_entity_id' => 'nullable|integer',
            'related_entity_type' => 'nullable|string',
        ];

        $request->validate($rules);
        try {

            $promotion = Promotion::findOrFail($id);
            $promotionData = $request->only(array_keys($rules));

            // Check if image is provided and promotion has an existing image
            if ($request->hasFile('featured_image') && $promotion->featured_image && Storage::exists($promotion->featured_image)) {
                // Delete the existing image file from storage
                Storage::delete($promotion->featured_image);
            }

            // Upload the new image if provided
            if ($request->hasFile('featured_image')) {
                $imagePath = $request->file('featured_image')->store('promotion_images');
                $promotionData['featured_image'] = $imagePath;
            }

            // Get user ID from authenticated user
            $promotionData['user_id'] = Auth::id();

            $promotionData['is_free'] = filter_var($request->input('is_free'), FILTER_VALIDATE_BOOLEAN);
            $promotionData['is_button_allowed'] = filter_var($request->input('is_button_allowed'), FILTER_VALIDATE_BOOLEAN);

            $promotion->update($promotionData);

            return ApiResponse::success("Promotion updated successfully", $promotion);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to update promotion: " . $e->getMessage(), $e);
        }
    }

    public function destroy($id)
    {
        try {
            $promotion = Promotion::findOrFail($id);
            $promotion->delete();
            return ApiResponse::success("Promotion deleted successfully", $promotion, 200);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to delete promotion: " . $e->getMessage());
        }
    }
}
