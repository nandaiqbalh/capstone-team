<?php

namespace App\Models\Api\Mahasiswa\Profile;

use App\Models\Api\ApiBaseModel;
use Illuminate\Support\Facades\DB;

class ApiAccountModel extends ApiBaseModel
{
    // get data by id
    public static function getById($id) {
        return DB::table('app_user')->where('user_id', $id)->first();
    }

    public static function update($user_id, $params) {
        return DB::table('app_user')->where('user_id', $user_id)->update($params);
    }
}
