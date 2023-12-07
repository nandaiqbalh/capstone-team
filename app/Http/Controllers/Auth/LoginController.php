<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Auth\LoginModel;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function user()
    {
        //return
        return view('user.client.index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return
        return view('auth.login');
    }

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
            'id_pengguna' => 'required',
            'password' => 'required|min:8|max:20',
        ];
        $this->validate($request, $rules);

        // process
        if (Auth::attempt(['nomor_induk' => $request->id_pengguna, 'password' => $request->password, 'user_active' => '1'])) {
            // regenerate session
            $request->session()->regenerate();

            // log login
            $params = [
                'id' => LoginModel::makeMicrotimeID(),
                'user_id'   => Auth::user()->user_id,
                'status' => 'login',
                'ip_address' => $request->ip(),
                'date'  => date('Y-m-d H:i:s')
            ];
            // insert
            // LoginModel::insert_app_log($params);
            session()->put('login', 'true');
            // return
            return redirect()->intended('/admin/dashboard');
        } else {
            $params = [
                'id' => LoginModel::makeMicrotimeID(),
                'nomor_induk'   => $request->id_pengguna,
                'password' => $request->password,
                'ip_address' => $request->ip(),
                'created_date'  => date('Y-m-d H:i:s')
            ];
            // insert
            // LoginModel::insert_app_login_attempt($params);

            // flash message
            $request->session()->flash('danger', 'Masuk gagal, silahkan cek kembali ID Pengguna & Kata Sandi Anda.');
            return redirect('/login')->withInput();
        }
    }
}
