<?php

namespace App\Models\Dosen\KelompokBimbingan;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KelompokBimbinganModel extends BaseModel
{

    // get data with pagination
    public static function getDataWithPagination()
    {
        return DB::table('kelompok as a')
            ->select('a.*', 'b.nama as nama_topik', 'c.nama_siklus')
            ->join('topik as b', 'a.id_topik', 'b.id')
            ->join('siklus as c', 'a.id_siklus', 'c.id') // Join dengan tabel siklus
            ->where(function ($query) {
                $userId = Auth::user()->user_id;
                $query->where('a.id_dosen_pembimbing_1', $userId)
                      ->orWhere('a.id_dosen_pembimbing_2', $userId);
            })
            ->orderBy('a.is_selesai') // Urutkan berdasarkan kelompok.is_selesai dari 0 ke 1
            ->orderByDesc('a.id') // Urutkan secara descending berdasarkan id (opsional)
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
    // get data by id
    public static function getDataById($id)
    {
        return DB::table('kelompok as a')
            ->select('a.*', 'b.nama as nama_topik', 'c.nama_siklus')
            ->join('topik as b', 'a.id_topik', 'b.id')
            ->join('siklus as c', 'a.id_siklus', 'c.id') // Join dengan tabel siklus
            ->where('a.id', $id)
            ->first();
    }


    public static function getDataMahasiswaById($user_id)
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

    public static function getMahasiswa($id_kelompok)
    {
        return DB::table('kelompok_mhs as a')
            ->select('b.*', 'a.*')
            ->join('app_user as b','a.id_mahasiswa', 'b.user_id')
            ->where('a.id_kelompok', $id_kelompok)
            ->get();
    }
    public static function update($id, $params)
    {
        return DB::table('kelompok')->where('id', $id)->update($params);
    }

    public static function getKelompokBimbinganStatus($status)
    {
        return DB::table('kelompok as a')
            ->select('a.*', 'b.nama as nama_topik')
            ->join('topik as b', 'a.id_topik', 'b.id')
            ->where('a.is_selesai', $status)
            ->where('a.id_dosen_pembimbing_1', Auth::user()->user_id)
            ->orWhere('a.id_dosen_pembimbing_2', Auth::user()->user_id)
            ->orderBy('a.is_selesai') // Urutkan berdasarkan kelompok.is_selesai dari 0 ke 1
            ->orderByDesc('a.id') // Urutkan secara descending berdasarkan id (opsional)
            ->paginate(20);
    }

}
