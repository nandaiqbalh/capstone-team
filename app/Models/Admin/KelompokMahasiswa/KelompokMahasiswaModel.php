<?php

namespace App\Models\Admin\KelompokMahasiswa;

use App\Models\Admin\BaseModel;
use Illuminate\Support\Facades\DB;

class KelompokMahasiswaModel extends BaseModel
{
    // get all data
    public static function getData()
    {
        return DB::table('kelompok as a')
            ->select('a.*','b.user_name as dosen_name','c.nama as topik_name')
            ->leftjoin('app_user as b','a.id_dosen','b.user_id')
            ->leftjoin('topik as c', 'a.id_topik', 'c.id')
            ->orderByDesc('a.id')
            ->get();
    }

     // pengecekan kelompok
     public static function pengecekan_kelompok_mahasiswa()
     {
        return DB::table('kelompok_mhs as a')
            ->select('a.*','b.id as kelompok')
            ->leftjoin('id as b','a.id_kelompok','b.id')
            ->orderByDesc('b.id')
            ->get();
     }


    // get data with pagination
    public static function getDataWithPagination()
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role_user as b', 'a.user_id', 'b.user_id')
            ->join('app_role as c', 'b.role_id', 'c.role_id')
            ->where('c.role_id', '03')
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

    public static function insertmahasiswa($params)
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
