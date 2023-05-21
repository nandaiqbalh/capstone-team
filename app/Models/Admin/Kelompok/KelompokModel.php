<?php

namespace App\Models\Admin\Kelompok;

use App\Models\Admin\BaseModel;
use Illuminate\Support\Facades\DB;

class KelompokModel extends BaseModel
{
    // get all data
    public static function getData()
    {
        return DB::table('kelompok')
            ->get();
    }

    // get data with pagination
    public static function getDataWithPagination()
    {
        return DB::table('kelompok as a')
            ->select('a.*','b.nama as topik_name')
            // ->leftjoin('app_user as b','a.id_dosen','b.user_id')
            ->leftjoin('topik as b', 'a.id_topik', 'b.id')
            ->orderByDesc('a.id')
            ->paginate(20);
    }

    // get search
    public static function getDataSearch($no_kel)
    {
        return DB::table('kelompok as a')
            ->select('a.*', 'b.user_name')
            ->leftjoin('app_user as b', 'a.id_dosen', 'b.user_name')
            ->where('a.nomor_kelompok', 'LIKE', "%" . $no_kel . "%")
            ->orderByDesc('a.id')
            ->paginate(20)->withQueryString();
    }

    // get data by id
    public static function getDataById($id)
    {
        return DB::table('kelompok as a')
            ->select('a.*','b.nama as nama_topik')
            ->join('topik as b','a.id_topik','b.id')
            ->where('a.id', $id)
            ->first();
    }
    // pengecekan kelompok
    public static function listKelompokMahasiswa($id_kelompok)
    {
        return DB::table('kelompok_mhs as a')
        ->select('a.*', 'b.user_name', 'b.nomor_induk','b.user_id')
        ->join('app_user as b', 'a.id_mahasiswa', 'b.user_id')
        ->where('a.id_kelompok', $id_kelompok)
        ->whereNot('a.id_kelompok', null)
        ->get();
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
