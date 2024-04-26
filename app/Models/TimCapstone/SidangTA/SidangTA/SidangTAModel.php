<?php

namespace App\Models\TimCapstone\SidangTA\SidangTA;

use App\Models\TimCapstone\BaseModel;
use Illuminate\Support\Facades\DB;

class SidangTAModel extends BaseModel
{
    // get all data
    public static function getData()
    {
        return DB::table('jadwal_periode_sidang_ta')
            ->get();
    }

    // get data with pagination
    public static function getDataPeriodeWithPagination()
    {
        return DB::table('jadwal_periode_sidang_ta')
            ->orderBy('id', 'desc') // Mengurutkan berdasarkan ID secara descending
            ->paginate(20);
    }


    // get data by id
    public static function getDataPeriodeById($id)
    {
        return DB::table('jadwal_periode_sidang_ta')->where('id', $id)->first();
    }

    public static function getDataMahasiswa($id)
    {
        return DB::table('pendaftaran_sidang_ta as a')
            ->select('a.*')
            ->where('a.id_mahasiswa', $id)->first();
    }

    public static function getPendaftarSidangTA($id)
    {
        return DB::table('pendaftaran_sidang_ta as a')
            -> select('a.*', 'b.*', 'c.*', 'd.*')
            -> join('app_user as b', 'a.id_mahasiswa', 'b.user_id')
            -> join('kelompok_mhs as c', 'a.id_mahasiswa', 'c.id_mahasiswa')
            -> join('kelompok as d', 'c.id_kelompok', 'd.id')
            ->where('a.id_periode', $id)
            ->get();
    }

    // pengecekan kelompok
    public static function pengecekan_kelompok_mahasiswa($user_id)
    {
        return DB::table('kelompok_mhs as a')
            ->select( 'b.*', 'c.nama as nama_topik', 'd.user_name', 'd.nomor_induk','a.*',)
            ->leftJoin('kelompok as b', 'a.id_kelompok', 'b.id')
            ->leftJoin('topik as c', 'a.id_topik_mhs', 'c.id')
            ->leftJoin('app_user as d', 'd.user_id', 'a.id_mahasiswa')
            ->where('a.id_mahasiswa', $user_id)
            ->orderBy('a.created_date', 'desc') // Urutkan berdasarkan created_date secara descending
            ->first();
    }


    public static function getIdKelompok($user_id)
    {
        return DB::table('kelompok_mhs as a')
            ->select( 'b.*',)
            ->leftJoin('kelompok as b', 'a.id_kelompok', 'b.id')
            ->where('a.id_mahasiswa', $user_id)
            ->orderBy('a.created_date', 'desc') // Urutkan berdasarkan created_date secara descending
            ->first();
    }

