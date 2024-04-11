<?php

namespace App\Http\Controllers\Dosen\PersetujuanLaporanTA;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Dosen\PersetujuanLaporanTA\PersetujuanLaporanTAModel;
use Illuminate\Support\Facades\Hash;


class PersetujuanLaporanTAController extends BaseController
{
    public function index()
    {
        // get data with pagination
        $rs_persetujuan_500 = PersetujuanLaporanTAModel::getDataWithPagination();

        foreach ($rs_persetujuan_500 as $persetujuan_c500) {
            if ($persetujuan_c500->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                $persetujuan_c500->jenis_dosen = 'Pembimbing 1';
                $persetujuan_c500 -> status_dosen = $persetujuan_c500 ->status_dosen_pembimbing_1;

            } else if ($persetujuan_c500->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                $persetujuan_c500->jenis_dosen = 'Pembimbing 2';
                $persetujuan_c500 -> status_dosen = $persetujuan_c500 ->status_dosen_pembimbing_2;
            } else {
                $persetujuan_c500->jenis_dosen = 'Belum diplot';
                $persetujuan_c500->status_dosen = 'Belum diplot';
            }

            $persetujuan_c500 -> status_dokumen_color = $this->getStatusColor($persetujuan_c500->file_status_c500);
            $persetujuan_c500 -> status_dosen_color = $this->getStatusColor($persetujuan_c500->status_dosen);

        }

        // data
        $data = ['rs_persetujuan_500' => $rs_persetujuan_500];
        // view
        return view('dosen.persetujuan-c500.index', $data);
    }


