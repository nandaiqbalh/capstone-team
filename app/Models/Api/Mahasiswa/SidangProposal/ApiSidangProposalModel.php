<?php

namespace App\Models\Api\Mahasiswa\SidangProposal;

use Illuminate\Support\Facades\DB;
use App\Models\Api\ApiBaseModel;

class ApiSidangProposalModel extends ApiBaseModel
{

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

    public static function pengecekan_kelompok_mahasiswa($user_id)
    {
       return DB::table('kelompok_mhs as a')
           ->select('a.id_kelompok','b.*','c.nama as nama_topik')
           ->leftjoin('kelompok as b','a.id_kelompok','b.id')
           ->leftjoin('topik as c', 'a.id_topik_mhs', 'c.id')
           ->where('a.id_mahasiswa', $user_id)
           ->first();
    }

    public static function getDataPendaftaranMhs($user_id)
    {
        return DB::table('kelompok_mhs')
            ->where('id_mahasiswa', $user_id)
            ->first();
    }

    public static function checkApakahSiklusMasihAktif($id_siklus)
    {
        return DB::table('siklus')
            ->where('id', $id_siklus)
            ->first();
    }
}
