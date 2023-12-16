<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Api\V1\Auth\LoginModel;
use App\Models\User;
use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\Mahasiswa\MahasiswaModel;

class LoginController extends Controller
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
        return response()->json(['status' => 'error', 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
    }

    // Attempt authentication
    if (Auth::attempt(['nomor_induk' => $request->nomor_induk, 'password' => $request->password, 'user_active' => '1'])) {
        // Regenerate session
        // $request->session()->regenerate();

        // // Set session variable
        // session()->put('login', 'true');

        // Get user data
        $user = Auth::user();

        // Return success response
        return response()->json(['status' => 'success', 'message' => 'Authentication successful', 'data' => $user], 200);
    } else {
        // Return error response
        return response()->json(['status' => 'error', 'message' => 'Authentication failed', 'errors' => ['authentication' => 'Invalid Nomor Induk or Password.']], 401);
    }
}

public function index()
    {
        // authorize
        // MahasiswaModel::authorize('R');

        // get data with pagination
        $rs_mahasiswa = MahasiswaModel::getDataWithPagination();

        // data
        $data = ['rs_mahasiswa' => $rs_mahasiswa];

        // return data as JSON
        return response()->json($data);
    }

}
