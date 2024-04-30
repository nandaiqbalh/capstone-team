<?php

namespace App\Http\Controllers\Dosen\PersetujuanMakalahTA;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Dosen\PersetujuanMakalahTA\PersetujuanMakalahTAModel;
use Illuminate\Support\Facades\Hash;


class PersetujuanMakalahTAController extends BaseController
{
    public function index()
    {
        // get data with pagination
        $rs_persetujuan_mta = PersetujuanMakalahTAModel::getDataWithPagination();

        foreach ($rs_persetujuan_mta as $persetujuan_mta) {
            if ($persetujuan_mta->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                $persetujuan_mta->jenis_dosen = 'Pembimbing 1';
                $persetujuan_mta -> status_dosen = $persetujuan_mta ->status_dosen_pembimbing_1;

            } else if ($persetujuan_mta->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                $persetujuan_mta->jenis_dosen = 'Pembimbing 2';
                $persetujuan_mta -> status_dosen = $persetujuan_mta ->status_dosen_pembimbing_2;
            } else {
                $persetujuan_mta->jenis_dosen = 'Belum diplot';
                $persetujuan_mta->status_dosen = 'Belum diplot';
            }

            $persetujuan_mta -> status_dokumen_color = $this->getStatusColor($persetujuan_mta->file_status_mta);
            $persetujuan_mta -> status_dosen_color = $this->getStatusColor($persetujuan_mta->status_dosen);

        }

        // data
        $data = ['rs_persetujuan_mta' => $rs_persetujuan_mta];
        // view
        return view('dosen.persetujuan-mta.index', $data);
    }


    public function tolakPersetujuanMakalahTASaya(Request $request, $id)
    {

        $rs_persetujuan_mta = PersetujuanMakalahTAModel::getDataWithPagination();

        $params = []; // Initialize $params outside the loop

        $jenis_dosen = ""; // Move the declaration outside the loop

        foreach ($rs_persetujuan_mta as $persetujuan_mta) {
            if ($persetujuan_mta->id == $id) {
                if ($persetujuan_mta->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 1';
                    $params = [
                        'file_status_mta_dosbing1' => 'Makalah TA Tidak Disetujui Dosbing 1',
                        'file_status_mta' => 'Makalah TA Tidak Disetujui Dosbing 1',
                    ];
                    break;
                } else if ($persetujuan_mta->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 2';
                    $params = [
                        'file_status_mta_dosbing2' => 'Makalah TA Tidak Disetujui Dosbing 2',
                        'file_status_mta' => 'Makalah TA Tidak Disetujui Dosbing 2',
                    ];
                    break;
                }
            }
        }

        // Check if $params is still empty, indicating no matching condition was met
        if (empty($params)) {
            session()->flash('danger', 'No matching condition found for the user.');
            return redirect('/dosen/persetujuan-mta');
        }

        // process
        if (PersetujuanMakalahTAModel::updateKelompokMhs($id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/dosen/persetujuan-mta');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/dosen/persetujuan-mta');
        }
    }


    public function terimaPersetujuanMakalahTASaya(Request $request, $id)
    {
        $rs_persetujuan_mta = PersetujuanMakalahTAModel::getDataWithPagination();
        $params = [];

        foreach ($rs_persetujuan_mta as $persetujuan_mta) {
            if ($persetujuan_mta->id == $id) {
                if ($persetujuan_mta->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $params = [
                        'file_status_mta_dosbing1' => 'Makalah TA Telah Disetujui'
                    ];
                    break;
                } else if ($persetujuan_mta->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $params = [
                        'file_status_mta_dosbing2' => 'Makalah TA Telah Disetujui'
                    ];

                    break;
                }
            }
        }

        // Check if $params is still empty, indicating no matching condition was met
        if (empty($params)) {
            session()->flash('danger', 'Tidak ditemukan kondisi yang cocok untuk pengguna.');
            return redirect('/dosen/persetujuan-mta');
        }

       // Process update
        if (PersetujuanMakalahTAModel::updateKelompokMhs($id, $params)) {
            $paramsUpdated = [];
            $persetujuan_mta_updated = PersetujuanMakalahTAModel::getDataById($id);

            if ($persetujuan_mta_updated->id == $id) {
                if ($persetujuan_mta_updated->file_status_mta_dosbing1 == "Makalah TA Telah Disetujui" &&
                    $persetujuan_mta_updated->file_status_mta_dosbing2 == "Makalah TA Telah Disetujui" ) {

                    $paramsUpdated = [
                        'file_status_mta'=> "Makalah TA Telah Disetujui",
                    ];

                    // Update status kelompok
                    PersetujuanMakalahTAModel::updateKelompokMhs($id, $paramsUpdated);
                } elseif($persetujuan_mta_updated->file_status_mta_dosbing1 == "Makalah TA Telah Disetujui"){
                    $paramsUpdated = [
                        'file_status_mta'=> "Makalah TA Menunggu Persetujuan Dosbing 2",
                    ];

                    PersetujuanMakalahTAModel::updateKelompokMhs($id, $paramsUpdated);
                } elseif($persetujuan_mta_updated->file_status_mta_dosbing2 == "Makalah TA Telah Disetujui"){
                    $paramsUpdated = [
                        'file_status_mta'=> "Makalah TA Menunggu Persetujuan Dosbing 1",
                    ];

                    PersetujuanMakalahTAModel::updateKelompokMhs($id, $paramsUpdated);
                } else {
                    $paramsUpdated = [
                        'file_status_mta'=> "Menunggu Persetujuan Makalah TA",
                    ];

                    PersetujuanMakalahTAModel::updateKelompokMhs($id, $paramsUpdated);

                }

            }


            // Flash message for success
            session()->flash('success', 'Data berhasil disimpan.');
        } else {
            // Flash message for failure
            session()->flash('danger', 'Data gagal disimpan.');
        }

        return redirect('/dosen/persetujuan-mta');
    }


    public function search(Request $request)
    {
        // data request
        $nama = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            $rs_persetujuan_mta = PersetujuanMakalahTAModel::getDataSearch($nama);

            foreach ($rs_persetujuan_mta as $persetujuan_mta) {
                if ($persetujuan_mta->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $persetujuan_mta->jenis_dosen = 'Pembimbing 1';
                    $persetujuan_mta -> status_dosen = $persetujuan_mta ->status_dosen_pembimbing_1;

                } else if ($persetujuan_mta->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $persetujuan_mta->jenis_dosen = 'Pembimbing 2';
                    $persetujuan_mta -> status_dosen = $persetujuan_mta ->status_dosen_pembimbing_2;
                } else {
                    $persetujuan_mta->jenis_dosen = 'Belum diplot';
                    $persetujuan_mta->status_dosen = 'Belum diplot';
                }

                $persetujuan_mta -> status_dokumen_color = $this->getStatusColor($persetujuan_mta->file_status_mta);
                $persetujuan_mta -> status_dosen_color = $this->getStatusColor($persetujuan_mta->status_dosen);

            }

            // data
            $data = ['rs_persetujuan_mta' => $rs_persetujuan_mta, 'nama' => $nama];
            // view
            return view('dosen.persetujuan-mta.index', $data);
        } else {
            return view('dosen/persetujuan-mta', $data);
        }
    }

}
