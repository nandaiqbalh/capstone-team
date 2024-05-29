<?php

namespace App\Models\TimCapstone\Balancing\PembimbingMahasiswa;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;

class PembimbingMahasiswaModel extends BaseModel
{

    public static function getDataBalancingDosbingMahasiswa()
    {
        return DB::table('app_user')
            ->leftJoin('kelompok', function ($join) {
                $join->on('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_1')
                    ->orOn('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_2');
            })
            ->leftJoin('kelompok_mhs', 'kelompok.id', '=', 'kelompok_mhs.id_kelompok')
            ->where('app_user.role_id', '=', '04') // Menambahkan kondisi role_id = '04'
            ->select(
                'app_user.user_id',
                'app_user.user_name',
                DB::raw('SUM(CASE WHEN kelompok_mhs.is_selesai = 0 THEN 1 ELSE 0 END) AS jumlah_mahasiswa_aktif_dibimbing'),
                DB::raw('SUM(CASE WHEN kelompok_mhs.is_selesai = 1 THEN 1 ELSE 0 END) AS jumlah_mahasiswa_tidak_aktif_dibimbing')
            )
            ->orderBy('jumlah_mahasiswa_aktif_dibimbing') // Mengurutkan berdasarkan jumlah kelompok aktif yang belum selesai paling sedikit
            ->groupBy('app_user.user_id', 'app_user.user_name')
            ->paginate(20);
    }

    public static function getDataBalancingDosbingMahasiswaFilterSiklus($id_siklus)
    {
        return DB::table('app_user')
            ->leftJoin('kelompok', function ($join) {
                $join->on('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_1')
                    ->orOn('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_2');
            })
            ->where('kelompok.id_siklus', '=', $id_siklus) // Menambahkan kondisi id_siklus
            ->leftJoin('kelompok_mhs', 'kelompok.id', '=', 'kelompok_mhs.id_kelompok')
            ->where('app_user.role_id', '=', '04') // Menambahkan kondisi role_id = '04'
            ->select(
                'app_user.user_id',
                'app_user.user_name',
                DB::raw('SUM(CASE WHEN kelompok_mhs.is_selesai = 0 THEN 1 ELSE 0 END) AS jumlah_mahasiswa_aktif_dibimbing'),
                DB::raw('SUM(CASE WHEN kelompok_mhs.is_selesai = 1 THEN 1 ELSE 0 END) AS jumlah_mahasiswa_tidak_aktif_dibimbing')
            )
            ->orderBy('jumlah_mahasiswa_aktif_dibimbing') // Mengurutkan berdasarkan jumlah kelompok aktif yang belum selesai paling sedikit
            ->groupBy('app_user.user_id', 'app_user.user_name')
            ->paginate(20);
    }

    public static function getDataBimbinganDosbingMahasiswa($user_id)
    {
        return DB::table('kelompok_mhs as km')
            ->select('km.*', 'k.*', 't.nama as nama_topik', 'k.id_dosen_pembimbing_1', 'k.id_dosen_pembimbing_2', 'u.*')
            ->join('kelompok as k', 'km.id_kelompok', '=', 'k.id')
            ->join('topik as t', 'k.id_topik', '=', 't.id')
            ->join('app_user as u', function ($join) use ($user_id) {
                $join->on('km.id_mahasiswa', '=', 'u.user_id')
                     ->where(function ($query) use ($user_id) {
                         $query->where('k.id_dosen_pembimbing_1', $user_id)
                               ->orWhere('k.id_dosen_pembimbing_2', $user_id);
                     });
            })
            ->orderByDesc('km.id')
            ->paginate(20);
    }

    public static function searchBalancingDosbingMahasiswa($search)
    {
        return DB::table('app_user')
            ->leftJoin('kelompok', function ($join) {
                $join->on('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_1')
                    ->orOn('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_2');
            })
            ->leftJoin('kelompok_mhs', 'kelompok.id', '=', 'kelompok_mhs.id_kelompok')
            ->where('app_user.role_id', '=', '04') // Menambahkan kondisi role_id = '04'
            ->select(
                'app_user.user_id',
                'app_user.user_name',
                DB::raw('SUM(CASE WHEN kelompok_mhs.is_selesai = 0 THEN 1 ELSE 0 END) AS jumlah_mahasiswa_aktif_dibimbing'),
                DB::raw('SUM(CASE WHEN kelompok_mhs.is_selesai = 1 THEN 1 ELSE 0 END) AS jumlah_mahasiswa_tidak_aktif_dibimbing')
            )
            ->where('app_user.user_name', 'LIKE', "%" . $search . "%")
            ->orderBy('jumlah_mahasiswa_aktif_dibimbing') // Mengurutkan berdasarkan jumlah kelompok aktif yang belum selesai paling sedikit
            ->groupBy('app_user.user_id', 'app_user.user_name')
            ->paginate(20);
    }

    public static function getSiklusAktif()
    {
        return DB::table('siklus')
        ->get();
    }

    public static function peminatanMahasiswa()
    {
        return DB::table('peminatan')
            ->get();
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

    public static function getSiklusById($id_siklus)
    {
        return DB::table('siklus')
        ->where('id', $id_siklus)
        ->first();
    }
}
