<?php

namespace App\Models\Admin\Bimbingan_Saya;

use App\Models\Admin\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BimbinganSayaModel extends BaseModel
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
            ->select('a.*','b.nama as nama_topik', 'c.status_dosen','c.status_persetujuan', 'c.id as id_dosen_kelompok')
            ->join('topik as b','a.id_topik','b.id')
            ->join('dosen_kelompok as c','a.id','c.id_kelompok')
            ->where('c.id_dosen', Auth::user()->user_id)
            ->where(function ($query) {
                $query->where('c.status_dosen', 'pembimbing 1')
                    ->orWhere('c.status_dosen', 'pembimbing 2');
            })
            ->orderByDesc('a.id')
            ->paginate(20);
    }

    // get search
    public static function getDataSearch($no_kel)
    {
        return DB::table('kelompok as a')
            ->select('a.*', 'b.user_name')
            ->leftjoin('app_user as b', 'a.id_dosen', 'b.user_name')
            ->where('a.nomor_kelompok', 'LIKE', "%" . $no_kel . "%")
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
    public static function getMahasiswa($id_kelompok)
    {
        return DB::table('kelompok_mhs as a')
            ->select('b.*')
            ->join('app_user as b','a.id_mahasiswa', 'b.user_id')
            // ->join('kelompok as c','a.id_kelompok','c.id')
            ->where('a.id_kelompok', $id_kelompok)
            ->get();
    }
    public static function update($id, $params)
    {
        return DB::table('dosen_kelompok')->where('id', $id)->update($params);
    }

   
}
