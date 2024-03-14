<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiLogoutController extends Controller
{
    public function logout(Request $request)
    {
        // Retrieve the token from the request headers
        $apiToken = $request->bearerToken();

        // Invalidate the token
        try {
            $this->invalidateToken($apiToken);
            return $this->successResponse('Berhasil keluar!');
        } catch (JWTException $exception) {
            return $this->failureResponse('Gagal keluar!');
        }
    }

    // Fungsi untuk membuat respons sukses
    private function successResponse($statusMessage)
    {
        return response()->json([
            'success' => true,
            'status' => $statusMessage,
        ]);
    }

    // Fungsi untuk membuat respons kegagalan
    private function failureResponse($statusMessage)
    {
        return response()->json([
            'success' => false,
            'status' => $statusMessage,
        ]);
    }

    // Fungsi untuk mematikan token
    private function invalidateToken($token)
    {
        JWTAuth::invalidate($token);
    }
}
