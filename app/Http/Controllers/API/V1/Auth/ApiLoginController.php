<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ApiLoginController extends Controller
{

    public function authenticate(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->only('nomor_induk', 'password'), [
            'nomor_induk' => 'required',
            'password' => 'required|min:8|max:20',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors()->first());
        }

        // Attempt authentication manually
        $user = User::where('nomor_induk', $request->nomor_induk)->first();

        if ($this->validateUser($user, $request->password)) {
            try {
                $token = JWTAuth::attempt($request->only('nomor_induk', 'password'));

                if (!$token) {
                    return $this->authenticationErrorResponse('Nomor Induk atau Password tidak valid.');
                } else {
                    $user->api_token = $token;
                    $userImageUrl = $this->getProfileImageUrl($user);

                    return $this->authenticationSuccessResponse($user, $userImageUrl);
                }
            } catch (\Exception $e) {
                return $this->authenticationErrorResponse('Gagal membuat token!');
            }
        } else {
            return $this->authenticationErrorResponse('NIM atau Kata Sandi tidak valid.');
        }
    }

    private function validationErrorResponse($errorMessage)
    {
        return response()->json([
            'success' => false,
            'status' => $errorMessage,
            'data' => null,
        ]);
    }

    private function validateUser($user, $password)
    {
        return $user && $user->user_active == '1' && $user->role_id == '03' && Hash::check($password, $user->user_password);
    }

    private function authenticationErrorResponse($errorMessage)
    {
        return response()->json([
            'success' => false,
            'status' => $errorMessage,
            'data' => null,
        ]);
    }

    private function authenticationSuccessResponse($user, $userImageUrl)
    {
        $user->user_img_url = $userImageUrl;

        return response()->json([
            'success' => true,
            'status' => 'Authentikasi berhasil.',
            'data' => $user,
        ]);
    }

    private function getProfileImageUrl($user)
    {
        if (!empty($user->user_img_name)) {
            $imageUrl = url($user->user_img_path . $user->user_img_name);
        } else {
            $imageUrl = url('img/user/default.jpg');
        }

        return $imageUrl;
    }
}
