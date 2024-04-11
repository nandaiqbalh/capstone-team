<?php

namespace App\Http\Controllers\Dosen\PersetujuanC400;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Dosen\PersetujuanC400\PersetujuanC400Model;
use Illuminate\Support\Facades\Hash;


class PersetujuanC400Controller extends BaseController
{
    public function index()
    {
        // get data with pagination
        $rs_persetujuan_400 = PersetujuanC400Model::getDataWithPagination();

        foreach ($rs_persetujuan_400 as $persetujuan_c400) {
            if ($persetujuan_c400->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                $persetujuan_c400->jenis_dosen = 'Pembimbing 1';
                $persetujuan_c400 -> status_dosen = $persetujuan_c400 ->status_dosen_pembimbing_1;

            } else if ($persetujuan_c400->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                $persetujuan_c400->jenis_dosen = 'Pembimbing 2';
                $persetujuan_c400 -> status_dosen = $persetujuan_c400 ->status_dosen_pembimbing_2;
            } else {
                $persetujuan_c400->jenis_dosen = 'Belum diplot';
                $persetujuan_c400->status_dosen = 'Belum diplot';
            }

            $persetujuan_c400 -> status_dokumen_color = $this->getStatusColor($persetujuan_c400->file_status_c400);
            $persetujuan_c400 -> status_dosen_color = $this->getStatusColor($persetujuan_c400->status_dosen);

        }

        // data
        $data = ['rs_persetujuan_400' => $rs_persetujuan_400];
        // view
        return view('dosen.persetujuan-c400.index', $data);
    }


    public function tolakPersetujuanC400Saya(Request $request, $id)
    {

        $rs_persetujuan_400 = PersetujuanC400Model::getDataWithPagination();

        $params = []; // Initialize $params outside the loop

        $jenis_dosen = ""; // Move the declaration outside the loop

        foreach ($rs_persetujuan_400 as $persetujuan_c400) {
            if ($persetujuan_c400->id == $id) {
                if ($persetujuan_c400->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 1';
                    $params = [
                        'status_dosen_pembimbing_1' => 'C400 Tidak Disetujui Dosbing 1!',
                        'file_status_c400_dosbing1' => 'C400 Tidak Disetujui Dosbing 1!',
                        'file_status_c400' => 'C400 Tidak Disetujui Dosbing 1!',
                    ];
                    break;
                } else if ($persetujuan_c400->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 2';
                    $params = [
                        'status_dosen_pembimbing_2' => 'C400 Tidak Disetujui Dosbing 2!',
                        'file_status_c400_dosbing2' => 'C400 Tidak Disetujui Dosbing 2!',
                        'file_status_c400' => 'C400 Tidak Disetujui Dosbing 2!',
                    ];
                    break;
                }
            }
        }

        // Check if $params is still empty, indicating no matching condition was met
        if (empty($params)) {
            session()->flash('danger', 'No matching condition found for the user.');
            return redirect('/dosen/persetujuan-c400');
        }

        // process
        if (PersetujuanC400Model::updateKelompok($id, $params)) {

            $paramsUpdated = [];
            $persetujuan_c400_updated = PersetujuanC400Model::getDataById($id);

            if ($persetujuan_c400_updated->id == $id) {
                if ($persetujuan_c400_updated->file_status_c400_dosbing1 == "C400 Tidak Disetujui Dosbing 1!" ||
                    $persetujuan_c400_updated->file_status_c400_dosbing2 == "C400 Tidak Disetujui Dosbing 2!") {

                    $paramsUpdated = [
                        'status_kelompok' => 'C400 Tidak Disetujui!',
                    ];
                    // Update status kelompok
                    PersetujuanC400Model::updateKelompok($id, $paramsUpdated);
                } else {
                    $paramsUpdated = [
                        'status_kelompok' => 'Menunggu Persetujuan Penguji!',
                    ];
                    PersetujuanC400Model::updateKelompok($id, $paramsUpdated);
                }
            }
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/dosen/persetujuan-c400');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/dosen/persetujuan-c400');
        }
    }


    public function terimaPersetujuanC400Saya(Request $request, $id)
    {
        $rs_persetujuan_400 = PersetujuanC400Model::getDataWithPagination();
        $params = [];

        foreach ($rs_persetujuan_400 as $persetujuan_c400) {
            if ($persetujuan_c400->id == $id) {
                if ($persetujuan_c400->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $params = [
                        'status_dosen_pembimbing_1' => 'C400 Telah Disetujui!',
                        'file_status_c400_dosbing1' => 'C400 Telah Disetujui!'
                    ];
                    break;
                } else if ($persetujuan_c400->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $params = [
                        'status_dosen_pembimbing_2' => 'C400 Telah Disetujui!',
                        'file_status_c400_dosbing2' => 'C400 Telah Disetujui!'
                    ];

                    break;
                }
            }
        }

        // Check if $params is still empty, indicating no matching condition was met
        if (empty($params)) {
            session()->flash('danger', 'Tidak ditemukan kondisi yang cocok untuk pengguna.');
            return redirect('/dosen/persetujuan-c400');
        }

       // Process update
        if (PersetujuanC400Model::updateKelompok($id, $params)) {
            $paramsUpdated = [];
            $persetujuan_c400_updated = PersetujuanC400Model::getDataById($id);

            if ($persetujuan_c400_updated->id == $id) {
                if ($persetujuan_c400_updated->file_status_c400_dosbing1 == "C400 Telah Disetujui!" &&
                    $persetujuan_c400_updated->file_status_c400_dosbing2 == "C400 Telah Disetujui!" ) {

                    $paramsUpdated = [
                        'status_kelompok' => 'C400 Telah Disetujui!',
                        'file_status_c400'=> "C400 Telah Disetujui!",
                    ];

                    // Update status kelompok
                    PersetujuanC400Model::updateKelompok($id, $paramsUpdated);
                } elseif($persetujuan_c400_updated->file_status_c400_dosbing1 == "C400 Telah Disetujui!"){
                    $paramsUpdated = [
                        'status_kelompok' => 'C400 Menunggu Persetujuan Dosbing 2!',
                        'file_status_c400'=> "C400 Menunggu Persetujuan Dosbing 2!",
                    ];

                    PersetujuanC400Model::updateKelompok($id, $paramsUpdated);
                } elseif($persetujuan_c400_updated->file_status_c400_dosbing2 == "C400 Telah Disetujui!"){
                    $paramsUpdated = [
                        'status_kelompok' => 'C400 Menunggu Persetujuan Dosbing 1!',
                        'file_status_c400'=> "C400 Menunggu Persetujuan Dosbing 1!",
                    ];

                    PersetujuanC400Model::updateKelompok($id, $paramsUpdated);
                } else {
                    $paramsUpdated = [
                        'status_kelompok' => 'Menunggu Persetujuan C400!',
                        'file_status_c400'=> "Menunggu Persetujuan C400!",
                    ];

                    PersetujuanC400Model::updateKelompok($id, $paramsUpdated);

                }

            }


            // Flash message for success
            session()->flash('success', 'Data berhasil disimpan.');
        } else {
            // Flash message for failure
            session()->flash('danger', 'Data gagal disimpan.');
        }

        return redirect('/dosen/persetujuan-c400');
    }


    public function search(Request $request)
    {
        // data request
        $nama = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_kelompok = PersetujuanC400Model::getDataSearch($nama);
            // data
            $data = ['rs_kelompok' => $rs_kelompok, 'nama' => $nama];
            // view
            return view('dosen.persetujuan-c400.index', $data);
        } else {
            return view('dosen/persetujuan-c400', $data);
        }
    }

}
