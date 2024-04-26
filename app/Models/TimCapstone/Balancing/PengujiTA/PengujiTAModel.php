<?php

namespace App\Models\TimCapstone\Balancing\PengujiTA;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;

class PengujiTAModel extends BaseModel
{

    public static function getDataBalancingPengujiTA()
    {
        return DB::table('app_user')
            ->leftJoin('kelompok_mhs', function ($join) {
                $join->on('app_user.user_id', '=', 'kelompok_mhs.id_dosen_penguji_ta1')
                    ->orOn('app_user.user_id', '=', 'kelompok_mhs.id_dosen_penguji_ta2');
            })
            ->where('app_user.role_id', '=', '04') // Menambahkan kondisi role_id = '04'
            ->select(
                'app_user.user_id',
                'app_user.user_name',
                DB::raw('COUNT(CASE WHEN kelompok_mhs.is_selesai = 0 THEN kelompok_mhs.id END) AS jumlah_mhs_aktif_dibimbing'),
                DB::raw('COUNT(CASE WHEN kelompok_mhs.is_selesai = 1 THEN kelompok_mhs.id END) AS jumlah_mhs_tidak_aktif_dibimbing')
            )
            ->orderBy('jumlah_mhs_aktif_dibimbing') // Mengurutkan berdasarkan jumlah kelompok aktif yang belum selesai paling sedikit
            ->groupBy('app_user.user_id', 'app_user.user_name')
            ->paginate(20);
    }


    public static function getDataBalancingPengujiTAFilterPeriode($id_periode)
    {
        return DB::table('app_user')
            ->leftJoin('kelompok_mhs', function ($join) {
                $join->on('app_user.user_id', '=', 'kelompok_mhs.id_dosen_penguji_ta1')
                    ->orOn('app_user.user_id', '=', 'kelompok_mhs.id_dosen_penguji_ta2');
            })
            ->leftJoin('jadwal_sidang_ta', 'jadwal_sidang_ta.id_mahasiswa', '=', 'kelompok_mhs.id_mahasiswa')
            ->where('jadwal_sidang_ta.id_periode', '=', $id_periode) // Menambahkan kondisi id_periode
            ->where('app_user.role_id', '=', '04') // Menambahkan kondisi role_id = '04'
            ->select(
                'app_user.user_id',
                'app_user.user_name',
                DB::raw('COUNT(CASE WHEN kelompok_mhs.is_selesai = 0 THEN kelompok_mhs.id END) AS jumlah_mhs_aktif_dibimbing'),
                DB::raw('COUNT(CASE WHEN kelompok_mhs.is_selesai = 1 THEN kelompok_mhs.id END) AS jumlah_mhs_tidak_aktif_dibimbing')
            )
            ->orderBy('jumlah_mhs_aktif_dibimbing') // Mengurutkan berdasarkan jumlah kelompok aktif yang belum selesai paling sedikit
            ->groupBy('app_user.user_id', 'app_user.user_name')
            ->paginate(20);
    }

    public static function getDataPengujianTA($user_id)
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.*', 'u.user_name', 'k.nomor_kelompok')
            ->join('kelompok as k', 'k.id', 'a.id_kelompok')
            ->join('app_user as u', 'u.user_id', 'a.id_mahasiswa')
            ->where('a.id_dosen_penguji_ta1', $user_id)
            ->orWhere('a.id_dosen_penguji_ta2', $user_id)
            ->orderByDesc('a.id')
            ->paginate(20);
    }

    public static function searchBalancingPengujiTA($search)
    {
        return DB::table('app_user')
            ->leftJoin('kelompok_mhs', function ($join) {
                $join->on('app_user.user_id', '=', 'kelompok_mhs.id_dosen_penguji_ta1')
                    ->orOn('app_user.user_id', '=', 'kelompok_mhs.id_dosen_penguji_ta2');
            })
            ->where('app_user.role_id', '=', '04') // Menambahkan kondisi role_id = '04'
            ->select(
                'app_user.user_id',
                'app_user.user_name',
                DB::raw('COUNT(CASE WHEN kelompok_mhs.is_selesai = 0 THEN kelompok_mhs.id END) AS jumlah_mhs_aktif_dibimbing'),
                DB::raw('COUNT(CASE WHEN kelompok_mhs.is_selesai = 1 THEN kelompok_mhs.id END) AS jumlah_mhs_tidak_aktif_dibimbing')
            )
            ->where('app_user.user_name', 'LIKE', "%" . $search . "%")
            ->orderBy('jumlah_mhs_aktif_dibimbing') // Mengurutkan berdasarkan jumlah kelompok aktif yang belum selesai paling sedikit
            ->groupBy('app_user.user_id', 'app_user.user_name')
            ->paginate(20);
    }

    public static function getPeriode()
    {
        return DB::table('jadwal_periode_sidang_ta')
        ->get();
    }

    public static function getPeriodeById($id_periode)
    {
        return DB::table('jadwal_periode_sidang_ta')
            ->where('id', $id_periode)
            ->first();
    }

    public static function getDataMahasiswaBimbinganById($user_id)
    {
        return DB::table('app_user as a')
             ->select('a.*', 'b.*', 'c.*', 'd.*')
            ->leftJoin('kelompok_mhs as b', 'a.user_id', 'b.id_mahasiswa')
            ->leftJoin('kelompok as c', 'b.id_kelompok', 'c.id')
            ->leftJoin('siklus as d', 'b.id_siklus', 'd.id')
            ->where('a.user_id', $user_id)
            ->first();
    }
    public static function peminatanMahasiswa()
    {
        return DB::table('peminatan')
            ->get();
    }
}

