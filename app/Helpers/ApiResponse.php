<?php

namespace App\Helpers;

use Illuminate\Http\Response;

class ApiResponse
{
    public static function success($message, $data = null, $statusCode = Response::HTTP_OK)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    public static function error($message, $data = null, $statusCode = Response::HTTP_BAD_REQUEST)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}