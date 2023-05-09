<?php

namespace App\Models\Api\V1\Auth;

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
    // get role checker
    public static function getRoleChecker($nik) {
        return DB::table('app_user as a')
                ->select('a.*')
                ->join('app_role_user as b','a.user_id','b.user_id')
                ->where('a.nik', $nik)
                ->where('b.role_id', '02')
                ->first();
    }   
    // get user by email
    public static function getUserByEmail($email) {
        return DB::table('app_user as a')
                ->select('a.*')
                ->where('a.user_email', $email)
                ->first();
    }
}
