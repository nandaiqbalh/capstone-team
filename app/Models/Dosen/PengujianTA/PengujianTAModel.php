<?php

namespace App\Models\Dosen\PengujianTA;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengujianTAModel extends BaseModel
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
    return DB::table('kelompok_mhs as a')
            ->select('b.*','a.*', 'd.*', 'e.*', 'u.*') // Menambahkan 'u.*' untuk memilih kolom dari tabel app_user
        ->leftJoin('kelompok as b', 'a.id_kelompok', '=', 'b.id')
        ->join('jadwal_sidang_ta as d', 'a.id_mahasiswa', '=', 'd.id_mahasiswa')
        ->join('ruang_sidangs as e', 'e.id', '=', 'd.id_ruangan')
        ->leftJoin('app_user as u', 'a.id_mahasiswa', '=', 'u.user_id') // Join dengan tabel app_user
        ->where(function ($query) {
            $query->where('a.id_dosen_penguji_ta1', Auth::user()->user_id)
                ->orWhere('a.id_dosen_penguji_ta2', Auth::user()->user_id)
                  ->orWhere('d.id_dosen_pembimbing_1', Auth::user()->user_id);
        })
        ->orderBy('a.is_selesai', 'asc') // Urutkan berdasarkan is_sidang_proposal dari 0 ke 1
        ->orderBy('d.waktu')
        ->paginate(20);
}

    public static function pengecekan_kelompok_mahasiswa($user_id)
    {
        return DB::table('kelompok_mhs as a')
            ->select( 'b.*')
            ->leftJoin('kelompok as b', 'a.id_kelompok', 'b.id')
            ->where('a.id_mahasiswa', $user_id)
            ->orderBy('a.created_date', 'desc') // Urutkan berdasarkan created_date secara descending
            ->first();
    }

    public static function getDataDetailMahasiswaSidang($user_id)
    {
        return DB::table('kelompok_mhs as a')
            ->select( 'b.*', 'c.nama as nama_topik', 'd.user_name', 'd.nomor_induk','a.*',)
            ->leftJoin('kelompok as b', 'a.id_kelompok', 'b.id')
            ->leftJoin('topik as c', 'a.id_topik_mhs', 'c.id')
            ->leftJoin('app_user as d', 'd.user_id', 'a.id_mahasiswa')
            ->where('a.id_mahasiswa', $user_id)
            ->orderBy('a.created_date', 'desc') // Urutkan berdasarkan created_date secara descending
            ->first();
    }
    public static function getAnggotaKelompok($id_kelompok)
    {
        return DB::table('kelompok_mhs as a')
            ->select('b.id as id_kelompok', 'a.*', )
            ->join('kelompok as b', 'a.id_kelompok', '=', 'b.id')
            ->where('b.id', $id_kelompok)
            ->get();
    }

    public static function getTopik()
    {
        return DB::table('topik')
        ->get();
    }

    public static function listKelompokMahasiswa($id_kelompok)
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.*', 'b.*')
            ->join('app_user as b', 'a.id_mahasiswa', 'b.user_id')
            ->where('a.id_kelompok', $id_kelompok)
            ->whereNot('a.id_kelompok', null)
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


    public static function getAkunPengujiTAKelompok($id_mahasiswa)
    {
        return DB::table('app_user')
            ->join('kelompok_mhs', function ($join) use ($id_mahasiswa) {
                $join->on('app_user.user_id', '=', 'kelompok_mhs.id_dosen_penguji_ta1')
                     ->where('kelompok_mhs.id_mahasiswa', '=', $id_mahasiswa)
                     ->orWhere(function ($query) use ($id_mahasiswa) {
                         $query->on('app_user.user_id', '=', 'kelompok_mhs.id_dosen_penguji_ta2')
                               ->where('kelompok_mhs.id_mahasiswa', '=', $id_mahasiswa);
                     });
            })
            ->orderBy('app_user.user_id')
            ->select('app_user.*')
            ->get();
    }



    public static function listMahasiswaSendiri($id_mahasiswa, $id_kelompok)
    {
        return DB::table('kelompok_mhs as a')
            ->select( 'a.*', 'c.nama as nama_topik', 'd.*', 'b.*', )
            ->leftJoin('kelompok as b', 'a.id_kelompok', '=', 'b.id')
            ->leftJoin('topik as c', 'a.id_topik_mhs', '=', 'c.id')
            ->leftJoin('app_user as d', 'd.user_id', '=', 'a.id_mahasiswa')
            ->leftJoin('pendaftaran_sidang_ta as e', 'a.id_mahasiswa', '=', 'e.id_mahasiswa')
            ->where('a.id_mahasiswa', $id_mahasiswa)
            ->where('a.id_kelompok', $id_kelompok)
            ->orderBy('a.created_date', 'desc') // Urutkan berdasarkan created_date secara descending
            ->get();
    }


