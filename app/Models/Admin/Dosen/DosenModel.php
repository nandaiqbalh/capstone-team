<?php

namespace App\Models\Admin\Dosen;

use App\Models\Admin\BaseModel;
use Illuminate\Support\Facades\DB;

class DosenModel extends BaseModel
{
    // get all data
    public static function getData()
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role as c', 'a.role_id', 'c.role_id')
            ->where('c.role_id', '04')
            ->orwhere('c.role_id', '02')
            ->get();
    }

    // get data with pagination
    public static function getDataWithPagination()
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role as c', 'a.role_id', 'c.role_id')
            ->where('c.role_id', '04')
            ->orwhere('c.role_id', '02')
            ->paginate(20);
    }

    // get search
    // public static function getDataSearch($nama)
    // {
    //     return DB::table('app_user')->where('nama', 'LIKE', "%" . $nama . "%")->paginate(20)->withQueryString();
    // }

    // get data by id
    public static function getDataById($user_id)
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name', 'c.role_id')
            ->join('app_role as c', 'a.role_id', 'c.role_id')
            ->where('a.user_id', $user_id)
            ->where('c.role_id', '04')
            ->orwhere('c.role_id', '02')
            ->where('a.user_id', $user_id)
            ->first();
    }

    public static function getDataSearch($search)
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role as c', 'a.role_id', 'c.role_id')
            ->where('c.role_id', '04')
            ->where('a.user_name', 'LIKE', "%" . $search . "%")
            ->orwhere('c.role_id', '02')
            ->where('a.user_name', 'LIKE', "%" . $search . "%")
            // ->orwhere('a.nomor_induk', 'LIKE', "%" . $search . "%")
            ->paginate(20)->withQueryString();
    }

    public static function insertDosen($params)
    {
        return DB::table('app_user')->insert($params);
    }

    // public static function insertrole($params2)
    // {
    //     return DB::table('app_role_user')->insert($params2);
    // }

    public static function update($user_id, $params)
    {
        return DB::table('app_user')->where('user_id', $user_id)->update($params);
    }
    // public static function updaterole($user_id, $params)
    // {
    //     return DB::table('app_role_user')->where('user_id', $user_id)->update($params);
    // }

    public static function delete($user_id)
    {
        return DB::table('app_user')->where('user_id', $user_id)->delete();
    }
}
