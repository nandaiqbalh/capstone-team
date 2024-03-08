<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
         $validator = Validator::make($request->only('nomor_induk', 'password'), [
             'nomor_induk' => 'required',
             'password' => 'required|min:8|max:20',
         ]);

         if ($validator->fails()) {
             // Return validation error response
             $response = [
                 'message' => 'Gagal',
                 'success' => false,
                 'status' => $validator->errors()->first(),
                 'data' => null,
             ];
         }

         // Attempt authentication manually
         $user = User::where('nomor_induk', $request->nomor_induk)->first();

         if ($user && $user->user_active == '1' && $user->role_id == '03' && Hash::check($request->password, $user->user_password)) {
             try {
                 // Generate and save a new api_token
                 $token = JWTAuth::attempt($request->only('nomor_induk', 'password'));

                 if (!$token) {
                     $response = [
                         'message' => 'Gagal',
                         'success' => false,
                         'status' => 'Nomor Induk atau Password tidak valid.',
                         'data' => null,
                     ];
                 }

                 $user->api_token = $token;

                 $userImageUrl = $this->getProfileImageUrl($user);
                 // Add the user_img_url to the user object
                 $user->user_img_url = $userImageUrl;

                 $response = [
                     'message' => 'Berhasil',
                     'success' => true,
                     'status' => 'Authentikasi berhasil.',
                     'data' => $user,
                 ];
             } catch (JWTException $e) {
                 $response = [
                     'message' => 'Gagal',
                     'success' => false,
                     'status' => 'Gagal membuat token!.',
                     'data' => null,
                 ];
             }
         } else {
             // Return error response
             $response = [
                'message' => 'Authentikasi gagal.',
                'success' => false,
                 'status' => 'Nomor Induk atau Password tidak valid.',
                 'data' => null,
             ];
         }
         return response()->json($response);
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
