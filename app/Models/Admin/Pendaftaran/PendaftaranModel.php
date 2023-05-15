<?php

namespace App\Models\Admin\Pendaftaran;

use App\Models\Admin\BaseModel;
use Illuminate\Support\Facades\DB;

class PendaftaranModel extends BaseModel
{
    // get all data
    public static function getData()
    {
        return DB::table('app_user as a')
            ->select('a.*', 'b.')
            ->join('app_role_user as b', 'a.user_id', 'b.user_id')
            ->join('app_role as c', 'b.role_id', 'c.role_id')
            ->where('c.role_id', '03')
            ->get();
    }

    // get data with pagination
    public static function getDataWithPagination()
    {
        return DB::table('app_user as a')
            ->select('a.*', 'f.nama as nama_topik')
            ->join('kelompok_mhs as d', 'a.user_id', 'd.id_mahasiswa')
            ->leftjoin('kelompok as e', 'd.id_kelompok', 'e.id')
            ->leftjoin('topik as f', 'e.id_topik', 'f.id')
            ->where('d.id_kelompok', NULL)
            ->paginate(20);
    }

    // get search
    public static function getDataSearch($search)
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role_user as b', 'a.user_id', 'b.user_id')
            ->join('app_role as c', 'b.role_id', 'c.role_id')
            ->where('c.role_id', '03')
            ->where('a.user_name', 'LIKE', "%" . $search . "%")
            // ->orwhere('a.nomor_induk', 'LIKE', "%" . $search . "%")
            ->paginate(20)->withQueryString();
    }

    // get data by id
    public static function getDataById($user_id)
    {
        return DB::table('app_user')->where('user_id', $user_id)->first();
    }

    public static function insertPendaftaran($params)
    {
        return DB::table('app_user')->insert($params);
    }

    public static function insertrole($params2)
    {
        return DB::table('app_role_user')->insert($params2);
    }

    public static function update($user_id, $params)
    {
        return DB::table('app_user')->where('user_id', $user_id)->update($params);
    }

    public static function delete($user_id)
    {
        return DB::table('app_user')->where('user_id', $user_id)->delete();
    }
}
