<?php

namespace App\Http\Controllers\Mahasiswa\TugasAkhir_Mahasiswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Mahasiswa\TugasAkhir_Mahasiswa\MahasiswaTugasAkhirModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PDO;

class MahasiswaTugasAkhirController extends BaseController
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Get data kelompok
        $kelompok = MahasiswaTugasAkhirModel::pengecekan_kelompok_mahasiswa($user->user_id);
        $jadwal_sidang = MahasiswaTugasAkhirModel::sidangTugasAkhirByMahasiswa($user->user_id);
        $statusPendaftaran = MahasiswaTugasAkhirModel::getStatusPendaftaran($user->user_id);


        if ($kelompok != null ) {
            $akun_mahasiswa = MahasiswaTugasAkhirModel::getAkunByID(Auth::user()->user_id);
            $data_mahasiswa = MahasiswaTugasAkhirModel::getDataMahasiswa(Auth::user()->user_id);

            $rs_dosbing = MahasiswaTugasAkhirModel::getAkunDosbingKelompok($kelompok->id_kelompok);
            $rs_dospengta = MahasiswaTugasAkhirModel::getAkunDospengTa($akun_mahasiswa ->user_id);
            $kelompok -> status_tugas_akhir_color = $this->getStatusColor($kelompok->status_tugas_akhir);

            foreach ($rs_dosbing as $dosbing) {

                if ($dosbing->user_id == $kelompok->id_dosen_pembimbing_1) {
                    $dosbing->jenis_dosen = 'Pembimbing 1';
                    $dosbing->status_dosen = $kelompok->status_dosen_pembimbing_1;
                } else if ($dosbing->user_id == $kelompok->id_dosen_pembimbing_2) {
                    $dosbing->jenis_dosen = 'Pembimbing 2';
                    $dosbing->status_dosen = $kelompok->status_dosen_pembimbing_2;
                }
            }

            foreach ($rs_dospengta as $dospengta) {

                if ($dospengta->user_id == $data_mahasiswa->id_dosen_penguji_ta1) {
                    $dospengta->jenis_dosen = 'Penguji 1';
                    $dospengta->status_dosen = $data_mahasiswa->status_dosen_penguji_ta1;
                } else if ($dospengta->user_id == $data_mahasiswa->id_dosen_penguji_ta2) {
                    $dospengta->jenis_dosen = 'Penguji 2';
                    $dospengta->status_dosen = $data_mahasiswa->status_dosen_penguji_ta2;
                }
            }

            $showButton = true;

            if ($data_mahasiswa -> status_tugas_akhir == "Gagal Sidang TA") {
                $showButton = true;
                $periodeAvailable = MahasiswaTugasAkhirModel::getPeriodeAvailable();

                $jadwal_sidang = null;
            } else {
                if ($statusPendaftaran != null) {
                    $periodeAvailable = MahasiswaTugasAkhirModel::getPeriodeSidangById($statusPendaftaran->id_periode);
                    $latest_sidang = MahasiswaTugasAkhirModel::getLatestPeriode();

                    if ($periodeAvailable ->id == $latest_sidang ->id && $periodeAvailable -> tanggal_selesai > now() ) {
                        if ($data_mahasiswa-> status_tugas_akhir == "Lulus Sidang TA") {
                            $showButton = false;
                        } else {
                            $showButton = true;
                        }
                    } else {
                        $showButton = false;
                    }
                } else {
                    $periodeAvailable = MahasiswaTugasAkhirModel::getPeriodeAvailable();
                }
            }

            if ($jadwal_sidang == null ) {
                if ($periodeAvailable != null) {
                    // BATAS PENDAFTARAN
                    $waktubatas = strtotime($periodeAvailable->tanggal_selesai);

                    $periodeAvailable->hari_batas = strftime('%A', $waktubatas); // Day

                    // Konversi nama hari ke bahasa Indonesia
                    $periodeAvailable->hari_batas = $this->convertDayToIndonesian($periodeAvailable->hari_batas);

                    $periodeAvailable->tanggal_batas = date('d-m-Y', $waktubatas); // Date
                    $periodeAvailable->waktu_batas = date('H:i:s', $waktubatas); // Time
                }
            } else {
                if ($periodeAvailable != null) {
                    $waktubatas = strtotime($periodeAvailable->tanggal_selesai);

                    $periodeAvailable->hari_batas = strftime('%A', $waktubatas); // Day

                    // Konversi nama hari ke bahasa Indonesia
                    $periodeAvailable->hari_batas = $this->convertDayToIndonesian($periodeAvailable->hari_batas);

                    $periodeAvailable->tanggal_batas = date('d-m-Y', $waktubatas); // Date
                    $periodeAvailable->waktu_batas = date('H:i:s', $waktubatas); // Time

                    // Extract day, date, and time from the "waktu" property
                    $waktuSidang = strtotime($jadwal_sidang->waktu);

                    $jadwal_sidang->hari_sidang = strftime('%A', $waktuSidang);
                    $jadwal_sidang->hari_sidang = $this->convertDayToIndonesian($jadwal_sidang->hari_sidang);
                    $jadwal_sidang->tanggal_sidang = date('d-m-Y', $waktuSidang);
                    $jadwal_sidang->waktu_sidang = date('H:i:s', $waktuSidang);
                }

                $statusPendaftaran = MahasiswaTugasAkhirModel::getStatusPendaftaran($user->user_id);
            }

            $data = [
                'kelompok' => $kelompok,
                'jadwal_sidang' => $jadwal_sidang,
                'rs_dosbing' => $rs_dosbing,
                'showButton' => $showButton,
                'rs_dospengta' => $rs_dospengta,
                'periode' => $periodeAvailable,
                'status_pendaftaran' => $statusPendaftaran,
                'akun_mahasiswa' => $akun_mahasiswa,
                'data_mahasiswa' => $data_mahasiswa

            ];
        } else {
            $data = [
                'kelompok' => $kelompok,

            ];
        }


        return view('mahasiswa.tugas-akhir.detail', $data);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function daftarTA(Request $request)
     {
         // Validasi input data
         $validatedData = $request->validate([
             'judul_ta_mhs' => ['required', 'string', 'max:255'], // Memastikan judul TA tidak melebihi 255 karakter
             'link_upload' => ['required', 'url'], // Memastikan link merupakan URL yang valid
         ], [
             'judul_ta_mhs.required' => 'Judul Tugas Akhir harus diisi.',
             'judul_ta_mhs.max' => 'Judul Tugas Akhir tidak boleh lebih dari 20 kata.',
             'link_upload.required' => 'Link upload harus diisi.',
             'link_upload.url' => 'Format link upload tidak valid. Pastikan menyertakan protocol (contoh: http:// atau https://).',
         ]);

         // Memecah judul TA menjadi kata-kata untuk menghitung jumlah kata
         $judulTa = $validatedData['judul_ta_mhs'];
         $jumlahKata = str_word_count($judulTa);

         // Validasi jumlah kata maksimal
         if ($jumlahKata > 20) {
             return redirect()->back()->with('danger', 'Judul Tugas Akhir tidak boleh lebih dari 20 kata.');
         }

         $user = $request->user();
         $periodeAvailable = MahasiswaTugasAkhirModel::getPeriodeAvailable();

         $kelompok = MahasiswaTugasAkhirModel::pengecekan_kelompok_mahasiswa(Auth::user()-> user_id);

         if ($kelompok->status_expo != "Lulus Expo Project") {
             return redirect()->back()->with('danger', 'Anda harus lulus expo terlebih dahulu');
         }

         // Cek apakah laporan TA sudah diunggah
         $existingFile = MahasiswaTugasAkhirModel::fileMHS($user->user_id);
         if (!$existingFile->file_name_laporan_ta) {
             return redirect()->back()->with('danger', 'Lengkapi terlebih dahulu laporan TA sebelum mendaftar sidang Tugas Akhir.');
         }

         if (!$existingFile->file_name_makalah) {
            return redirect()->back()->with('danger', 'Lengkapi terlebih dahulu makalah TA sebelum mendaftar sidang Tugas Akhir.');
        }

        $statusesAllowed = ["Laporan TA Telah Disetujui", "Final Laporan TA Telah Disetujui"];

        if (!in_array($existingFile->file_status_lta, $statusesAllowed)) {
            return redirect()->back()->with('danger', 'Laporan TA belum disetujui kedua dosen pembimbing');
        }

        if ($existingFile->file_status_mta != "Makalah TA Telah Disetujui") {
            return redirect()->back()->with('danger', 'Makalah TA belum disetujui kedua dosen pembimbing');
        }

         // Registration parameters
         $registrationParams = [
             'id_mahasiswa' => $user->user_id,
             'id_periode' => $periodeAvailable->id,
             'status' => 'Menunggu Persetujuan Pendaftaran Sidang',
             'created_by' => $user->user_id,
             'created_date' => now(), // Gunakan fungsi helper Laravel untuk tanggal dan waktu saat ini
         ];

         // Gunakan updateOrInsert untuk menangani penyisipan dan pembaruan
         if (DB::table('pendaftaran_sidang_ta')->updateOrInsert(
             ['id_mahasiswa' => $user->user_id], // Kondisi untuk memeriksa apakah data sudah ada
             $registrationParams // Data yang akan diperbarui atau disisipkan
         )) {
             // Perbarui kelompok mahasiswa
             $berkasParams = [
                 'link_upload' => $validatedData['link_upload'],
                 'judul_ta_mhs' => $validatedData['judul_ta_mhs'],
                 'is_mendaftar_sidang' => '1',
                 'status_individu' => 'Menunggu Persetujuan Pendaftaran Sidang',
                 'status_tugas_akhir' => 'Menunggu Persetujuan Pendaftaran Sidang',
             ];

             if (MahasiswaTugasAkhirModel::updateKelompokMHS($user->user_id, $berkasParams)) {
                 return redirect()->back()->with('success', 'Berhasil mendaftarkan sidang Tugas Akhir');
             } else {
                 return redirect()->back()->with('danger', 'Gagal memperbarui data pendaftaran');
             }
         } else {
             return redirect()->back()->with('danger', 'Gagal mendaftarkan sidang Tugas Akhir');
         }
     }
}
