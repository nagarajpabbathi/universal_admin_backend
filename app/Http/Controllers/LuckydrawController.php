<?php

namespace App\Http\Controllers;

use App\Models\Luckydraw;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Storage;

class LuckydrawController extends Controller
{
    public function index(Request $request)
    {
        try {
            $luckydraws = Luckydraw::get();
            return ApiResponse::success("Luckydraws retrieved successfully", $luckydraws);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve luckydraws: " . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'title' => 'required|string|max:255',
                'logo_image' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'cover_image' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'text_area' => 'required|string',
                'category' => 'required|string',
                'prize_amount' => 'required|numeric',
                'entry_amount' => 'required|numeric',
                'max_tickets_per_submission' => 'required|integer',
                'lucky_number_digits' => 'required|integer',
                'allow_upload_receipt' => 'required|in:true,false',
                'expired_date' => 'required|date',
                'draw_date' => 'required|date',
            ];
            $request->validate($rules);

            // Handle file uploads
            $logoImagePath = $request->file('logo_image')->store('logo_images', 'public');
            $coverImagePath = $request->file('cover_image')->store('cover_images', 'public');

            $luckydraw = Luckydraw::create([
                'title' => $request->title,
                'logo_image' => $logoImagePath,
                'cover_image' => $coverImagePath,
                'text_area' => $request->text_area,
                'category' => $request->category,
                'prize_amount' => $request->prize_amount,
                'entry_amount' => $request->entry_amount,
                'max_tickets_per_submission' => $request->max_tickets_per_submission,
                'lucky_number_digits' => $request->lucky_number_digits,
                'allow_upload_receipt' => filter_var($request->allow_upload_receipt, FILTER_VALIDATE_BOOLEAN),
                'expired_date' => $request->expired_date,
                'draw_date' => $request->draw_date,
            ]);

            return ApiResponse::success("Luckydraw created successfully", $luckydraw, 201);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to create luckydraw: " . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $luckydraw = Luckydraw::findOrFail($id);
            return ApiResponse::success("Luckydraw retrieved successfully", $luckydraw);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve luckydraw: " . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $rules = [
                'title' => 'sometimes|required|string|max:255',
                'logo_image' => 'sometimes|required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'cover_image' => 'sometimes|required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'text_area' => 'sometimes|required|string',
                'category' => 'sometimes|required|string',
                'prize_amount' => 'sometimes|required|numeric',
                'entry_amount' => 'sometimes|required|numeric',
                'max_tickets_per_submission' => 'sometimes|required|integer',
                'lucky_number_digits' => 'sometimes|required|integer',
                'allow_upload_receipt' => 'sometimes|required|in:true,false',
                'expired_date' => 'sometimes|required|date',
                'draw_date' => 'sometimes|required|date',
            ];
            $request->validate($rules);

            $luckydraw = Luckydraw::findOrFail($id);

            // Handle file uploads
            if ($request->hasFile('logo_image')) {
                $logoImagePath = $request->file('logo_image')->store('logo_images', 'public');
                // Delete the old image
                Storage::disk('public')->delete($luckydraw->logo_image);
                $luckydraw->logo_image = $logoImagePath;
            }

            if ($request->hasFile('cover_image')) {
                $coverImagePath = $request->file('cover_image')->store('cover_images', 'public');
                // Delete the old image
                Storage::disk('public')->delete($luckydraw->cover_image);
                $luckydraw->cover_image = $coverImagePath;
            }

            $luckydraw->update(array_merge(
                $request->except(['logo_image', 'cover_image']),
                ['allow_upload_receipt' => filter_var($request->allow_upload_receipt, FILTER_VALIDATE_BOOLEAN)]
            ));

            return ApiResponse::success("Luckydraw updated successfully", $luckydraw);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to update luckydraw: " . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $luckydraw = Luckydraw::findOrFail($id);
            // Delete the images
            Storage::disk('public')->delete($luckydraw->logo_image);
            Storage::disk('public')->delete($luckydraw->cover_image);
            $luckydraw->delete();
            return ApiResponse::success("Luckydraw deleted successfully", null, 200);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to delete luckydraw: " . $e->getMessage());
        }
    }
}
