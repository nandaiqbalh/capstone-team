<?php

namespace App\Models\TimCapstone;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardModel extends BaseModel
{
    /**
     * SUPER ADMIN
     */


    // get data komponen Pekerjaan
    public static function getBroadcast()
    {
        return DB::table('broadcast')
            ->where('tgl_mulai','<=',date('Y-m-d'))
            ->where('tgl_selesai', '>=', date('Y-m-d'))
            ->get();
    }
    // get data komponen Pekerjaan
    public static function getJadwalCap()
    {
        return DB::table('pendaftaran_capstone as a')
            ->select()
            ->join('siklus as b','a.siklus_id','b.id')
            ->where('b.status','aktif')
            ->get();
    }
    // get data ksidang prop
    public static function getJadwalSidang()
    {
        return DB::table('jadwal_sidang_proposal as a')
            ->select('a.waktu')
            ->join('siklus as b', 'a.siklus_id', 'b.id')
            ->join('kelompok as c','a.id_kelompok','c.id')
            ->join('kelompok_mhs as d', 'c.id', 'd.id_kelompok')
            ->where('d.id_mahasiswa', Auth::user()->user_id)
            ->where('b.status', 'aktif')
            ->get();
    }
    public static function getJadwalExpo()
    {
        return DB::table('jadwal_expo as a')
            ->select('a.tanggal_mulai', 'a.tanggal_selesai')
            ->join('siklus as b', 'a.id_siklus', 'b.id')
            ->join('pendaftaran_expo as c', 'a.id', 'c.id_expo')
            ->join('kelompok as d', 'c.id_kelompok', 'd.id')
            ->join('kelompok_mhs as e', 'd.id', 'e.id_kelompok')
            ->where('e.id_mahasiswa', Auth::user()->user_id)
            ->where('b.status', 'aktif')
            ->get();
    }
}
