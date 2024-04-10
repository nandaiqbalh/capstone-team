<?php

namespace App\Http\Controllers\Dosen\PersetujuanC100;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Dosen\PersetujuanC100\PersetujuanC100Model;
use Illuminate\Support\Facades\Hash;


class PersetujuanC100Controller extends BaseController
{
    public function index()
    {
        // get data with pagination
        $rs_persetujuan_100 = PersetujuanC100Model::getDataWithPagination();

        foreach ($rs_persetujuan_100 as $persetujuan_c100) {
            if ($persetujuan_c100->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                $persetujuan_c100->jenis_dosen = 'Pembimbing 1';
                $persetujuan_c100 -> status_dosen = $persetujuan_c100 ->status_dosen_pembimbing_1;

            } else if ($persetujuan_c100->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                $persetujuan_c100->jenis_dosen = 'Pembimbing 2';
                $persetujuan_c100 -> status_dosen = $persetujuan_c100 ->status_dosen_pembimbing_2;
            } else {
                $persetujuan_c100->jenis_dosen = 'Belum diplot';
                $persetujuan_c100->status_dosen = 'Belum diplot';
            }

            $persetujuan_c100 -> status_dokumen_color = $this->getStatusColor($persetujuan_c100->file_status_c100);
            $persetujuan_c100 -> status_dosen_color = $this->getStatusColor($persetujuan_c100->status_dosen);

        }

        // data
        $data = ['rs_persetujuan_100' => $rs_persetujuan_100];
        // view
        return view('dosen.persetujuan-c100.index', $data);
    }


    public function tolakPersetujuanC100Saya(Request $request, $id)
    {

        $rs_persetujuan_100 = PersetujuanC100Model::getDataWithPagination();

        $params = []; // Initialize $params outside the loop

        $jenis_dosen = ""; // Move the declaration outside the loop

        foreach ($rs_persetujuan_100 as $persetujuan_c100) {
            if ($persetujuan_c100->id == $id) {
                if ($persetujuan_c100->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 1';
                    $params = [
                        'status_dosen_pembimbing_1' => 'C100 Tidak Disetujui Dosbing 1!',
                        'file_status_c100' => 'C100 Tidak Disetujui Dosbing 1!',
                    ];
                    break;
                } else if ($persetujuan_c100->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 2';
                    $params = [
                        'status_dosen_pembimbing_2' => 'C100 Tidak Disetujui Dosbing 2!',
                        'file_status_c100' => 'C100 Tidak Disetujui Dosbing 2!',
                    ];
                    break;
                }
            }
        }

        // Check if $params is still empty, indicating no matching condition was met
        if (empty($params)) {
            session()->flash('danger', 'No matching condition found for the user.');
            return redirect('/dosen/persetujuan-c100');
        }

        // process
        if (PersetujuanC100Model::updateKelompok($id, $params)) {

            $paramsUpdated = [];
            $persetujuan_c100_updated = PersetujuanC100Model::getDataById($id);

            if ($persetujuan_c100_updated->id == $id) {
                if ($persetujuan_c100_updated->status_dosen_pembimbing_1 == "C100 Tidak Disetujui Dosbing 1!" &&
                    $persetujuan_c100_updated->status_dosen_pembimbing_2 == "C100 Tidak Disetujui Dosbing 2!") {

                    $paramsUpdated = [
                        'status_kelompok' => 'C100 Tidak Disetujui!',
                        'status_sidang_proposal' => NULL,
                    ];
                    // Update status kelompok
                    PersetujuanC100Model::updateKelompok($id, $paramsUpdated);
                } else {
                    $paramsUpdated = [
                        'status_kelompok' => 'Menunggu Persetujuan Penguji!',
                        'status_sidang_proposal' => NULL,
                    ];
                    PersetujuanC100Model::updateKelompok($id, $paramsUpdated);
                }
            }
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/dosen/persetujuan-c100');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/dosen/persetujuan-c100');
        }
    }


    public function terimaPersetujuanC100Saya(Request $request, $id)
    {
        $rs_persetujuan_100 = PersetujuanC100Model::getDataWithPagination();
        $params = [];

        foreach ($rs_persetujuan_100 as $persetujuan_c100) {
            if ($persetujuan_c100->id == $id) {
                if ($persetujuan_c100->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $params = ['status_dosen_pembimbing_1' => 'C100 Telah Disetujui!'];
                    break;
                } else if ($persetujuan_c100->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $params = ['status_dosen_pembimbing_2' => 'C100 Telah Disetujui!'];
                    break;
                }
            }
        }

        // Check if $params is still empty, indicating no matching condition was met
        if (empty($params)) {
            session()->flash('danger', 'Tidak ditemukan kondisi yang cocok untuk pengguna.');
            return redirect('/dosen/persetujuan-c100');
        }

       // Process update
        if (PersetujuanC100Model::updateKelompok($id, $params)) {
            $paramsUpdated = [];
            $persetujuan_c100_updated = PersetujuanC100Model::getDataById($id);

            if ($persetujuan_c100_updated->id == $id) {
                if ($persetujuan_c100_updated->status_dosen_pembimbing_1 == "C100 Telah Disetujui!" &&
                    $persetujuan_c100_updated->status_dosen_pembimbing_2 == "C100 Telah Disetujui!" ) {

                    $paramsUpdated = [
                        'status_kelompok' => 'C100 Telah Disetujui!',
                        'status_sidang_proposal'=> "Menunggu Dijadwalkan Sidang!",
                        'file_status_c100'=> "C100 Telah Disetujui!",
                    ];
                    // Update status kelompok
                    PersetujuanC100Model::updateKelompok($id, $paramsUpdated);
                } elseif($persetujuan_c100_updated->status_dosen_pembimbing_1 == "C100 Telah Disetujui!"){
                    $paramsUpdated = [
                        'status_kelompok' => 'C100 Menunggu Persetujuan Dosbing 2!',
                        'file_status_c100'=> "C100 Menunggu Persetujuan Dosbing 2!",
                    ];

                    PersetujuanC100Model::updateKelompok($id, $paramsUpdated);
                } elseif($persetujuan_c100_updated->status_dosen_pembimbing_2 == "C100 Telah Disetujui!"){
                    $paramsUpdated = [
                        'status_kelompok' => 'C100 Menunggu Persetujuan Dosbing 1!',
                        'file_status_c100'=> "C100 Menunggu Persetujuan Dosbing 1!",
                    ];

                    PersetujuanC100Model::updateKelompok($id, $paramsUpdated);
                } else {
                    $paramsUpdated = [
                        'status_kelompok' => 'Menunggu Persetujuan C100!',
                        'file_status_c100'=> "Menunggu Persetujuan C100!",
                    ];

                    PersetujuanC100Model::updateKelompok($id, $paramsUpdated);

                }

            }


            // Flash message for success
            session()->flash('success', 'Data berhasil disimpan.');
        } else {
            // Flash message for failure
            session()->flash('danger', 'Data gagal disimpan.');
        }

        return redirect('/dosen/persetujuan-c100');
    }


    public function search(Request $request)
    {
        // data request
        $nama = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_kelompok = PersetujuanC100Model::getDataSearch($nama);
            // data
            $data = ['rs_kelompok' => $rs_kelompok, 'nama' => $nama];
            // view
            return view('dosen.persetujuan-c100.index', $data);
        } else {
            return view('dosen/persetujuan-c100', $data);
        }
    }

}
