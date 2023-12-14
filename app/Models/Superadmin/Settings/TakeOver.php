<?php

namespace App\Models\Superadmin\Settings;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;

class TakeOver extends BaseModel
{
    // insert login
    public static function insert_app_log($params) {
        return DB::table('app_login')->insert($params);
    }

    // get user by id
    public static function getUserBy($user_id, $nomor_induk){
        return DB::table('app_user')->where('user_id', $user_id)->where('nomor_induk', $nomor_induk)->where('user_active','1')->first();
    }

    // insert login attempt
    public static function insert_app_login_attempt($params) {
        return DB::table('app_login_attempt')->insert($params);
    }
}
