<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class ApiLogoutController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function logout(Request $request)
    {
           // Retrieve the token from the request headers
        $apiToken = $request->bearerToken();

        // Invalidate the token
        try {
            JWTAuth::invalidate($apiToken);
            return response()->json([
                'message' => 'Berhasil',
                'success' => true,
                'status' => 'Berhasil keluar!',
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'message' => 'Gagal',
                'success' => false,
                'status' => 'Gagal keluar!',
            ]);
        }
    }
}