    public static function getAkunDosbingKelompok($id_kelompok)
    {
        return DB::table('app_user')
            ->join('kelompok', function ($join) {
                $join->on('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_1')
                    ->orOn('app_user.user_id', '=', 'kelompok.id_dosen_pembimbing_2');
            })
            ->where('kelompok.id', '=', $id_kelompok)
            ->orderByRaw('
                CASE
                    WHEN app_user.user_id = kelompok.id_dosen_pembimbing_1 THEN 1
                    WHEN app_user.user_id = kelompok.id_dosen_pembimbing_2 THEN 2
                END
            ')
            ->select('app_user.*')
            ->get();
    }

    public static function getAkunPengujiTAKelompok($id_mahasiswa)
    {
        return DB::table('app_user')
            ->join('kelompok_mhs', function ($join) {
                $join->on('app_user.user_id', '=', 'kelompok_mhs.id_dosen_penguji_ta1')
                    ->orOn('app_user.user_id', '=', 'kelompok_mhs.id_dosen_penguji_ta2');
            })
            ->where('kelompok_mhs.id_mahasiswa', '=', $id_mahasiswa)
            ->orderByRaw('
                CASE
                    WHEN app_user.user_id = kelompok_mhs.id_dosen_penguji_ta1 THEN 1
                    WHEN app_user.user_id = kelompok_mhs.id_dosen_penguji_ta2 THEN 2
                END
            ')
            ->select('app_user.*')
            ->get();
    }

    public static function getJadwalSidangTA($id_mahasiswa)
    {
        return DB::table('jadwal_sidang_ta as a')
        ->select('a.*', 'b.nama_ruang')
        ->join('ruang_sidangs as b', 'b.id', 'a.id_ruangan')
        ->where('id_mahasiswa', $id_mahasiswa)
        ->first();
    }

    public static function getJadwalSidangTAExitst($id_kelompok)
    {
        return DB::table('jadwal_sidang_ta as a')
        ->select('a.*', 'b.nama_ruang')
        ->join('ruang_sidangs as b', 'b.id', 'a.id_ruangan')
        ->where('a.id_kelompok', $id_kelompok)
        ->get();
    }

    public static function getJadwalSidangTASekelompok($id_kelompok)
    {
        return DB::table('jadwal_sidang_ta as a')
        ->select('a.*', 'b.nama_ruang')
        ->join('ruang_sidangs as b', 'b.id', 'a.id_ruangan')
        ->where('id_kelompok', $id_kelompok)
        ->first();
    }

    public static function getDosenPengujiTA()
    {
        return DB::table('app_user')
            ->where('app_user.role_id', '04')
            ->select('app_user.*')
            ->orderBy('app_user.user_name')
            ->get();
    }

    public static function getRuangSidang()
    {
        return DB::table('ruang_sidangs')
        ->get();
    }

    public static function insertjadwal_periode_sidang_ta($params)
    {
        return DB::table('jadwal_periode_sidang_ta')->insert($params);
    }
    public static function editjadwal_periode_sidang_ta($id, $params)
    {
        return DB::table('jadwal_periode_sidang_ta')->where('id', $id)->update($params);
    }

    public static function update($id, $params)
    {
        return DB::table('jadwal_periode_sidang_ta')->where('id', $id)->update($params);
    }

    public static function updatePendaftaranSidangTA($id, $params)
    {
        return DB::table('pendaftaran_sidang_ta')->where('id_mahasiswa', $id)->update($params);
    }

    public static function updateKelompokMhs($id, $params)
    {
        return DB::table('kelompok_mhs')->where('id_mahasiswa', $id)->update($params);
    }

    public static function delete($id)
    {
        return DB::table('jadwal_periode_sidang_ta')->where('id', $id)->delete();
    }

    public static function deleteJadwalSidangTA($id)
    {
        return DB::table('jadwal_sidang_ta')->where('id_periode', $id)->delete();
    }

    public static function deletePendaftaranSidangTA($id)
    {
        return DB::table('pendaftaran_sidang_ta')->where('id_periode', $id)->delete();
    }

    public static function getMahasiswaSidangTA($id)
    {
        return DB::table('pendaftaran_sidang_ta')->where('id_periode', $id)->get();
    }


    public static function listMahasiswaSendiri($id_mahasiswa, $id_kelompok)
    {
        return DB::table('kelompok_mhs as a')
            ->select( 'a.*', 'c.nama as nama_topik', 'd.*', 'b.*', )
            ->leftJoin('kelompok as b', 'a.id_kelompok', '=', 'b.id')
            ->leftJoin('topik as c', 'a.id_topik_mhs', '=', 'c.id')
            ->leftJoin('app_user as d', 'd.user_id', '=', 'a.id_mahasiswa')
            ->leftJoin('pendaftaran_sidang_ta as e', 'a.id_mahasiswa', '=', 'e.id_mahasiswa')
            ->where('a.id_mahasiswa', $id_mahasiswa)
            ->where('a.id_kelompok', $id_kelompok)
            ->orderBy('a.created_date', 'desc') // Urutkan berdasarkan created_date secara descending
            ->get();
    }

    public static function listMahasiswaDijadwalkan($id_kelompok)
    {
        return DB::table('kelompok_mhs as a')
            ->select('b.*', 'a.*', 'd.*')
            ->leftJoin('kelompok as b', 'a.id_kelompok', '=', 'b.id')
            ->leftJoin('app_user as d', 'd.user_id', '=', 'a.id_mahasiswa')
            ->leftJoin('jadwal_sidang_ta as f', function ($join) {
                $join->on('a.id', '=', 'f.id_kelompok_mhs');
            })
            ->where('a.id_kelompok', $id_kelompok)
            ->whereNotNull('f.id_kelompok_mhs')
            ->orderBy('a.created_date', 'desc') // Urutkan berdasarkan created_date secara descending
            ->get();
    }

    public static function listMahasiswa($id_kelompok, $id_already_scheduled)
    {
        return DB::table('kelompok_mhs as a')
            ->select('b.*', 'a.*', 'c.nama as nama_topik', 'd.*')
            ->leftJoin('kelompok as b', 'a.id_kelompok', '=', 'b.id')
            ->leftJoin('topik as c', 'a.id_topik_mhs', '=', 'c.id')
            ->leftJoin('app_user as d', 'd.user_id', '=', 'a.id_mahasiswa')
            ->leftJoin('pendaftaran_sidang_ta as e', 'a.id_mahasiswa', '=', 'e.id_mahasiswa')
            ->leftJoin('jadwal_sidang_ta as f', function($join) use ($id_already_scheduled) {
                $join->on('a.id_mahasiswa', '=', 'f.id_mahasiswa')
                     ->where('f.id', '<>', $id_already_scheduled); // Join and exclude id_already_scheduled
            })
            ->where('a.id_kelompok', $id_kelompok)
            ->whereNull('f.id') // Filter to include only mahasiswa without scheduled jadwal sidang
            ->orderBy('a.created_date', 'desc') // Urutkan berdasarkan created_date secara descending
            ->get();
    }

    public static function getDosenPengujiProposal($id_kelompok)
    {
        return DB::table('app_user')
            ->where('app_user.role_id', '04')
            ->select('app_user.*')
            ->orderBy('app_user.user_name')
            ->get();
    }

    // add dosen
    public static function getKelompokMhsById($id_mahasiswa)
    {
        return DB::table('kelompok_mhs as a')
        ->where('id_mahasiswa', $id_mahasiswa)
        ->first();
    }


    public static function insertJadwalSidangTA($params)
    {
        return DB::table('jadwal_sidang_ta')->insert($params);
    }

    public static function updateJadwalSidangTA($id, $params)
    {
        return DB::table('jadwal_sidang_ta')
        ->where('id', $id)
        ->update($params);
    }

    public static function updateAllMahasiswaKelompok($id_kelompok, $params)
    {
        return DB::table('kelompok_mhs')
            ->where('id_kelompok', $id_kelompok)
            ->where('is_mendaftar_sidang', '1') // Tambahkan kondisi is_mendaftar_sidang = 1
            ->update($params);
    }

    public static function getAnggotaKelompok($id_kelompok)
    {
        return DB::table('kelompok_mhs as a')
            ->select('b.id as id_kelompok', 'a.*', )
            ->join('kelompok as b', 'a.id_kelompok', '=', 'b.id')
            ->where('b.id', $id_kelompok)
            ->get();
    }

    public static function getDataPendaftaranSidangTA($id_mahasiswa)
    {
        return DB::table('pendaftaran_sidang_ta as a')
            ->where('a.id_mahasiswa', $id_mahasiswa)
            ->get();
    }

    public static function checkOverlap($waktuMulai, $waktuSelesai, $id_ruangan, $currentIdKelompok)
    {
        // Periksa apakah terdapat jadwal yang bertabrakan
        $overlapRecord = DB::table('jadwal_sidang_ta')
            ->where('id_ruangan', $id_ruangan)
            ->where(function ($query) use ($waktuMulai, $waktuSelesai, $currentIdKelompok) {
                $query->where(function ($query) use ($waktuMulai, $waktuSelesai) {
                    $query->whereBetween('waktu', [$waktuMulai, $waktuSelesai])
                        ->orWhereBetween('waktu_selesai', [$waktuMulai, $waktuSelesai]);
                })
                ->orWhere(function ($query) use ($waktuMulai, $waktuSelesai, $currentIdKelompok) {
                    $query->where('waktu', '<', $waktuMulai)
                        ->where('waktu_selesai', '>', $waktuSelesai);
                });
            })
            ->where('id_kelompok', '<>', $currentIdKelompok) // Exclude records with the same kelompok id
            ->first(); // Retrieve the first matching record

        return $overlapRecord;
    }


    public static function checkOverlapPembimbing1($waktuMulai, $waktuSelesai, $dosenPembimbing1Id)
    {
        return DB::table('jadwal_sidang_ta')
            ->where(function ($query) use ($dosenPembimbing1Id) {
                $query->where('id_dosen_penguji_ta1', $dosenPembimbing1Id)
                    ->orWhere('id_dosen_penguji_ta2', $dosenPembimbing1Id)
                    ->orWhere('id_dosen_pembimbing_1', $dosenPembimbing1Id);
            })
            ->where(function ($query) use ($waktuMulai, $waktuSelesai, $currentIdKelompok) {
                $query->where(function ($query) use ($waktuMulai, $waktuSelesai) {
                    $query->whereBetween('waktu', [$waktuMulai, $waktuSelesai])
                          ->orWhereBetween('waktu_selesai', [$waktuMulai, $waktuSelesai])
                          ->orWhere(function ($query) use ($waktuMulai, $waktuSelesai) {
                              $query->where('waktu', '<', $waktuMulai)
                                    ->where('waktu_selesai', '>', $waktuSelesai);
                          });
                })
                ->where('id_kelompok', '<>', $currentIdKelompok); // Exclude records with the same kelompok id
            })
            ->first();
    }

    public static function checkOverlapPenguji1($waktuMulai, $waktuSelesai, $dosenPenguji1Id, $currentIdKelompok)
    {
        return DB::table('jadwal_sidang_ta')
            ->where(function ($query) use ($dosenPenguji1Id) {
                $query->where('id_dosen_penguji_ta1', $dosenPenguji1Id)
                    ->orWhere('id_dosen_penguji_ta2', $dosenPenguji1Id)
                    ->orWhere('id_dosen_pembimbing_1', $dosenPenguji1Id);
            })
            ->where(function ($query) use ($waktuMulai, $waktuSelesai, $currentIdKelompok) {
                $query->where(function ($query) use ($waktuMulai, $waktuSelesai) {
                    $query->whereBetween('waktu', [$waktuMulai, $waktuSelesai])
                          ->orWhereBetween('waktu_selesai', [$waktuMulai, $waktuSelesai])
                          ->orWhere(function ($query) use ($waktuMulai, $waktuSelesai) {
                              $query->where('waktu', '<', $waktuMulai)
                                    ->where('waktu_selesai', '>', $waktuSelesai);
                          });
                })
                ->where('id_kelompok', '<>', $currentIdKelompok); // Exclude records with the same kelompok id
            })
            ->first();
    }


    public static function checkOverlapPenguji2($waktuMulai, $waktuSelesai, $dosenPenguji2Id, $currentIdKelompok)
{
    return DB::table('jadwal_sidang_ta')
        ->where(function ($query) use ($dosenPenguji2Id) {
            $query->where('id_dosen_penguji_ta1', $dosenPenguji2Id)
                ->orWhere('id_dosen_penguji_ta2', $dosenPenguji2Id)
                ->orWhere('id_dosen_pembimbing_1', $dosenPenguji2Id);
        })
        ->where(function ($query) use ($waktuMulai, $waktuSelesai, $currentIdKelompok) {
            $query->where(function ($query) use ($waktuMulai, $waktuSelesai) {
                $query->whereBetween('waktu', [$waktuMulai, $waktuSelesai])
                      ->orWhereBetween('waktu_selesai', [$waktuMulai, $waktuSelesai])
                      ->orWhere(function ($query) use ($waktuMulai, $waktuSelesai) {
                          $query->where('waktu', '<', $waktuMulai)
                                ->where('waktu_selesai', '>', $waktuSelesai);
                      });
            })
            ->where('id_kelompok', '<>', $currentIdKelompok); // Exclude records with the same kelompok id
        })
        ->first();
}


}
