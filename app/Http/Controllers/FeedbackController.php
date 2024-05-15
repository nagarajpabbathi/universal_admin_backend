<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;



class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        try {
            $request->validate([
                'related_entity_type' => 'required|string|in:shop_services,brands,shops',
                'related_entity_id' => 'required|exists:' . $request->related_entity_type . ',id',
            ]);
    
            $type = $request->related_entity_type;
            $relatedEntityId = $request->related_entity_id;
    
            $feedbacks = Feedback::with("user")->where('related_entity_type', $type)
                ->where('related_entity_id', $relatedEntityId)
                ->get();
    
            return ApiResponse::success("Feedbacks retrieved successfully", $feedbacks);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve feedbacks: " . $e->getMessage(),$e);
        }
    }
    

    public function store(Request $request)
    {
        try {
            $rules = [
                'related_entity_type' => 'required|string|in:shop_services,brands,shops',
                'related_entity_id' => 'required|exists:' . $request->related_entity_type . ',id',
                'positivity' => 'required|string',
                'comment_description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'amount_purchased' => 'nullable|integer',
                'rating_count' => 'nullable|integer',
                'served_by' => 'nullable|string',

            ];
            $request->validate($rules);




            $feedbackData = $request->only('related_entity_type', 'related_entity_id', 'positivity', 'comment_description', 'amount_purchased', 'served_by','rating_count');

            // Upload image if provided
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('feedback_images');
                $feedbackData['image'] = $imagePath;
            }
            $user = Auth::user();
            $userId = $user->id;
            $feedbackData['user_id'] = $userId;
            $feedback = Feedback::create($feedbackData);

            return ApiResponse::success("Feedback created successfully", $feedback, 201);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to create feedback: " . $e->getMessage(), $e);
        }
    }

    public function show($id)
    {
        try {
            $feedback = Feedback::findOrFail($id);
            return ApiResponse::success("Feedback retrieved successfully", $feedback);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve feedback: " . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $rules = [
                'positivity' => 'required|string',
                'comment_description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'amount_purchased' => 'nullable|integer',
                'served_by' => 'nullable|string',
            ];

            $request->validate($rules);
            $feedback = Feedback::findOrFail($id);
            $feedbackData = $request->only('positivity', 'comment_description', 'amount_purchased', 'served_by');

            // Check if image is provided and feedback has an existing image
            if ($request->hasFile('image') && $feedback->image && Storage::exists($feedback->image)) {
                // Delete the existing image file from storage
                Storage::delete($feedback->image);
            }
            $user = Auth::user();
            $userId = $user->id;
            $feedbackData['user_id'] = $userId;

            // Upload the new image if provided
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('feedback_images');
                $feedbackData['image'] = $imagePath;
            }

            $feedback->update($feedbackData);

            return ApiResponse::success("Feedback updated successfully", $feedback);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to update feedback: " . $e->getMessage(), $e);
        }
    }

    public function destroy($id)
    {
        try {
            $feedback = Feedback::findOrFail($id);
            $feedback->delete();
            return ApiResponse::success("Feedback deleted successfully", $feedback, 200);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to delete feedback: " . $e->getMessage());
        }
    }
}
