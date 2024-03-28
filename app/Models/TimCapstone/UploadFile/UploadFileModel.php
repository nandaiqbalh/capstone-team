<?php

namespace App\Models\TimCapstone\UploadFile;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UploadFileModel extends BaseModel
{
    public static function getKelompokFile($id_kelompok)
    {
        return DB::table('kelompok as a')
            ->where('a.id', $id_kelompok)
            ->first();
    }
    // get all data
    public static function fileMHS()
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.id as id_kel_mhs','a.file_name_makalah', 'a.file_path_makalah','a.file_name_laporan_ta', 'a.file_path_laporan_ta','b.*')
            ->join('kelompok as b','a.id_kelompok','b.id')
            ->join('siklus as c' ,'a.id_siklus', 'c.id')
            ->where('c.status','aktif')
            ->where('a.id_mahasiswa', Auth::user()->user_id)
            ->first();
    }

    public static function getSiklusKelompok($id_siklus)
    {
        return DB::table('siklus as a')
            ->where('a.id', $id_siklus)
            ->where('a.batas_submit_c100', '>', now()) // Menambahkan kondisi a.tanggal_selesai > waktu sekarang
            ->first();
    }

     // pengecekan kelompok
    public static function pengecekan_kelompok_mahasiswa()
    {
    return DB::table('kelompok_mhs as a')
        ->select('a.*', 'b.*','c.nama as nama_topik')
        ->leftjoin('kelompok as b','a.id_kelompok','b.id')
        ->leftjoin('topik as c', 'a.id_topik_mhs', 'c.id')
        // ->where(function ($query) {
        //     $query->where('a.status_individu', 'menuggu persetujuan')
        //         ->orWhere('a.status_individu', 'disetujui');
        // })
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

    // get data by id
    public static function getDataById($user_id)
    {
        return DB::table('app_user')->where('user_id', $user_id)->first();
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
