<?php

namespace App\Http\Controllers\Api\V1\Mahasiswa\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Mahasiswa\Profile\ApiAccountModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiProfileController extends Controller
{
    // path store in database
    protected $upload_path = '/img/user/';


    public function index(Request $request)
    {
        try {
            // Check if the token is still valid
            JWTAuth::checkOrFail();

            // Get the user from the JWT token in the request headers
            $user = JWTAuth::parseToken()->authenticate();

            // Check if the user exists and is active
            if ($user != null && $user->user_active == 1) {
                // Get the profile image URL
                $userImageUrl = $this->getProfileImageUrl($user);

                // Add the user_img_url to the user object
                $user->user_img_url = $userImageUrl;

                // Response data
                $response = [
                    'message' => 'Berhasil',
                    'success' => true,
                    'status' => 'Berhasil mendapatkan profil pengguna!',
                    'data' => $user,
                ];

                // Return JSON response for the API
                return response()->json($response);
            } else {
                // User not found or not active
                $response = [
                    'message' => 'Gagal',
                    'success' => false,
                    'status' => 'Gagal mendapatkan profil pengguna!',
                    'data' => null,
                ];

                return response()->json($response); // 401 Unauthorized
            }
        } catch (JWTException $e) {
            $response = [
                'message' => 'Gagal',
                'success' => false,
                'status' => 'Gagal. Silahkan masuk terlebih dahulu!',
                'data' => null,
            ];

            return response()->json($response); // 401 Unauthorized
        }
    }