    public function tolakPersetujuanLaporanTASaya(Request $request, $id)
    {

        $rs_persetujuan_500 = PersetujuanLaporanTAModel::getDataWithPagination();

        $params = []; // Initialize $params outside the loop

        $jenis_dosen = ""; // Move the declaration outside the loop

        foreach ($rs_persetujuan_500 as $persetujuan_c500) {
            if ($persetujuan_c500->id == $id) {
                if ($persetujuan_c500->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 1';
                    $params = [
                        'status_dosen_pembimbing_1' => 'C500 Tidak Disetujui Dosbing 1!',
                        'file_status_c500_dosbing1' => 'C500 Tidak Disetujui Dosbing 1!',
                        'file_status_c500' => 'C500 Tidak Disetujui Dosbing 1!',
                    ];
                    break;
                } else if ($persetujuan_c500->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 2';
                    $params = [
                        'status_dosen_pembimbing_2' => 'C500 Tidak Disetujui Dosbing 2!',
                        'file_status_c500_dosbing2' => 'C500 Tidak Disetujui Dosbing 2!',
                        'file_status_c500' => 'C500 Tidak Disetujui Dosbing 2!',
                    ];
                    break;
                }
            }
        }

        // Check if $params is still empty, indicating no matching condition was met
        if (empty($params)) {
            session()->flash('danger', 'No matching condition found for the user.');
            return redirect('/dosen/persetujuan-c500');
        }

        // process
        if (PersetujuanLaporanTAModel::updateKelompok($id, $params)) {

            $paramsUpdated = [];
            $persetujuan_c500_updated = PersetujuanLaporanTAModel::getDataById($id);

            if ($persetujuan_c500_updated->id == $id) {
                if ($persetujuan_c500_updated->file_status_c500_dosbing1 == "C500 Tidak Disetujui Dosbing 1!" ||
                    $persetujuan_c500_updated->file_status_c500_dosbing2 == "C500 Tidak Disetujui Dosbing 2!") {

                    $paramsUpdated = [
                        'status_kelompok' => 'C500 Tidak Disetujui!',
                    ];
                    // Update status kelompok
                    PersetujuanLaporanTAModel::updateKelompok($id, $paramsUpdated);
                } else {
                    $paramsUpdated = [
                        'status_kelompok' => 'Menunggu Persetujuan Penguji!',
                    ];
                    PersetujuanLaporanTAModel::updateKelompok($id, $paramsUpdated);
                }
            }
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/dosen/persetujuan-c500');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/dosen/persetujuan-c500');
        }
    }


    public function terimaPersetujuanLaporanTASaya(Request $request, $id)
    {
        $rs_persetujuan_500 = PersetujuanLaporanTAModel::getDataWithPagination();
        $params = [];

        foreach ($rs_persetujuan_500 as $persetujuan_c500) {
            if ($persetujuan_c500->id == $id) {
                if ($persetujuan_c500->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $params = [
                        'status_dosen_pembimbing_1' => 'C500 Telah Disetujui!',
                        'file_status_c500_dosbing1' => 'C500 Telah Disetujui!'
                    ];
                    break;
                } else if ($persetujuan_c500->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $params = [
                        'status_dosen_pembimbing_2' => 'C500 Telah Disetujui!',
                        'file_status_c500_dosbing2' => 'C500 Telah Disetujui!'
                    ];

                    break;
                }
            }
        }

        // Check if $params is still empty, indicating no matching condition was met
        if (empty($params)) {
            session()->flash('danger', 'Tidak ditemukan kondisi yang cocok untuk pengguna.');
            return redirect('/dosen/persetujuan-c500');
        }

       // Process update
        if (PersetujuanLaporanTAModel::updateKelompok($id, $params)) {
            $paramsUpdated = [];
            $persetujuan_c500_updated = PersetujuanLaporanTAModel::getDataById($id);

            if ($persetujuan_c500_updated->id == $id) {
                if ($persetujuan_c500_updated->file_status_c500_dosbing1 == "C500 Telah Disetujui!" &&
                    $persetujuan_c500_updated->file_status_c500_dosbing2 == "C500 Telah Disetujui!" ) {

                    $paramsUpdated = [
                        'status_kelompok' => 'C500 Telah Disetujui!',
                        'file_status_c500'=> "C500 Telah Disetujui!",
                    ];

                    // Update status kelompok
                    PersetujuanLaporanTAModel::updateKelompok($id, $paramsUpdated);
                } elseif($persetujuan_c500_updated->file_status_c500_dosbing1 == "C500 Telah Disetujui!"){
                    $paramsUpdated = [
                        'status_kelompok' => 'C500 Menunggu Persetujuan Dosbing 2!',
                        'file_status_c500'=> "C500 Menunggu Persetujuan Dosbing 2!",
                    ];

                    PersetujuanLaporanTAModel::updateKelompok($id, $paramsUpdated);
                } elseif($persetujuan_c500_updated->file_status_c500_dosbing2 == "C500 Telah Disetujui!"){
                    $paramsUpdated = [
                        'status_kelompok' => 'C500 Menunggu Persetujuan Dosbing 1!',
                        'file_status_c500'=> "C500 Menunggu Persetujuan Dosbing 1!",
                    ];

                    PersetujuanLaporanTAModel::updateKelompok($id, $paramsUpdated);
                } else {
                    $paramsUpdated = [
                        'status_kelompok' => 'Menunggu Persetujuan C500!',
                        'file_status_c500'=> "Menunggu Persetujuan C500!",
                    ];

                    PersetujuanLaporanTAModel::updateKelompok($id, $paramsUpdated);

                }

            }


            // Flash message for success
            session()->flash('success', 'Data berhasil disimpan.');
        } else {
            // Flash message for failure
            session()->flash('danger', 'Data gagal disimpan.');
        }

        return redirect('/dosen/persetujuan-c500');
    }


    public function search(Request $request)
    {
        // data request
        $nama = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_kelompok = PersetujuanLaporanTAModel::getDataSearch($nama);
            // data
            $data = ['rs_kelompok' => $rs_kelompok, 'nama' => $nama];
            // view
            return view('dosen.persetujuan-c500.index', $data);
        } else {
            return view('dosen/persetujuan-c500', $data);
        }
    }

}
