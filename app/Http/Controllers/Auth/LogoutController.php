<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Auth\LogoutModel;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class LogoutController extends Controller
{
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // log logout
        $user_id = Auth::user()->user_id;
        $params = [
            'id'        => LogoutModel::makeMicrotimeID(),
            'user_id'   => $user_id,
            'status'    => 'logout',
            'ip_address'=> $request->ip(),
            'date'      => date('Y-m-d H:i:s')
        ];
        // insert
        if(true) {

            // cek apakah sebelummnya login menggunakan metode take over
            if($request->session()->has('old_take_over_user_id') && $request->session()->has('old_take_over_nik')){
                // ambil dari session sekaligus hapus
                // masih dalam bentuk terencrypt
                $old_take_over_user_id  = Crypt::decryptString($request->session()->pull('old_take_over_user_id'));
                $old_take_over_nik      = Crypt::decryptString($request->session()->pull('old_take_over_nik'));

                // logout
                Auth::logout();
                // invalidate session
                $request->session()->invalidate();
                // generate new token
                $request->session()->regenerateToken();

                // -----------------------------------------------------------------------------
                // login dengan data user
                Auth::loginUsingId($old_take_over_user_id);
                // regenerate session
                $request->session()->regenerate();

                // cek login
                if(Auth::check()){
                    // log login
                    $params = [
                        'id'        => LogoutModel::makeMicrotimeID(),
                        'user_id'   => $old_take_over_user_id,
                        'status'    => 'login',
                        'ip_address'=> $request->ip(),
                        'date'      => date('Y-m-d H:i:s')
                    ];
                    // insert
                    // LogoutModel::insert_app_log($params);

                    // log
                    Log::info('User '.$old_take_over_user_id.' selesai mengambil alih akun '.$user_id);

                    // return
                    return redirect()->intended('admin/dashboard');
                }
                else {
                    // insert percobaan login
                    $params = [
                        'id'            => LogoutModel::makeMicrotimeID(),
                        'nik'           => $old_take_over_nik,
                        'password'      => 'Take over login',
                        'ip_address'    => $request->ip(),
                        'created_date'  => date('Y-m-d H:i:s')
                    ];
                    // insert
                    // LogoutModel::insert_app_login_attempt($params);

                    // -----------------------------------------------------------------------------
                    // login dengan data user
                    Auth::loginUsingId($user_id);
                    // regenerate session
                    $request->session()->regenerate();

                    // flash message
                    $request->session()->flash('danger', 'Gagal, terjadi kesalahan.');
                    return redirect()->back();
                }


            }
            else {
                // logout normal

                // logout
                Auth::logout();
                // invalidate session
                $request->session()->invalidate();
                // generate new token
                $request->session()->regenerateToken();
                // flash message
                $request->session()->flash('success', 'Berhasil, Anda sudah keluar dari aplikasi.');
                session()->put('login', 'false');
                return redirect('/login');
            }

        }
        else {
            // flash message
            $request->session()->flash('danger', 'Gagal, terjadi kesalahan.');
            return redirect()->back();
        }
    }
}
