<?php

namespace App\Models\Api\Mahasiswa\Mahasiswa;

use Illuminate\Support\Facades\DB;
use App\Models\Api\ApiBaseModel;

class ApiMahasiswaModel extends ApiBaseModel
{
    // get all data
    public static function getData()
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role as c', 'a.role_id', 'c.role_id')
            ->where('a.role_id', '03') // Filter berdasarkan role_id di tabel app_user
            ->orderBy('a.user_name') // Sort the result by user_name
            ->get();
    }

    public static function getDataMahasiswaAvailable()
    {
        return DB::table('app_user as a')
        ->select('a.*', 'c.role_name')
        ->join('app_role as c', 'a.role_id', 'c.role_id')
        ->leftJoin('kelompok_mhs as km', 'a.user_id', 'km.id_mahasiswa')
        ->where('a.role_id', '03') // Filter berdasarkan role_id di tabel app_user
        ->whereNull('km.id_mahasiswa') // Pastikan user_id tidak terdapat pada kelompok_mhs
        ->orderBy('a.user_name') // Sort the result by user_name
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

    public static function update($user_id, $params)
    {
        return DB::table('app_user')->where('user_id', $user_id)->update($params);
    }

    public static function delete($user_id)
    {
        return DB::table('app_user')->where('user_id', $user_id)->delete();
    }
}
