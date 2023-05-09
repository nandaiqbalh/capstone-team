<?php

namespace App\Models\Admin\Settings;

use App\Models\Admin\BaseModel;
use Illuminate\Support\Facades\DB;

class ContohHalamanModel extends BaseModel
{
    // get all data
    public static function getData() {
        return DB::table('contoh_tabel')->get();
    }
    
    // get data with pagination
    public static function getDataWithPagination() {
        return DB::table('contoh_tabel')->paginate(20);
    }

    // get search
    public static function getDataSearch($nama) {
        return DB::table('contoh_tabel')->where('nama', 'LIKE', "%".$nama."%")->paginate(20)->withQueryString();
    }

    // get data by id
    public static function getDataById($id) {
        return DB::table('contoh_tabel')->where('id', $id)->first();
    }

    public static function insert($params) {
        return DB::table('contoh_tabel')->insert($params);
    }

    public static function update($id, $params) {
        return DB::table('contoh_tabel')->where('id', $id)->update($params);
    }

    public static function delete($id) {
        return DB::table('contoh_tabel')->where('id', $id)->delete();
    }
}
