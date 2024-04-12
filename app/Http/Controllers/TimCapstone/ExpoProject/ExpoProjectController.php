<?php

namespace App\Http\Controllers\TimCapstone\ExpoProject;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\ExpoProject\ExpoProjectModel;
use Carbon\Carbon;


class ExpoProjectController extends BaseController
{

    public function index()
    {
        // Get data with pagination
        $rs_expo = ExpoProjectModel::getDataWithPagination();
        $rs_siklus = ExpoProjectModel::getSiklus();

        foreach ($rs_expo as $expo_project) {
            if ($expo_project != null) {
                $dateExpo = strtotime($expo_project->waktu);

                $expo_project->hari_expo = strftime('%A', $dateExpo);
                $expo_project->hari_expo = $this->convertDayToIndonesian($expo_project->hari_expo);
                $expo_project->tanggal_expo = date('d-m-Y', $dateExpo);
                $expo_project->waktu_expo = date('H:i:s', $dateExpo);

            }
        }

        // Data
        $data = [
            'rs_expo' => $rs_expo,
            'rs_siklus' => $rs_siklus
        ];

        return view('tim_capstone.expo-project.index', $data);
    }

    public function addExpoProject()
    {
        // Get data with pagination
        $rs_siklus = ExpoProjectModel::getSiklus();


        // Data
        $data = [
            'rs_siklus' => $rs_siklus
        ];

        return view('tim_capstone.expo-project.add', $data);
    }

    public function addExpoProjectProcess(Request $request)
    {
        // Validasi tanggal
        $tanggalMulai = Carbon::parse($request->tanggal_mulai);
        $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
        $waktuEvent = Carbon::parse($request->waktu);

        // Memeriksa apakah tanggal mulai lebih awal dari tanggal selesai
        if ($tanggalMulai >= $tanggalSelesai) {
            // Flash message untuk kesalahan
            session()->flash('danger', 'Tanggal mulai harus lebih awal dari tanggal selesai.');
            return redirect('/admin/expo-project/add')->withInput();
        }

        // Memeriksa apakah tanggal mulai sebelum waktu event
        if ($tanggalSelesai >= $waktuEvent) {
            // Flash message untuk kesalahan
            session()->flash('danger', 'Tanggal selesai harus sebelum waktu expo.');
            return redirect('/admin/expo-project/add')->withInput();
        }

        // Data valid, lanjutkan menyimpan
        $params = [
            'id_siklus' => $request->id_siklus,
            'tempat' => $request->tempat,
            'waktu' => $waktuEvent,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => now()
        ];

        // Proses menyimpan data
        $insert = ExpoProjectModel::insertExpoProject($params);
        if ($insert) {
            // Flash message untuk sukses
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/expo-project');
        } else {
            // Flash message untuk kegagalan
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/expo-project/add')->withInput();
        }
    }

    public function editExpoProject($id)
    {
         // get data
         $expo = ExpoProjectModel::getDataEditById($id);
         $rs_siklus = ExpoProjectModel::getSiklus();

         // check
         if (empty($expo)) {
             // flash message
             session()->flash('danger', 'Data tidak ditemukan.');
             return redirect('/admin/expo-project');
         }

         // data
         $data = [
            'expo' => $expo,
            'rs_siklus' => $rs_siklus
        ];

         // view
         return view('tim_capstone.expo-project.edit', $data);
    }

