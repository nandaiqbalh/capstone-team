<?php

namespace App\Models\TimCapstone\RuangSidang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;

class RuangSidang extends BaseModel
{
    // get all data
    public static function getData()
    {
        return DB::table('ruang_sidangs')
        ->get();

    }

    // get data with pagination
    public static function getDataWithPagination()
    {
        return DB::table('ruang_sidangs')
        ->paginate(20);

    }

    // get search
    public static function getDataSearch($nama)
    {
        return DB::table('ruang_sidangs')->where('nama', 'LIKE', "%" . $nama . "%")->paginate(20)->withQueryString();
    }

    // get data by id
    public static function getDataById($id)
    {
        return DB::table('ruang_sidangs')->where('id', $id)->first();
    }

    public static function insertruangsidang($params)
    {
        return DB::table('ruang_sidangs')->insert($params);
    }
    public static function editruangsidang($params)
    {
        return DB::table('ruang_sidangs')->where('id', $id)->edit($params);
    }

    public static function update($id, $params)
    {
        return DB::table('ruang_sidangs')->where('id', $id)->update($params);
    }

    public static function delete($id)
    {
        return DB::table('ruang_sidangs')->where('id', $id)->delete();
    }
}