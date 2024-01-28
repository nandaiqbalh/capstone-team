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


class ApiProfileController extends Controller
{
    // path store in database
    protected $upload_path = '/img/user/';


    public function index(Request $request)
    {
         // Get api_token and user_id from the request body
        $apiToken = $request->input('api_token');
        $userId = $request->input('user_id');

        // Check if api_token is provided
        if (empty($apiToken)) {
            $response = [
                'status' => false,
                'message' => 'Missing api_token in the request body.',
                'data' => null,
            ];
            return response()->json($response); // 400 Bad Request
        }

        $user = ApiAccountModel::getById($userId);

        // Check if the user exists
        if ($user != null) {
            // Attempt to authenticate the user based on api_token
            if ($user->api_token != null) {
                // Authorize
                $isAuthorized = ApiAccountModel::authorize('U', $userId);

                if (!$isAuthorized) {
                    $response = [
                        'status' => false,
                        'message' => 'Akses tidak sah!',
                        'data' => null,
                    ];
                    return response()->json($response);
                } else {
                    // Check if the provided api_token matches the user's api_token
                    if ($user->api_token == $apiToken) {
                        // Data
                        $response = [
                            'status' => true,
                            'message' => 'Berhasil mendapatkan data pengguna!',
                            'data' => $user,
                        ];
                        // Return JSON response for the API
                        return response()->json($response);

                    } else {
                        $response = [
                            'status' => false,
                            'message' => 'Token tidak valid!',
                            'data' => null,
                        ];
                        return response()->json($response); // 401 Unauthorized
                    }
                }
            } else {
                $response = [
                    'status' => false,
                    'message' => 'Pengguna harus login terlebih dahulu!',
                    'data' => null,
                ];
                return response()->json($response); // 401 Unauthorized
            }
        } else {
            // User not found or api_token is null
            $response = [
                'status' => false,
                'message' => 'Pengguna tidak ditemukan!',
                'data' => null,
            ];
            return response()->json($response); // 401 Unauthorized
        }
    }


