<?php

namespace App\Models\Mahasiswa\TugasAkhir_Mahasiswa;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MahasiswaTugasAkhirModel extends BaseModel
{
      // pengecekan kelompok
      public static function pengecekan_kelompok_mahasiswa($user_id)
      {
          return DB::table('kelompok_mhs as a')
              ->select('a.id_kelompok', 'a.judul_ta_mhs', 'a.status_individu',  'a.status_tugas_akhir',   'b.*', 'c.nama as nama_topik', 'd.user_name as pengusul_kelompok')
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
        ->select('a.*', 'b.id as siklus_id', 'b.nama_siklus', 'c.judul_capstone', 'c.status_kelompok', 'd.kode_ruang', 'd.nama_ruang')
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
      public static function getPeriodeAvailable()
      {
          return DB::table('jadwal_periode_sidang_ta as a')
          ->select('a.*')
          ->where('a.tanggal_mulai', '<', now())
          ->where('a.tanggal_selesai', '>', now()) // Menambahkan kondisi a.tanggal_selesai > waktu sekarang
          ->orderBy('a.id', 'desc')
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

      public static function getStatusPendaftaran($id_mahasiswa)
      {
          return DB::table('pendaftaran_sidang_ta as a')
              ->select('a.*')
              ->where('a.id_mahasiswa', $id_mahasiswa)
              ->first();
      }

      public static function getLatestPeriode()
    {
        return DB::table('jadwal_periode_sidang_ta as a')
            ->select('a.*',)
            ->orderBy('a.id', 'asc')
            ->first();
    }

    public static function getPeriodeSidangById($id_periode)
    {
        return DB::table('jadwal_periode_sidang_ta as a')
            ->select('a.*', )
            ->where('a.id', $id_periode)
            ->orderBy('a.tanggal_mulai', 'asc')
            ->first();
    }

      public static function getDataMahasiswa()
      {
          return DB::table('kelompok_mhs as a')
              ->select('a.*')
              ->join('siklus as b','a.id_siklus','b.id')
              ->where('b.status','aktif')
              ->where('a.id_mahasiswa',Auth::user()->user_id)
              ->first();
      }

      public static function updateKelompokMHS($id_mahasiswa, $params)
    {
        return DB::table('kelompok_mhs')
            ->where('id_mahasiswa', $id_mahasiswa)
            ->update($params);
    }

    public static function fileMHS()
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.id as id_kel_mhs', 'a.file_status_lta', 'a.file_status_mta', 'a.file_name_makalah', 'a.file_path_makalah','a.file_name_laporan_ta', 'a.file_path_laporan_ta','b.*')
            ->join('kelompok as b','a.id_kelompok','b.id')
            ->join('siklus as c' ,'a.id_siklus', 'c.id')
            ->where('c.status','aktif')
            ->where('a.id_mahasiswa', Auth::user()->user_id)
            ->first();
    }

}
