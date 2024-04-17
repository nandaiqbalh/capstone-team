<?php

namespace App\Models\Dosen\PengujianProposal;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengujianProposalModel extends BaseModel
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
            ->select('a.*','b.nama as nama_topik', 'd.*', 'e.*')
            ->join('topik as b','a.id_topik','b.id')
            ->join('jadwal_sidang_proposal as d', 'a.id', 'd.id_kelompok')
            ->join('ruang_sidangs as e', 'e.id', 'd.ruangan_id')
            ->where('a.id_dosen_pembimbing_2', Auth::user()->user_id)
            ->orWhere('a.id_dosen_penguji_1', Auth::user()->user_id)
            ->orWhere('a.id_dosen_penguji_2', Auth::user()->user_id)
            ->orderBy('a.is_sidang_proposal', 'asc') // Urutkan berdasarkan is_sidang_proposal dari 0 ke 1
            ->orderBy('d.waktu')
            ->paginate(20);
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

    public static function getAkunPengujiProposalKelompok($id_kelompok)
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


    public static function getJadwalSidangProposal($id_kelompok)
    {
        return DB::table('jadwal_sidang_proposal as a')
        ->select('a.*', 'b.*')
        ->join('ruang_sidangs as b', 'b.id', 'a.ruangan_id')
        ->where('id_kelompok', $id_kelompok)
        ->first();
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

    // get data by id
    public static function getDataById($id)
    {
        return DB::table('kelompok as a')
            ->select('a.*', 'b.nama as nama_topik')
            ->join('topik as b','a.id_topik', 'b.id')
            ->where('a.id', $id)->first();
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

}
