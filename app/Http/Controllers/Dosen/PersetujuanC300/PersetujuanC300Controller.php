<?php

namespace App\Http\Controllers\Dosen\PersetujuanC300;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Dosen\PersetujuanC300\PersetujuanC300Model;
use Illuminate\Support\Facades\Hash;


class PersetujuanC300Controller extends BaseController
{
    public function index()
    {
        // get data with pagination
        $rs_persetujuan_300 = PersetujuanC300Model::getDataWithPagination();

        foreach ($rs_persetujuan_300 as $persetujuan_c300) {
            if ($persetujuan_c300->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                $persetujuan_c300->jenis_dosen = 'Pembimbing 1';
                $persetujuan_c300 -> status_dosen = $persetujuan_c300 ->status_dosen_pembimbing_1;

            } else if ($persetujuan_c300->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                $persetujuan_c300->jenis_dosen = 'Pembimbing 2';
                $persetujuan_c300 -> status_dosen = $persetujuan_c300 ->status_dosen_pembimbing_2;
            } else {
                $persetujuan_c300->jenis_dosen = 'Belum diplot';
                $persetujuan_c300->status_dosen = 'Belum diplot';
            }

            $persetujuan_c300 -> status_dokumen_color = $this->getStatusColor($persetujuan_c300->file_status_c300);
            $persetujuan_c300 -> status_dosen_color = $this->getStatusColor($persetujuan_c300->status_dosen);

        }

        // data
        $data = ['rs_persetujuan_300' => $rs_persetujuan_300];
        // view
        return view('dosen.persetujuan-c300.index', $data);
    }