public static function getDosenPengujiProposal($id_kelompok)
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
    // get search
    public static function getDataSearch($nama_mahasiswa)
    {
        return DB::table('kelompok_mhs as a')
            ->select('b.*', 'a.*', 'd.*', 'e.*', 'u.user_name') // Menambahkan 'u.user_name' untuk memilih kolom user_name dari tabel app_user
            ->leftJoin('kelompok as b', 'a.id_kelompok', '=', 'b.id')
            ->join('jadwal_sidang_ta as d', 'a.id_mahasiswa', '=', 'd.id_mahasiswa')
            ->join('ruang_sidangs as e', 'e.id', '=', 'd.id_ruangan')
            ->leftJoin('app_user as u', 'a.id_mahasiswa', '=', 'u.user_id') // Join dengan tabel app_user
            ->where(function ($query) {
                $query->where('a.id_dosen_penguji_ta1', Auth::user()->user_id)
                    ->orWhere('a.id_dosen_penguji_ta2', Auth::user()->user_id);
            })
            ->where('u.user_name', 'like', '%' . $nama_mahasiswa . '%') // Cari user_name yang mengandung nilai $nama_mahasiswa
            ->orderBy('a.is_selesai', 'asc') // Urutkan berdasarkan is_selesai dari 0 ke 1
            ->orderBy('d.waktu')
            ->paginate(20);
    }


    // get data by id
    public static function getDataById($id_mahasiswa)
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.*')
            ->where('a.id_mahasiswa', $id_mahasiswa)->first();
    }

    public static function getDataMahasiswaById($user_id)
    {
        return DB::table('app_user as a')
            ->leftJoin('kelompok_mhs as b' ,'a.user_id', 'b.id_mahasiswa')
            ->leftJoin('siklus as c','b.id_siklus','c.id')
            ->where('user_id', $user_id)->first();
    }

    public static function peminatanMahasiswa()
    {
        return DB::table('peminatan')
            ->get();
    }

    public static function getMahasiswa($id_kelompok)
    {
        return DB::table('kelompok_mhs as a')
            ->select('b.*', 'a.*')
            ->join('app_user as b','a.id_mahasiswa', 'b.user_id')
            ->where('a.id_kelompok', $id_kelompok)
            ->get();
    }

    public static function updateKelompok($id_kelompok, $params)
    {
        return DB::table('kelompok')->where('id', $id_kelompok)->update($params);
    }

    public static function updateAllMahasiswaKelompok($id_kelompok, $params)
    {
        return DB::table('kelompok_mhs')
            ->where('id_kelompok', $id_kelompok)
            ->where('is_mendaftar_sidang', 1) // Tambahkan kondisi is_mendaftar_sidang = 1
            ->update($params);
    }


    public static function updateKelompokMhs($id_mahasiswa, $params)
    {
        return DB::table('kelompok_mhs')->where('id_mahasiswa', $id_mahasiswa)->update($params);
    }

    public static function getDataPendaftaranSidangTA($id_mahasiswa)
    {
        return DB::table('pendaftaran_sidang_ta as a')
            ->where('a.id_mahasiswa', $id_mahasiswa)
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
}
