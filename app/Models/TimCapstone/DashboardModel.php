<?php

namespace App\Models\TimCapstone;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardModel extends BaseModel
{

    // get all data
    public static function getData()
    {
        return DB::table('broadcast')
            ->get();
    }

    public static function getDataWithPagination()
    {
        return DB::table('broadcast')->orderBy('created_date', 'desc')->get();
    }

    public static function getDataWithHomePagination()
    {
        return DB::table('broadcast')->orderBy('created_date', 'desc')->paginate(3);
    }

    // get data by id
    public static function getDataById($id)
    {
        return DB::table('broadcast')->where('id', $id)->first();
    }


    // mahasiswa
    public static function pengecekan_kelompok_mahasiswa($user_id)
    {
       return DB::table('kelompok_mhs as a')
           ->select('a.id_kelompok','b.*','c.nama as nama_topik')
           ->leftjoin('kelompok as b','a.id_kelompok','b.id')
           ->leftjoin('topik as c', 'a.id_topik_mhs', 'c.id')
           ->where('a.id_mahasiswa', $user_id)
           ->first();
    }

    public static function sidangProposalByKelompok($idKelompok)
    {
        return DB::table('jadwal_sidang_proposal as a')
        ->select('a.*', 'b.id as siklus_id', 'b.nama_siklus', 'c.judul_capstone', 'c.status_kelompok', 'd.kode_ruang', 'd.nama_ruang')
        ->join('siklus as b', 'a.siklus_id', '=', 'b.id')
        ->leftJoin('kelompok as c', 'a.id_kelompok', '=', 'c.id')
        ->leftJoin('ruang_sidangs as d', 'a.ruangan_id', '=', 'd.id')
        ->where('c.id', $idKelompok)

        ->first();
    }

    public static function cekStatusPendaftaranSidangTA($user_id)
   {
       return DB::table('pendaftaran_sidang_ta as a')
            ->select('a.status as status_pendaftaran')
            ->where('a.id_mahasiswa', $user_id)
            ->first();
   }

   public static function sidangTugasAkhirByMahasiswa($id_mahasiswa)
   {
       return DB::table('jadwal_sidang_ta as a')
           ->select('a.*','b.status_individu', 'b.id_dosen_penguji_ta1', 'b.status_dosen_penguji_ta1','b.id_dosen_penguji_ta2','b.status_dosen_penguji_ta2','b.judul_ta_mhs', 'b.link_upload','c.*', 'd.nama_ruang')
           ->join('kelompok_mhs as b', 'a.id_mahasiswa', 'b.id_mahasiswa')
           ->leftjoin('app_user as c','a.id_mahasiswa','c.user_id')
           ->leftjoin('ruang_sidangs as d','a.id_ruangan','d.id')
           ->where('a.id_mahasiswa', $id_mahasiswa)
           ->first();
   }

   public static function checkKelompokMhs($user_id)
   {
       return DB::table('kelompok_mhs as a')
           ->select('a.*')
           ->join('siklus as b', 'a.id_siklus', 'b.id')
           ->leftJoin('kelompok as c', 'a.id_kelompok', 'c.id')

           ->where('a.id_mahasiswa', $user_id)
           ->first();
   }


   // dosen
   public static function getDataBalancingDosbingKelompok()
   {
       $loggedInUserId = Auth::user()->user_id;

       return DB::table('app_user')
           ->leftJoin('kelompok', function ($join) use ($loggedInUserId) {
               $join->on('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_1')
                   ->orOn('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_2');
           })
           ->where('app_user.user_id', $loggedInUserId) // Filter berdasarkan user_id yang sedang login
           ->where('app_user.role_id', '04') // Menggunakan string tanpa tanda petik karena operator adalah string
           ->select(
               'app_user.user_id',
               'app_user.user_name',
               DB::raw('SUM(CASE WHEN kelompok.is_selesai = 0 THEN 1 ELSE 0 END) AS jumlah_kelompok_aktif_dibimbing'),
               DB::raw('SUM(CASE WHEN kelompok.is_selesai = 1 THEN 1 ELSE 0 END) AS jumlah_kelompok_tidak_aktif_dibimbing'),
               DB::raw('COUNT(kelompok.id) AS jumlah_total_kelompok_dibimbing')
           )
           ->groupBy('app_user.user_id', 'app_user.user_name')
           ->orderBy('jumlah_kelompok_aktif_dibimbing') // Mengurutkan berdasarkan jumlah kelompok aktif yang belum selesai paling sedikit
           ->first();
   }

   public static function getDataBalancingDosbingMahasiswa()
   {
        $loggedInUserId = Auth::user()->user_id;

       return DB::table('app_user')
           ->leftJoin('kelompok', function ($join) use ($loggedInUserId) {
               $join->on('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_1')
                   ->orOn('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_2');
           })
           ->leftJoin('kelompok_mhs', 'kelompok.id', '=', 'kelompok_mhs.id_kelompok')
           ->where('app_user.user_id', $loggedInUserId) // Filter hanya data terkait dengan user yang sedang login
           ->where('app_user.role_id', '04') // Menambahkan kondisi role_id = '04'
           ->select(
               'app_user.user_id',
               'app_user.user_name',
               DB::raw('SUM(CASE WHEN kelompok_mhs.is_selesai = 0 THEN 1 ELSE 0 END) AS jumlah_mahasiswa_aktif_dibimbing'),
               DB::raw('SUM(CASE WHEN kelompok_mhs.is_selesai = 1 THEN 1 ELSE 0 END) AS jumlah_mahasiswa_tidak_aktif_dibimbing'),
               DB::raw('COUNT(DISTINCT kelompok_mhs.id_mahasiswa) AS jumlah_total_mahasiswa_dibimbing')
           )
           ->orderBy('jumlah_mahasiswa_aktif_dibimbing') // Mengurutkan berdasarkan jumlah mahasiswa aktif yang belum selesai paling sedikit
           ->groupBy('app_user.user_id', 'app_user.user_name')
           ->first();
   }

