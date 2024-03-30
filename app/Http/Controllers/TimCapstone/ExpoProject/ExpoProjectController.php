<?php

namespace App\Http\Controllers\TimCapstone\ExpoProject;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\ExpoProject\ExpoProjectModel;

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

    public function addExpoProjectProcess(Request $request)
    {
        $params = [
            'id_siklus' => $request->id_siklus,
            'tempat' => $request->tempat,
            'waktu' => $request->waktu,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => now()
        ];

        // Process
        $insert = ExpoProjectModel::insertExpoProject($params);
        if ($insert) {
            // Flash message for success
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/expo-project');
        } else {
            // Flash message for failure
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/expo-project')->withInput();
        }
    }

    public function editExpoProjectProcess(Request $request)
    {
        // Params
        $params = [
            'id_siklus' => $request->id_siklus,
            'tempat' => $request->tempat,
            'waktu' => $request->waktu,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => now()
        ];

        // Process
        if (ExpoProjectModel::updateExpoProject($request->id, $params)) {
            // Flash message for success
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/expo-project');
        } else {
            // Flash message for failure
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/expo-project/edit/' . $request->user_id);
        }
    }

    public function deleteExpoProjectProcess($id)
    {
        // Get data
        $delete = ExpoProjectModel::getDataById($id);

        // Delete pendaftaran expo
        if (ExpoProjectModel::deletePendaftaranExpo($id)) {
            // Process delete expo project
            if (ExpoProjectModel::deleteExpoProject($id)) {
                // Update status kelompok
                $paramKelompok = ['status_kelompok' => "C500 Telah Disetujui!"];
                if (ExpoProjectModel::updateKelompok($delete->id_kelompok, $paramKelompok)) {
                    // Flash message for success
                    session()->flash('success', 'Data berhasil dihapus.');
                    return redirect('/admin/expo-project');
                } else {
                    // Flash message for failure
                    session()->flash('danger', 'Data gagal dihapus.');
                    return redirect('/admin/expo-project');
                }
            } else {
                // Flash message for failure
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/expo-project');
            }
        } else {
            // Flash message for failure
            session()->flash('danger', 'Data gagal dihapus.');
            return redirect('/admin/expo-project');
        }
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
        $params = ['status' => 'Validasi Expo Berhasil!'];

        // Get data pendaftaran
        $dataPendaftaranExpo = ExpoProjectModel::getDataPendaftaranExpo($id);

        if ($dataPendaftaranExpo) { // Periksa apakah data pendaftaran ditemukan
            // Process
            if (ExpoProjectModel::updateExpoProjectKelompok($id, $params)) {
                $paramKelompok = ['status_kelompok' => "Validasi Expo Berhasil!"];
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
        $params = ['status' => 'Validasi Expo Gagal!'];

        // Get data pendaftaran
        $dataPendaftaranExpo = ExpoProjectModel::getDataPendaftaranExpo($id);

        if ($dataPendaftaranExpo) { // Periksa apakah data pendaftaran ditemukan
            // Process
            if (ExpoProjectModel::updateExpoProjectKelompok($id, $params)) {
                $paramKelompok = ['status_kelompok' => "Validasi Expo Gagal!"];
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
