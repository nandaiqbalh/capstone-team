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

    public static function pengecekan_kelompok_mahasiswa($user_id)
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.id_kelompok', 'b.*', 'c.nama as nama_topik', 'd.user_name as pengusul_kelompok')
            ->leftJoin('kelompok as b', 'a.id_kelompok', 'b.id')
            ->leftJoin('topik as c', 'a.id_topik_mhs', 'c.id')
            ->leftJoin('app_user as d', 'd.user_id', 'b.created_by')
            ->where('a.id_mahasiswa', $user_id)
            ->orderBy('a.created_date', 'desc') // Urutkan berdasarkan created_date secara descending
            ->first();
    }

    public static function isKelompokSidang($id_kelompok)
    {
        $jadwal_sidang = DB::table('jadwal_sidang_proposal as a')
                            ->select('a.*')
                            ->join('kelompok as b', 'a.id_kelompok', 'b.id')
                            ->where('b.id', $id_kelompok)
                            ->first();

        if ($jadwal_sidang) {
            // Jika waktu pada tabel telah lewat waktu sekarang
            return now()->gt($jadwal_sidang->waktu);
        }

        // Jika tidak ada jadwal sidang ditemukan untuk kelompok tersebut
        return false;
    }

    public static function isMahasiswaSidangTA($id_kelompok_mhs)
    {
        $jadwal_sidang = DB::table('jadwal_sidang_ta as a')
                            ->select('a.*')
                            ->where('id_kelompok_mhs', $id_kelompok_mhs)
                            ->first();

        if ($jadwal_sidang) {
            // Jika waktu pada tabel telah lewat waktu sekarang
            return now()->gt($jadwal_sidang->waktu);
        }

        // Jika tidak ada jadwal sidang ditemukan untuk kelompok tersebut
        return false;
    }


    public static function getKelompokFile($id_kelompok)
    {
        return DB::table('kelompok as a')
            ->where('a.id', $id_kelompok)
            ->first();
    }

    // getSiklusKelompok
    public static function getSiklusKelompok($id_siklus)
    {
        return DB::table('siklus as a')
            ->where('a.id', $id_siklus)
            ->where('a.batas_submit_c100', '>', now()) // Menambahkan kondisi a.tanggal_selesai > waktu sekarang
            ->first();
    }
    // get all data
    public static function fileMHS($user_id)
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.id as id_kel_mhs', 'a.file_status_mta', 'a.file_status_lta', 'a.id_mahasiswa', 'a.file_name_makalah', 'a.file_path_makalah','a.file_name_laporan_ta', 'a.file_path_laporan_ta','b.*')
            ->join('kelompok as b','a.id_kelompok','b.id')
            ->join('siklus as c' ,'a.id_siklus', 'c.id')
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
