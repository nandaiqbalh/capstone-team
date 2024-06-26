<?php

namespace App\Models\Mahasiswa\Kelompok_Mahasiswa;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MahasiswaKelompokModel extends BaseModel
{
    // pengecekan kelompok
    public static function pengecekan_kelompok_mahasiswa($user_id)
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.id_kelompok', 'b.*', 'c.nama as nama_topik', 'd.user_name as pengusul_kelompok')
            ->leftJoin('kelompok as b', 'a.id_kelompok', 'b.id')
            ->leftJoin('topik as c', 'b.id_topik', 'c.id')
            ->leftJoin('app_user as d', 'd.user_id', 'b.created_by')
            ->where('a.id_mahasiswa', $user_id)
            ->orderBy('a.created_date', 'desc') // Urutkan berdasarkan created_date secara descending
            ->first();
    }

    // pengecekan kelompok
    public static function listKelompokMahasiswa($id_kelompok)
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.*', 'b.user_name', 'b.nomor_induk', 'b.user_img_path', 'b.user_img_name')
            ->join('app_user as b', 'a.id_mahasiswa', 'b.user_id')
            ->where('a.id_kelompok', $id_kelompok)
            ->whereNot('a.id_kelompok', null)
            ->get();
    }

    // get akun by id user
    public static function getAkunByID($user_id)
    {
        return DB::table('app_user as a')
            ->select('a.*', 'b.status_individu', 'b.id_siklus')
            ->join('kelompok_mhs as b', 'a.user_id', 'b.id_mahasiswa')
            ->where('a.user_id', $user_id)
            ->first();
    }

    public static function getDataDosbing1()
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role as c', 'a.role_id', '=', 'c.role_id') // Penambahan '=' pada join condition
            ->where(function ($query) { // Penggunaan fungsi where dengan closure untuk menangani OR condition
                $query->where('a.role_id', '04');
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
                $query->where('a.role_id', '04');
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

    public static function isAccountExist($user_id)
    {
        $account = DB::table('app_user as a')
            ->where('a.user_id', $user_id)
            ->first();

        return !empty($account); // Return true if the account exists, false otherwise
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

    public static function getDataMahasiswaAvailable()
    {
        $loggedInUserId = Auth::user()->user_id;

        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role as c', 'a.role_id', 'c.role_id')
            ->leftJoin('kelompok_mhs as km', 'a.user_id', 'km.id_mahasiswa')
            ->where('a.role_id', '03') // Filter berdasarkan role_id di tabel app_user
            ->whereNull('km.id_mahasiswa') // Pastikan user_id tidak terdapat pada kelompok_mhs
            ->where('a.user_id', '!=', $loggedInUserId) // Filter agar tidak termasuk user yang sedang login
            ->orderBy('a.user_name') // Sort the result by user_name
            ->get();
    }

    public static function getMahasiswaById($user_id)
    {
        return DB::table('app_user as a')
            ->where('user_id', $user_id)->first();
    }
    public static function getDataPendaftaranMhs($user_id)
    {
        return DB::table('kelompok_mhs')
            ->where('id_mahasiswa', $user_id)
            ->first();
    }

    public static function getSiklusAktif()
    {
        return DB::table('siklus')
            ->orderBy('id', 'desc') // Urutkan berdasarkan 'id' secara descending
            ->first();
    }

    public static function getPeriodePendaftaranSiklus()
    {
        return DB::table('siklus')
            ->where('pendaftaran_mulai', '<', now())
            ->where('pendaftaran_selesai', '>', now())
            ->orderBy('id', 'desc') // Urutkan berdasarkan 'id' secara descending
            ->first();
    }

    public static function checkApakahSiklusMasihAktif($id_siklus)
    {
        return DB::table('siklus')
            ->where('id', $id_siklus)
            ->first();
    }

    public static function getSidangProposal($id_kelompok)
    {
        return DB::table('jadwal_sidang_proposal')
            ->where('id_kelompok', $id_kelompok)
            ->first();
    }

    public static function getPendaftaranExpo($id_kelompok)
    {
        return DB::table('pendaftaran_expo')
            ->where('id_kelompok', $id_kelompok)
            ->first();
    }

    public static function insertKelompok($params)
    {
        return DB::table('kelompok')->insert($params);
    }

    public static function insertKelompokMHS($params)
    {
        return DB::table('kelompok_mhs')->insert($params);
    }

    public static function updateMahasiswa($user_id, $params)
    {
        return DB::table('app_user')->where('user_id', $user_id)->update($params);
    }

    public static function updateKelompokMHS($user_id, $params)
    {
        return DB::table('kelompok_mhs')->where('id_mahasiswa', $user_id)->update($params);
    }

    public static function updateKelompok($id_kelompok, $params)
    {
        return DB::table('kelompok')->where('id', $id_kelompok)->update($params);
    }

    public static function deleteKelompok($id_kelompok)
    {
        return DB::table('kelompok')->where('id', $id_kelompok)->delete();
    }

    public static function deleteKelompokMhs($id_mahasiswa)
    {
        return DB::table('kelompok_mhs')->where('id_mahasiswa', $id_mahasiswa)->delete();
    }

    public static function deleteSidangProposal($id_kelompok)
    {
        return DB::table('jadwal_sidang_proposal')->where('id_kelompok', $id_kelompok)->delete();
    }

    public static function deletePendaftaranExpo($id_kelompok)
    {
        return DB::table('pendaftaran_expo')->where('id_kelompok', $id_kelompok)->delete();
    }
}
