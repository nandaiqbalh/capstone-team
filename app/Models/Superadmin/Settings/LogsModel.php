<?php

namespace App\Models\Superadmin\Settings;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;

class LogsModel extends BaseModel
{
    // ------------------------------------------------------------------------------
    // LOGIN
    // get data with pagination
    public static function getLogLogin() {
        return DB::table('app_login as a')
                ->select('a.*','b.user_name')
                ->join('app_user as b','a.user_id','=','b.user_id')
                ->whereDate('a.date', date('Y-m-d'))
                ->orderByDesc('a.date')
                ->paginate(10);
    }

    // get search
    public static function getLogLoginBy($date) {
        return DB::table('app_login as a')
                ->select('a.*','b.user_name')
                ->join('app_user as b','a.user_id','=','b.user_id')
                ->whereDate('a.date', $date)
                ->orderByDesc('a.date')
                ->paginate(10);
    }

    // ------------------------------------------------------------------------------
    // LOGIN ATTEMPT
    // get data with pagination
    public static function getLogLoginAttempt() {
        return DB::table('app_login_attempt as a')
                ->whereDate('a.created_date', date('Y-m-d'))
                ->orderByDesc('a.created_date')
                ->paginate(10);
    }

    // get search
    public static function getLogLoginAttemptBy($date) {
        return DB::table('app_login_attempt as a')
                ->whereDate('a.created_date', $date)
                ->orderByDesc('a.created_date')
                ->paginate(10);
    }

    // ------------------------------------------------------------------------------
    // RESET PASSWORD
    // get data with pagination
    public static function getResetPassword() {
        return DB::table('app_reset_password as a')
                ->select('a.*','b.user_name')
                ->join('app_user as b','a.user_id','=','b.user_id')
                ->whereDate('a.created_date', date('Y-m-d'))
                ->orderByDesc('a.created_date')
                ->paginate(10);
    }

    // get search
    public static function getResetPasswordBy($date) {
        return DB::table('app_reset_password as a')
                ->select('a.*','b.user_name')
                ->join('app_user as b','a.user_id','=','b.user_id')
                ->whereDate('a.created_date', $date)
                ->orderByDesc('a.created_date')
                ->paginate(10);
    }
}
