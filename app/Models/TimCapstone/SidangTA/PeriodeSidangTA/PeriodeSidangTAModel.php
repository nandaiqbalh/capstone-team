<?php

namespace App\Models\TimCapstone\SidangTA\PeriodeSidangTA;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;

class PeriodeSidangTAModel extends BaseModel
{
    // get all data
    public static function getData()
    {
        return DB::table('jadwal_periode_sidang_ta')
            ->get();
    }

    // get data with pagination
    public static function getDataWithPagination()
    {
        return DB::table('jadwal_periode_sidang_ta')
            ->paginate(20);
    }

    // get data by id
    public static function getDataById($id)
    {
        return DB::table('jadwal_periode_sidang_ta')->where('id', $id)->first();
    }

    public static function getDataMahasiswa($id)
    {
        return DB::table('pendaftaran_sidang_ta as a')
            ->select('a.*')
            ->where('a.id_mahasiswa', $id)->first();
    }

    public static function getPendaftarSidangTA($id)
    {
        return DB::table('pendaftaran_sidang_ta as a')
        -> select('a.*', 'b.*', 'c.*', 'd.*')
        -> join('app_user as b', 'a.id_mahasiswa', 'b.user_id')
        -> join('kelompok_mhs as c', 'a.id_mahasiswa', 'c.id_mahasiswa')
        -> join('kelompok as d', 'c.id_kelompok', 'd.id')
            ->where('a.id_periode', $id)
            ->get();
    }
    
    // pengecekan kelompok
    public static function pengecekan_kelompok_mahasiswa($user_id)
    {
        return DB::table('kelompok_mhs as a')
            ->select( 'b.*', 'c.nama as nama_topik', 'd.user_name as pengusul_kelompok', 'a.*',)
            ->leftJoin('kelompok as b', 'a.id_kelompok', 'b.id')
            ->leftJoin('topik as c', 'a.id_topik_mhs', 'c.id')
            ->leftJoin('app_user as d', 'd.user_id', 'b.created_by')
            ->where('a.id_mahasiswa', $user_id)
            ->orderBy('a.created_date', 'desc') // Urutkan berdasarkan created_date secara descending
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

    public static function getAkunPengujiTAKelompok($id_mahasiswa)
    {
        return DB::table('app_user')
            ->join('kelompok_mhs', function ($join) {
                $join->on('app_user.user_id', '=', 'kelompok_mhs.id_dosen_penguji_ta1')
                    ->orOn('app_user.user_id', '=', 'kelompok_mhs.id_dosen_penguji_ta2');
            })
            ->where('kelompok_mhs.id_mahasiswa', '=', $id_mahasiswa)
            ->orderByRaw('
                CASE
                    WHEN app_user.user_id = kelompok_mhs.id_dosen_penguji_ta1 THEN 1
                    WHEN app_user.user_id = kelompok_mhs.id_dosen_penguji_ta2 THEN 2
                END
            ')
            ->select('app_user.*')
            ->get();
    }

    public static function getJadwalSidangTA($id_mahasiswa)
    {
        return DB::table('jadwal_sidang_ta as a')
        ->select('a.*', 'b.nama_ruang')
        ->join('ruang_sidangs as b', 'b.id', 'a.id_ruangan')
        ->where('id_mahasiswa', $id_mahasiswa)
        ->first();
    }

    public static function getDosenPengujiTA()
    {
        return DB::table('app_user')
            ->where('app_user.role_id', '04')
            ->select('app_user.*')
            ->orderBy('app_user.user_name')
            ->get();
    }

    public static function getRuangSidang()
    {
        return DB::table('ruang_sidangs')
        ->get();
    }

    public static function insertjadwal_periode_sidang_ta($params)
    {
        return DB::table('jadwal_periode_sidang_ta')->insert($params);
    }
    public static function editjadwal_periode_sidang_ta($id, $params)
    {
        return DB::table('jadwal_periode_sidang_ta')->where('id', $id)->update($params);
    }

    public static function update($id, $params)
    {
        return DB::table('jadwal_periode_sidang_ta')->where('id', $id)->update($params);
    }

    public static function updatePendaftaranSidangTA($id, $params)
    {
        return DB::table('pendaftaran_sidang_ta')->where('id_mahasiswa', $id)->update($params);
    }
   
    public static function updateKelompokMhs($id, $params)
    {
        return DB::table('kelompok_mhs')->where('id_mahasiswa', $id)->update($params);
    }
    
    public static function delete($id)
    {
        return DB::table('jadwal_periode_sidang_ta')->where('id', $id)->delete();
    }

    public static function countMahasiswaJadwal($id_kelompok)
    {
        // Menghitung jumlah mahasiswa yang terjadwal sidang TA pada kelompok tertentu
        return DB::table('jadwal_sidang_ta')->where('id_kelompok', $id_kelompok)->count();
    }

    // pengecekan kelompok
    public static function listMahasiswaSendiri($id_mahasiswa, $id_kelompok)
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.*', 'b.*', 'c.nama as nama_topik', 'd.*')
            ->leftJoin('kelompok as b', 'a.id_kelompok', '=', 'b.id')
            ->leftJoin('topik as c', 'a.id_topik_mhs', '=', 'c.id')
            ->leftJoin('app_user as d', 'd.user_id', '=', 'a.id_mahasiswa')
            ->leftJoin('pendaftaran_sidang_ta as e', 'a.id_mahasiswa', '=', 'e.id_mahasiswa')
            ->where('a.id_mahasiswa', $id_mahasiswa)
            ->where('a.id_kelompok', $id_kelompok)
            ->orderBy('a.created_date', 'desc') // Urutkan berdasarkan created_date secara descending
            ->get();
    }

    public static function listMahasiswa($id_kelompok)
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.*', 'b.*', 'c.nama as nama_topik', 'd.*')
            ->leftJoin('kelompok as b', 'a.id_kelompok', '=', 'b.id')
            ->leftJoin('topik as c', 'a.id_topik_mhs', '=', 'c.id')
            ->leftJoin('app_user as d', 'd.user_id', '=', 'a.id_mahasiswa')
            ->leftJoin('pendaftaran_sidang_ta as e', 'a.id_mahasiswa', '=', 'e.id_mahasiswa')
            ->where('a.id_kelompok', $id_kelompok)
            ->orderBy('a.created_date', 'desc') // Urutkan berdasarkan created_date secara descending
            ->get();
    }
    

    // add dosen
    public static function getKelompokMhsById($id_mahasiswa)
    {
        return DB::table('kelompok_mhs as a')
        ->where('id_mahasiswa', $id_mahasiswa)
        ->first();
    }
    
}
