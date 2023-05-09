<?php

namespace App\Models\User\Home;

use Illuminate\Support\Facades\DB;

class HomeModel extends DB
{
    // get data with pagination
    public static function getDataEventWithPagination()
    {
        return DB::table('branch_event')
            ->where("status", "belum berjalan")
            ->where("close_order", 0)
            ->orderByDesc('id')
            ->paginate(10);
    }
    
    // get data with pagination
    public static function getDataEventWithPaginationIndex() {
        return DB::table('branch_event')
            ->where("status", "belum berjalan")
            ->where("close_order", 0)
            ->orderByDesc('id')
            ->paginate(3);
    }
    // get data with pagination
    public static function getDataEventById($id)
    {
        return DB::table('branch_event')
            // ->where("id", $id)
            // ->where("status", "belum berjalan")
            // ->where("close_order", 0)
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
    // get data s]guest star
    public static function getDataTicketDetail($id)
    {
        return DB::table('event_ticket')
        ->where('id', $id)
        ->orderByDesc('id')
        ->first();
    }
    public static function getDataTicketEventById($id)
    {
    return DB::table('branch_event as a')
        ->select("a.*","c.no_rekening", "c.bank_rekening", "c.an_rekening")
        ->join("event_ticket as b", "a.id","b.event_id")
        ->join("master_branch as c", "a.branch_id", "c.id")
        ->where('b.id', $id)
        ->orderByDesc('id')
        ->first();
    }
    public static function insertTicketSell($params)
    {
        return DB::table('event_ticket_sell')->insert($params);
    }

    public static function update($id,
        $params
    ) {
        return DB::table('branch_event')->where('id', $id)->update($params);
    }

    public static function delete($id)
    {
        return DB::table('branch_event')->where('id', $id)->delete();
    }


}
