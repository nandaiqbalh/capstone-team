<?php

namespace App\Models\Admin\PJ\Register;

use App\Models\Admin\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AcaraPCModel extends BaseModel
{
    
    // get data with pagination
    public static function getDataEventWithPagination() {
        return DB::table('branch_event')
            ->where('branch_id', Auth::user()->branch_id)
            ->orderByDesc('id')
            ->paginate(10);
    }

    // search
    public static function getDataEventSearch($search)
    {
        return DB::table('branch_event')
            ->where('branch_id', Auth::user()->branch_id)
            ->where('name', 'LIKE', "%" . $search . "%")
            ->orderByDesc('id')
            ->paginate(10)->withQueryString();
    }

    // get by id
    public static function getDataEventById($id)
    {
        return DB::table('branch_event')
            ->where('branch_id', Auth::user()->branch_id)
            ->where('id', $id)
            ->first();
    }
    // get by id
    public static function getDataGuestEventById($id)
    {
        return DB::table('event_guest')
            ->where('id', $id)
            ->first();
    }
    // get by id
    public static function getDataTicketEventById($id)
    {
        return DB::table('event_ticket')
            ->where('id', $id)
            ->first();
    }
    // get by id
    public static function getDataRundownEventById($id)
    {
        return DB::table('event_rundown')
            ->where('id', $id)
            ->first();
    }
    // get data s]guest star
    public static function getDataGuestStar($id)
    {
        return DB::table('event_guest')
            ->where('event_id', $id)
            ->orderByDesc('id')
            ->get();
    }

    // get data s]guest star
    public static function getDataTicket($id)
    {
        return DB::table('event_ticket')
            ->where('event_id', $id)
            ->orderByDesc('id')
            ->get();
    }
    // get data s]guest star
    public static function getDataRundown($id)
    {
        return DB::table('event_rundown')
            ->where('event_id', $id)
            ->orderByDesc('id')
            ->get();
    }

    // get user branch 
    public static function getUserBranch()
    {
        return DB::table('app_user')
            ->where('branch_id', Auth::user()->branch_id)
            ->orderByDesc('user_id')
            ->get();
    }

    public static function insertEvent($params) {
        return DB::table('branch_event')->insert($params);
    }
    public static function insertEventGuest($params)
    {
        return DB::table('event_guest')->insert($params);
    }
    public static function insertEventTicket($params)
    {
        return DB::table('event_ticket')->insert($params);
    }
    public static function insertEventRundown($params)
    {
        return DB::table('event_rundown')->insert($params);
    }

    public static function update($id, $params) {
        return DB::table('branch_event')->where('user_id', $id)->update($params);
    }

    public static function delete($id) {
        return DB::table('branch_event')->where('id', $id)->delete();
    }
    public static function deleteGuest($id)
    {
        return DB::table('event_guest')->where('id', $id)->delete();
    }
    public static function deleteTicket($id)
    {
        return DB::table('event_ticket')->where('id', $id)->delete();
    }
    public static function deleteRundown($id)
    {
        return DB::table('event_rundown')->where('id', $id)->delete();
    }
}
