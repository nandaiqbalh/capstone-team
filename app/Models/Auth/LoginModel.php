<?php

namespace App\Models\Auth;

use Illuminate\Support\Facades\DB;

class LoginModel extends DB
{
    // make microtime ID
    public static function makeMicrotimeID() {
        return str_replace('.','',microtime(true));
    }

    // insert login
    public static function insert_app_log($params) {
        return DB::table('app_login')->insert($params);
    }

    // insert login attempt
    public static function insert_app_login_attempt($params) {
        return DB::table('app_login_attempt')->insert($params);
    }

    // get user by email
    public static function getUserByEmail($email) {
        return DB::table('app_user as a')
                ->select('a.*')
                ->where('a.user_email', $email)
                ->first();
    }
}
