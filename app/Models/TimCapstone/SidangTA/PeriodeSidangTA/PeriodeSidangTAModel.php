<?php

namespace App\Models\TimCapstone\SidangTA\PeriodeSidangTA;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;

class PeriodeSidangTAModel extends BaseModel
{
    // get all data
    public static function getData()
    {
        return DB::table('jadwal_periode_sidang_ta')
            ->get();
    }

    // get data with pagination
    public static function getDataWithPagination()
    {
        return DB::table('jadwal_periode_sidang_ta')
            ->paginate(20);
    }

    // get data by id
    public static function getDataById($id)
    {
        return DB::table('jadwal_periode_sidang_ta')->where('id', $id)->first();
    }

    public static function insertjadwal_periode_sidang_ta($params)
    {
        return DB::table('jadwal_periode_sidang_ta')->insert($params);
    }
    public static function editjadwal_periode_sidang_ta($id, $params)
    {
        return DB::table('jadwal_periode_sidang_ta')->where('id', $id)->update($params);
    }

    public static function update($id, $params)
    {
        return DB::table('jadwal_periode_sidang_ta')->where('id', $id)->update($params);
    }

    public static function delete($id)
    {
        return DB::table('jadwal_periode_sidang_ta')->where('id', $id)->delete();
    }

}
