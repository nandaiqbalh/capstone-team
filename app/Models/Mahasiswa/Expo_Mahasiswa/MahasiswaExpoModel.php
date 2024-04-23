<?php

namespace App\Models\Mahasiswa\Expo_Mahasiswa;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MahasiswaExpoModel extends BaseModel
{
    // get all data
    public static function getDataExpo()
    {
        return DB::table('jadwal_expo as a')
            ->select('a.*', 'b.nama_siklus')
            ->join('siklus as b', 'a.id_siklus', 'b.id')
            ->where('b.status', 'aktif')
            ->where('a.tanggal_mulai', '<', now())
            ->where('a.tanggal_selesai', '>', now())
            ->orderBy('a.tanggal_mulai', 'asc')
            ->first();
    }

    public static function getLatestExpo()
    {
        return DB::table('jadwal_expo as a')
            ->select('a.*',)
            ->orderBy('a.id', 'asc')
            ->first();
    }

    public static function getExpoById($id_expo)
    {
        return DB::table('jadwal_expo as a')
            ->select('a.*', 'b.nama_siklus')
            ->join('siklus as b', 'a.id_siklus', 'b.id')
            ->where('a.id', $id_expo)
            ->orderBy('a.tanggal_mulai', 'asc')
            ->first();
    }


    public static function kelengkapanExpo()
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.*')
            ->join('siklus as b','a.id_siklus','b.id')
            ->where('b.status','aktif')
            ->where('a.id_mahasiswa',Auth::user()->user_id)
            ->first();
    }
    // get all data
    public static function cekExpo()
    {
        return DB::table('pendaftaran_expo as a')
        ->select('a.*')
        ->join('jadwal_expo as b', 'a.id_expo', 'b.id')
        ->join('kelompok_mhs as c', 'a.id_kelompok', 'c.id_kelompok')
        ->join('siklus as d', 'b.id_siklus', 'd.id')
        ->where('d.status', 'aktif')
        ->where('c.id_mahasiswa', Auth::user()->user_id)
        ->first();
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

    public static function updateKelompokById($id_kelompok, $params)
    {
        return DB::table('kelompok')
        ->where('id', $id_kelompok)
        ->update($params);
    }

    public static function checkApakahSiklusMasihAktif($id_siklus)
      {
          return DB::table('siklus')
              ->where('id', $id_siklus)
              ->where('status', 'aktif')
              ->first();
      }
    // get akun by id user
    public static function idKelompok($user_id)
    {
        return DB::table('kelompok_mhs')
        ->where('id_mahasiswa', $user_id)
        ->value('id_kelompok');
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
    // get akun by id user
    public static function getAkun()
    {
        return DB::table('app_user as a')
        ->select('a.*')
        ->where('role_id','03')
        ->get();
    }

    // get akun by id user
    public static function getAkunDosen()
    {
        return DB::table('app_user as a')
        ->select('a.*')
        ->where('role_id', '04')
        ->get();
    }

    // pengecekan pendaftaran_expo
    public static function getAkunDosbingExpo($id_pendaftaran_expo)
    {
        return DB::table('dosen_pendaftaran_expo as a')
        ->select('a.*', 'b.user_name', 'b.nomor_induk' )
        ->join('app_user as b', 'a.id_dosen', 'b.user_id')
        ->where('a.id_pendaftaran_expo', $id_pendaftaran_expo)
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
            ->paginate(20)->withQueryString();
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
    public static function getDataById($id)
    {
        return DB::table('kelompok_mhs')->where('id', $id)->first();
    }

    public static function insertmahasiswa($params)
    {
        return DB::table('app_user')->insert($params);
    }

    public static function insertDosenExpo($params)
    {
        return DB::table('dosen_pendaftaran_expo')->insert($params);
    }
    public static function insertExpo($params)
    {
        return DB::table('pendaftaran_expo')->insert($params);
    }
    public static function insertExpoMHS($params)
    {
        return DB::table('pendaftaran_expo_mhs')->insert($params);
    }

    public static function insertPeminatan($params)
    {
        return DB::table('peminatan')->insert($params);
    }
    public static function insertTopikMHS($params)
    {
        return DB::table('topik_mhs')->insert($params);
    }

    public static function insertIDKelompok($params)
    {
        return DB::table('pendaftaran_expo')->insert($params);
    }

    public static function delete($user_id)
    {
        return DB::table('app_user')->where('user_id', $user_id)->delete();
    }
    public static function updateKelompokMHS($user_id, $params)
    {
        return DB::table('kelompok_mhs')->where('id_mahasiswa', $user_id)->update($params);
    }

    public static function fileMHS($user_id)
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.id as id_kel_mhs', 'a.id_mahasiswa', 'a.file_status_mta','a.file_status_lta','a.file_name_makalah', 'a.file_path_makalah','a.file_name_laporan_ta', 'a.file_path_laporan_ta','b.*')
            ->join('kelompok as b','a.id_kelompok','b.id')
            ->join('siklus as c' ,'a.id_siklus', 'c.id')
            ->where('c.status','aktif')
            ->where('a.id_mahasiswa', $user_id)
            ->first();
    }
}
