<?php

namespace App\Models\Superadmin\Settings;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;

class Smtp extends BaseModel
{
    // get data by id
    public static function getById($id) {
        return DB::table('app_smtp_email')->where('email_id', $id)->first();
    }

    public static function update($id, $params) {
        return DB::table('app_smtp_email')->where('email_id', $id)->update($params);
    }
}
