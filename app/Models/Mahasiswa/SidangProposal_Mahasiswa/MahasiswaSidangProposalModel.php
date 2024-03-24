<?php

namespace App\Models\Mahasiswa\SidangProposal_Mahasiswa;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MahasiswaSidangProposalModel extends BaseModel
{
      // pengecekan kelompok
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

      // pengecekan kelompok
      public static function listKelompokMahasiswa($id_kelompok)
      {
          return DB::table('kelompok_mhs as a')
              ->select('a.*', 'b.user_name', 'b.nomor_induk', 'b.user_img_path', 'b.user_img_name' )
              ->join('app_user as b','a.id_mahasiswa','b.user_id')
              ->where('a.id_kelompok', $id_kelompok)
              ->whereNot('a.id_kelompok', null)
              ->get();
      }

      // get akun by id user
      public static function getAkunByID($user_id)
      {
          return DB::table('app_user as a')
              ->select('a.*', 'b.status_individu', 'b.id_siklus')
              ->join('kelompok_mhs as b','a.user_id','b.id_mahasiswa')
              ->where('a.user_id', $user_id)
              ->first();
      }

      public static function getDataDosbing1()
        {
            return DB::table('app_user as a')
                ->select('a.*', 'c.role_name')
                ->join('app_role as c', 'a.role_id', '=', 'c.role_id') // Penambahan '=' pada join condition
                ->where(function ($query) { // Penggunaan fungsi where dengan closure untuk menangani OR condition
                    $query->where('a.role_id', '04')
                        ->orWhere('a.role_id', '02');
                })
                ->where('a.dosbing1', '1')
                ->orderBy('a.user_name')
                ->get();
        }

        public static function getDataDosbing2()
        {
            return DB::table('app_user as a')
                ->select('a.*', 'c.role_name')
                ->join('app_role as c', 'a.role_id', '=', 'c.role_id') // Penambahan '=' pada join condition
                ->where(function ($query) { // Penggunaan fungsi where dengan closure untuk menangani OR condition
                    $query->where('a.role_id', '04')
                        ->orWhere('a.role_id', '02');
                })
                ->where('a.dosbing2', '1')
                ->orderBy('a.user_name')
                ->get();
        }


      public static function getAkunBelumPunyaKelompok($user_id)
      {
          return DB::table('app_user as a')
              ->where('a.user_id', $user_id)
              ->first();
      }

      public static function getAkunDosbingKelompok($id_kelompok)
      {
          return DB::table('app_user')
              ->join('kelompok', function ($join) {
                  $join->on('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_1')
                      ->orOn('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_2');
              })
              ->where('kelompok.id', '=', $id_kelompok)
              ->orderByRaw('
                  CASE
                      WHEN app_user.user_id = kelompok.id_dosen_pembimbing_1 THEN 1
                      WHEN app_user.user_id = kelompok.id_dosen_pembimbing_2 THEN 2
                  END
              ')
              ->select('app_user.*')
              ->get();
      }

      public static function getAkunDospengKelompok($id_kelompok)
      {
          return DB::table('app_user')
              ->join('kelompok', function ($join) {
                  $join->on('app_user.user_id', '=', 'kelompok.id_dosen_penguji_1')
                      ->orOn('app_user.user_id', '=', 'kelompok.id_dosen_penguji_2');
              })
              ->where('kelompok.id', '=', $id_kelompok)
              ->orderByRaw('
                  CASE
                      WHEN app_user.user_id = kelompok.id_dosen_penguji_1 THEN 1
                      WHEN app_user.user_id = kelompok.id_dosen_penguji_2 THEN 2
                  END
              ')
              ->select('app_user.*')
              ->get();
      }

      public static function getAkunDospengTa($user_id)
      {
          return DB::table('app_user')
              ->join('kelompok_mhs', function ($join) {
                  $join->on('app_user.user_id', '=', 'kelompok_mhs.id_dosen_penguji_ta1')
                      ->orOn('app_user.user_id', '=', 'kelompok_mhs.id_dosen_penguji_ta2');
              })
              ->where('kelompok_mhs.id_mahasiswa', '=', $user_id)
              ->orderByRaw('
                  CASE
                      WHEN app_user.user_id = kelompok_mhs.id_dosen_penguji_ta1 THEN 1
                      WHEN app_user.user_id = kelompok_mhs.id_dosen_penguji_ta2 THEN 2
                  END
              ')
              ->select('app_user.*')
              ->get();
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
              ->where('status', 'aktif')
              ->first();
      }

}
