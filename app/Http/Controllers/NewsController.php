<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        try {
            // $shop_id = $request->input('shop_id');
            $news = News::get();
            return ApiResponse::success("News retrieved successfully", $news);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve news: " . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'shop_id' => 'nullable|exists:shops,id',
                'title' => 'required|string|max:255',
                'featured_image' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'text_area' => 'required|string',
                'category' => 'required|string',
                'question' => 'required|string',
                'answer1' => 'required|string',
                'answer2' => 'required|string',
                'correct_answer' => 'required|string',
                'youtube_link' => 'nullable|string',
                'upload_video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:10000',
            ];
            $request->validate($rules);

            // Handle file uploads
            $featuredImagePath = $request->file('featured_image')->store('featured_images', 'public');
            $uploadVideoPath = $request->hasFile('upload_video') ? $request->file('upload_video')->store('videos', 'public') : null;

            $news = News::create([
                'shop_id' => $request->shop_id,
                'title' => $request->title,
                'featured_image' => $featuredImagePath,
                'text_area' => $request->text_area,
                'category' => $request->category,
                'question' => $request->question,
                'answer1' => $request->answer1,
                'answer2' => $request->answer2,
                'correct_answer' => $request->correct_answer,
                'youtube_link' => $request->youtube_link,
                'upload_video' => $uploadVideoPath,
            ]);

            return ApiResponse::success("News created successfully", $news, 201);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to create news: " . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $news = News::findOrFail($id);
            return ApiResponse::success("News retrieved successfully", $news);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve news: " . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $rules = [
                'shop_id' => 'nullable|exists:shops,id',
                'title' => 'sometimes|required|string|max:255',
                'featured_image' => 'sometimes|required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'text_area' => 'sometimes|required|string',
                'category' => 'sometimes|required|string',
                'question' => 'sometimes|required|string',
                'answer1' => 'sometimes|required|string',
                'answer2' => 'sometimes|required|string',
                'correct_answer' => 'sometimes|required|string',
                'youtube_link' => 'nullable|string',
                'upload_video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:10000',
            ];
            $request->validate($rules);

            $news = News::findOrFail($id);

            // Handle file uploads
            if ($request->hasFile('featured_image')) {
                $featuredImagePath = $request->file('featured_image')->store('featured_images', 'public');
                // Delete the old image
                Storage::disk('public')->delete($news->featured_image);
                $news->featured_image = $featuredImagePath;
            }

            if ($request->hasFile('upload_video')) {
                $uploadVideoPath = $request->file('upload_video')->store('videos', 'public');
                // Delete the old video
                Storage::disk('public')->delete($news->upload_video);
                $news->upload_video = $uploadVideoPath;
            }

            $news->update($request->except(['featured_image', 'upload_video']));

            return ApiResponse::success("News updated successfully", $news);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to update news: " . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $news = News::findOrFail($id);
            // Delete the images and videos
            if ($news->featured_image && Storage::disk('public')->exists($news->featured_image)) {
                Storage::disk('public')->delete($news->featured_image);
            }
            if ($news->upload_video && Storage::disk('public')->exists($news->upload_video)) {
                Storage::disk('public')->delete($news->upload_video);
            }
            $news->delete();
            return ApiResponse::success("News deleted successfully", null, 200);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to delete news: " . $e->getMessage());
        }
    }
}
