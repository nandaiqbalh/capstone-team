<?php

namespace App\Models\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiBaseModel
{
    // make microtime ID
    public static function makeMicrotimeID() {
        return str_replace('.','',microtime(true));
    }

}
