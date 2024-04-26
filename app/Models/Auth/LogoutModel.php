<?php

namespace App\Models\Auth;

use Illuminate\Support\Facades\DB;

class LogoutModel extends DB
{
    // make microtime ID
    public static function makeMicrotimeID() {
        return str_replace('.','',microtime(true));
    }
}