    public function editProcess(Request $request)
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
        return response()->json($response); // 400 Bad Request
    }

    $userId = $request->input('user_id');
    $user = ApiAccountModel::getById($userId);

    if ($user != null && $user->api_token == $apiToken) {
        // Authorize
        ApiAccountModel::authorize('U', $userId);

        // Validate only if there are request parameters
        $rules = [
            'user_name' => 'filled|required',
            'no_telp' => 'filled|required|digits_between:10,13|numeric',
            'user_email' => 'filled|email',
            'angkatan' => 'filled|numeric',
            'ipk' => 'filled|numeric',
            'sks' => 'filled|numeric',
            'jenis_kelamin' => 'filled|in:L,P',
            'alamat' => 'filled|string',
            'user_img' => 'filled|image|mimes:jpeg,jpg,png|max:5120'
        ];

        try {
            $this->validate($request, $rules);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            $errorMessage = $exception->validator->errors()->first();
            return response()->json(['status' => false, 'message' => $errorMessage, 'data' => null]);
        }

        // Initialize variables for image upload
        $upload = false;
        $newImageName = null;

        // Check if user_img is provided for update
        if ($request->hasFile('user_img') || $request -> user_img != null) {
            $path = public_path($this->upload_path);
            $file = $request->file('user_img');
            $newImageName = Str::slug($user->user_name, '-') . '-' . uniqid() . '.jpg';

            // Unlink (delete) the old image
            $oldImagePath = public_path($user->user_img_path . $user->user_img_name);
            if (file_exists($oldImagePath) && $user->user_img_name != 'default.png') {
                unlink($oldImagePath);
            }

            // Upload new image
            $upload = $file->move($path, $newImageName);
        }

        // Params
        $params = [
            'user_name' => $request->filled('user_name') ? $request->input('user_name') : $user->user_name,
            'user_email' => $request->filled('user_email') ? $request->input('user_email') : $user->user_email,
            'no_telp' => $request->filled('no_telp') ? $request->input('no_telp') : $user->no_telp,
            'angkatan' => $request->filled('angkatan') ? $request->input('angkatan') : $user->angkatan,
            'ipk' => $request->filled('ipk') ? $request->input('ipk') : $user->ipk,
            'sks' => $request->filled('sks') ? $request->input('sks') : $user->sks,
            'jenis_kelamin' => $request->filled('jenis_kelamin') ? $request->input('jenis_kelamin') : $user->jenis_kelamin,
            'alamat' => $request->filled('alamat') ? $request->input('alamat') : $user->alamat,
            'modified_by' => $userId,
            'modified_date' => now(),
        ];

        // Conditionally add 'user_img_path' and 'user_img_name' based on $upload
        if ($upload) {
            $params['user_img_path'] = $this->upload_path;
            $params['user_img_name'] = $newImageName;
        }

        // Process
        if (ApiAccountModel::update($userId, $params)) {
            $userUpdated = ApiAccountModel::getById($userId);

            // Response for success
            $response = [
                'status' => true,
                'message' => 'Data berhasil disimpan.',
                'data' => $userUpdated,
            ];
            return response()->json($response);
        } else {
            // Response for failure
            $response = [
                'status' => false,
                'message' => 'Data gagal disimpan.',
                'data' => $user,
            ];
            return response()->json($response);
        }
    } else {
        $response = [
            'status' => false,
            'message' => 'Token tidak valid!',
            'data' => null,
        ];
        return response()->json($response); // 401 Unauthorized
    }
}

    public function editPassword(Request $request)
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
             return response()->json($response); // 400 Bad Request
         }

         $userId = $request->input('user_id');
         $user = ApiAccountModel::getById($userId);

         if ($user != null) {

            if ($apiToken == $user -> api_token) {

                // Authorize
                ApiAccountModel::authorize('U', $userId);

                // Validate only if there are request parameters
                $rules = [
                    'current_password' => 'required|min:8|max:20',
                    'new_password' => 'required|min:8|max:20',
                    'repeat_new_password' => 'required|min:8|max:20|same:new_password',
                ];

                try {
                    $this->validate($request, $rules);
                } catch (\Illuminate\Validation\ValidationException $exception) {
                    $errorMessage = $exception->validator->errors()->first();
                    return response()->json(['status' => false, 'message' => $errorMessage, 'data' => null]);
                }

                // Check current password
                $currentPasswordInputByUser = $request->input('current_password');
                if (!Hash::check($currentPasswordInputByUser, $user->user_password)) {
                    return response()->json(['status' => false, 'message' => 'Password saat ini salah.', 'data' => null]);
                }

                $newPassword = Hash::make($request->input('new_password'));
                $params = [
                    'user_password' => $request->filled('new_password') ? $newPassword : $user->user_password,
                    'modified_by' => $userId,
                    'modified_date' => now(),
                ];

                // Process
                if (ApiAccountModel::update($userId, $params)) {
                    $userUpdated = ApiAccountModel::getById($userId);

                    // Response for success
                    $response = [
                        'status' => true,
                        'message' => 'Password baru berhasil disimpan.',
                        'data' => $userUpdated,
                    ];
                    return response()->json($response);
                } else {
                    // Response for failure
                    $response = [
                        'status' => false,
                        'message' => 'Password baru gagal disimpan.',
                        'data' => $user,
                    ];
                    return response()->json($response);
                }

            } else {
                $response = [
                    'status' => false,
                    'message' => 'Token tidak valid!',
                    'data' => null,
                ];
                return response()->json($response); // 401 Unauthorized
            }

         } else {
              // User not found or api_token is null
              $response = [
                'status' => false,
                'message' => 'Pengguna tidak ditemukan!',
                'data' => null,
            ];
            return response()->json($response); // 401 Unauthorized
         }
    }

    public function imageProfile(Request $request)
    {
          // Get api_token from the request body
          $apiToken = $request->input('api_token');

          // Check if api_token is provided
          if (empty($apiToken)) {
              return response()->json([
                  'status' => false,
                  'message' => 'Missing api_token in the request body.',
                  'data' => null
              ], );
          }

          $userId = $request->input('user_id');
          $user = ApiAccountModel::getById($userId);

          // Check if the user exists
          if ($user != null) {
              // Attempt to authenticate the user based on api_token
              if ($user->api_token != null) {
                  // Authorize
                  $isAuthorized = ApiAccountModel::authorize('C', $userId);

                  if (!$isAuthorized) {
                      return response()->json([
                          'status' => false,
                          'message' => 'Akses tidak sah!',
                          'data' => null
                      ], );
                  } else {
                      // Check if the provided api_token matches the user's api_token
                      if ($user->api_token == $apiToken) {

                        $storagePath = public_path($user->user_img_path . $user->user_img_name);
                         // Check if the file exists in storage
                         if (file_exists($storagePath)) {
                             // Read the file contents into a string
                             $fileContents = File::get($storagePath);

                              // Convert the string to base64
                            $base64File = base64_encode($fileContents);

                             return response()->json([
                                 'status' => true,
                                 'message' => 'Berhasil mendapatkan gambar profil',
                                 'data' => $base64File,
                             ], );
                         } else {
                             // If file not found, return a 404 response
                             return response()->json(['status' => false, 'message' => 'File tidak ditemukan', 'data' => null], );
                         }
                      } else {
                          return response()->json([
                              'status' => false,
                              'message' => 'Token tidak valid!',
                              'data' => null
                          ], );
                      }
                  }
              }
          } else {
              // User not found or api_token is null
              return response()->json([
                  'status' => false,
                  'message' => 'Pengguna tidak ditemukan!',
                  'data' => null
              ], );
          }
    }


}
