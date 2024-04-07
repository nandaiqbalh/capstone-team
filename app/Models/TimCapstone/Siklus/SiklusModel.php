<?php

namespace App\Models\TimCapstone\Siklus;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;

class SiklusModel extends BaseModel
{
    // get all data
    public static function getData()
    {
        return DB::table('siklus')
            ->get();
    }

    // get data with pagination
    public static function getDataWithPagination()
    {
        return DB::table('siklus')
            ->paginate(20);
    }

    // get search
    public static function getDataSearch($nama)
    {
        return DB::table('siklus')->where('nama', 'LIKE', "%" . $nama . "%")->paginate(20)->withQueryString();
    }

    // get data by id
    public static function getDataById($id)
    {
        return DB::table('siklus')->where('id', $id)->first();
    }

    public static function insertSiklus($params)
    {
        return DB::table('siklus')->insert($params);
    }

    public static function insertrole($params2)
    {
        return DB::table('siklus')->insert($params2);
    }

    public static function update($id, $params)
    {
        return DB::table('siklus')->where('id', $id)->update($params);
    }

    public static function delete($id)
    {
        return DB::table('siklus')->where('id', $id)->delete();
    }

    public static function deleteJadwalExpo($id_siklus)
    {
        return DB::table('jadwal_expo')->where('id_siklus', $id_siklus)->delete();
    }

    public static function deleteJadwalSidangProposal($id_siklus)
    {
        return DB::table('jadwal_sidang_proposal')->where('siklus_id', $id_siklus)->delete();
    }

    public static function deleteKelompok($id_siklus)
    {
        return DB::table('kelompok')->where('id_siklus', $id_siklus)->delete();
    }

    public static function deletependaftaranExpo($id_siklus)
    {
        return DB::table('pendaftaran_expo')->where('id_siklus', $id_siklus)->delete();
    }

    public static function deleteKelompokMhs($id_siklus)
    {
        return DB::table('kelompok_mhs')->where('id_siklus', $id_siklus)->delete();
    }
}