    public function editExpoProjectProcess(Request $request)
    {
        // Validasi tanggal
        $tanggalMulai = Carbon::parse($request->tanggal_mulai);
        $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
        $waktuEvent = Carbon::parse($request->waktu);

        // Memeriksa apakah tanggal mulai lebih awal dari tanggal selesai
        if ($tanggalMulai >= $tanggalSelesai) {
            // Flash message untuk kesalahan
            session()->flash('danger', 'Tanggal mulai harus lebih awal dari tanggal selesai.');
            return redirect('/admin/expo-project/edit/' . $request->id)->withInput();
        }

        // Memeriksa apakah tanggal mulai sebelum waktu event
        if ($tanggalSelesai >= $waktuEvent) {
            // Flash message untuk kesalahan
            session()->flash('danger', 'Tanggal selesai harus sebelum waktu expo.');
            return redirect('/admin/expo-project/edit/' . $request->id)->withInput();
        }

        // Data valid, lanjutkan menyimpan
        $params = [
            'id_siklus' => $request->id_siklus,
            'tempat' => $request->tempat,
            'waktu' => $waktuEvent,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => now()
        ];

        // Proses update data
        if (ExpoProjectModel::updateExpoProject($request->id, $params)) {
            // Flash message untuk sukses
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/expo-project');
        } else {
            // Flash message untuk kegagalan
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/expo-project/edit/' . $request->id)->withInput();
        }
    }

    public function deleteExpoProjectProcess($id)
    {
        $pendaftaranExpo = ExpoProjectModel::getKelompokMendaftar($id);

        if ($pendaftaranExpo != null) {
            // Delete pendaftaran expo
            if (ExpoProjectModel::deletePendaftaranExpo($id)) {
                // Process delete expo project
                if (ExpoProjectModel::deleteExpoProject($id)) {
                    // Update status kelompok
                    $paramKelompok = ['status_kelompok' => "C500 Telah Disetujui!", 'status_expo' => NULL];
                    if (ExpoProjectModel::updateKelompok($pendaftaranExpo->id_kelompok, $paramKelompok)) {
                        // Flash message for success
                        session()->flash('success', 'Data berhasil dihapus.');
                    } else {
                        // Flash message for failure
                        session()->flash('danger', 'Gagal memperbarui status kelompok.');
                    }
                } else {
                    // Flash message for failure
                    session()->flash('danger', 'Gagal menghapus data proyek expo.');
                }
            } else {
                // Flash message for failure
                session()->flash('danger', 'Gagal menghapus pendaftaran expo.');
            }
        } else {
            // Directly delete expo project
            if (ExpoProjectModel::deleteExpoProject($id)) {
                // Flash message for success
                session()->flash('success', 'Data berhasil dihapus.');
            } else {
                // Flash message for failure
                session()->flash('danger', 'Gagal menghapus data proyek expo.');
            }
        }

        return redirect('/admin/expo-project');

    }

    public function detailExpoProject($id)
    {
        // Get data with pagination
        $expo_project = ExpoProjectModel::getDataById($id);

        if ($expo_project != null) {
            $dateExpo = strtotime($expo_project->waktu);

            $expo_project->hari_expo = strftime('%A', $dateExpo);
            $expo_project->hari_expo = $this->convertDayToIndonesian($expo_project->hari_expo);
            $expo_project->tanggal_expo = date('d-m-Y', $dateExpo);
            $expo_project->waktu_expo = date('H:i:s', $dateExpo);

        }

        // Check
        if (empty($expo_project)) {
            // Flash message
            session()->flash('danger', 'Belum ada kelompok yang mendaftar!');
            return redirect('/admin/expo-project');
        }

        $rs_kel_expo = ExpoProjectModel::getExpoDaftar($id);

        foreach ($rs_kel_expo as $kel_expo) {
            $kel_expo->status_expo_color = $this->getStatusColor($kel_expo->status_expo);
        }

        // Data
        $data = [
            'expo' => $expo_project,
            'rs_kel_expo' => $rs_kel_expo
        ];

        // View
        return view('tim_capstone.expo-project.detail', $data);
    }

    public function terimaKelompok($id)
    {
        // Params
        $params = ['status' => 'Kelompok Disetujui Expo!'];

        // Get data pendaftaran
        $dataPendaftaranExpo = ExpoProjectModel::getDataPendaftaranExpo($id);

        if ($dataPendaftaranExpo) { // Periksa apakah data pendaftaran ditemukan
            // Process
            if (ExpoProjectModel::updateExpoProjectKelompok($id, $params)) {
                $paramKelompok = ['status_kelompok' => "Kelompok Disetujui Expo!", 'status_expo' => "Kelompok Disetujui Expo!"];
                if (ExpoProjectModel::updateKelompok($dataPendaftaranExpo->id_kelompok, $paramKelompok)) {
                    // Flash message for success
                    session()->flash('success', 'Data berhasil disimpan.');
                    return back();
                } else {
                    // Flash message for failure
                    session()->flash('danger', 'Gagal memperbarui status kelompok.');
                    return back();
                }
            } else {
                // Flash message for failure
                session()->flash('danger', 'Gagal memperbarui status pendaftaran.');
                return back();
            }
        } else {
            // Flash message for failure
            session()->flash('danger', 'Data pendaftaran tidak ditemukan.');
            return back();
        }
    }


