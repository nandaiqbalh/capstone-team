<?php

namespace App\Models\TimCapstone;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardModel extends BaseModel
{

    // get all data
    public static function getData()
    {
        return DB::table('broadcast')
            ->get();
    }

    public static function getDataWithPagination()
    {
        return DB::table('broadcast')->orderBy('created_date', 'desc')->get();
    }

    public static function getDataWithHomePagination()
    {
        return DB::table('broadcast')->orderBy('created_date', 'desc')->paginate(3);
    }

    // get data by id
    public static function getDataById($id)
    {
        return DB::table('broadcast')->where('id', $id)->first();
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

    public static function sidangProposalByKelompok($idKelompok)
    {
        return DB::table('jadwal_sidang_proposal as a')
        ->select('a.*', 'b.id as siklus_id', 'b.tahun_ajaran', 'c.judul_capstone', 'c.status_kelompok', 'd.kode_ruang', 'd.nama_ruang')
        ->join('siklus as b', 'a.siklus_id', '=', 'b.id')
        ->leftJoin('kelompok as c', 'a.id_kelompok', '=', 'c.id')
        ->leftJoin('ruang_sidangs as d', 'a.ruangan_id', '=', 'd.id')
        ->where('c.id', $idKelompok)
        ->where('b.status', 'aktif')
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

   public static function checkKelompokMhs($user_id)
   {
       return DB::table('kelompok_mhs as a')
           ->select('a.*')
           ->join('siklus as b', 'a.id_siklus', 'b.id')
           ->leftJoin('kelompok as c', 'a.id_kelompok', 'c.id')
           ->where('b.status', 'aktif')
           ->where('a.id_mahasiswa', $user_id)
           ->first();
   }
}
