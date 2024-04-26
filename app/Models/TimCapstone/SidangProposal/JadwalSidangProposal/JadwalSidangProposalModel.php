<?php

namespace App\Models\TimCapstone\SidangProposal\JadwalSidangProposal;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;

class JadwalSidangProposalModel extends BaseModel
{

    public function ruangSidang()
    {
        return $this->belongsTo(RuangSidang::class, 'ruangan_id');
    }

    public static function getDataWithPagination()
    {
        return DB::table('jadwal_sidang_proposal as a')
            ->select('a.*', 'b.id as siklus_id', 'b.nama_siklus', 'c.nomor_kelompok', 'c.status_kelompok', 'c.file_status_c100', 'c.status_sidang_proposal', 'd.id as id_ruang', 'd.nama_ruang', 'dpem2.user_name as nama_dosen_pembimbing_2', 'dp1.user_name as nama_dosen_penguji_1', 'dp2.user_name as nama_dosen_penguji_2')
            ->join('siklus as b', 'a.siklus_id', 'b.id')
            ->join('ruang_sidangs as d', 'd.id', 'a.ruangan_id')
            ->leftjoin('kelompok as c', 'a.id_kelompok', 'c.id')
            ->leftjoin('app_user as dpem2', 'a.id_dosen_pembimbing_2', 'dpem2.user_id')
            ->leftjoin('app_user as dp1', 'a.id_dosen_penguji_1', 'dp1.user_id')
            ->leftjoin('app_user as dp2', 'a.id_dosen_penguji_2', 'dp2.user_id')
            ->orderByRaw("CASE WHEN a.waktu >= NOW() THEN 0 ELSE 1 END, a.waktu ASC")
            ->orderBy('a.waktu', 'asc')

            ->paginate(20);
    }
    public static function filterSiklusKelompok($id_siklus){
        return DB::table('jadwal_sidang_proposal as a')
        ->select('a.*', 'b.id as siklus_id', 'b.nama_siklus', 'c.nomor_kelompok', 'c.status_kelompok', 'c.file_status_c100', 'c.status_sidang_proposal', 'd.id as id_ruang', 'd.nama_ruang', 'dpem2.user_name as nama_dosen_pembimbing_2', 'dp1.user_name as nama_dosen_penguji_1', 'dp2.user_name as nama_dosen_penguji_2')
        ->join('siklus as b', 'a.siklus_id', 'b.id')
        ->join('ruang_sidangs as d', 'd.id', 'a.ruangan_id')
        ->leftjoin('kelompok as c', 'a.id_kelompok', 'c.id')
        ->leftjoin('app_user as dpem2', 'a.id_dosen_pembimbing_2', 'dpem2.user_id')
        ->leftjoin('app_user as dp1', 'a.id_dosen_penguji_1', 'dp1.user_id')
        ->leftjoin('app_user as dp2', 'a.id_dosen_penguji_2', 'dp2.user_id')
        ->orderBy('a.waktu', 'asc')
        ->where('c.id_siklus', $id_siklus)

        ->paginate(20);
    }


    public static function getSiklus()
    {
        return DB::table('siklus')
            ->get();
    }
    public static function getKelompok()
    {
        return DB::table('kelompok as a')
            ->select('a.*','c.id as id_prop')
            ->join('siklus as b','a.id_siklus','b.id')
            ->leftjoin('jadwal_sidang_proposal as c', 'a.id','c.id_kelompok' )

            ->where('c.id',null)
            ->whereNotNull('a.nomor_kelompok')
            ->get();
    }

    public static function getTopikbyid($id_topik)
    {
        return DB::table('topik')
            ->where('id', $id_topik)
            ->first();
    }


    public static function getjadwalSidang($id)
    {
        return DB::table('jadwal_sidang_proposal')
        ->where('id', $id)
        ->first();
    }

    public static function getjadwalSidang2($id, $kelompok_id)
    {
        return DB::table('jadwal_sidang_proposal as a')
            ->select('a.*', 'b.nomor_kelompok')
            ->join('kelompok as b', 'a.id_kelompok', 'b.id')
            ->where('a.id', $id)
            ->where('a.id_kelompok', $kelompok_id)
            ->first();
    }

