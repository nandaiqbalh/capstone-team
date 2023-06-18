<?php

namespace App\Models\Admin\Expo_Mahasiswa;

use App\Models\Admin\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MahasiswaExpoModel extends BaseModel
{
    // get all data
    public static function getDataExpo()
    {
        return DB::table('jadwal_expo as a')
            ->select('a.*','b.tahun_ajaran')
            ->join('siklus as b','a.id_siklus','b.id')
            ->where('b.status','aktif')
            ->get();
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
        ->select('a.status as status_expo')
        ->join('jadwal_expo as b', 'a.id_expo', 'b.id')
        ->join('kelompok_mhs as c', 'a.id_kelompok', 'c.id_kelompok')
        ->join('siklus as d', 'b.id_siklus', 'd.id')
        ->where('d.status', 'aktif')
        ->where('c.id_mahasiswa', Auth::user()->user_id)
        ->first();
    }

    // pengecekan kelompok
    public static function pengecekan_kelompok_mahasiswa()
    {
        return DB::table('kelompok_mhs as a')
        ->select('a.*', 'b.*')
        ->join('kelompok as b', 'a.id_kelompok', 'b.id')
        ->join('siklus as d', 'a.id_siklus', 'd.id')
        ->where('d.status', 'aktif')
        ->where('a.id_mahasiswa', Auth::user()->user_id)
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
            ->where('a.user_id', $user_id)
            ->first();
    }
    // get akun by id user 
    public static function getAkun()
    {
        return DB::table('app_user as a')
        ->select('a.*')
        ->join('app_role_user as b', 'a.user_id','b.user_id')
        ->where('b.role_id','03')
        ->get();
    }

    // get akun by id user 
    public static function getAkunDosen()
    {
        return DB::table('app_user as a')
        ->select('a.*')
        ->join('app_role_user as b', 'a.user_id', 'b.user_id')
        ->where('b.role_id', '04')
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
            ->join('app_role_user as b', 'a.user_id', 'b.user_id')
            ->join('app_role as c', 'b.role_id', 'c.role_id')
            ->where('c.role_id', '03')
            ->paginate(20);
    }

    // get search
    public static function getDataSearch($search)
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role_user as b', 'a.user_id', 'b.user_id')
            ->join('app_role as c', 'b.role_id', 'c.role_id')
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
    public static function insertrole($params2)
    {
        return DB::table('app_role_user')->insert($params2);
    }

    public static function insertIDKelompok($params)
    {
        return DB::table('pendaftaran_expo')->insert($params);
    }

    public static function delete($user_id)
    {
        return DB::table('app_user')->where('user_id', $user_id)->delete();
    }
    public static function updateKelompokMHS($id,$params)
    {
        return DB::table('kelompok_mhs')->where('id', $id)->update($params);
    }
}
