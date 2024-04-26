<?php

namespace App\Models\TimCapstone\Balancing\PembimbingKelompok;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;

class PembimbingKelompokModel extends BaseModel
{


    public static function getDataBalancingDosbingKelompok()
    {
        return DB::table('app_user')
            ->leftJoin('kelompok', function ($join) {
                $join->on('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_1')
                    ->orOn('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_2');
            })
            ->where('app_user.role_id', '04') // Menggunakan string tanpa tanda petik karena operator adalah string
            ->select(
                'app_user.user_id',
                'app_user.user_name',
                DB::raw('SUM(CASE WHEN kelompok.is_selesai = 0 THEN 1 ELSE 0 END) AS jumlah_kelompok_aktif_dibimbing'),
                DB::raw('SUM(CASE WHEN kelompok.is_selesai = 1 THEN 1 ELSE 0 END) AS jumlah_kelompok_tidak_aktif_dibimbing')
            )
            ->groupBy('app_user.user_id', 'app_user.user_name')
            ->orderBy('jumlah_kelompok_aktif_dibimbing') // Mengurutkan berdasarkan jumlah kelompok aktif yang belum selesai paling sedikit
            ->paginate(20);
    }



    public static function getDataBalancingDosbingKelompokFilterSiklus($id_siklus)
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
            ->orderBy('jumlah_kelompok_aktif_dibimbing') // Mengurutkan berdasarkan jumlah kelompok aktif yang belum selesai paling sedikit
            ->groupBy('app_user.user_id', 'app_user.user_name')
            ->paginate(20);
    }

    public static function getDataBimbinganDosbingKelompok($user_id)
    {
        return DB::table('kelompok as a')
            ->select('a.*','b.nama as nama_topik')
            ->join('topik as b','a.id_topik','b.id')
            ->where('a.id_dosen_pembimbing_1', $user_id)
            ->orWhere('a.id_dosen_pembimbing_2', $user_id)
            ->orderByDesc('a.id')
            ->paginate(20);
    }

    public static function searchBalancingDosbingKelompok($search)
    {
        return DB::table('app_user')
            ->leftJoin('kelompok', function ($join) {
                $join->on('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_1')
                    ->orOn('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_2');
            })
            ->where('app_user.role_id', '04') // Menggunakan string tanpa tanda petik karena operator adalah string
            ->select(
                'app_user.user_id',
                'app_user.user_name',
                DB::raw('SUM(CASE WHEN kelompok.is_selesai = 0 THEN 1 ELSE 0 END) AS jumlah_kelompok_aktif_dibimbing'),
                DB::raw('SUM(CASE WHEN kelompok.is_selesai = 1 THEN 1 ELSE 0 END) AS jumlah_kelompok_tidak_aktif_dibimbing')
            )
            ->where('app_user.user_name', 'LIKE', "%" . $search . "%")
            ->groupBy('app_user.user_id', 'app_user.user_name')
            ->orderBy('jumlah_kelompok_aktif_dibimbing') // Mengurutkan berdasarkan jumlah kelompok aktif yang belum selesai paling sedikit
            ->paginate(20);
    }

    public static function getSiklusAktif()
    {
        return DB::table('siklus')
        ->get();
    }

    public static function getSiklusById($id_siklus)
    {
        return DB::table('siklus')
        ->where('id', $id_siklus)
        ->first();
    }
}
