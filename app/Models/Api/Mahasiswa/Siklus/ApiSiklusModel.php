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
            ->orderBy('id', 'desc') // Urutkan berdasarkan 'id' secara descending
            ->first();
    }

    public static function getPeriodePendaftaranSiklus()
    {
        return DB::table('siklus')
            ->where('pendaftaran_mulai', '<', now())
            ->where('pendaftaran_selesai', '>', now())
            ->orderBy('id', 'desc') // Urutkan berdasarkan 'id' secara descending
            ->first();
    }

}