   public static function getJadwalSidangProposalTerdekat()
   {
       $currentTime = Carbon::now(); // Waktu sekarang

       return DB::table('kelompok as a')
           ->select('a.*', 'b.nama as nama_topik', 'd.*', 'e.*')
           ->join('topik as b', 'a.id_topik', 'b.id')
           ->join('jadwal_sidang_proposal as d', 'a.id', 'd.id_kelompok')
           ->join('ruang_sidangs as e', 'e.id', 'd.ruangan_id')
           ->where(function ($query) {
               $query->where('a.id_dosen_pembimbing_2', Auth::user()->user_id)
                     ->orWhere('a.id_dosen_penguji_1', Auth::user()->user_id)
                     ->orWhere('a.id_dosen_penguji_2', Auth::user()->user_id);
           })
           ->where('d.waktu', '>', $currentTime) // Waktu harus lebih besar dari sekarang
           ->orderBy('d.waktu') // Urutkan berdasarkan waktu terdekat
           ->first();
   }

   public static function getJadwalSidangTATerdekat()
   {
       $currentTime = Carbon::now(); // Waktu sekarang

       return DB::table('kelompok_mhs as a')
           ->select('a.*','d.*', 'e.*')
           ->join('jadwal_sidang_ta as d', 'a.id_mahasiswa', 'd.id_mahasiswa')
           ->join('ruang_sidangs as e', 'e.id', 'd.id_ruangan')
           ->where(function ($query) {
               $query->where('a.id_dosen_penguji_ta1', Auth::user()->user_id)
                     ->orWhere('a.id_dosen_penguji_ta2', Auth::user()->user_id);
           })
           ->where('d.waktu', '>', $currentTime) // Waktu harus lebih besar dari sekarang
           ->orderBy('d.waktu') // Urutkan berdasarkan waktu terdekat
           ->first();
   }



    public static function getDataBalancingPengujiProposal()
    {
        $loggedInUserId = Auth::user()->user_id;

        return DB::table('app_user')
            ->leftJoin('kelompok', function ($join) use ($loggedInUserId) {
                $join->on('app_user.user_id', '=', 'kelompok.id_dosen_penguji_1')
                    ->orOn('app_user.user_id', '=', 'kelompok.id_dosen_penguji_2');
            })
            ->where('app_user.user_id', $loggedInUserId) // Filter hanya data terkait dengan user yang sedang login
            ->where('app_user.role_id', '04') // Menambahkan kondisi role_id = '04'
            ->select(
                'app_user.user_id',
                'app_user.user_name',
                DB::raw('SUM(CASE WHEN kelompok.is_sidang_proposal = 0 THEN 1 ELSE 0 END) AS jumlah_kelompok_aktif_dibimbing'),
                DB::raw('SUM(CASE WHEN kelompok.is_sidang_proposal = 1 THEN 1 ELSE 0 END) AS jumlah_kelompok_tidak_aktif_dibimbing'),
                DB::raw('COUNT(DISTINCT kelompok.id) AS jumlah_total_kelompok_dibimbing')
            )
            ->orderBy('jumlah_kelompok_aktif_dibimbing') // Mengurutkan berdasarkan jumlah kelompok aktif yang belum selesai paling sedikit
            ->groupBy('app_user.user_id', 'app_user.user_name')
            ->first();
    }

    public static function getDataBalancingPengujiTA()
    {
        $loggedInUserId = Auth::user()->user_id;

        return DB::table('app_user')
            ->leftJoin('kelompok_mhs', function ($join) use ($loggedInUserId) {
                $join->on('app_user.user_id', '=', 'kelompok_mhs.id_dosen_penguji_ta1')
                    ->orOn('app_user.user_id', '=', 'kelompok_mhs.id_dosen_penguji_ta2');
            })
            ->where('app_user.user_id', $loggedInUserId) // Filter hanya data terkait dengan user yang sedang login
            ->where('app_user.role_id', '04') // Menambahkan kondisi role_id = '04'
            ->select(
                'app_user.user_id',
                'app_user.user_name',
                DB::raw('SUM(CASE WHEN kelompok_mhs.is_selesai = 0 THEN 1 ELSE 0 END) AS jumlah_mhs_aktif_dibimbing'),
                DB::raw('SUM(CASE WHEN kelompok_mhs.is_selesai = 1 THEN 1 ELSE 0 END) AS jumlah_mhs_tidak_aktif_dibimbing'),
                DB::raw('COUNT(DISTINCT kelompok_mhs.id) AS jumlah_total_mhs_dibimbing')
            )
            ->orderBy('jumlah_mhs_aktif_dibimbing') // Mengurutkan berdasarkan jumlah kelompok aktif yang belum selesai paling sedikit
            ->groupBy('app_user.user_id', 'app_user.user_name')
            ->first();
    }


}
