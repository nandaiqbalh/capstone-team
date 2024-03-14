<?php

namespace App\Models\Api\Mahasiswa\Topik;

use App\Models\Api\ApiBaseModel;
use Illuminate\Support\Facades\DB;

class ApiTopikModel extends ApiBaseModel
{
    public static function getTopik()
    {
        return DB::table('topik')
            ->get();
    }

}
