<?php

namespace App\Models\Api\Mahasiswa\Mahasiswa;

use Illuminate\Support\Facades\DB;
use App\Models\Api\ApiBaseModel;

class ApiMahasiswaModel extends ApiBaseModel
{
    public static function getDataMahasiswaAvailable()
    {
        return DB::table('app_user as a')
        ->select('a.*', 'c.role_name')
        ->join('app_role as c', 'a.role_id', 'c.role_id')
        ->leftJoin('kelompok_mhs as km', 'a.user_id', 'km.id_mahasiswa')
        ->where('a.role_id', '03') // Filter berdasarkan role_id di tabel app_user
        ->whereNull('km.id_mahasiswa') // Pastikan user_id tidak terdapat pada kelompok_mhs
        ->orderBy('a.user_name') // Sort the result by user_name
        ->get();
    }
}
