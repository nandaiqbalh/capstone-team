<?php

namespace App\Models\TimCapstone\Kelompok\PenetapanAnggota;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenetapanAnggotaModel extends BaseModel
{
    // get all data
    public static function getData()
    {
        return DB::table('app_user as a')
            ->select('a.*', 'b.')
            ->join('app_role as c', 'a.role_id', 'c.role_id')
            ->where('c.role_id', '03')
            ->get();
    }

    // get data with pagination Pendataran mahasiswa yang belum punya kelompok
    public static function getDataWithPagination()
    {
        return DB::table('app_user as a')
            ->select('a.*', 'b.*', 'c.nama as nama_topik','d.nama_siklus')
            ->join('kelompok_mhs as b', 'a.user_id', 'b.id_mahasiswa')
            ->leftjoin('topik as c', 'b.id_topik_mhs', 'c.id')
            ->join('siklus as d','b.id_siklus','d.id')
            ->where('b.id_kelompok', NULL)
            ->orderby('b.created_date', 'asc')
            ->paginate(20);
    }

    public static function getTopik()
    {
        return DB::table('topik')
            ->get();
    }

    public static function getPeminatan()
    {
        return DB::table('peminatan')
            ->get();
    }


    public static function getTopikPrioritas()
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.*','b.nama as nama_topik')
            ->join('topik as b','a.id_topik_individu1','b.id')
            ->get();
    }

    public static function getTopikbyid($id_topik)
    {
        return DB::table('topik')
            ->where('id', $id_topik)
            ->first();
    }

    public static function getMahasiswa($id_topik)
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.nama as nama_topik', 'b.*', 'c.id as id_topik', 'd.nama_peminatan as prioritas_peminatan')
            ->join('kelompok_mhs as b', 'a.user_id', 'b.id_mahasiswa')
            ->join('topik as c', 'b.id_topik_individu1', 'c.id')
            ->leftJoin('peminatan as d', 'b.id_peminatan_individu1', 'd.id') // Join peminatan dengan leftJoin
            ->where('b.id_kelompok', NULL)
            ->where('b.id_topik_individu1', $id_topik)
            ->get();
    }

    // get data topik
    public static function getSiklusAktif()
    {
        return DB::table('siklus')
            ->orderBy('id', 'desc') // Urutkan berdasarkan id descending
            ->first();
    }

    public static function getDosen($user_id)
    {
        return DB::table('app_user as a')
            ->select('a.*')
            ->where('role_id', '04')
            ->orwhere('role_id', '02')
            ->get();
    }

    // get search
    public static function getDataSearch($search)
    {

        return DB::table('app_user as a')
            ->select('a.*', 'b.*', 'c.nama as nama_topik','d.nama_siklus')
            ->join('kelompok_mhs as b', 'a.user_id', 'b.id_mahasiswa')
            ->leftjoin('topik as c', 'b.id_topik_mhs', 'c.id')
            ->join('siklus as d','b.id_siklus','d.id')
            ->where('b.id_kelompok', NULL)
            ->orderby('b.created_date', 'asc')
            ->where('a.user_name', 'LIKE', "%" . $search . "%")
            ->paginate(20);
    }

    // get data by id
    public static function getDataById($user_id)
    {
        return DB::table('app_user')->where('user_id', $user_id)->first();
    }

    public static function insertPendaftaranKelompok($params)
    {
        return DB::table('kelompok')->insert($params);
    }

    public static function updateMhsTopik($user_id, $params)
    {
        return DB::table('kelompok_mhs')->where('id_mahasiswa', $user_id)->update($params);
    }

    public static function updateKelompokMHS($user_id, $params)
    {
        return DB::table('kelompok_mhs')->where('id_mahasiswa', $user_id)->update($params);
    }

    public static function delete($user_id)
    {
        return DB::table('app_user')->where('user_id', $user_id)->delete();
    }
}
