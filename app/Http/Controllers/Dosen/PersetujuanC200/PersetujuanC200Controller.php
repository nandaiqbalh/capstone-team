<?php

namespace App\Http\Controllers\Dosen\PersetujuanC200;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Dosen\PersetujuanC200\PersetujuanC200Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersetujuanC200Controller extends BaseController
{
    public function index()
    {
        // get data with pagination
        $rs_persetujuan_200 = PersetujuanC200Model::getDataWithPagination();

        dd($rs_persetujuan_200);
        foreach ($rs_persetujuan_200 as $persetujuan_c200) {
            if ($persetujuan_c200->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                $persetujuan_c200->jenis_dosen = 'Pembimbing 1';
                $persetujuan_c200->status_dosen = $persetujuan_c200->status_dosen_pembimbing_1;

            } else if ($persetujuan_c200->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                $persetujuan_c200->jenis_dosen = 'Pembimbing 2';
                $persetujuan_c200->status_dosen = $persetujuan_c200->status_dosen_pembimbing_2;
            } else {
                $persetujuan_c200->jenis_dosen = 'Belum diplot';
                $persetujuan_c200->status_dosen = 'Belum diplot';
            }

            $persetujuan_c200->status_dokumen_color = $this->getStatusColor($persetujuan_c200->file_status_c200);
            $persetujuan_c200->status_dosen_color = $this->getStatusColor($persetujuan_c200->status_dosen);

        }

        // data
        $data = ['rs_persetujuan_200' => $rs_persetujuan_200];
        // view
        return view('dosen.persetujuan-c200.index', $data);
    }

