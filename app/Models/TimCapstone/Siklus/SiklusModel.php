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
}
