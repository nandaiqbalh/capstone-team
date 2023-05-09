<?php

namespace App\Models\Auth;

use Illuminate\Support\Facades\DB;

class LogoutModel extends DB
{
    // make microtime ID
    public static function makeMicrotimeID() {
        return str_replace('.','',microtime(true));
    }

    // insert login
    public static function insert_app_log($params) {
        return DB::table('app_login')->insert($params);
    }
}
