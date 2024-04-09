<?php

namespace App\Models\TimCapstone\Kelompok\PenetapanDosbing;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;

class PenetapanDosbingModel extends BaseModel
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
            ->select('a.*', 'b.nama as topik_name', 'c.nama_siklus')
            ->leftjoin('topik as b', 'a.id_topik', 'b.id')
            ->join('siklus as c', 'a.id_siklus', 'c.id')
            ->where('c.status', 'aktif')
            ->where('a.status_kelompok', "Menunggu Penetapan Dosbing!")
            ->orwhere('a.status_kelompok', "Menunggu Persetujuan Dosbing!")
            ->orwhere('a.status_kelompok', "Dosbing Tidak Setuju!")
            ->orderByDesc('a.id')
            ->paginate(20);
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

    // get search
    public static function getDataSearch($no_kel)
    {
        return DB::table('kelompok as a')
            ->select('a.*', 'b.nama as topik_name', 'c.nama_siklus')
            ->leftjoin('topik as b', 'a.id_topik', 'b.id')
            ->join('siklus as c', 'a.id_siklus', 'c.id')
            ->where('a.nomor_kelompok', 'LIKE', "%" . $no_kel . "%")
            ->where('c.status', 'aktif')
            ->orderByDesc('a.id')
            ->paginate(20)->withQueryString();
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


    // get data by id
    public static function getDataById($id)
    {
        return DB::table('kelompok as a')
            ->select('a.*', 'b.nama as nama_topik', 'c.nama_siklus')
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

    public static function deleteJadwalSidangProposal($id)
    {
        return DB::table('jadwal_sidang_proposal')->where('id_kelompok', $id)->delete();
    }

    public static function deleteKelompok($id)
    {
        return DB::table('kelompok')->where('id', $id)->delete();
    }


    public static function deleteKelompokMhs($id)
    {
        return DB::table('kelompok_mhs')
        ->where('id_mahasiswa', $id)
        ->delete();
    }

    public static function getKelompokById($id)
    {
        return DB::table('kelompok as a')
            ->where('a.id', $id)
            ->first();
    }

    // pengecekan kelompok
    public static function listKelompokMahasiswa($id_kelompok)
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.*', 'b.*')
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
        return DB::table('kelompok as a')
            ->select('a.*', 'b.user_name', 'b.user_id', 'b.nomor_induk')
            ->join('app_user as b', 'a.id_dosen_pembimbing_1', 'b.user_id')
            ->where('a.id', $id_kelompok)
            ->get();
    }

    public static function listDospenguji($id_kelompok)
    {
        return DB::table('kelompok as a')
            ->select('a.*', 'b.user_name', 'b.user_id', 'b.nomor_induk')
            ->join('app_user as b', 'a.id_dosen_penguji_1', 'b.user_id')
            ->where('a.id', $id_kelompok)
            ->get();
    }
    // pengecekan Dosbing
    public static function listDosbingAvail()
    {
        return DB::table('app_user as a')
        ->select('a.user_name', 'a.user_id', 'a.nomor_induk')
        ->where('role_id','04')
        ->get();
    }

    // pengecekan Dosbing
    public static function checkDosbing($id_kelompok, $id_dosen)
    {
        return DB::table('kelompok as a')
        ->select('a.*', 'b.user_name', 'b.user_id', 'b.nomor_induk')
        ->join('app_user as b', 'a.id_dosen_pembimbing_1', 'b.user_id')
        ->where('a.id', $id_kelompok)
        ->get();
    }


    public static function updateKelompokMHS($user_id, $params)
    {
        return DB::table('kelompok_mhs')->where('id_mahasiswa', $user_id)->update($params);
    }

    public static function updateKelompok($id_kelompok, $params)
    {
        return DB::table('kelompok')->where('id', $id_kelompok)->update($params);
    }

}
