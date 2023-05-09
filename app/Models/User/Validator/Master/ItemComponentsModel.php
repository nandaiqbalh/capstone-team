<?php

namespace App\Models\Admin\Validator\Master;

use Illuminate\Support\Facades\DB;
use App\Models\Admin\BaseModel;

class ItemComponentsModel extends BaseModel
{
    
    // get all data
    public static function getAll() {
        return DB::table('master_assessment_component as a')
                ->select('a.*','b.name as items_name')
                ->join('master_items as b','a.items_id','=','b.id')
                ->where('a.data_status', '1')
                ->orderBy('b.name','asc')
                ->orderBy('a.name','asc')
                ->get();
    }
    
    // get data with pagination
    public static function getAllPaginate() {
        return DB::table('master_assessment_component as a')
                ->select('a.*','b.name as items_name')
                ->join('master_items as b','a.items_id','=','b.id')
                ->where('a.data_status', '1')
                ->orderBy('b.name','asc')
                ->orderBy('a.name','asc')
                ->paginate(10)
                ->withQueryString();
    }

    // get search
    public static function getAllSearch($query_all) {
        return DB::table('master_assessment_component as a')
            ->select('a.*','b.name as items_name')
            ->join('master_items as b','a.items_id','=','b.id')
            ->where('a.name','LIKE', "%".$query_all."%")
            ->where('a.data_status', '1')
            ->orWhere(function($query) use($query_all) {
                $query->where('b.name', 'LIKE', "%".$query_all."%")
                    ->where('a.data_status', '1');
            })
            ->orderBy('b.name','asc')
            ->orderBy('a.name','asc')
            ->paginate(10)
            ->withQueryString();
    }

    // get data by id
    public static function getById($id) {
        return DB::table('master_assessment_component as a')
                ->select('a.*','b.name as items_name')
                ->join('master_items as b','a.items_id','=','b.id')
                ->where('a.id', $id)
                ->where('a.data_status', '1')
                ->first();
    }

    public static function getByItemsIdAndName($items_id, $name) {
        return DB::table('master_assessment_component as a')
                ->select('a.*','b.name as items_name')
                ->join('master_items as b','a.items_id','=','b.id')
                ->where('a.name', $name)
                ->where('a.items_id', $items_id)
                ->where('a.data_status', '1')
                ->first();
    }

    public static function getByName($items_id, $name) {
        return DB::table('master_assessment_component as a')
                ->select('a.*','b.name as items_name')
                ->join('master_items as b','a.items_id','=','b.id')
                ->where('a.name', $name)
                ->where('a.items_id', $items_id)
                ->where('a.data_status', '1')
                ->first();
    }

    public static function insert($params) {
        return DB::table('master_assessment_component')->insert($params);
    }

    public static function insert_or_ignore($params) {
        return DB::table('master_assessment_component')->insertOrIgnore($params);
    }

    public static function update($id, $params) {
        return DB::table('master_assessment_component')->where('id', $id)->update($params);
    }

    // public static function delete($id) {
    //     return DB::table('master_assessment_component')->where('id', $id)->delete();
    // }


    // get master item
    public static function getMasterItems() {
        return DB::table('master_items')->select('id','name')->where('data_status', '1')->orderBy('name','asc')->get();
    }

    // get item by name
    public static function getItemByName($name) {
        return DB::table('master_items')->select('id','name')->where('name',$name)->where('data_status', '1')->first();
    }
}
