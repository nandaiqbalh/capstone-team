<?php

namespace App\Http\Controllers\Mahasiswa\Expo_Mahasiswa;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Mahasiswa\Expo_Mahasiswa\MahasiswaExpoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MahasiswaExpoController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //coba comment

    public function index()
    {

        $cekExpo = MahasiswaExpoModel::cekExpo();

        $kelompok = MahasiswaExpoModel::pengecekan_kelompok_mahasiswa(Auth::user()->user_id);

        if ($kelompok != null) {
            $akun_mahasiswa = MahasiswaExpoModel::getAkunByID(Auth::user()->user_id);
            $siklusSudahPunyaKelompok = MahasiswaExpoModel::checkApakahSiklusMasihAktif($akun_mahasiswa->id_siklus);
            $id_kelompok = MahasiswaExpoModel::idKelompok(Auth::user()->user_id);
            // get data expo
            $kelompok->status_expo_color = $this->getStatusColor($kelompok->status_expo);

            $showButton = true;

            if ($kelompok->status_expo == "Gagal Expo Project") {
                $showButton = true;
                $rs_expo = MahasiswaExpoModel::getDataExpo();

            } else if ($kelompok->status_expo == "Kelompok Tidak Disetujui Expo") {
                $showButton = true;
                $rs_expo = MahasiswaExpoModel::getDataExpo();

            } else {
                if ($cekExpo != null) {
                    $rs_expo = MahasiswaExpoModel::getExpoById($cekExpo->id_expo);
                    $latest_expo = MahasiswaExpoModel::getLatestExpo();

                    if ($rs_expo->id == $latest_expo->id && $rs_expo->tanggal_selesai > now()) {
                        $showButton = true;
                    } else {
                        $showButton = false;
                    }
                } else {
                    $rs_expo = MahasiswaExpoModel::getDataExpo();
                }
            }

            if ($rs_expo != null) {
                $waktuExpo = strtotime($rs_expo->waktu);

                $rs_expo->hari_expo = strftime('%A', $waktuExpo);
                $rs_expo->hari_expo = $this->convertDayToIndonesian($rs_expo->hari_expo);
                $rs_expo->tanggal_expo = date('d-m-Y', $waktuExpo);
                $rs_expo->waktu_expo = date('H:i:s', $waktuExpo);

                $tanggalSelesai = strtotime($rs_expo->tanggal_selesai);

                $rs_expo->hari_batas = strftime('%A', $tanggalSelesai);
                $rs_expo->hari_batas = $this->convertDayToIndonesian($rs_expo->hari_batas);
                $rs_expo->tanggal_batas = date('d-m-Y', $tanggalSelesai);
                $rs_expo->waktu_batas = date('H:i:s', $tanggalSelesai);
            }

            $kelengkapanExpo = MahasiswaExpoModel::kelengkapanExpo();

            // data
            $data = [
                'cekExpo' => $cekExpo,
                'showButton' => $showButton,
                'rs_expo' => $rs_expo,
                'kelompok' => $kelompok,
                'kelengkapan' => $kelengkapanExpo,
                'siklus_sudah_punya_kelompok' => $siklusSudahPunyaKelompok,
            ];
        } else {
            $data = [

                'kelompok' => $kelompok,

            ];
        }

        // view
        return view('mahasiswa.expo-mahasiswa.detail', $data);
    }

    public function daftarExpo(Request $request)
    {
        // Validasi user
        if (!$request->user()) {
            return redirect()->back()->with('danger', 'Gagal mendapatkan data user');
        }

        // Validasi kelompok mahasiswa
        $kelompok = MahasiswaExpoModel::pengecekan_kelompok_mahasiswa($request->user()->user_id);
        if (!$kelompok) {
            return redirect()->back()->with('danger', 'Anda belum memiliki kelompok');
        }

        // Validasi berkas Capstone (file_name_c500)
        if ($kelompok->file_status_c100 != "C100 Telah Disetujui" && $kelompok->file_status_c100 != "Final C100 Telah Disetujui") {
            return redirect()->back()->with('danger', 'Dokumen C100 belum disetujui kedua dosen pembimbing');
        }

        if ($kelompok->file_status_c200 != "C200 Telah Disetujui") {
            return redirect()->back()->with('danger', 'Dokumen C200 belum disetujui kedua dosen pembimbing');
        }

        if ($kelompok->file_status_c300 != "C300 Telah Disetujui") {
            return redirect()->back()->with('danger', 'Dokumen C300 belum disetujui kedua dosen pembimbing');
        }

        if ($kelompok->file_status_c400 != "C400 Telah Disetujui") {
            return redirect()->back()->with('danger', 'Dokumen C400 belum disetujui kedua dosen pembimbing');
        }

        if ($kelompok->file_status_c500 != "C500 Telah Disetujui") {
            return redirect()->back()->with('danger', 'Dokumen C500 belum disetujui kedua dosen pembimbing');
        }

        $existingFile = MahasiswaExpoModel::fileMHS(Auth::user()->user_id);

        if ($existingFile->file_status_lta != "Laporan TA Telah Disetujui") {
            return redirect()->back()->with('danger', 'Laporan TA belum disetujui kedua dosen pembimbing');
        }

        if ($existingFile->file_status_mta != "Makalah TA Telah Disetujui") {
            return redirect()->back()->with('danger', 'Makalah TA belum disetujui kedua dosen pembimbing');
        }

        // Validasi laporan TA sudah diunggah
        if (!$existingFile || !$existingFile->file_name_laporan_ta) {
            return redirect()->back()->with('danger', 'Lengkapi laporan TA terlebih dahulu sebelum mendaftar expo');
        }

        // Validasi judul TA mahasiswa (maksimum 20 kata)
        $judulTaMhs = $request->input('judul_ta_mhs');
        $wordCount = str_word_count($judulTaMhs);

        if ($wordCount > 20) {
            return redirect()->back()->with('danger', 'Judul TA maksimal 20 kata');
        }

        // Validasi link upload sebagai URL
        $validatedData = $request->validate([
            'link_berkas_expo' => 'required|url',
        ], [
            'link_berkas_expo.required' => 'Kolom link berkas expo harus diisi.',
            'link_berkas_expo.url' => 'Format link berkas expo tidak valid.',
        ]);

        // Persiapan data pendaftaran expo
        $params = [
            'id_kelompok' => $kelompok->id,
            'id_expo' => $request->id_expo,
            'id_siklus' => $kelompok->id_siklus,
            'status' => 'Menunggu Persetujuan Expo',
            'created_by' => $request->user()->user_id,
            'created_date' => now(),
        ];

        // Proses penyimpanan data pendaftaran expo
        if (DB::table('pendaftaran_expo')->updateOrInsert(['id_kelompok' => $kelompok->id], $params)) {
            // Update status kelompok dan mahasiswa
            $kelompokParams = [
                'link_berkas_expo' => $validatedData['link_berkas_expo'],
                'status_kelompok' => "Menunggu Persetujuan Expo",
                'status_expo' => "Menunggu Persetujuan Expo",
                'is_selesai' => '0',
                'is_lulus_expo' => '0',

            ];
            MahasiswaExpoModel::updateKelompokById($kelompok->id_kelompok, $kelompokParams);

            $kelompokMHSParams = [
                'judul_ta_mhs' => $judulTaMhs,
                'status_individu' => "Menunggu Persetujuan Expo",
            ];
            $statusDaftar = MahasiswaExpoModel::updateKelompokMHS($request->user()->user_id, $kelompokMHSParams);

            return redirect()->back()->with('success', 'Berhasil mendaftarkan expo');
        } else {
            return redirect()->back()->with('danger', 'Gagal menyimpan data pendaftaran expo');
        }
    }

}
