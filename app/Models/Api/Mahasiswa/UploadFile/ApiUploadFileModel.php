<?php

namespace App\Models\Api\Mahasiswa\UploadFile;

use App\Models\Api\ApiBaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiUploadFileModel extends ApiBaseModel
{
    public static function getKelompokFile($id_kelompok)
    {
        return DB::table('kelompok as a')
            ->join('siklus as c' ,'a.id_siklus', 'c.id')
            ->where('c.status','aktif')
            ->where('a.id', $id_kelompok)
            ->first();
    }
    // get all data
    public static function fileMHS($user_id)
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.id as id_kel_mhs','a.file_name_makalah', 'a.file_path_makalah','a.file_name_laporan_ta', 'a.file_path_laporan_ta','b.*')
            ->join('kelompok as b','a.id_kelompok','b.id')
            ->join('siklus as c' ,'a.id_siklus', 'c.id')
            ->where('c.status','aktif')
            ->where('a.id_mahasiswa', $user_id)
            ->first();
    }

     // pengecekan kelompok
    public static function pengecekan_kelompok_mahasiswa()
    {
    return DB::table('kelompok_mhs as a')
        ->select('a.*', 'b.*','c.nama as nama_topik')
        ->leftjoin('kelompok as b','a.id_kelompok','b.id')
        ->leftjoin('topik as c', 'a.id_topik_mhs', 'c.id')
        ->where('a.id_mahasiswa', Auth::user()->user_id)
        ->first();
    }
    // pengecekan kelompok
    public static function listKelompokMahasiswa($id_kelompok)
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.*','b.user_name','b.nomor_induk')
            ->join('app_user as b','a.id_mahasiswa','b.user_id')
            ->where('a.id_kelompok', $id_kelompok)
            ->whereNot('a.id_kelompok', null)
            ->get();
    }

    // get akun by id user
    public static function getAkunByID($user_id)
    {
        return DB::table('app_user as a')
            ->where('a.user_id', $user_id)
            ->first();
    }
    // get akun by id user
    public static function getAkun()
    {
        return DB::table('app_user as a')
        ->select('a.*')
        ->where('a.role_id','03')
        ->get();
    }

    // get akun by id user
    public static function getAkunDosen()
    {
        return DB::table('app_user as a')
        ->select('a.*')
        ->where('a.role_id', '04')
        ->get();
    }


    // get data with pagination
    public static function getDataWithPagination()
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role as c', 'a.role_id', 'c.role_id')
            ->where('c.role_id', '03')
            ->paginate(20);
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
            ->paginate(20);
    }

    // get data topik
    public static function getTopik()
    {
        return DB::table('topik')
            ->get();
    }

    // get data topik
    public static function getSiklusAktif()
    {
        return DB::table('siklus')
            ->where('status','aktif')
            ->get();
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

    public static function insertDosenKelompok($params)
    {
        return DB::table('dosen_kelompok')->insert($params);
    }
    public static function insertKelompok($params)
    {
        return DB::table('kelompok')->insert($params);
    }
    public static function insertKelompokMHS($params)
    {
        return DB::table('kelompok_mhs')->insert($params);
    }

    public static function insertPeminatan($params)
    {
        return DB::table('peminatan')->insert($params);
    }
    public static function insertTopikMHS($params)
    {
        return DB::table('topik_mhs')->insert($params);
    }

    public static function uploadFileMHS($id_mahasiswa, $params)
    {
        return DB::table('kelompok_mhs')->where('id_mahasiswa', $id_mahasiswa)->update($params);
    }

    public static function uploadFileKel($id_kelompok, $params)
    {
        return DB::table('kelompok')->where('id', $id_kelompok)->update($params);
    }

}
