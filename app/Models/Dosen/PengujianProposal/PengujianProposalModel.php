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
            ->orderBy('d.waktu')
            ->paginate(20);
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
