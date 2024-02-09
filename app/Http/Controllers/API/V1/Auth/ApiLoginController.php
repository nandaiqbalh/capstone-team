<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\User; // Gantilah sesuai namespace dan lokasi model User Anda
use Illuminate\Support\Facades\Hash;

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
    $validator = Validator::make($request->all(), [
        'nomor_induk' => 'required',
        'password' => 'required|min:8|max:20',
    ]);

    if ($validator->fails()) {
        $errors = $validator->errors()->first();

        return response()->json([
            'status' => false,
            'message' => 'Terjadi kesalahan: ' . $errors,
        ]);
    }

    // Attempt authentication manually
    $user = User::where('nomor_induk', $request->nomor_induk)->first();

    if ($user && $user->user_active == '1' && $user && $user->role_id == 03 && Hash::check($request->password, $user->user_password)) {
        // Generate and save api_token
        $apiToken = Str::random(60); // or use Passport::apiToken() if you're using Passport version 11.x
        $user->forceFill([
            'api_token' => $apiToken,
        ])->save();

        // Return success response
        return response()->json([
            'status' => true,
            'message' => 'Autentikasi berhasil!',
            'data' => $user,
        ]);
    } else {
        // Return error response
        return response()->json([
            'status' => false,
            'message' => 'Autentikasi gagal! Nomor Induk atau Password tidak valid.',
        ]);
    }
}

}




