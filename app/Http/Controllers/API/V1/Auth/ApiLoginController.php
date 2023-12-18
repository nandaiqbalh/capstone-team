<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Api\TimCapstone\Mahasiswa\ApiMahasiswaModel;

class ApiLoginController extends Controller
{

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

     public function authenticate(Request $request)
     {
         // Validate input
         $validator = \Validator::make($request->all(), [
             'nomor_induk' => 'required',
             'password' => 'required|min:8|max:20',
         ]);

         if ($validator->fails()) {
             return response()->json(['status' => false, 'message' => 'Nomor Induk dan Password harus semuanya diisi.', 'data' => null,], 422);
         }

         // Attempt authentication
         if (Auth::attempt(['nomor_induk' => $request->nomor_induk, 'password' => $request->password, 'user_active' => '1'])) {
             // Get user data
             $user = Auth::user();

             // Generate and save api_token
             $apiToken = Str::random(60); // or use Passport::apiToken() if you're using Passport version 11.x
             $user->forceFill([
                 'api_token' => $apiToken,
             ])->save();

             // Return success response
             return response()->json(['status' => true, 'message' => 'Autentikasi berhasil!', 'data' => $user], 200);
         } else {
             // Return error response
             return response()->json(['status' => false, 'message' => 'Autentikasi gagal! Nomor Induk atau Password tidak valid.', 'data' => null,], 401);
         }
     }

public function index(Request $request)
{
    // Mendapatkan user_id dari query parameter
    $user_id = $request->query('user_id');

    // authorize
    $isAutorized = ApiMahasiswaModel::authorize('R', $user_id);
    if (true) {
        // get data with pagination
    $rs_mahasiswa = ApiMahasiswaModel::getDataWithPagination();

    // return data as JSON
    return response()->json(['status' => true, 'data' => ['rs_mahasiswa' => $rs_mahasiswa]]);
    } else {
        return response()->json(['status' => false, 'message' => 'Unauthorized', 'user_id' => $user_id], 403);

    }


}




}


