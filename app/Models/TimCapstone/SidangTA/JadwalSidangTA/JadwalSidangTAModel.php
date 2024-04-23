<?php

namespace App\Models\TimCapstone\SidangTA\JadwalSidangTA;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;

class JadwalSidangTAModel extends BaseModel
{

    public function ruangSidang()
    {
        return $this->belongsTo(RuangSidang::class, 'ruangan_id');
    }

    public static function getDataWithPagination()
    {
        return DB::table('jadwal_sidang_ta as a')
            ->select('a.*', 'p.nama_periode', 'b.status_tugas_akhir', 'b.file_status_lta','c.nomor_kelompok', 'd.id as id_ruang', 'd.nama_ruang', 'u.user_name')
            ->join('ruang_sidangs as d', 'd.id', 'a.id_ruangan')
            ->join('kelompok as c','a.id_kelompok','c.id')
            ->join('kelompok_mhs as b','a.id_mahasiswa','b.id_mahasiswa')
            ->join('app_user as u','a.id_mahasiswa','u.user_id')
            ->join('jadwal_periode_sidang_ta as p','a.id_periode','p.id')
            ->orderBy('b.is_selesai', 'asc') // Urutkan berdasarkan is_sidang_proposal dari 0 ke 1
            ->orderBy('a.waktu', 'asc')
            ->paginate(20);
    }

    public static function getSiklus()
    {
        return DB::table('siklus')
            ->where('status','aktif')
            ->get();
    }
    public static function getKelompok()
    {
        return DB::table('kelompok as a')
            ->select('a.*','c.id as id_prop')
            ->join('siklus as b','a.id_siklus','b.id')
            ->leftjoin('jadwal_sidang_proposal as c', 'a.id','c.id_kelompok' )
            ->where('b.status', 'aktif')
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

    public static function getKelompokMhs($id_mahasiswa)
    {
        return DB::table('kelompok_mhs')
        ->where('id_mahasiswa', $id_mahasiswa)
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
    public static function getDataSearch($search)
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role as c', 'a.role_id', 'c.role_id')
            ->where('c.role_id', '03')
            ->where('a.user_name', 'LIKE', "%" . $search . "%")
            // ->orwhere('a.nomor_induk', 'LIKE', "%" . $search . "%")
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

    public static function updateKelompokMhs($id_mahasiswa,$params)
    {
        return DB::table('kelompok_mhs')->where('id_mahasiswa', $id_mahasiswa)->update($params);
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


    public static function getRuangSidang()
    {
        return DB::table('ruang_sidangs')
        ->get();
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


    public static function getJadwalSidangTA($id_mahasiswa)
    {
        return DB::table('jadwal_sidang_ta as a')
        ->select('a.*', 'b.nama_ruang')
        ->join('ruang_sidangs as b', 'b.id', 'a.id_ruangan')
        ->where('id_mahasiswa', $id_mahasiswa)
        ->first();
    }
}
