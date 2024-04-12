<?php

namespace App\Models\Dosen\PersetujuanMakalahTA;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PersetujuanMakalahTAModel extends BaseModel
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
        $userId = Auth::user()->user_id;

        return DB::table('kelompok as a')
            ->select('a.*', 'b.nama as nama_topik', 'c.*', 'u.user_name') // Memilih kolom-kolom dari tabel yang diperlukan
            ->join('topik as b', 'a.id_topik', 'b.id')
            ->join('kelompok_mhs as c', 'a.id', 'c.id_kelompok') // Join dengan tabel kelompok_mhs
            ->join('app_user as u', 'c.id_mahasiswa', 'u.user_id') // Join dengan tabel users untuk mendapatkan username
            ->whereNotNull('c.file_status_mta')
            ->whereNotNull('c.file_name_makalah')
            ->where(function ($query) use ($userId) {
                $query->where('a.id_dosen_pembimbing_1', $userId)
                      ->orWhere('a.id_dosen_pembimbing_2', $userId);
            })
            ->orderBy('a.is_selesai') // Urutkan berdasarkan kelompok.is_selesai dari rendah ke tinggi
            ->orderByDesc('a.id') // Urutkan secara descending berdasarkan id
            ->paginate(20);
    }

    // get search
    public static function getDataSearch($nama)
    {
        return DB::table('kelompok as a')
            ->select('a.*', 'b.nama as nama_topik', 'c.*', 'u.user_name') // Memilih kolom-kolom dari tabel yang diperlukan
            ->join('topik as b', 'a.id_topik', 'b.id')
            ->join('kelompok_mhs as c', 'a.id', 'c.id_kelompok') // Join dengan tabel kelompok_mhs
            ->join('app_user as u', 'c.id_mahasiswa', 'u.user_id') // Join dengan tabel app_user untuk mendapatkan username
            ->whereNotNull('c.file_status_mta')
            ->whereNotNull('c.file_name_makalah')
            ->where('u.user_name', 'LIKE', "%" . $nama . "%")
            ->orderBy('a.is_selesai') // Urutkan berdasarkan kelompok.is_selesai dari rendah ke tinggi
            ->orderByDesc('a.id') // Urutkan secara descending berdasarkan id
            ->paginate(20)
            ->withQueryString(); // Menambahkan query string untuk mempertahankan parameter pencarian
    }


    // get data by id
    public static function getDataById($id)
    {
        return DB::table('kelompok_mhs as a')
            ->select('a.*')
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

    public static function updateKelompokMhs($id_kel_mhs, $params)
    {
        return DB::table('kelompok_mhs')->where('id', $id_kel_mhs)->update($params);
    }

}
