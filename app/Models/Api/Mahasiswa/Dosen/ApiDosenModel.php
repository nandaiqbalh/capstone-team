<?php

namespace App\Models\Api\Mahasiswa\Dosen;

use Illuminate\Support\Facades\DB;
use App\Models\Api\ApiBaseModel;

class ApiDosenModel extends ApiBaseModel
{
    // get all data
    public static function getData()
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role as c', 'a.role_id', 'c.role_id')
            ->where('a.role_id', '04') // Filter berdasarkan role_id di tabel app_user
            ->orwhere('a.role_id', '02')
            ->orderBy('a.user_name') // Sort the result by user_name
            ->get();
    }
}
