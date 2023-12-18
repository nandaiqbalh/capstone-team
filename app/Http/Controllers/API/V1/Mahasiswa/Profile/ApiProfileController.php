<?php

namespace App\Http\Controllers\Api\V1\Mahasiswa\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Mahasiswa\Profile\ApiAccountModel;
use Illuminate\Support\Facades\Auth;

class ApiProfileController extends Controller
{
    // path store in database
    protected $upload_path = '/img/user/';


    public function index(Request $request)
{
    // get user_id from request parameters
    $userId = $request->input('user_id');

    // authorize
    $isAuthorized = ApiAccountModel::authorize('U', $userId);

    if (!$isAuthorized) {
        $response = [
            'status' => false,
            'message' => 'Akses tidak sah!',
            'data' => null,
        ];
        return response()->json($response, 403);
    } else {
        // get data
        $account = ApiAccountModel::getById($userId);

        // check if data is retrieved successfully
        if ($account) {
            // data
            $data = [
                'status' => true,
                'message' => 'Berhasil mendapatkan data pengguna!',
                'data' => $account
            ];
            // return JSON response for the API
            return response()->json($data);
        } else {
            // response for failure scenario
            $response = [
                'status' => false,
                'message' => 'Gagal mendapatkan profil pengguna! Pengguna tidak ditemukan atau ada error lain.',
                'data' => null,
            ];
            return response()->json($response, 404); // You can customize the HTTP status code
        }
    }
}

public function editProcess(Request $request)
{
    try {
        // // Get user_id from the request
        $userId = $request->input('user_id');

        // // Authorize
        // ApiAccountModel::authorize('U', $userId);

        // Validate
        $rules = [
            'user_id' => 'required',
            'user_name' => 'required',
            'no_telp' => 'required|digits_between:10,13|numeric',
            'user_img' => 'image|mimes:jpeg,jpg,png|max:5120'
        ];
        $this->validate($request, $rules);

        // Params
        $params = [
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
            'no_telp' => $request->no_telp,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => now()
        ];

        // Process
        if (ApiAccountModel::update($userId, $params)) {
            // Response for success
            $response = [
                'status' => true,
                'message' => 'Data berhasil disimpan.'
            ];
            return response()->json($response);
        } else {
            // Response for failure
            $response = [
                'status' => false,
                'message' => 'Data gagal disimpan.'
            ];
            return response()->json($response, 500);
        }
    } catch (ValidationException $e) {
        // Validation error response
        $response = [
            'status' => false,
            'message' => 'Validasi gagal.',
            'errors' => $e->errors()
        ];
        return response()->json($response, 422);
    } catch (\Exception $e) {
        // Generic error response
        $response = [
            'status' => false,
            'message' => 'Generic Terjadi kesalahan.',
            'error' => $e->getMessage()
        ];
        return response()->json($response, 500);
    }
}

}
