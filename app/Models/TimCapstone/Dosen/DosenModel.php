<?php

namespace App\Models\TimCapstone\Dosen;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;

class DosenModel extends BaseModel
{
    // get all data
    public static function getData()
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role as c', 'a.role_id', 'c.role_id')
            ->where('c.role_id', '04')
            ->orwhere('c.role_id', '02')
            ->get();
    }

    // get data with pagination
    public static function getDataWithPagination()
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role as c', 'a.role_id', 'c.role_id')
            ->where('c.role_id', '04')
            ->paginate(20);
    }

    public static function getDataBalancingDosbing()
    {
        return DB::table('app_user')
            ->leftJoin('kelompok', function ($join) {
                $join->on('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_1')
                    ->orOn('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_2');
            })
            ->where('app_user.role_id', '=', '04') // Menambahkan kondisi role_id = '04'
            ->select(
                'app_user.user_id',
                'app_user.user_name',
                DB::raw('COUNT(CASE WHEN kelompok.is_selesai = 0 THEN kelompok.id END) AS jumlah_kelompok_aktif_dibimbing'),
                DB::raw('COUNT(CASE WHEN kelompok.is_selesai = 1 THEN kelompok.id END) AS jumlah_kelompok_tidak_aktif_dibimbing')
            )
            ->groupBy('app_user.user_id', 'app_user.user_name')
            ->paginate(20);
    }


    public static function getDataBalancingDosbingFilterSiklus($id_siklus)
    {
        return DB::table('app_user')
            ->leftJoin('kelompok', function ($join) {
                $join->on('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_1')
                    ->orOn('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_2');
            })
            ->where('app_user.role_id', '=', '04') // Menambahkan kondisi role_id = '04'
            ->where('kelompok.id_siklus', '=', $id_siklus) // Menambahkan kondisi id_siklus
            ->select(
                'app_user.user_id',
                'app_user.user_name',
                DB::raw('COUNT(CASE WHEN kelompok.is_selesai = 0 THEN kelompok.id END) AS jumlah_kelompok_aktif_dibimbing'),
                DB::raw('COUNT(CASE WHEN kelompok.is_selesai = 1 THEN kelompok.id END) AS jumlah_kelompok_tidak_aktif_dibimbing')
            )
            ->groupBy('app_user.user_id', 'app_user.user_name')
            ->paginate(20);
    }

    public static function getDataBimbinganDosbing($user_id)
    {
        return DB::table('kelompok as a')
            ->select('a.*','b.nama as nama_topik')
            ->join('topik as b','a.id_topik','b.id')
            ->where('a.id_dosen_pembimbing_1', $user_id)
            ->orWhere('a.id_dosen_pembimbing_2', $user_id)
            ->orderByDesc('a.id')
            ->paginate(20);
    }


    // get data by id
    public static function getDataById($user_id)
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name', 'c.role_id')
            ->join('app_role as c', 'a.role_id', 'c.role_id')
            ->where('a.user_id', $user_id)
            ->where('c.role_id', '04')
            ->orwhere('c.role_id', '02')
            ->where('a.user_id', $user_id)
            ->first();
    }

    public static function getDataSearch($search)
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role as c', 'a.role_id', 'c.role_id')
            ->where('c.role_id', '04')
            ->where('a.user_name', 'LIKE', "%" . $search . "%")
            ->orwhere('c.role_id', '02')
            ->where('a.user_name', 'LIKE', "%" . $search . "%")
            // ->orwhere('a.nomor_induk', 'LIKE', "%" . $search . "%")
            ->paginate(20)->withQueryString();
    }

    public static function insertDosen($params)
    {
        return DB::table('app_user')->insert($params);
    }

    public static function update($user_id, $params)
    {
        return DB::table('app_user')->where('user_id', $user_id)->update($params);
    }
    public static function delete($user_id)
    {
        return DB::table('app_user')->where('user_id', $user_id)->delete();
    }

    public static function getSiklusAktif()
    {
        return DB::table('siklus')
        ->where('status', 'aktif')
        ->get();
    }
}
