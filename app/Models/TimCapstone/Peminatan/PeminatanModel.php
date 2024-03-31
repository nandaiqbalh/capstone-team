<?php

namespace App\Models\TimCapstone\Peminatan;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;

class PeminatanModel extends BaseModel
{
    // get all data
    public static function getData()
    {
        return DB::table('peminatan')
        ->get();

    }

    // get data with pagination
    public static function getDataWithPagination()
    {
        return DB::table('peminatan')
        ->paginate(20);

    }

    // get search
    public static function getDataSearch($nama_peminatan)
    {
        return DB::table('peminatan')->where('nama_peminatan', 'LIKE', "%" . $nama_peminatan . "%")->paginate(20)->withQueryString();
    }

    // get data by id
    public static function getDataById($id)
    {
        return DB::table('peminatan')->where('id', $id)->first();
    }

    public static function insertpeminatan($params)
    {
        return DB::table('peminatan')->insert($params);
    }
    public static function editpeminatan($params)
    {
        return DB::table('peminatan')->where('id', $id)->edit($params);
    }

    public static function update($id, $params)
    {
        return DB::table('peminatan')->where('id', $id)->update($params);
    }

    public static function delete($id)
    {
        return DB::table('peminatan')->where('id', $id)->delete();
    }
}
