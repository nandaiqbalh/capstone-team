<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Settings\TakeOver;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class TakeOverLoginController extends BaseController
{


    /**
     * take over login
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function takeOverProcess(Request $request)
    {
        // authorize
        TakeOver::authorize('R');

        // decrypt data
        $incoming_user_id   = Crypt::decryptString($request->id);
        $incoming_nomor_induk       = Crypt::decryptString($request->nomor_induk);

        // cegah take over login diri sendiri
        if($incoming_user_id == Auth::user()->user_id){
            // flash message
            $request->session()->flash('danger', 'Gagal, terjadi kesalahan.');
            return redirect()->back();
        }

        // cek user
        // get user by
        $incoming_user = TakeOver::getUserBy($incoming_user_id, $incoming_nomor_induk);
        if(empty($incoming_user)){
            // flash message
            $request->session()->flash('danger', 'Gagal, terjadi kesalahan.');
            return redirect()->back();
        }

        // persiapan logout user sebelummnya
        $old_user_id    = Auth::user()->user_id;
        $old_nomor_induk        = Auth::user()->nomor_induk;

        $params = [
            'id'        => TakeOver::makeMicrotimeID(),
            'user_id'   => $old_user_id,
            'status'    => 'logout',
            'ip_address'=> $request->ip(),
            'date'      => date('Y-m-d H:i:s')
        ];
        // insert
        if(true) {

            // logout
            Auth::logout();
            // invalidate session
            $request->session()->invalidate();
            // generate new token
            $request->session()->regenerateToken();

            // -----------------------------------------------------------------------------
            // login dengan data user
            Auth::loginUsingId($incoming_user_id);
            // regenerate session
            $request->session()->regenerate();

            // cek login
            if(Auth::check()){
                // log login
                $params = [
                    'id'        => TakeOver::makeMicrotimeID(),
                    'user_id'   => $incoming_user_id,
                    'status'    => 'login',
                    'ip_address'=> $request->ip(),
                    'date'      => date('Y-m-d H:i:s')
                ];
                // insert
                // TakeOver::insert_app_log($params);

                // simpan informasi login sebelummnya
                $request->session()->put('old_take_over_user_id', Crypt::encryptString($old_user_id));
                $request->session()->put('old_take_over_nomor_induk', Crypt::encryptString($old_nomor_induk));

                // log
                Log::info('User '.$old_user_id.' berhasil mengambil alih akun '.$incoming_user_id);

                // return
                return redirect()->intended('/admin/dashboard');
            }else {
                // insert percobaan login
                $params = [
                    'id'            => TakeOver::makeMicrotimeID(),
                    'nomor_induk'           => $incoming_user->nomor_induk,
                    'password'      => $request->password,
                    'ip_address'    => $request->ip(),
                    'created_date'  => date('Y-m-d H:i:s')
                ];
                 // insert
                //  TakeOver::insert_app_login_attempt($params);

                // -----------------------------------------------------------------------------
                // login dengan data user
                Auth::loginUsingId($old_user_id);
                // regenerate session
                $request->session()->regenerate();

                // log
                Log::info('User '.$old_user_id.' gagal mengambil alih akun '.$incoming_user_id);

                // flash message
                $request->session()->flash('danger', 'Gagal, terjadi kesalahan.');
                return redirect()->back();
            }
        }
        else {
            // flash message
            $request->session()->flash('danger', 'Gagal, terjadi kesalahan.');
            return redirect()->back();
        }

    }


}
