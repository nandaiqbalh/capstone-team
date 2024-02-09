<?php

namespace App\Models\Api\TimCapstone\Mahasiswa;

use Illuminate\Support\Facades\DB;

class ApiMahasiswaModel extends Model
{
    // get all data
    public static function getData()
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role as c', 'a.role_id', 'c.role_id')
            ->where('a.role_id', '03') // Filter berdasarkan role_id di tabel app_user
            ->get();
    }

    // get data with pagination
    public static function getDataWithPagination()
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role as c', 'a.role_id', 'c.role_id')
            ->where('a.role_id', '03') // Filter berdasarkan role_id di tabel app_user
            ->paginate(20);
    }

    // get search
    public static function getDataSearch($search)
    {

        // return DB::table('app_user as a')
        // ->select('a.*', 'c.role_name')
        // ->join('app_role_user as b', 'a.id', 'b.id')
        // ->join('app_role as c', 'b.role_id', 'c.role_id')
        // ->where('c.role_id', '03')
        // ->where('a.user_name', 'LIKE', "%" . $search . "%")
        // // ->orwhere('a.nomor_induk', 'LIKE', "%" . $search . "%")
        // ->paginate(20)->withQueryString();

        // return DB::table('app_user as a')
        // ->select('a.*', 'c.role_name')
        // ->join('app_role as c', 'a.role_id', 'c.role_id')
        // ->where('a.role_id', '03') // Filter berdasarkan role_id di tabel app_user
        // ->paginate(20);

        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role as c', 'a.role_id', 'c.role_id')
            ->where('a.role_id', '03') // Filter berdasarkan role_id di tabel app_user
            ->where('a.user_name', 'LIKE', "%" . $search . "%")
            ->paginate(20)->withQueryString();
    }

    // get data by id
    public static function getDataById($user_id)
    {
        return DB::table('app_user as a')
            ->leftJoin('kelompok_mhs as b' ,'a.user_id', 'b.id_mahasiswa')
            ->leftJoin('siklus as c','b.id_siklus','c.id')
            ->where('user_id', $user_id)->first();
    }

    public static function peminatan($user_id)
    {
        return DB::table('peminatan')
            ->where('id_mahasiswa', $user_id)
            ->orderBy('prioritas')
            ->get();
    }


    public static function insertmahasiswa($params)
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

    public static function delete($user_id)
    {
        return DB::table('app_user')->where('user_id', $user_id)->delete();
    }
}
