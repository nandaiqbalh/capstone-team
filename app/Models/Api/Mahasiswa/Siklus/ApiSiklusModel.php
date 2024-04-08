<?php

namespace App\Models\Api\Mahasiswa\Siklus;

use App\Models\Api\ApiBaseModel;
use Illuminate\Support\Facades\DB;

class ApiSiklusModel extends ApiBaseModel
{
    // get data by id
    public static function getAkunByID($id) {
        return DB::table('app_user')->where('user_id', $id)->first();
    }

    public static function getSiklusAktif()
    {
        return DB::table('siklus')
            ->where('status','aktif')
             // Order by 'created_at' in descending order (newest first)
            ->first();
    }

    public static function getPeriodePendaftaranSiklus()
    {
        return DB::table('siklus')
            ->where('status','aktif')
            ->where('siklus.pendaftaran_mulai', '<', now())
            ->where('siklus.pendaftaran_selesai', '>', now()) // Menambahkan kondisi a.tanggal_selesai > waktu sekarang
            ->first();
    }

}
