<?php

namespace App\Models\TimCapstone\JadwalSidangProposal;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;

class JadwalSidangProposalModel extends BaseModel
{

    public function ruangSidang()
    {
        return $this->belongsTo(RuangSidang::class, 'ruangan_id');
    }
    // get all data
    // public static function getData()
    // {
    //     return DB::table('app_user as a')
    //         ->select('a.*', 'b.')
    //         ->join('app_role_user as b', 'a.user_id', 'b.user_id')
    //         ->join('app_role as c', 'b.role_id', 'c.role_id')
    //         ->where('c.role_id', '03')
    //         ->get();
    // }

    // get data with pagination Pendataran mahasiswa yang belum punya kelompok
    public static function getDataWithPagination()
    {
        return DB::table('jadwal_sidang_proposal as a')
            ->select('a.*','b.id as siklus_id', 'b.tahun_ajaran','c.nomor_kelompok')
            ->join('siklus as b', 'a.siklus_id', 'b.id')
            ->leftjoin('kelompok as c','a.id_kelompok','c.id')
            ->where('b.status', 'aktif')
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

    // get data by id
    public static function getDataById($id)
    {
        return DB::table('jadwal_sidang_proposal')->where('id', $id)->first();
    }

    public static function insertJadwalSidangProposal($params)
    {
        return DB::table('jadwal_sidang_proposal')->insert($params);
    }
    public static function insertDosenKelompok($params)
    {
        return DB::table('dosen_kelompok')->insert($params);
    }
    public static function updateJadwalSidangProposal($id, $params)
    {
        return DB::table('jadwal_sidang_proposal')->where('id', $id)->update($params);
    }
    public static function updateDosenKelompok($id,$params)
    {
        return DB::table('dosen_kelompok')->where('id', $id)->update($params);;
    }

    public static function deleteJadwalSidangProposal($id)
    {
        return DB::table('jadwal_sidang_proposal')->where('id', $id)->delete();
    }
}
