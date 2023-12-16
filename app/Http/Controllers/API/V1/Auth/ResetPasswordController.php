<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Api\V1\Auth\ResetPasswordModel;
use App\Models\Api\V1\Auth\LoginModel;
use App\Models\User;

use DateTime;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Throwable;

class ResetPasswordController extends Controller
{

    
    /**
     * Handle reset password request
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function resetPasswordProcess(Request $request)
    {
        // Validate & auto redirect when fail
        $rules = [
            'id_pengguna' => 'required|digits_between:6,11|numeric',
            'email' => 'required|email|max:100',
        ];
        $this->validate($request, $rules );

        // cek email
        $user = ResetPasswordModel::getUserBy($request->id_pengguna,$request->email);
        if(empty($user)) {
            // response message
            $response = [
                "status"=> false,
                "message"=> 'Akun tidak ditemukan!'
            ];
            return response()->json($response)->setStatusCode(200);
        }

        // cek jumlah reset hari ini
        $count = ResetPasswordModel::countResetPassword($user->user_id);
        
        if($count > 0) {
            // response message
            $response = [
                "status"=> false,
                "message"=> 'Anda sudah melakukan Reset Password Hari ini!'
            ];
            return response()->json($response)->setStatusCode(200);
        }

        // insert log
        $token = hash('sha256',$request->email);
        $id = ResetPasswordModel::makeMicrotimeID();
        $params =[
            'id' => $id,
            'user_id'=> $user->user_id,
            'ip_address'=> $request->ip(),
            'token'=> $token,
            'status'=> '1',
            'created_date'  => date('Y-m-d H:i:s'),
            'max_age'  => date_format(date_modify(new DateTime(date('Y-m-d H:i:s')), '+ 60 minutes'), 'Y-m-d H:i:s')
        ];
        
        // update
        if(ResetPasswordModel::insert_reset_password($params)) {
            // params mail
            $data = [
                'title'=> 'Pemberitahuan Reset Kata Sandi',
                'user_name' => $user->user_name,
                'user_email' => $user->user_email,
                'reset_password_url' => env('APP_URL').'/ubah-password?token='.$token,
                'email_type'=> 'reset-password'
            ];
            // send mail
            if($this->sendMail($data)) {
                // flash message
                $response = [
                    "status"=> True,
                    "message"=> 'Silahkan cek E-mail anda untuk perubahan kata sandi!',
                ];
                return response()->json($response)->setStatusCode(200);
            }
            else {
                // delete current reset password
                ResetPasswordModel::delete_reset_password($id);
               // response message
                $response = [
                    "status"=> false,
                    "message"=> 'Gagal mengirim E-mail, silahkan coba beberapa saat lagi!'
                ];
                return response()->json($response)->setStatusCode(200);
            }

        }
        else {
             // response message
             $response = [
                "status"=> false,
                "message"=> 'Gagal mereset kata sandi!'
            ];
            return response()->json($response)->setStatusCode(200);
        }
    }

    /**
     * Display a listing of the resource.
     *@param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function ubahPassword(Request $request)
    {   
        // Validate & auto redirect when fail
        $rules = [
            'token' => 'required',
        ];
        $this->validate($request, $rules );

        // get data
        $reset_password = ResetPasswordModel::getResetPassword($request->token);

        if(empty($reset_password)) {
            // flash message
            // response message
            $response = [
                "status"=> false,
                "message"=> 'Token tidak valid!'
            ];
            return response()->json($response)->setStatusCode(200);
        }

        // cek status
        if($reset_password->status == '0') {
            // flash message
            // response message
            $response = [
                "status"=> false,
                "message"=> 'Url sudah kadaluwarsa!'
            ];
            return response()->json($response)->setStatusCode(200);
        }

        // cek max_age
        if($reset_password->max_age < date('Y-m-d H:i:s')) {
            // update status
            ResetPasswordModel::update_reset_password($reset_password->id, ['status'=>'0']);
            // response message
            $response = [
                "status"=> false,
                "message"=> 'Url sudah kadaluwarsa!'
            ];
            return response()->json($response)->setStatusCode(200);
        }

        // return
        return view('auth.ubah-password',['reset_token'=>$request->token]);
    }

    /**
     * Display a listing of the resource.
     *@param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function ubahPasswordProcess(Request $request)
    {   
        // Validate & auto redirect when fail
        $rules = [
            'reset_token' => 'required',
            'password' => 'required|min:8|max:20|confirmed',
            'password_confirmation' => 'required|min:8|max:20',
            'g-recaptcha-response' => 'required|recaptchav3:register,0.5',
        ];
        $this->validate($request, $rules );

        // get data
        $reset_password = ResetPasswordModel::getResetPassword($request->reset_token);

        // cek max_age
        if($reset_password->max_age < date('Y-m-d H:i:s')) {
            // update status
            ResetPasswordModel::update_reset_password($reset_password->id, ['status'=>'0']);
            // flash message
            $request->session()->flash('danger', 'Url sudah kadaluwarsa!');
            return redirect('/lupa-password');
        }

        // params password user
        $params = [
            'user_password'=> Hash::make($request->password),
            'modified_by'=> $reset_password->user_id,
            'modified_date'=> date('Y-m-d H:i:s')
        ];
        
        // update password
        if(ResetPasswordModel::update_user($reset_password->user_id, $params)) {
            // update status reset password
            ResetPasswordModel::update_reset_password($reset_password->id, ['status'=>'0']);
             // flash message
             $request->session()->flash('success', 'Password berhasil diubah, silahkan login.');
             return redirect('/login');
        }
        else {
            // flash message
            $request->session()->flash('danger', 'Password gagal diubah!');
            return redirect()->back();
        }

    }

     /**
     * SEND MAIL
     */

    public function sendMail($data) {
        try {
            // try send mail
            Mail::to($data['user_email'])->send(new SendMail($data));
            // dd('berhasil');
            return true;
        } catch (Throwable $e) {
            report($e);
            // dd($e);
            return false;
        }
    }
    
}
