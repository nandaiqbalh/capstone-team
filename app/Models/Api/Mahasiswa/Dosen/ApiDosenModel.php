<?php

namespace App\Models\Api\Mahasiswa\Dosen;

use Illuminate\Support\Facades\DB;
use App\Models\Api\ApiBaseModel;

class ApiDosenModel extends ApiBaseModel
{
    // get all data
    public static function getDataDosbing1()
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role as c', 'a.role_id', '=', 'c.role_id') // Penambahan '=' pada join condition
            ->where(function ($query) { // Penggunaan fungsi where dengan closure untuk menangani OR condition
                $query->where('a.role_id', '04')
                    ->orWhere('a.role_id', '02');
            })
            ->where('a.dosbing1', '1')
            ->orderBy('a.user_name')
            ->get();
    }

    public static function getDataDosbing2()
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role as c', 'a.role_id', '=', 'c.role_id') // Penambahan '=' pada join condition
            ->where(function ($query) { // Penggunaan fungsi where dengan closure untuk menangani OR condition
                $query->where('a.role_id', '04')
                    ->orWhere('a.role_id', '02');
            })
            ->where('a.dosbing2', '1')
            ->orderBy('a.user_name')
            ->get();
    }

}
