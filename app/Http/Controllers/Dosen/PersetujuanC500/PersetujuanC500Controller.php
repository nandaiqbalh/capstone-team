<?php

namespace App\Http\Controllers\Dosen\PersetujuanC500;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Dosen\PersetujuanC500\PersetujuanC500Model;
use Illuminate\Support\Facades\Hash;


class PersetujuanC500Controller extends BaseController
{
    public function index()
    {
        // get data with pagination
        $rs_persetujuan_500 = PersetujuanC500Model::getDataWithPagination();

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


    public function tolakPersetujuanC500Saya(Request $request, $id)
    {

        $rs_persetujuan_500 = PersetujuanC500Model::getDataWithPagination();

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
        if (PersetujuanC500Model::updateKelompok($id, $params)) {

            $paramsUpdated = [];
            $persetujuan_c500_updated = PersetujuanC500Model::getDataById($id);

            if ($persetujuan_c500_updated->id == $id) {
                if ($persetujuan_c500_updated->file_status_c500_dosbing1 == "C500 Tidak Disetujui Dosbing 1!" ||
                    $persetujuan_c500_updated->file_status_c500_dosbing2 == "C500 Tidak Disetujui Dosbing 2!") {

                    $paramsUpdated = [
                        'status_kelompok' => 'C500 Tidak Disetujui!',
                    ];
                    // Update status kelompok
                    PersetujuanC500Model::updateKelompok($id, $paramsUpdated);
                } else {
                    $paramsUpdated = [
                        'status_kelompok' => 'Menunggu Persetujuan Penguji!',
                    ];
                    PersetujuanC500Model::updateKelompok($id, $paramsUpdated);
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


    public function terimaPersetujuanC500Saya(Request $request, $id)
    {
        $rs_persetujuan_500 = PersetujuanC500Model::getDataWithPagination();
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
        if (PersetujuanC500Model::updateKelompok($id, $params)) {
            $paramsUpdated = [];
            $persetujuan_c500_updated = PersetujuanC500Model::getDataById($id);

            if ($persetujuan_c500_updated->id == $id) {
                if ($persetujuan_c500_updated->file_status_c500_dosbing1 == "C500 Telah Disetujui!" &&
                    $persetujuan_c500_updated->file_status_c500_dosbing2 == "C500 Telah Disetujui!" ) {

                    $paramsUpdated = [
                        'status_kelompok' => 'C500 Telah Disetujui!',
                        'file_status_c500'=> "C500 Telah Disetujui!",
                    ];

                    // Update status kelompok
                    PersetujuanC500Model::updateKelompok($id, $paramsUpdated);
                } elseif($persetujuan_c500_updated->file_status_c500_dosbing1 == "C500 Telah Disetujui!"){
                    $paramsUpdated = [
                        'status_kelompok' => 'C500 Menunggu Persetujuan Dosbing 2!',
                        'file_status_c500'=> "C500 Menunggu Persetujuan Dosbing 2!",
                    ];

                    PersetujuanC500Model::updateKelompok($id, $paramsUpdated);
                } elseif($persetujuan_c500_updated->file_status_c500_dosbing2 == "C500 Telah Disetujui!"){
                    $paramsUpdated = [
                        'status_kelompok' => 'C500 Menunggu Persetujuan Dosbing 1!',
                        'file_status_c500'=> "C500 Menunggu Persetujuan Dosbing 1!",
                    ];

                    PersetujuanC500Model::updateKelompok($id, $paramsUpdated);
                } else {
                    $paramsUpdated = [
                        'status_kelompok' => 'Menunggu Persetujuan C500!',
                        'file_status_c500'=> "Menunggu Persetujuan C500!",
                    ];

                    PersetujuanC500Model::updateKelompok($id, $paramsUpdated);

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
        // Data request
        $nama = $request->nama;

        // New search or reset
        if ($request->action == 'search') {
            // Get data with pagination
            $rs_persetujuan_500 = PersetujuanC500Model::getDataSearch($nama);

            // Check if result is null
            if (!$rs_persetujuan_500) {
                // Handle the case when no data is found
                $data = ['rs_persetujuan_500' => null, 'nama' => $nama];
            } else {
                // Set status colors if data is available

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

                // Prepare data for view
                $data = ['rs_persetujuan_500' => $rs_persetujuan_500, 'nama' => $nama];
            }

            // Return view with appropriate data
            return view('dosen.persetujuan-c500.index', $data);
        } else {
            // Handle other cases (e.g., when action is not 'search')
            return view('dosen.persetujuan-c500.index');
        }
    }

}
