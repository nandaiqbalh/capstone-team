<?php

namespace App\Models\TimCapstone\Balancing\PengujiProposal;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;

class PengujiProposalModel extends BaseModel
{

    public static function getDataBalancingPengujiProposal()
    {
        return DB::table('app_user')
            ->leftJoin('kelompok', function ($join) {
                $join->on('app_user.user_id', '=', 'kelompok.id_dosen_penguji_1')
                    ->orOn('app_user.user_id', '=', 'kelompok.id_dosen_penguji_2');
            })
            ->where('app_user.role_id', '=', '04') // Menambahkan kondisi role_id = '04'
            ->select(
                'app_user.user_id',
                'app_user.user_name',
                DB::raw('COUNT(CASE WHEN kelompok.is_sidang_proposal = 0 THEN kelompok.id END) AS jumlah_kelompok_aktif_dibimbing'),
                DB::raw('COUNT(CASE WHEN kelompok.is_sidang_proposal = 1 THEN kelompok.id END) AS jumlah_kelompok_tidak_aktif_dibimbing')
            )
            ->orderBy('jumlah_kelompok_aktif_dibimbing') // Mengurutkan berdasarkan jumlah kelompok aktif yang belum selesai paling sedikit
            ->groupBy('app_user.user_id', 'app_user.user_name')
            ->paginate(20);
    }


    public static function getDataBalancingPengujiProposalFilterSiklus($id_siklus)
    {
        return DB::table('app_user')
            ->leftJoin('kelompok', function ($join) {
                $join->on('app_user.user_id', '=', 'kelompok.id_dosen_penguji_1')
                    ->orOn('app_user.user_id', '=', 'kelompok.id_dosen_penguji_2');
            })
            ->where('app_user.role_id', '=', '04') // Menambahkan kondisi role_id = '04'
            ->where('kelompok.id_siklus', '=', $id_siklus) // Menambahkan kondisi id_siklus
            ->select(
                'app_user.user_id',
                'app_user.user_name',
                DB::raw('COUNT(CASE WHEN kelompok.is_sidang_proposal = 0 THEN kelompok.id END) AS jumlah_kelompok_aktif_dibimbing'),
                DB::raw('COUNT(CASE WHEN kelompok.is_sidang_proposal = 1 THEN kelompok.id END) AS jumlah_kelompok_tidak_aktif_dibimbing')
            )
            ->orderBy('jumlah_kelompok_aktif_dibimbing') // Mengurutkan berdasarkan jumlah kelompok aktif yang belum selesai paling sedikit
            ->groupBy('app_user.user_id', 'app_user.user_name')
            ->paginate(20);
    }

    public static function getDataPengujianProposal($user_id)
    {
        return DB::table('kelompok as a')
            ->select('a.*','b.nama as nama_topik')
            ->join('topik as b','a.id_topik','b.id')
            ->where('a.id_dosen_penguji_1', $user_id)
            ->orWhere('a.id_dosen_penguji_2', $user_id)
            ->orderByDesc('a.id')
            ->paginate(20);
    }

    public static function searchBalancingPengujiProposal($search)
    {
        return DB::table('app_user')
            ->leftJoin('kelompok', function ($join) {
                $join->on('app_user.user_id', '=', 'kelompok.id_dosen_penguji_1')
                    ->orOn('app_user.user_id', '=', 'kelompok.id_dosen_penguji_2');
            })
            ->where('app_user.role_id', '=', '04') // Menambahkan kondisi role_id = '04'
            ->select(
                'app_user.user_id',
                'app_user.user_name',
                DB::raw('COUNT(CASE WHEN kelompok.is_sidang_proposal = 0 THEN kelompok.id END) AS jumlah_kelompok_aktif_dibimbing'),
                DB::raw('COUNT(CASE WHEN kelompok.is_sidang_proposal = 1 THEN kelompok.id END) AS jumlah_kelompok_tidak_aktif_dibimbing')
            )
            ->orderBy('jumlah_kelompok_aktif_dibimbing') // Mengurutkan berdasarkan jumlah kelompok aktif yang belum selesai paling sedikit
            ->where('app_user.user_name', 'LIKE', "%" . $search . "%")
            ->groupBy('app_user.user_id', 'app_user.user_name')
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
