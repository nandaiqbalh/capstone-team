<?php

namespace App\Models\Superadmin\Settings;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;

class Account extends BaseModel
{
    // get data by id
    public static function getById($id) {
        return DB::table('app_user')->where('user_id', $id)->first();
    }

    public static function update($id, $params) {
        return DB::table('app_user')->where('user_id', $id)->update($params);
    }
}
