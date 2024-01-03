<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Api\Mahasiswa\Profile\ApiAccountModel;

use App\Models\Api\V1\Auth\LogoutModel;

class ApiLogoutController extends Controller
{
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function logout(Request $request)
    {
        // Get api_token from the request body
        $apiToken = $request->input('api_token');

        // Check if api_token is provided
        if (empty($apiToken)) {
            $response = [
                'status' => false,
                'message' => 'Missing api_token in the request body.',
                'data' => null,
            ];
            return response()->json($response, 400); // 400 Bad Request
        }

        $userId = $request->input('user_id');
        $user = ApiAccountModel::getById($userId);

        // Check if the user exists
        if ($user != null) {
            // Attempt to authenticate the user based on api_token
            if ($user->api_token != null) {
                // Authorize
                $isAuthorized = ApiAccountModel::authorize('R', $userId);

                if (!$isAuthorized) {
                    $response = [
                        'status' => false,
                        'message' => 'Akses tidak sah!',
                        'data' => null,
                    ];
                    return response()->json($response, 403);
                } else {
                    // Check if the provided api_token matches the user's api_token
                    if ($user->api_token == $apiToken) {
                        // Data
                        // Check if the user is authenticated
                        if ($user->api_token != null) {
                            // Revoke the user's API token
                            $params = [
                                'api_token' => null,
                            ];

                            // Process
                            if (ApiAccountModel::update($userId, $params)) {
                                // Response for success
                                $response = [
                                    "status" => true,
                                    "message" => 'Logout berhasil!',
                                    "data" => $user,
                                ];

                                return response()->json($response, 200);
                            } else {
                                // Response for failure
                                $response = [
                                    "status" => true,
                                    "message" => 'Logout gagal!',
                                    "data" => $user,

                                ];
                                return response()->json($response, 500);
                            }

                        } else {
                            // Return an error response if the user is not authenticated
                            $response = [
                                "status" => false,
                                "message" => 'User not authenticated.',
                                "data" => $user,
                            ];

                            return response()->json($response)->setStatusCode(401);
                        }

                    } else {
                        $response = [
                            'status' => false,
                            'message' => 'Token tidak valid!',
                            'data' => null,
                        ];
                        return response()->json($response, 401); // 401 Unauthorized
                    }
                }
            }
        } else {
            // User not found or api_token is null
            $response = [
                'status' => false,
                'message' => 'Pengguna tidak ditemukan!',
                'data' => null,
            ];
            return response()->json($response, 404); // 401 Unauthorized
        }
    }

}
