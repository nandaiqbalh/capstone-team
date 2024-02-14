<?php

namespace App\Models\Api\Mahasiswa\Kelompok;

use App\Models\Api\ApiBaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiKelompokSayaModel extends ApiBaseModel
{
    // get all data
    public static function getData()
    {
        return DB::table('kelompok as a')
            ->select('a.*','b.user_name as dosen_name','c.nama as topik_name')
            ->leftjoin('app_user as b','a.id_dosen','b.user_id')
            ->leftjoin('topik as c', 'a.id_topik', 'c.id')
            ->orderByDesc('a.id')
            ->get();
    }

     // pengecekan kelompok
     public static function pengecekan_kelompok_mahasiswa($user_id)
     {
        return DB::table('kelompok_mhs as a')
            ->select('a.id_kelompok','b.*','c.nama as nama_topik')
            ->leftjoin('kelompok as b','a.id_kelompok','b.id')
            ->leftjoin('topik as c', 'a.id_topik_mhs', 'c.id')
            ->join('siklus as d', 'a.id_siklus', 'd.id')
            ->where('d.status', 'aktif')
            ->where('a.id_mahasiswa', $user_id)
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
            ->where('a.user_id', $user_id)
            ->first();
    }

    public static function isAccountExist($user_id)
    {
        $account = DB::table('app_user as a')
            ->where('a.user_id', $user_id)
            ->first();

        return !empty($account); // Return true if the account exists, false otherwise
    }
    // get akun by id user
    public static function getAkun()
    {
        return DB::table('app_user as a')
        ->select('a.*')
        ->where('a.role_id','03')
        ->get();
    }

    // get akun by id user
    public static function getAkunDosen()
    {
        return DB::table('app_user as a')
        ->select('a.*')
        ->where('a.role_id', '04')
        ->orwhere('a.role_id', '02')
        ->get();
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

    public static function getTopikById($id)
    {
        return DB::table('topik')->where('id', $id)
            ->first();
    }

    public static function getPeminatanById($id)
    {
        return DB::table('peminatan')->where('id', $id)
            ->first();
    }


    // get data topik
    public static function getSiklusAktif()
    {
        return DB::table('siklus')
            ->where('status','aktif')
            ->get();
    }

    // get data by id
    public static function getDataById($user_id)
    {
        return DB::table('app_user')->where('user_id', $user_id)->first();
    }

    public static function insertmahasiswa($params)
    {
        return DB::table('app_user')->insert($params);
    }

    public static function insertKelompok($params)
    {
        return DB::table('kelompok')->insert($params);
    }
    public static function insertKelompokMHS($params)
    {
        return DB::table('kelompok_mhs')->insert($params);
    }

    public static function insertPeminatan($params)
    {
        return DB::table('peminatan')->insert($params);
    }
    public static function insertTopikMHS($params)
    {
        return DB::table('topik_mhs')->insert($params);
    }

    public static function update($user_id, $params)
    {
        return DB::table('app_user')->where('user_id', $user_id)->update($params);
    }

    public static function updateMahasiswa($user_id, $params)
    {
        return DB::table('app_user')->where('user_id', $user_id)->update($params);
    }

    public static function delete($user_id)
    {
        return DB::table('app_user')->where('user_id', $user_id)->delete();
    }
}