    public function editProcess(Request $request)
    {
        try {
            // Authenticate user using the provided token
            $user = JWTAuth::parseToken()->authenticate();

            // Validate only if there are request parameters
            $rules = [
                'user_name' => 'filled',
                'no_telp' => 'filled|digits_between:10,13|numeric',
                'user_email' => 'filled|email',
            ];

            $this->validate($request, $rules);

            // Params
            $params = [
                'user_name' => $request->filled('user_name') ? $request->input('user_name') : $user->user_name,
                'user_email' => $request->filled('user_email') ? $request->input('user_email') : $user->user_email,
                'no_telp' => $request->filled('no_telp') ? $request->input('no_telp') : $user->no_telp,
                'modified_by' => $user->user_id,
                'modified_date' => now(),
            ];

            // Process
            if (ApiAccountModel::update($user->user_id, $params)) {
                $userUpdated = ApiAccountModel::getById($user->user_id);

                $userImageUrl = $this->getProfileImageUrl($user);
                // Add the user_img_url to the user object
                $userUpdated->user_img_url = $userImageUrl;

                // Response for success
                $response = [
                    'message' => 'Berhasil',
                    'success' => true,
                    'status' => 'Profil berhasil diperbaharui!',
                    'data' => $userUpdated,
                ];
                return response()->json($response);
            } else {
                // Response for failure
                $response = [
                    'message' => 'Gagal',
                    'success' => false,
                    'status' => 'Profil gagal diperbaharui!',
                    'data' => $user,
                ];
                return response()->json($response);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            // Token has expired
            $response = [
                'message' => 'Gagal',
                'success' => false,
                'status' => 'Token is Expired',
                'data' => null,
            ];
            return response()->json($response);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            // Token is invalid
            $response = [
                'message' => 'Gagal',
                'success' => false,
                'status' => 'Token is Invalid',
                'data' => null,
            ];
            return response()->json($response);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            // Other JWT exceptions
            $response = [
                'message' => 'Gagal',
                'success' => false,
                'status' => 'Token is Expired',
                'data' => null,
            ];
            return response()->json($response);
        }
    }

    public function editPassword(Request $request)
    {
        try {
            // Authenticate user using the provided token
            $user = JWTAuth::parseToken()->authenticate();

            // Validate only if there are request parameters
            $rules = [
                'current_password' => 'required|min:8',
                'new_password' => 'required|min:8',
                'repeat_new_password' => 'required|min:8|same:new_password',
            ];

            $this->validate($request, $rules);

            // Check current password
            $currentPasswordInputByUser = $request->input('current_password');
            if (!Hash::check($currentPasswordInputByUser, $user->user_password)) {
                $response = [
                    'message' => 'Gagal',
                    'success' => false,
                    'status' => 'Password saat ini salah.',
                    'data' => null,
                ];
                return response()->json($response);
            }

            $newPassword = Hash::make($request->input('new_password'));
            $params = [
                'user_password' => $request->filled('new_password') ? $newPassword : $user->user_password,
                'modified_by' => $user->user_id,
                'modified_date' => now(),
            ];

            // Process
            if (ApiAccountModel::update($user->user_id, $params)) {
                $userUpdated = ApiAccountModel::getById($user->user_id);

                $userImageUrl = $this->getProfileImageUrl($user);
                // Add the user_img_url to the user object
                $userUpdated->user_img_url = $userImageUrl;

                // Response for success
                $response = [
                    'message' => 'Berhasil',
                    'success' => true,
                    'status' => 'Password baru berhasil disimpan.',
                    'data' => $userUpdated,
                ];
                return response()->json($response);
            } else {
                // Response for failure
                $response = [
                    'message' => 'Gagal',
                    'success' => false,
                    'status' => 'Password baru gagal disimpan.',
                    'data' => $user,
                ];
                return response()->json($response);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            // Token has expired
            return response()->json(['message' => 'Gagal', 'success' => false, 'status' => 'Token expired.', 'data' => null]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            // Token is invalid
            return response()->json(['message' => 'Gagal', 'success' => false, 'status' => 'Token is invalid.', 'data' => null]);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            // Other JWT exceptions
            return response()->json(['message' => 'Gagal', 'success' => false, 'status' => 'Failed to authenticate token.', 'data' => null]);
        }
    }



    public function editPhotoProcess(Request $request)
    {
        try {
            // Authenticate user using the provided token
            $user = JWTAuth::parseToken()->authenticate();

            // Validate only if there are request parameters
            $rules = [
                'user_img' => 'filled|required|image|mimes:jpeg,jpg,png|max:5120'
            ];

            $this->validate($request, $rules);

            // Check if user_img is provided for update
            if ($request->hasFile('user_img') && $request->user_img != null) {
                $path = public_path($this->upload_path);
                $file = $request->file('user_img');
                $newImageName = Str::slug($user->user_name, '-') . '-' . uniqid() . '.jpg';

                if ($user->user_img_name != null) {
                    // Unlink (delete) the old image
                    $oldImagePath = public_path($user->user_img_path . $user->user_img_name);
                    if (file_exists($oldImagePath) && $user->user_img_name != 'default.png') {
                        unlink($oldImagePath);
                    }
                }

                // Upload new image
                $file->move($path, $newImageName);

                // Params
                $params = [
                    'user_img_path' => $this->upload_path,
                    'user_img_name' => $newImageName,
                    'modified_by' => $user->user_id,
                    'modified_date' => now(),
                ];
            }

            // Process
            if (ApiAccountModel::update($user->user_id, $params)) {
                $userUpdated = ApiAccountModel::getById($user->user_id);

                $userImageUrl = $this->getProfileImageUrl($user);
                // Add the user_img_url to the user object
                $userUpdated->user_img_url = $userImageUrl;

                // Response for success
                $response = [
                    'success' => true,
                    'message' =>'Gagal',
                    'status' => 'Berhasil memperbaharui foto profil!',
                    'data' => $userUpdated,
                ];
                return response()->json($response);
            } else {
                // Response for failure
                $response = [
                    'success' => true,
                    'message' =>'Gagal',
                    'status' => 'Gagal memperbaharui foto profil!',
                    'data' => $user,
                ];
                return response()->json($response);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            // Token has expired
            return response()->json(['message' => 'Gagal', 'success' => false,'status' => 'Token expired.', 'data' => null]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            // Token is invalid
            return response()->json(['message' => 'Gagal', 'success' => false, 'status' => 'Token is invalid.', 'data' => null]);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            // Other JWT exceptions
            return response()->json(['message' => 'Gagal', 'success' => false, 'status' => 'Failed to authenticate token.', 'data' => null]);
        }
    }

    private function getProfileImageUrl($user)
    {
        if (!empty($user->user_img_name)) {
            $imageUrl = url($user->user_img_path . $user->user_img_name);
        } else {
            $imageUrl = url('img/user/default_profile.jpg');
        }

        return $imageUrl;
    }
}
