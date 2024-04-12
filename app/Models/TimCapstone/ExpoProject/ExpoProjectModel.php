<?php

namespace App\Models\TimCapstone\ExpoProject;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;

class ExpoProjectModel extends BaseModel
{
    // get data with pagination Pendataran mahasiswa yang belum punya kelompok
    public static function getDataWithPagination()
    {
        return DB::table('jadwal_expo as a')
            ->select('a.*', 'b.id as id_siklus', 'b.nama_siklus')
            ->join('siklus as b', 'a.id_siklus', 'b.id')
            ->orderBy('a.id', 'desc') // Mengurutkan berdasarkan ID terbaru (descending)
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
        return DB::table('jadwal_expo as a')
            ->select('a.*', 'b.nama_siklus','c.*')
            ->join('siklus as b', 'a.id_siklus', 'b.id')
            ->join('pendaftaran_expo as c','a.id','c.id_expo')
            ->where('a.id', $id)->first();
    }

    public static function getDataEditById($id)
    {
        return DB::table('jadwal_expo as a')
            ->select('a.*', 'b.nama_siklus')
            ->join('siklus as b', 'a.id_siklus', 'b.id')
            ->where('a.id', $id)->first();
    }

    // get data by id
    public static function getExpoDaftar($id)
    {
        return DB::table('pendaftaran_expo as a')
            ->select('a.id as id_pendaftaran', 'a.status as status_pendaftaran', 'a.id_expo','b.id as id_kelompok', 'b.*')
            ->join('kelompok as b','a.id_kelompok','b.id')
            ->where('a.id_expo', $id)
            ->get();
    }

    public static function insertExpoProject($params)
    {
        return DB::table('jadwal_expo')->insert($params);
    }
    public static function updateExpoProject($id, $params)
    {
        return DB::table('jadwal_expo')->where('id', $id)->update($params);
    }

    public static function updateExpoProjectKelompok($id, $params)
    {
        return DB::table('pendaftaran_expo')->where('id', $id)->update($params);
    }
    public static function getDataPendaftaranExpo($id)
    {
        return DB::table('pendaftaran_expo as a')
            ->select('a.*')
            ->where('a.id', $id)->first();
    }

    public static function getKelompokMendaftar($id)
    {
        return DB::table('pendaftaran_expo as a')
            ->select('a.*')
            ->where('a.id_expo', $id)->first();
    }

    public static function deleteExpoProject($id)
    {
        return DB::table('jadwal_expo')->where('id', $id)->delete();
    }

    public static function deletePendaftaranExpo($id_expo)
    {
        return DB::table('pendaftaran_expo')->where('id_expo', $id_expo)->delete();
    }

    public static function updateKelompok($id_kelompok, $params)
    {
        return DB::table('kelompok')->where('id', $id_kelompok)->update($params);
    }

    public static function updateKelompokMhsByKelompok($id_kelompok, $params)
    {
        return DB::table('kelompok_mhs')->where('id_kelompok', $id_kelompok)->update($params);
    }

    public static function getDataKelompok($id)
    {
        return DB::table('kelompok as a')
            ->where('a.id', $id)->first();
    }
}
