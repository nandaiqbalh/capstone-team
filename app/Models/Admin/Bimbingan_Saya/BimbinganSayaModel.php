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
            ->select('a.*','b.nama')
            ->join('topik as b','a.id_topik','b.id')
            ->where('a.id_dosen_1', Auth::user()->user_id)
            ->orwhere('a.id_dosen_2', Auth::user()->user_id)
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
    public static function getDataById($user_id)
    {
        return DB::table('app_user')->where('user_id', $user_id)->first();
    }

    public static function update($id, $params)
    {
        return DB::table('kelompok')->where('id', $id)->update($params);
    }

   
}
