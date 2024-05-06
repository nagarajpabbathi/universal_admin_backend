<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('MyAppToken')->accessToken;
            $respone = ["user" => $user, 'token' => $token];
            return ApiResponse::success('Logged In Successfully', $respone, 200);
        } else {
            return ApiResponse::error('Unauthenticated', 401);
        }
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'position' => 'nullable|string',
            'hired_by' => 'nullable|string',

            
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            "position" => $request->position,
        ]);
        $user->save();

        $token = $user->createToken('MyAppToken')->accessToken;
        $respone = ["user" => $user, 'token' => $token];

        return ApiResponse::success('User Registered Successfully', $respone, 201);
    }

    public function handleGoogleCallback(Request $request)
    {
        $accessCode = $request->get('code');

        try {
            $token = Socialite::driver('google')->getAccessTokenResponse($accessCode);
            $user = Socialite::driver('google')->userFromToken($token['access_token']);

            $existingUser = User::where('email', $user->email)->first();

            if (!$existingUser) {
                // If the user doesn't exist, you can create a new user in your database
                $password = bcrypt('your_password_here');

                $existingUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $password,
                    // Add any other user fields as needed
                ]);
            }

            // Issue a Passport token for the user
            $passportToken = $existingUser->createToken('MyAppToken');

            return response()->json([
                'user' => $existingUser,
                'access_token' => $passportToken->accessToken,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



}