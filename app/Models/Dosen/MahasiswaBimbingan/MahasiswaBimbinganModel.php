<?php

namespace App\Models\Dosen\MahasiswaBimbingan;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MahasiswaBimbinganModel extends BaseModel
{

    // get data with pagination
    public static function getDataWithPagination()
    {
        $loggedInUserId = Auth::user()->user_id;

        return DB::table('kelompok as a')
            ->select('a.*', 'b.nama as nama_topik', 'c.*', 'km.*', 's.*') // Menambahkan s.* untuk mendapatkan kolom dari tabel siklus
            ->join('topik as b', 'a.id_topik', 'b.id')
            ->leftJoin('kelompok_mhs as km', 'a.id', '=', 'km.id_kelompok')
            ->leftJoin('app_user as c', 'km.id_mahasiswa', '=', 'c.user_id')
            ->leftJoin('siklus as s', 'a.id_siklus', '=', 's.id') // Melakukan join dengan tabel siklus
            ->where('a.id_dosen_pembimbing_1', $loggedInUserId)
            ->orWhere('a.id_dosen_pembimbing_2', $loggedInUserId)
            ->orderBy('a.is_selesai') // Urutkan berdasarkan kelompok.is_selesai dari 0 ke 1
            ->orderByDesc('a.id') // Urutkan secara descending berdasarkan id (opsional)
            ->paginate(20);
    }

    // get search
    public static function getDataSearch($no_kel)
    {
        return DB::table('kelompok_mhs as km')
            ->select('a.*', 'u.*', 'km.*', 'b.nama as topik_name', 'c.nama_siklus')
            ->join('kelompok as a', 'km.id_kelompok', 'a.id')
            ->leftjoin('topik as b', 'a.id_topik', 'b.id')
            ->join('siklus as c', 'a.id_siklus', 'c.id')
            ->join('app_user as u', 'km.id_mahasiswa', 'u.user_id')
            ->where('u.user_name', 'LIKE', "%" . $no_kel . "%")
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

    public static function getMahasiswaBimbinganStatus($status)
    {
        $loggedInUserId = Auth::user()->user_id;

        return DB::table('kelompok_mhs as km')
            ->select('a.*', 'b.nama as nama_topik', 'c.*', 'km.*')
            ->join('kelompok as a', 'km.id_kelompok', '=', 'a.id')
            ->join('topik as b', 'a.id_topik', '=', 'b.id')
            ->join('app_user as c', 'km.id_mahasiswa', '=', 'c.user_id')
            ->where('km.is_selesai', $status)
            ->where(function ($query) use ($loggedInUserId) {
                $query->where('a.id_dosen_pembimbing_1', $loggedInUserId)
                    ->orWhere('a.id_dosen_pembimbing_2', $loggedInUserId);
            })
            ->orderBy('a.is_selesai') // Urutkan berdasarkan kelompok.is_selesai dari 0 ke 1
            ->orderByDesc('a.id') // Urutkan secara descending berdasarkan id (opsional)
            ->paginate(20);
    }



}
