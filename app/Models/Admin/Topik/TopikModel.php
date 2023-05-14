<?php

namespace App\Models\Admin\Topik;

use App\Models\Admin\BaseModel;
use Illuminate\Support\Facades\DB;

class TopikModel extends BaseModel
{
    // get all data
    public static function getData()
    {
        return DB::table('topik')
        ->get();
           
    }

    // get data with pagination
    public static function getDataWithPagination()
    {
        return DB::table('topik')
        ->paginate(20);
            
    }

    // get search
    public static function getDataSearch($nama)
    {
        return DB::table('topik')->where('nama', 'LIKE', "%" . $nama . "%")->paginate(20)->withQueryString();
    }

    // get data by id
    public static function getDataById($id)
    {
        return DB::table('topik')->where('id', $id)->first();
    }

    public static function inserttopik($params)
    {
        return DB::table('topik')->insert($params);
    }
    public static function edittopik($params)
    {
        return DB::table('topik')->where('id', $id)->edit($params);
    }

    public static function update($id, $params)
    {
        return DB::table('topik')->where('id', $id)->update($params);
    }

    public static function delete($id)
    {
        return DB::table('topik')->where('id', $id)->delete();
    }
}