    public function tolakPersetujuanC300Saya(Request $request, $id)
    {

        $rs_persetujuan_300 = PersetujuanC300Model::getDataWithPagination();

        $params = []; // Initialize $params outside the loop

        $jenis_dosen = ""; // Move the declaration outside the loop

        foreach ($rs_persetujuan_300 as $persetujuan_c300) {
            if ($persetujuan_c300->id == $id) {
                if ($persetujuan_c300->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 1';
                    $params = [
                        'status_dosen_pembimbing_1' => 'C300 Tidak Disetujui Dosbing 1!',
                        'file_status_c300_dosbing1' => 'C300 Tidak Disetujui Dosbing 1!',
                        'file_status_c300' => 'C300 Tidak Disetujui Dosbing 1!',
                    ];
                    break;
                } else if ($persetujuan_c300->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 2';
                    $params = [
                        'status_dosen_pembimbing_2' => 'C300 Tidak Disetujui Dosbing 2!',
                        'file_status_c300_dosbing2' => 'C300 Tidak Disetujui Dosbing 2!',
                        'file_status_c300' => 'C300 Tidak Disetujui Dosbing 2!',
                    ];
                    break;
                }
            }
        }

        // Check if $params is still empty, indicating no matching condition was met
        if (empty($params)) {
            session()->flash('danger', 'No matching condition found for the user.');
            return redirect('/dosen/persetujuan-c300');
        }

        // process
        if (PersetujuanC300Model::updateKelompok($id, $params)) {

            $paramsUpdated = [];
            $persetujuan_c300_updated = PersetujuanC300Model::getDataById($id);

            if ($persetujuan_c300_updated->id == $id) {
                if ($persetujuan_c300_updated->file_status_c300_dosbing1 == "C300 Tidak Disetujui Dosbing 1!" ||
                    $persetujuan_c300_updated->file_status_c300_dosbing2 == "C300 Tidak Disetujui Dosbing 2!") {

                    $paramsUpdated = [
                        'status_kelompok' => 'C300 Tidak Disetujui!',
                    ];
                    // Update status kelompok
                    PersetujuanC300Model::updateKelompok($id, $paramsUpdated);
                } else {
                    $paramsUpdated = [
                        'status_kelompok' => 'Menunggu Persetujuan Penguji!',
                    ];
                    PersetujuanC300Model::updateKelompok($id, $paramsUpdated);
                }
            }
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/dosen/persetujuan-c300');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/dosen/persetujuan-c300');
        }
    }


    public function terimaPersetujuanC300Saya(Request $request, $id)
    {
        $rs_persetujuan_300 = PersetujuanC300Model::getDataWithPagination();
        $params = [];

        foreach ($rs_persetujuan_300 as $persetujuan_c300) {
            if ($persetujuan_c300->id == $id) {
                if ($persetujuan_c300->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $params = [
                        'status_dosen_pembimbing_1' => 'C300 Telah Disetujui!',
                        'file_status_c300_dosbing1' => 'C300 Telah Disetujui!'
                    ];
                    break;
                } else if ($persetujuan_c300->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $params = [
                        'status_dosen_pembimbing_2' => 'C300 Telah Disetujui!',
                        'file_status_c300_dosbing2' => 'C300 Telah Disetujui!'
                    ];

                    break;
                }
            }
        }

        // Check if $params is still empty, indicating no matching condition was met
        if (empty($params)) {
            session()->flash('danger', 'Tidak ditemukan kondisi yang cocok untuk pengguna.');
            return redirect('/dosen/persetujuan-c300');
        }

       // Process update
        if (PersetujuanC300Model::updateKelompok($id, $params)) {
            $paramsUpdated = [];
            $persetujuan_c300_updated = PersetujuanC300Model::getDataById($id);

            if ($persetujuan_c300_updated->id == $id) {
                if ($persetujuan_c300_updated->file_status_c300_dosbing1 == "C300 Telah Disetujui!" &&
                    $persetujuan_c300_updated->file_status_c300_dosbing2 == "C300 Telah Disetujui!" ) {

                    $paramsUpdated = [
                        'status_kelompok' => 'C300 Telah Disetujui!',
                        'file_status_c300'=> "C300 Telah Disetujui!",
                    ];

                    // Update status kelompok
                    PersetujuanC300Model::updateKelompok($id, $paramsUpdated);
                } elseif($persetujuan_c300_updated->file_status_c300_dosbing1 == "C300 Telah Disetujui!"){
                    $paramsUpdated = [
                        'status_kelompok' => 'C300 Menunggu Persetujuan Dosbing 2!',
                        'file_status_c300'=> "C300 Menunggu Persetujuan Dosbing 2!",
                    ];

                    PersetujuanC300Model::updateKelompok($id, $paramsUpdated);
                } elseif($persetujuan_c300_updated->file_status_c300_dosbing2 == "C300 Telah Disetujui!"){
                    $paramsUpdated = [
                        'status_kelompok' => 'C300 Menunggu Persetujuan Dosbing 1!',
                        'file_status_c300'=> "C300 Menunggu Persetujuan Dosbing 1!",
                    ];

                    PersetujuanC300Model::updateKelompok($id, $paramsUpdated);
                } else {
                    $paramsUpdated = [
                        'status_kelompok' => 'Menunggu Persetujuan C300!',
                        'file_status_c300'=> "Menunggu Persetujuan C300!",
                    ];

                    PersetujuanC300Model::updateKelompok($id, $paramsUpdated);

                }

            }


            // Flash message for success
            session()->flash('success', 'Data berhasil disimpan.');
        } else {
            // Flash message for failure
            session()->flash('danger', 'Data gagal disimpan.');
        }

        return redirect('/dosen/persetujuan-c300');
    }


    public function search(Request $request)
    {
        // data request
        $nama = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_kelompok = PersetujuanC300Model::getDataSearch($nama);
            // data
            $data = ['rs_kelompok' => $rs_kelompok, 'nama' => $nama];
            // view
            return view('dosen.persetujuan-c300.index', $data);
        } else {
            return view('dosen/persetujuan-c300', $data);
        }
    }

}
