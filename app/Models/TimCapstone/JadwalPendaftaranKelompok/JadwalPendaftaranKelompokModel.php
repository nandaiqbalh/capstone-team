<?php

namespace App\Models\TimCapstone\JadwalPendaftaranKelompok;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;

class JadwalPendaftaranKelompokModel extends BaseModel
{
    // get all data
    // public static function getData()
    // {
    //     return DB::table('app_user as a')
    //         ->select('a.*', 'b.')
    //         ->join('app_role_user as b', 'a.user_id', 'b.user_id')
    //         ->join('app_role as c', 'b.role_id', 'c.role_id')
    //         ->where('c.role_id', '03')
    //         ->get();
    // }

    // get data with pagination Pendataran mahasiswa yang belum punya kelompok
    public static function getDataWithPagination()
    {
        return DB::table('pendaftaran_capstone as a')
            ->select('a.*','b.id as siklus_id', 'b.tahun_ajaran')
            ->join('siklus as b', 'a.siklus_id', 'b.id')
            ->where('b.status', 'aktif')
            ->paginate(20);
    }

    public static function getSiklus()
    {
        return DB::table('siklus')
            ->where('status','aktif')
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
            ->select('a.*', 'c.nama as nama_topik', 'c.id as id_topik')
            ->join('kelompok_mhs as b', 'a.user_id', 'b.id_mahasiswa')
            ->join('topik as c', 'b.id_topik_mhs', 'c.id')
            ->where('b.id_topik_mhs', $id_topik)
            ->get();
    }

    public static function getDosen($user_id)
    {
        return DB::table('app_user')
            ->where('user_id', '04')
            ->get();
    }

    // get search
    public static function getDataSearch($search)
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role as c', 'a.role_id', 'c.role_id')
            ->where('c.role_id', '03')
            ->where('a.user_name', 'LIKE', "%" . $search . "%")
            // ->orwhere('a.nomor_induk', 'LIKE', "%" . $search . "%")
            ->paginate(20)->withQueryString();
    }

    // get data by id
    public static function getDataById($id)
    {
        return DB::table('pendaftaran_capstone')->where('id', $id)->first();
    }

    public static function insertJadwalPendaftaranKelompok($params)
    {
        return DB::table('pendaftaran_capstone')->insert($params);
    }
    public static function updateJadwalPendaftaranKelompok($id, $params)
    {
        return DB::table('pendaftaran_capstone')->where('id', $id)->update($params);
    }

    public static function deleteJadwalPendaftaranKelompok($id)
    {
        return DB::table('pendaftaran_capstone')->where('id', $id)->delete();
    }
}
