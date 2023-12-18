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
        $userId = $request->input('user_id');
        $user = ApiAccountModel::getById($userId);

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
    }

}
