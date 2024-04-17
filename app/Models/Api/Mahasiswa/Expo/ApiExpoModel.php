<?php

namespace App\Models\Api\Mahasiswa\Expo;

use Illuminate\Support\Facades\DB;
use App\Models\Api\ApiBaseModel;

class ApiExpoModel extends ApiBaseModel
{
    public static function getDataExpo()
    {
        return DB::table('jadwal_expo as a')
            ->select('a.*', 'b.nama_siklus')
            ->join('siklus as b', 'a.id_siklus', 'b.id')
            ->where('b.status', 'aktif')
            ->where('a.tanggal_selesai', '>', now())
            ->orderBy('a.tanggal_mulai', 'asc')
            ->first();
    }

    public static function kelengkapanExpo($user_id)
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.*', 'c.link_berkas_expo')
            ->join('siklus as b', 'a.id_siklus', 'b.id')
            ->leftJoin('kelompok as c', 'a.id_kelompok', 'c.id')
            ->where('b.status', 'aktif')
            ->where('a.id_mahasiswa', $user_id)
            ->first();
    }

    public static function cekStatusExpo($user_id)
    {
        return DB::table('pendaftaran_expo as a')
            ->select('a.status as status_expo')
            ->join('jadwal_expo as b', 'a.id_expo', 'b.id')
            ->join('kelompok_mhs as c', 'a.id_kelompok', 'c.id_kelompok')
            ->join('siklus as d', 'b.id_siklus', 'd.id')
            ->where('d.status', 'aktif')
            ->where('c.id_mahasiswa', $user_id)
            ->first();
    }

    public static function idKelompok($user_id)
    {
        return DB::table('kelompok_mhs')
            ->where('id_mahasiswa', $user_id)
            ->value('id_kelompok');
    }

    public static function updateKelompokMHS($user_id, $params)
    {
        return DB::table('kelompok_mhs')->where('id_mahasiswa', $user_id)->update($params);
    }

    public static function pengecekan_kelompok_mahasiswa($user_id)
    {
       return DB::table('kelompok_mhs as a')
           ->select('a.id_kelompok','b.*','c.nama as nama_topik')
           ->leftjoin('kelompok as b','a.id_kelompok','b.id')
           ->leftjoin('topik as c', 'a.id_topik_mhs', 'c.id')
           ->where('a.id_mahasiswa', $user_id)
           ->first();
    }

    public static function updateKelompokById($id_kelompok, $params)
    {
        return DB::table('kelompok')
        ->where('id', $id_kelompok)
        ->update($params);
    }

    public static function fileMHS($user_id)
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.id as id_kel_mhs', 'a.file_status_lta', 'a.file_status_mta', 'a.id_mahasiswa', 'a.file_name_makalah', 'a.file_path_makalah','a.file_name_laporan_ta', 'a.file_path_laporan_ta','b.*')
            ->join('kelompok as b','a.id_kelompok','b.id')
            ->join('siklus as c' ,'a.id_siklus', 'c.id')
            ->where('c.status','aktif')
            ->where('a.id_mahasiswa', $user_id)
            ->first();
    }

}