    public function tolakKelompok($id)
    {
        // Params
        $params = ['status' => 'Kelompok Tidak Disetujui Expo!',];

        // Get data pendaftaran
        $dataPendaftaranExpo = ExpoProjectModel::getDataPendaftaranExpo($id);

        if ($dataPendaftaranExpo) { // Periksa apakah data pendaftaran ditemukan
            // Process
            if (ExpoProjectModel::updateExpoProjectKelompok($id, $params)) {
                $paramKelompok = ['status_kelompok' => "Kelompok Tidak Disetujui Expo!",
                    'status_expo' => 'Kelompok Tidak Disetujui Expo!',
            ];
                if (ExpoProjectModel::updateKelompok($dataPendaftaranExpo->id_kelompok, $paramKelompok)) {
                    // Flash message for success
                    session()->flash('success', 'Data berhasil disimpan.');
                    return back();
                } else {
                    // Flash message for failure
                    session()->flash('danger', 'Gagal memperbarui status kelompok.');
                    return back();
                }
            } else {
                // Flash message for failure
                session()->flash('danger', 'Gagal memperbarui status pendaftaran.');
                return back();
            }
        } else {
            // Flash message for failure
            session()->flash('danger', 'Data pendaftaran tidak ditemukan.');
            return back();
        }
    }

    public function toLulusExpo($id)
    {
        // get data
        $dataKelompok = ExpoProjectModel::getDataKelompok($id);

        // if exist
        if ($dataKelompok != null) {

            $paramKelompok = [
                'status_kelompok' => 'Lulus Expo Project!',
                'status_expo' => 'Lulus Expo Project!',
                'is_selesai' => 1,
                'is_lulus_expo' => 1,
            ];

            $updateKelompok = ExpoProjectModel::updateKelompok($dataKelompok -> id, $paramKelompok);

            if ($updateKelompok) {
                $paramKelompokMhs = [
                    'status_individu' => 'Lulus Expo Project!',
                    'status_tugas_akhir' => 'Belum Mendaftar Sidang TA!',
                ];

                ExpoProjectModel::updateKelompokMhsByKelompok($dataKelompok -> id, $paramKelompokMhs);
                session()->flash('success', 'Data berhasil diperbaharui!');
                return back();
            } else{
                session()->flash('danger', 'Data tidak ditemukan.');
                return back();
            }


        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return back();
        }
    }

    public function toGagalExpo($id)
    {
        // get data
        $dataKelompok = ExpoProjectModel::getDataKelompok($id);

        // if exist
        if ($dataKelompok != null) {

            $paramKelompok = [
                'status_kelompok' => 'Gagal Expo Project!',
                'status_expo' => 'Gagal Expo Project!',
            ];

            $updateKelompok = ExpoProjectModel::updateKelompok($dataKelompok -> id, $paramKelompok);

            if ($updateKelompok) {
                $paramKelompokMhs = [
                    'status_individu' => NULL,
                    'status_tugas_akhir' => NULL,
                ];

                ExpoProjectModel::updateKelompokMhsByKelompok($dataKelompok -> id, $paramKelompokMhs);
                session()->flash('success', 'Data berhasil diperbaharui!');
                return back();
            } else{
                session()->flash('danger', 'Data tidak ditemukan.');
                return back();
            }

        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return back();
        }
    }

    private function convertDayToIndonesian($day)
    {
        $dayMappings = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        return array_key_exists($day, $dayMappings) ? $dayMappings[$day] : $day;
    }

}
