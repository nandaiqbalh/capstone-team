<?php

namespace App\Models\Auth;

use Illuminate\Support\Facades\DB;

class ResetPasswordModel extends DB
{
    // make microtime ID
    public static function makeMicrotimeID() {
        return str_replace('.','',microtime(true));
    }

    public static function insert_app_login_attempt($params) {
        return DB::table('app_login_attempt')->insert($params);
    }

    // get user by email
    public static function getUserBy($nik, $email) {
        return DB::table('app_user')
                ->select('*')
                ->where('nik', $nik)
                ->where('user_email', $email)
                ->first();
    }

    // count reset password
    public static function countResetPassword($user_id) {
        return DB::table('app_reset_password')->where('user_id', $user_id)->whereDate('created_date', date('Y-m-d'))->count();
    }

    public static function update_user($id, $params) {
        return DB::table('app_user')->where('user_id', $id)->update($params);
    }

    public static function insert_reset_password($params) {
        return DB::table('app_reset_password')->insert($params);
    }

    public static function update_reset_password($id, $params) {
        return DB::table('app_reset_password')->where('id', $id)->update($params);
    }

    public static function delete_reset_password($id) {
        return DB::table('app_reset_password')->where('id', $id)->delete();
    }

    // get reset password
    public static function getResetPassword($token) {
        return DB::table('app_reset_password')->where('token', $token)->first();
    }
       
}
