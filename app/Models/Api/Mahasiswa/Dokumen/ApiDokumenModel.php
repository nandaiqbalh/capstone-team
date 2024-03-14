<?php

namespace App\Models\Api\Mahasiswa\Dokumen;

use App\Models\Api\ApiBaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiDokumenModel extends ApiBaseModel
{
    public static function getById($id) {
        return DB::table('app_user')->where('user_id', $id)->first();
    }

    public static function getKelompokFile($id_kelompok)
    {
        return DB::table('kelompok as a')
            ->join('siklus as c' ,'a.id_siklus','c.id')
            ->where('c.status','aktif')
            ->where('a.id', $id_kelompok)
            ->first();
    }
    // get all data
    public static function fileMHS($user_id)
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.id as id_kel_mhs', 'a.id_mahasiswa', 'a.file_name_makalah', 'a.file_path_makalah','a.file_name_laporan_ta', 'a.file_path_laporan_ta','b.*')
            ->join('kelompok as b','a.id_kelompok','b.id')
            ->join('siklus as c' ,'a.id_siklus', 'c.id')
            ->where('c.status','aktif')
            ->where('a.id_mahasiswa', $user_id)
            ->first();
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
