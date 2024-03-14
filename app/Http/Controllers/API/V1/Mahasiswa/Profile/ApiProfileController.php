<?php

namespace App\Http\Controllers\Api\V1\Mahasiswa\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Mahasiswa\Profile\ApiProfileModel;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApiProfileController extends Controller
{
    // Path store in database
    protected $upload_path = '/img/user/';

    public function index(Request $request)
    {
        try {
            JWTAuth::checkOrFail();
            $user = $this->getAuthenticatedUser();

            if ($user != null && $user->user_active == 1) {
                $userImageUrl = $this->getProfileImageUrl($user);
                $user->user_img_url = $userImageUrl;

                $response = $this->successResponse('Berhasil mendapatkan profil pengguna!', $user);
            } else {
                $response = $this->failureResponse('Pengguna bukan merupakan pengguna aktif!');
            }
        } catch (JWTException $e) {
            $response = $this->failureResponse('Gagal. Silahkan masuk terlebih dahulu!');
        }

        return response()->json($response);
    }

    public function editProcess(Request $request)
    {
        try {
            $user = $this->getAuthenticatedUser();
            $rules = [
                'user_name' => 'filled',
                'no_telp' => 'filled|digits_between:10,13|numeric',
                'user_email' => 'filled|email',
            ];

            $this->validate($request, $rules);

            $params = $this->prepareUpdateParams($request, $user);

            if (ApiProfileModel::update($user->user_id, $params)) {
                $userUpdated = ApiProfileModel::getById($user->user_id);
                $userImageUrl = $this->getProfileImageUrl($userUpdated);

                $userUpdated->user_img_url = $userImageUrl;

                $response = $this->successResponse('Profil berhasil diperbaharui!', $userUpdated);
            } else {
                $response = $this->failureResponse('Profil gagal diperbaharui!', $user);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            $response = $this->tokenExpiredResponse();
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            $response = $this->tokenInvalidResponse();
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $response = $this->tokenExpiredResponse();
        }

        return response()->json($response);
    }

    public function editPassword(Request $request)
    {
        try {
            $user = $this->getAuthenticatedUser();
            $rules = [
                'current_password' => 'required|min:8',
                'new_password' => 'required|min:8',
                'repeat_new_password' => 'required|min:8|same:new_password',
            ];

            $this->validate($request, $rules);

            $currentPasswordInputByUser = $request->input('current_password');
            if (!Hash::check($currentPasswordInputByUser, $user->user_password)) {
                $response = $this->failureResponse('Password saat ini salah!', $user);
                return response()->json($response);
            }

            $newPassword = Hash::make($request->input('new_password'));
            $params = [
                'user_password' => $request->filled('new_password') ? $newPassword : $user->user_password,
                'modified_by' => $user->user_id,
                'modified_date' => now(),
            ];

            if (ApiProfileModel::update($user->user_id, $params)) {
                $userUpdated = ApiProfileModel::getById($user->user_id);
                $userImageUrl = $this->getProfileImageUrl($userUpdated);

                $userUpdated->user_img_url = $userImageUrl;

                $response = $this->successResponse('Password baru berhasil disimpan.', $userUpdated);
            } else {
                $response = $this->failureResponse('Password baru gagal disimpan!', $user);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            $response = $this->tokenExpiredResponse();
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            $response = $this->tokenInvalidResponse();
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $response = $this->tokenExpiredResponse();
        }

        return response()->json($response);
    }

    public function editPhotoProcess(Request $request)
    {
        try {
            $user = $this->getAuthenticatedUser();
            $rules = [
                'user_img' => 'filled|required|image|mimes:jpeg,jpg,png|max:5120',
            ];

            $this->validate($request, $rules);

            if ($request->hasFile('user_img') && $request->user_img != null) {
                $params = $this->uploadAndPrepareUpdateParams($request, $user);
            }

            if (ApiProfileModel::update($user->user_id, $params)) {
                $userUpdated = ApiProfileModel::getById($user->user_id);
                $userImageUrl = $this->getProfileImageUrl($userUpdated);

                $userUpdated->user_img_url = $userImageUrl;

                $response = $this->successResponse('Berhasil memperbaharui foto profil!', $userUpdated);
            } else {
                $userUpdated = ApiProfileModel::getById($user->user_id);
                $userImageUrl = $this->getProfileImageUrl($userUpdated);

                $userUpdated->user_img_url = $userImageUrl;

                $response = $this->failureResponse('Gagal memperbaharui foto profil!', $userUpdated);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            $response = $this->tokenExpiredResponse();
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            $response = $this->tokenInvalidResponse();
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $response = $this->tokenExpiredResponse();
        }

        return response()->json($response);
    }

    private function getAuthenticatedUser()
    {
        return JWTAuth::parseToken()->authenticate();
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

    private function prepareUpdateParams(Request $request, $user)
    {
        return [
            'user_name' => $request->filled('user_name') ? $request->input('user_name') : $user->user_name,
            'user_email' => $request->filled('user_email') ? $request->input('user_email') : $user->user_email,
            'no_telp' => $request->filled('no_telp') ? $request->input('no_telp') : $user->no_telp,
            'modified_by' => $user->user_id,
            'modified_date' => now(),
        ];
    }

    private function uploadAndPrepareUpdateParams(Request $request, $user)
    {
        $path = public_path($this->upload_path);
        $file = $request->file('user_img');
        $newImageName = Str::slug($user->user_name, '-') . '-' . uniqid() . '.jpg';

        if ($user->user_img_name != null) {
            $oldImagePath = public_path($user->user_img_path . $user->user_img_name);
            if (file_exists($oldImagePath) && $user->user_img_name != 'default.png') {
                unlink($oldImagePath);
            }
        }

        $file->move($path, $newImageName);

        return [
            'user_img_path' => $this->upload_path,
            'user_img_name' => $newImageName,
            'modified_by' => $user->user_id,
            'modified_date' => now(),
        ];
    }

    private function successResponse($statusMessage, $data)
    {
        return [
            'success' => true,
            'status' => $statusMessage,
            'data' => $data,
        ];
    }

    private function failureResponse($statusMessage, $data = null)
    {
        return [
            'success' => false,
            'status' => $statusMessage,
            'data' => $data,
        ];
    }

    private function tokenExpiredResponse()
    {
        return $this->failureResponse('Token expired.', null);
    }

    private function tokenInvalidResponse()
    {
        return $this->failureResponse('Token is invalid.', null);
    }
}
