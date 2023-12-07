<?php

namespace App\Models\Admin\Kelompok;

use App\Models\Admin\BaseModel;
use Illuminate\Support\Facades\DB;

class KelompokModel extends BaseModel
{
    // get all data
    public static function getData()
    {
        return DB::table('kelompok')
            ->get();
    }

    // get data with pagination
    public static function getDataWithPagination()
    {
        return DB::table('kelompok as a')
            ->select('a.*', 'b.nama as topik_name', 'c.tahun_ajaran')
            ->leftjoin('topik as b', 'a.id_topik', 'b.id')
            ->join('siklus as c', 'a.id_siklus', 'c.id')
            ->where('c.status', 'aktif')
            ->orderByDesc('a.id')
            ->paginate(20);
    }


    // get search
    public static function getDataSearch($no_kel)
    {
        return DB::table('kelompok as a')
            ->select('a.*', 'b.nama as topik_name', 'c.tahun_ajaran')
            ->leftjoin('topik as b', 'a.id_topik', 'b.id')
            ->join('siklus as c', 'a.id_siklus', 'c.id')
            ->where('a.nomor_kelompok', 'LIKE', "%" . $no_kel . "%")
            ->where('c.status', 'aktif')
            ->orderByDesc('a.id')
            ->paginate(20)->withQueryString();
    }

    // get data by id
    public static function getDataById($id)
    {
        return DB::table('kelompok as a')
            ->select('a.*', 'b.nama as nama_topik', 'c.tahun_ajaran')
            ->join('topik as b', 'a.id_topik', 'b.id')
            ->join('siklus as c', 'a.id_siklus', 'c.id')
            ->where('a.id', $id)
            ->first();
    }

    // get data by id
    public static function getKelompokMhs($id_mahasiswa, $id)
    {
        return DB::table('kelompok_mhs')
            ->where('id_kelompok', $id)
            ->where('id_mahasiswa', $id_mahasiswa)
            ->first();
    }

    public static function getKelompokMhsAll( $id)
    {
        return DB::table('kelompok_mhs')
        ->where('id_kelompok', $id)
        ->get();
    }

    public static function getTopik()
    {
        return DB::table('topik')
        ->get();
    }

    public static function updateKelompokMhsAll($id)
    {
        return DB::table('kelompok_mhs')
        ->where('id_mahasiswa', $id)
        ->update(["id_kelompok"=>null]);
    }
    public static function getKelompokDosen($id_mahasiswa, $id)
    {
        return DB::table('dosen_kelompok')
            ->where('id_kelompok', $id)
            ->where('id_dosen', $id_mahasiswa)
            ->first();
    }

    public static function deleteKelompok($id)
    {
        return DB::table('kelompok')
            ->where('id', $id)
            ->delete();
    }

    public static function deleteKelompokMhs($id)
    {
        return DB::table('kelompok_mhs')
            ->where('id', $id)
            ->update(['id_kelompok' => null]);
    }
    public static function deleteDosenMhs($id_dosen, $id)
    {
        return DB::table('dosen_kelompok')
            ->where('id_kelompok', $id)
            ->where('id_dosen', $id_dosen)
            ->delete();
    }
    // pengecekan kelompok
    public static function listKelompokMahasiswa($id_kelompok)
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.*', 'b.user_name', 'b.nomor_induk', 'b.user_id')
            ->join('app_user as b', 'a.id_mahasiswa', 'b.user_id')
            ->where('a.id_kelompok', $id_kelompok)
            ->whereNot('a.id_kelompok', null)
            ->get();
    }

    public static function listKelompokMahasiswaNokel($id_topik)
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.*', 'b.user_name', 'b.nomor_induk', 'b.user_id')
            ->join('app_user as b', 'a.id_mahasiswa', 'b.user_id')
            ->where('a.id_topik_mhs', $id_topik)
            ->where('a.id_kelompok', null)
            ->get();
    }

    // pengecekan Dosbing
    public static function listDosbing($id_kelompok)
    {
        return DB::table('dosen_kelompok as a')
            ->select('a.*', 'b.user_name', 'b.user_id', 'b.nomor_induk')
            ->join('app_user as b', 'a.id_dosen', 'b.user_id')
            ->where('a.status_dosen','pembimbing 1')
            ->where('a.id_kelompok', $id_kelompok)
            ->orwhere('a.status_dosen', 'pembimbing 2')
            ->where('a.id_kelompok', $id_kelompok)
            ->get();
    }

    public static function listDospenguji($id_kelompok)
    {
        return DB::table('dosen_kelompok as a')
        ->select('a.*', 'b.user_name', 'b.user_id', 'b.nomor_induk')
        ->join('app_user as b', 'a.id_dosen', 'b.user_id')
        ->where('a.status_dosen', 'penguji 1')
        ->where('a.id_kelompok', $id_kelompok)
        ->orwhere('a.status_dosen', 'penguji 2')
        ->where('a.id_kelompok', $id_kelompok)
        ->get();
    }
    // pengecekan Dosbing
    public static function listDosbingAvail()
    {
        return DB::table('app_user as a')
        ->select('b.*', 'a.user_name', 'a.user_id', 'a.nomor_induk')
        ->where('role_id','02')
        ->orwhere('role_id','04')
        ->get();
    }
    // pengecekan Dosbing
    public static function checkDosbing($id_kelompok, $id_dosen)
    {
        return DB::table('dosen_kelompok as a')
        ->select('a.*', 'b.user_name', 'b.user_id', 'b.nomor_induk')
        ->join('app_user as b', 'a.id_dosen', 'b.user_id')
        ->where('a.id_kelompok', $id_kelompok)
        ->where('a.id_dosen', $id_dosen)
        ->first();
    }

    public static function checkStatusDosen($id_kelompok, $id_dosen)
    {
        return DB::table('dosen_kelompok as a')
        ->where('a.id_kelompok', $id_kelompok)
        ->where('a.id_dosen', $id_dosen)
        ->first();
    }

    public static function checkPosisi($id_kelompok, $status)
    {
        return DB::table('dosen_kelompok as a')
        ->where('a.id_kelompok', $id_kelompok)
        ->where('a.status_dosen', $status)
        ->first();
    }

    public static function updateKelompokMHS($user_id, $params)
    {
        return DB::table('kelompok_mhs')->where('id_mahasiswa', $user_id)->update($params);
    }

    public static function updateKelompokNomor($id, $params)
    {
        return DB::table('kelompok')->where('id', $id)->update($params);
    }

    public static function insertDosenKelompok($params)
    {
        return DB::table('dosen_kelompok')->insert($params);
    }
}
