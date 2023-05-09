<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Api\V1\Auth\LoginModel;
use App\Models\User;

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
        // Validate & auto redirect when fail
        $rules = [
            'nik' => 'required|digits_between:6,11|numeric',
            'password' => 'required|min:8|max:20'
        ];
        
        $this->validate($request, $rules );
        if (!LoginModel::getRoleChecker($request->nik)) {
            // return json
            $response = [
                "status"=> false,
                "message"=> 'Login gagal. Role Tidak Diizinkan.'
            ];
            return response()->json($response)->setStatusCode(200);
        }
        // process
        if (Auth::attempt(['nik' => $request->nik, 'password' => $request->password,'user_active'=> '1'])) {

            // log login
            $params = [
                'id'=> LoginModel::makeMicrotimeID(),
                'user_id'   => Auth::user()->user_id,
                'status'=> 'login',
                'ip_address'=> $request->ip(),
                'date'  => date('Y-m-d H:i:s'),
                'type'=> 'api',
            ];
            // insert
            LoginModel::insert_app_log($params);
            
            // get user
            $user = User::where('nik', $request->nik)->first();

            // return json
            $response = [
                "status"=> true,
                "message"=> 'Login berhasil.',
                "data"=>[
                    "access_token"=> $user->createToken($request->nik)->plainTextToken,
                    "token_type"=> "Bearer"
                ]
            ];
            return response()->json($response)->setStatusCode(200);
            
        }
        else {
            $params = [
                'id'=> LoginModel::makeMicrotimeID(),
                'nik'   => $request->nik,
                'password' => $request->password,
                'ip_address'=> $request->ip(),
                'created_date'  => date('Y-m-d H:i:s')
            ];
             // insert
             LoginModel::insert_app_login_attempt($params);

            // return json
            $response = [
                "status"=> false,
                "message"=> 'Login gagal. Silahkan cek kembali ID Pengguna & Kata Sandi Anda.'
            ];
            return response()->json($response)->setStatusCode(200);
        }
    }

    
}