    public static function getMahasiswa($id_topik)
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.nama as nama_topik', 'c.id as id_topik')
            ->join('kelompok_mhs as b', 'a.user_id', 'b.id_mahasiswa')
            ->join('topik as c', 'b.id_topik_mhs', 'c.id')
            ->where('b.id_topik_mhs', $id_topik)
            ->get();
    }

    public static function getDosen()
    {
        return DB::table('app_user as a')
            ->where('role_id', '04')
            ->orwhere('role_id', '02')
            ->get();
    }

    public static function getDosenPenguji1($id_kelompok)
    {
        return DB::table('app_user as a')
        ->join('dosen_kelompok as b', 'a.user_id', 'b.id_dosen')
        ->where('b.status_dosen', 'penguji 1')
        ->where('b.id_kelompok', $id_kelompok)
        ->first();
    }
    public static function getDosenPenguji2($id_kelompok)
    {
        return DB::table('app_user as a')
        ->join('dosen_kelompok as b', 'a.user_id', 'b.id_dosen')
        ->where('b.status_dosen', 'penguji 2')
        ->where('b.id_kelompok', $id_kelompok)
        ->first();
    }


    // get search
    public static function getDataSearch($no_kel)
    {
        return DB::table('jadwal_sidang_proposal as a')
        ->select('a.*', 'b.id as siklus_id', 'b.nama_siklus', 'c.nomor_kelompok', 'c.status_kelompok', 'c.file_status_c100', 'c.status_sidang_proposal', 'd.id as id_ruang', 'd.nama_ruang', 'dpem2.user_name as nama_dosen_pembimbing_2', 'dp1.user_name as nama_dosen_penguji_1', 'dp2.user_name as nama_dosen_penguji_2')
        ->join('siklus as b', 'a.siklus_id', 'b.id')
        ->join('ruang_sidangs as d', 'd.id', 'a.ruangan_id')
        ->leftjoin('kelompok as c', 'a.id_kelompok', 'c.id')
        ->leftjoin('app_user as dpem2', 'a.id_dosen_pembimbing_2', 'dpem2.user_id')
        ->leftjoin('app_user as dp1', 'a.id_dosen_penguji_1', 'dp1.user_id')
        ->leftjoin('app_user as dp2', 'a.id_dosen_penguji_2', 'dp2.user_id')
        ->orderBy('a.waktu', 'asc')

        ->where('c.nomor_kelompok', 'LIKE', "%" . $no_kel . "%")
        ->paginate(20);
    }

    public static function getDataById($id)
    {
        return DB::table('kelompok as a')
            ->select('a.*', 'b.nama as nama_topik', 'c.nama_siklus', 'jsp.*', 'rs.nama_ruang as nama_ruangan')
            ->join('topik as b', 'a.id_topik', 'b.id')
            ->join('siklus as c', 'a.id_siklus', 'c.id')
            ->leftJoin('jadwal_sidang_proposal as jsp', 'a.id', 'jsp.id_kelompok')
            ->leftJoin('ruang_sidangs as rs', 'jsp.ruangan_id', 'rs.id')
            ->where('a.id', $id)
            ->first();
    }

    public static function insertJadwalSidangProposal($params)
    {
        return DB::table('jadwal_sidang_proposal')->insert($params);
    }

    public static function updateJadwalSidangProposal($id, $params)
    {
        return DB::table('jadwal_sidang_proposal')->where('id', $id)->update($params);
    }

    public static function updateKelompok($id,$params)
    {
        return DB::table('kelompok')->where('id', $id)->update($params);
    }

    public static function deleteJadwalSidangProposal($id)
    {
        return DB::table('jadwal_sidang_proposal')->where('id', $id)->delete();
    }
    public static function getKelompokById($id)
    {
        return DB::table('kelompok')->where('id', $id)->first();
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

    public static function getSiklusAktif()
    {
        return DB::table('siklus')
        ->get();
    }
}
