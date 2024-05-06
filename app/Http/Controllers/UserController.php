<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $users = User::all();
        $verifiedUsers = User::where('verified_by_admin', true)->get();
        return ApiResponse::success("User details Fetched Successfully", $verifiedUsers, 200);
    }

    public function unVerifiedUsers()
    {
        $verifiedUsers = User::where('verified_by_admin', null)->get();
        return ApiResponse::success("User details Fetched Successfully", $verifiedUsers, 200);
    }

    public function verifyUser(Request $request, $user_id)
    {
        $user = User::find($user_id);
        if (!$user) {
            return ApiResponse::error("User not found", null, 404);
        }
        $user->update(['verified_by_admin' => true]);
        return ApiResponse::success("User verified successfully", $user, 200);
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            if ($user->role === 'admin') {
                return ApiResponse::error("Cannot delete admin user", null, 403);
            }
            $user->delete();

            return ApiResponse::success("User deleted successfully", null, 200);
        } catch (\Exception $e) {
            // Handle exceptions (e.g., model not found) and return an error response
            return ApiResponse::error("Failed to delete user", $e, 500);
        }
    }
    public function updatePassword(Request $request)
    {
        try {
            $user = Auth::user();

            $request->validate([
                'current_password' => "required|min:6",
                'new_password' => "required|min:6"
            ]);

            if (Hash::check($request->current_password, $user->password)) {
                $user->update(['password' => bcrypt($request->new_password)]);
            } else {
                return ApiResponse::error("Incorrect Old Password");
            }
            return ApiResponse::success("Password Updated successfully", null, 200);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to Update the Password", null, 500);
        }
    }
    public function updateProfile(Request $request)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Validate the incoming request data
        $validatedData =  $request->validate([
            'name' => 'nullable|string',
            'display_name' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'country' => 'nullable|string',
            'hired_by' => 'nullable|string',
            'bjt_profile_link' => 'nullable|string',
            'is_full_time' => 'nullable|boolean',
            'is_office_staff' => 'nullable|boolean',
            'salary_amount' => 'nullable|numeric',
            'payment' => 'nullable|string',
            'upwork_id' => 'nullable|string',
            'fiver_id' => 'nullable|string',
            'freelancer_id' => 'nullable|string',
            'paypal_email' => 'nullable|email',
            'local_bank_details' => 'nullable|string',
            'binance_email' => 'nullable|email',
            'usdt_address' => 'nullable|string',
            'salary_claim_count' => 'nullable|integer',
            'contribution_points' => 'nullable|integer',
            'position' => 'nullable|string',
        ]);


        $user->update($validatedData);

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $user->update(['profile_image' => $imagePath]);
        }

        return ApiResponse::success("Profile updated successfully", $user, 200);
    }
}