    public function tolakPersetujuanC200Saya(Request $request, $id)
    {

        $rs_persetujuan_200 = PersetujuanC200Model::getDataWithPagination();

        $params = []; // Initialize $params outside the loop

        $jenis_dosen = ""; // Move the declaration outside the loop

        foreach ($rs_persetujuan_200 as $persetujuan_c200) {
            if ($persetujuan_c200->id == $id) {
                if ($persetujuan_c200->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 1';
                    $params = [
                        'status_dosen_pembimbing_1' => 'C200 Tidak Disetujui Dosbing 1',
                        'file_status_c200_dosbing1' => 'C200 Tidak Disetujui Dosbing 1',
                        'file_status_c200' => 'C200 Tidak Disetujui Dosbing 1',
                    ];
                    break;
                } else if ($persetujuan_c200->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 2';
                    $params = [
                        'status_dosen_pembimbing_2' => 'C200 Tidak Disetujui Dosbing 2',
                        'file_status_c200_dosbing2' => 'C200 Tidak Disetujui Dosbing 2',
                        'file_status_c200' => 'C200 Tidak Disetujui Dosbing 2',
                    ];
                    break;
                }
            }
        }

        // Check if $params is still empty, indicating no matching condition was met
        if (empty($params)) {
            session()->flash('danger', 'No matching condition found for the user.');
            return redirect('/dosen/persetujuan-c200');
        }

        // process
        if (PersetujuanC200Model::updateKelompok($id, $params)) {

            $paramsUpdated = [];
            $persetujuan_c200_updated = PersetujuanC200Model::getDataById($id);

            if ($persetujuan_c200_updated->id == $id) {
                if ($persetujuan_c200_updated->file_status_c200_dosbing1 == "C200 Tidak Disetujui Dosbing 1" &&
                    $persetujuan_c200_updated->file_status_c200_dosbing2 == "C200 Tidak Disetujui Dosbing 2") {

                    $paramsUpdated = [
                        'status_kelompok' => 'C200 Tidak Disetujui',
                    ];
                    // Update status kelompok
                    PersetujuanC200Model::updateKelompok($id, $paramsUpdated);
                } else if ($persetujuan_c200_updated->file_status_c200_dosbing1 == "C200 Tidak Disetujui Dosbing 1") {

                    $paramsUpdated = [
                        'status_kelompok' => 'C200 Tidak Disetujui Dosbing 1',
                    ];
                    // Update status kelompok
                    PersetujuanC200Model::updateKelompok($id, $paramsUpdated);
                } else if ($persetujuan_c200_updated->file_status_c200_dosbing2 == "C200 Tidak Disetujui Dosbing 2") {
                    $paramsUpdated = [
                        'status_kelompok' => 'C200 Tidak Disetujui Dosbing 2',
                    ];
                    // Update status kelompok
                    PersetujuanC200Model::updateKelompok($id, $paramsUpdated);
                } else {
                    $paramsUpdated = [
                        'status_kelompok' => 'Menunggu Persetujuan Penguji',
                    ];
                    PersetujuanC200Model::updateKelompok($id, $paramsUpdated);
                }
            }
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/dosen/persetujuan-c200');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/dosen/persetujuan-c200');
        }
    }

    public function terimaPersetujuanC200Saya(Request $request, $id)
    {
        $rs_persetujuan_200 = PersetujuanC200Model::getDataWithPagination();
        $params = [];

        foreach ($rs_persetujuan_200 as $persetujuan_c200) {
            if ($persetujuan_c200->id == $id) {
                if ($persetujuan_c200->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $params = [
                        'status_dosen_pembimbing_1' => 'C200 Telah Disetujui',
                        'file_status_c200_dosbing1' => 'C200 Telah Disetujui',
                    ];
                    break;
                } else if ($persetujuan_c200->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $params = [
                        'status_dosen_pembimbing_2' => 'C200 Telah Disetujui',
                        'file_status_c200_dosbing2' => 'C200 Telah Disetujui',
                    ];

                    break;
                }
            }
        }

        // Check if $params is still empty, indicating no matching condition was met
        if (empty($params)) {
            session()->flash('danger', 'Tidak ditemukan kondisi yang cocok untuk pengguna.');
            return redirect('/dosen/persetujuan-c200');
        }

        // Process update
        if (PersetujuanC200Model::updateKelompok($id, $params)) {
            $paramsUpdated = [];
            $persetujuan_c200_updated = PersetujuanC200Model::getDataById($id);

            if ($persetujuan_c200_updated->id == $id) {
                if ($persetujuan_c200_updated->file_status_c200_dosbing1 == "C200 Telah Disetujui" &&
                    $persetujuan_c200_updated->file_status_c200_dosbing2 == "C200 Telah Disetujui") {

                    $paramsUpdated = [
                        'status_kelompok' => 'C200 Telah Disetujui',
                        'file_status_c200' => "C200 Telah Disetujui",
                    ];

                    // Update status kelompok
                    PersetujuanC200Model::updateKelompok($id, $paramsUpdated);
                } elseif ($persetujuan_c200_updated->file_status_c200_dosbing1 == "C200 Telah Disetujui") {
                    $paramsUpdated = [
                        'status_kelompok' => 'C200 Menunggu Persetujuan Dosbing 2',
                        'file_status_c200' => "C200 Menunggu Persetujuan Dosbing 2",
                    ];

                    PersetujuanC200Model::updateKelompok($id, $paramsUpdated);
                } elseif ($persetujuan_c200_updated->file_status_c200_dosbing2 == "C200 Telah Disetujui") {
                    $paramsUpdated = [
                        'status_kelompok' => 'C200 Menunggu Persetujuan Dosbing 1',
                        'file_status_c200' => "C200 Menunggu Persetujuan Dosbing 1",
                    ];

                    PersetujuanC200Model::updateKelompok($id, $paramsUpdated);
                } else {
                    $paramsUpdated = [
                        'status_kelompok' => 'Menunggu Persetujuan C200',
                        'file_status_c200' => "Menunggu Persetujuan C200",
                    ];

                    PersetujuanC200Model::updateKelompok($id, $paramsUpdated);

                }

            }

            // Flash message for success
            session()->flash('success', 'Data berhasil disimpan.');
        } else {
            // Flash message for failure
            session()->flash('danger', 'Data gagal disimpan.');
        }

        return redirect('/dosen/persetujuan-c200');
    }

    public function search(Request $request)
    {
        // Data request
        $nama = $request->nama;

        // New search or reset
        if ($request->action == 'search') {
            // Get data with pagination
            $rs_persetujuan_200 = PersetujuanC200Model::getDataSearch($nama);

            // Check if result is null
            if (!$rs_persetujuan_200) {
                // Handle the case when no data is found
                $data = ['rs_persetujuan_200' => null, 'nama' => $nama];
            } else {
                // Set status colors if data is available

                foreach ($rs_persetujuan_200 as $persetujuan_c200) {
                    if ($persetujuan_c200->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                        $persetujuan_c200->jenis_dosen = 'Pembimbing 1';
                        $persetujuan_c200->status_dosen = $persetujuan_c200->status_dosen_pembimbing_1;

                    } else if ($persetujuan_c200->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                        $persetujuan_c200->jenis_dosen = 'Pembimbing 2';
                        $persetujuan_c200->status_dosen = $persetujuan_c200->status_dosen_pembimbing_2;
                    } else {
                        $persetujuan_c200->jenis_dosen = 'Belum diplot';
                        $persetujuan_c200->status_dosen = 'Belum diplot';
                    }

                    $persetujuan_c200->status_dokumen_color = $this->getStatusColor($persetujuan_c200->file_status_c200);
                    $persetujuan_c200->status_dosen_color = $this->getStatusColor($persetujuan_c200->status_dosen);

                }

                // Prepare data for view
                $data = ['rs_persetujuan_200' => $rs_persetujuan_200, 'nama' => $nama];
            }

            // Return view with appropriate data
            return view('dosen.persetujuan-c200.index', $data);
        } else {
            // Handle other cases (e.g., when action is not 'search')
            return view('dosen.persetujuan-c200.index');
        }
    }

}
