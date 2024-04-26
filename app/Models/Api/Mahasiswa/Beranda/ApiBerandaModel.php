<?php

namespace App\Models\Api\Mahasiswa\Beranda;

use App\Models\Api\ApiBaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiBerandaModel extends ApiBaseModel
{

    public static function pengecekan_kelompok_mahasiswa($user_id)
    {
       return DB::table('kelompok_mhs as a')
           ->select('a.id_kelompok','b.*','c.nama as nama_topik')
           ->leftjoin('kelompok as b','a.id_kelompok','b.id')
           ->leftjoin('topik as c', 'a.id_topik_mhs', 'c.id')
           ->where('a.id_mahasiswa', $user_id)
           ->first();
    }

    public static function checkKelompokMhs($user_id)
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.*')
            ->join('siklus as b', 'a.id_siklus', 'b.id')
            ->leftJoin('kelompok as c', 'a.id_kelompok', 'c.id')
            ->where('a.id_mahasiswa', $user_id)
            ->first();
    }

    public static function cekStatusPendaftaranSidangTA($user_id)
   {
       return DB::table('pendaftaran_sidang_ta as a')
            ->select('a.status as status_pendaftaran')
            ->where('a.id_mahasiswa', $user_id)
            ->first();
   }

    public static function sidangTugasAkhirByMahasiswa($id_mahasiswa)
    {
        return DB::table('jadwal_sidang_ta as a')
            ->select('a.*','b.status_individu', 'b.id_dosen_penguji_ta1', 'b.status_dosen_penguji_ta1','b.id_dosen_penguji_ta2','b.status_dosen_penguji_ta2','b.judul_ta_mhs', 'b.link_upload','c.*', 'd.nama_ruang')
            ->join('kelompok_mhs as b', 'a.id_mahasiswa', 'b.id_mahasiswa')
            ->leftjoin('app_user as c','a.id_mahasiswa','c.user_id')
            ->leftjoin('ruang_sidangs as d','a.id_ruangan','d.id')
            ->where('a.id_mahasiswa', $id_mahasiswa)
            ->first();
    }

    public static function cekStatusExpo($user_id)
    {
        return DB::table('pendaftaran_expo as a')
            ->select('a.status as status_expo')
            ->join('jadwal_expo as b', 'a.id_expo', 'b.id')
            ->join('kelompok_mhs as c', 'a.id_kelompok', 'c.id_kelompok')
            ->join('siklus as d', 'b.id_siklus', 'd.id')
            ->where('c.id_mahasiswa', $user_id)
            ->first();
    }

    public static function checkApakahSiklusMasihAktif($id_siklus)
    {
        return DB::table('siklus')
            ->where('id', $id_siklus)
            ->first();
    }

    public static function getDataPendaftaranMhs($user_id)
    {
        return DB::table('kelompok_mhs')
            ->where('id_mahasiswa', $user_id)
            ->first();
    }

    public static function sidangProposalByKelompok($idKelompok)
    {
        return DB::table('jadwal_sidang_proposal as a')
        ->select('a.*', 'b.id as siklus_id', 'b.nama_siklus', 'c.judul_capstone', 'c.status_kelompok', 'd.kode_ruang', 'd.nama_ruang')
        ->join('siklus as b', 'a.siklus_id', '=', 'b.id')
        ->leftJoin('kelompok as c', 'a.id_kelompok', '=', 'c.id')
        ->leftJoin('ruang_sidangs as d', 'a.ruangan_id', '=', 'd.id')
        ->where('c.id', $idKelompok)
        ->first();
    }
}
