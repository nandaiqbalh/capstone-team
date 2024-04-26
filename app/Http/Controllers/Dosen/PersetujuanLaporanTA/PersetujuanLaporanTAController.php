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
        $rs_persetujuan_lta = PersetujuanLaporanTAModel::getDataWithPagination();

        foreach ($rs_persetujuan_lta as $persetujuan_lta) {
            if ($persetujuan_lta->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                $persetujuan_lta->jenis_dosen = 'Pembimbing 1';
                $persetujuan_lta -> status_dosen = $persetujuan_lta ->status_dosen_pembimbing_1;

            } else if ($persetujuan_lta->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                $persetujuan_lta->jenis_dosen = 'Pembimbing 2';
                $persetujuan_lta -> status_dosen = $persetujuan_lta ->status_dosen_pembimbing_2;
            } else {
                $persetujuan_lta->jenis_dosen = 'Belum diplot';
                $persetujuan_lta->status_dosen = 'Belum diplot';
            }

            $persetujuan_lta -> status_dokumen_color = $this->getStatusColor($persetujuan_lta->file_status_lta);
            $persetujuan_lta -> status_dosen_color = $this->getStatusColor($persetujuan_lta->status_dosen);

        }

        // data
        $data = ['rs_persetujuan_lta' => $rs_persetujuan_lta];
        // view
        return view('dosen.persetujuan-lta.index', $data);
    }


    public function tolakPersetujuanLaporanTASaya(Request $request, $id)
    {

        $rs_persetujuan_lta = PersetujuanLaporanTAModel::getDataWithPagination();

        $params = []; // Initialize $params outside the loop

        $jenis_dosen = ""; // Move the declaration outside the loop

        foreach ($rs_persetujuan_lta as $persetujuan_lta) {

            $isMahasiswaSidangTA = PersetujuanLaporanTAModel::isMahasiswaSidangTA($id);
            if ($persetujuan_lta->id == $id) {
                if ($persetujuan_lta->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 1';
                    if ($isMahasiswaSidangTA) {
                        $params = [
                            'file_status_lta_dosbing1' => 'Final Laporan TA Tidak Disetujui Dosbing 1!',
                            'file_status_lta' => 'Final Laporan TA Tidak Disetujui Dosbing 1!',
                        ];
                    } else {
                        $params = [
                            'file_status_lta_dosbing1' => 'Laporan TA Tidak Disetujui Dosbing 1!',
                            'file_status_lta' => 'Laporan TA Tidak Disetujui Dosbing 1!',
                        ];
                    }

                    break;
                } else if ($persetujuan_lta->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 1';
                    if ($isMahasiswaSidangTA) {
                        $params = [
                            'file_status_lta_dosbing2' => 'Final Laporan TA Tidak Disetujui Dosbing 2!',
                            'file_status_lta' => 'Final Laporan TA Tidak Disetujui Dosbing 2!',
                        ];
                    } else {
                        $params = [
                            'file_status_lta_dosbing2' => 'Laporan TA Tidak Disetujui Dosbing 2!',
                            'file_status_lta' => 'Laporan TA Tidak Disetujui Dosbing 2!',
                        ];
                    }

                    break;
                }
            }
        }

        // Check if $params is still empty, indicating no matching condition was met
        if (empty($params)) {
            session()->flash('danger', 'No matching condition found for the user.');
            return redirect('/dosen/persetujuan-lta');
        }

        // process
        if (PersetujuanLaporanTAModel::updateKelompokMhs($id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/dosen/persetujuan-lta');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/dosen/persetujuan-lta');
        }
    }


    public function terimaPersetujuanLaporanTASaya(Request $request, $id)
    {
        $rs_persetujuan_lta = PersetujuanLaporanTAModel::getDataWithPagination();
        $params = [];

        foreach ($rs_persetujuan_lta as $persetujuan_lta) {
            $isMahasiswaSidangTA = PersetujuanLaporanTAModel::isMahasiswaSidangTA($id);

            if ($persetujuan_lta->id == $id) {
                if ($persetujuan_lta->id_dosen_pembimbing_1 == Auth::user()->user_id) {

                    if ($isMahasiswaSidangTA) {
                        $params = [
                            'file_status_lta_dosbing1' => 'Final Laporan TA Telah Disetujui!'
                        ];
                    } else {
                        $params = [
                            'file_status_lta_dosbing1' => 'Laporan TA Telah Disetujui!'
                        ];
                    }
                    break;
                } else if ($persetujuan_lta->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    if ($isMahasiswaSidangTA) {
                        $params = [
                            'file_status_lta_dosbing2' => 'Final Laporan TA Telah Disetujui!'
                        ];
                    } else {
                        $params = [
                            'file_status_lta_dosbing2' => 'Laporan TA Telah Disetujui!'
                        ];
                    }

                    break;
                }
            }
        }

        // Check if $params is still empty, indicating no matching condition was met
        if (empty($params)) {
            session()->flash('danger', 'Tidak ditemukan kondisi yang cocok untuk pengguna.');
            return redirect('/dosen/persetujuan-lta');
        }

       // Process update
        if (PersetujuanLaporanTAModel::updateKelompokMhs($id, $params)) {
            $paramsUpdated = [];
            $persetujuan_lta_updated = PersetujuanLaporanTAModel::getDataById($id);

            if ($persetujuan_lta_updated->id == $id) {

                if ($isMahasiswaSidangTA) {

                    // ini sudah sidang
                    if ($persetujuan_lta_updated->file_status_lta_dosbing1 == "Final Laporan TA Telah Disetujui!" &&
                    $persetujuan_lta_updated->file_status_lta_dosbing2 == "Final Laporan TA Telah Disetujui!" ) {

                    $paramsUpdated = [
                        'file_status_lta'=> "Final Laporan TA Telah Disetujui!",
                    ];

                    // Update status kelompok
                    PersetujuanLaporanTAModel::updateKelompokMhs($id, $paramsUpdated);
                    } elseif($persetujuan_lta_updated->file_status_lta_dosbing1 == "Final Laporan TA Telah Disetujui!"){
                        $paramsUpdated = [
                            'file_status_lta'=> "Final Laporan TA Menunggu Persetujuan Dosbing 2!",
                        ];

                        PersetujuanLaporanTAModel::updateKelompokMhs($id, $paramsUpdated);
                    } elseif($persetujuan_lta_updated->file_status_lta_dosbing2 == "Final Laporan TA Telah Disetujui!"){
                        $paramsUpdated = [
                            'file_status_lta'=> "Final Laporan TA Menunggu Persetujuan Dosbing 1!",
                        ];

                        PersetujuanLaporanTAModel::updateKelompokMhs($id, $paramsUpdated);
                    } else {
                        $paramsUpdated = [
                            'file_status_lta'=> "Menunggu Persetujuan Final Laporan TA!",
                        ];

                        PersetujuanLaporanTAModel::updateKelompokMhs($id, $paramsUpdated);

                    }
                } else {

                    // ini belum sidang
                    if ($persetujuan_lta_updated->file_status_lta_dosbing1 == "Laporan TA Telah Disetujui!" &&
                    $persetujuan_lta_updated->file_status_lta_dosbing2 == "Laporan TA Telah Disetujui!" ) {

                    $paramsUpdated = [
                        'file_status_lta'=> "Laporan TA Telah Disetujui!",
                    ];

                    // Update status kelompok
                    PersetujuanLaporanTAModel::updateKelompokMhs($id, $paramsUpdated);
                    } elseif($persetujuan_lta_updated->file_status_lta_dosbing1 == "Laporan TA Telah Disetujui!"){
                        $paramsUpdated = [
                            'file_status_lta'=> "Laporan TA Menunggu Persetujuan Dosbing 2!",
                        ];

                        PersetujuanLaporanTAModel::updateKelompokMhs($id, $paramsUpdated);
                    } elseif($persetujuan_lta_updated->file_status_lta_dosbing2 == "Laporan TA Telah Disetujui!"){
                        $paramsUpdated = [
                            'file_status_lta'=> "Laporan TA Menunggu Persetujuan Dosbing 1!",
                        ];

                        PersetujuanLaporanTAModel::updateKelompokMhs($id, $paramsUpdated);
                    } else {
                        $paramsUpdated = [
                            'file_status_lta'=> "Menunggu Persetujuan Laporan TA!",
                        ];

                        PersetujuanLaporanTAModel::updateKelompokMhs($id, $paramsUpdated);

                    }
                }

            }


            // Flash message for success
            session()->flash('success', 'Data berhasil disimpan.');
        } else {
            // Flash message for failure
            session()->flash('danger', 'Data gagal disimpan.');
        }

        return redirect('/dosen/persetujuan-lta');
    }


    public function search(Request $request)
    {
        // data request
        $nama = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            $rs_persetujuan_lta = PersetujuanLaporanTAModel::getDataSearch($nama);

            foreach ($rs_persetujuan_lta as $persetujuan_lta) {
                if ($persetujuan_lta->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $persetujuan_lta->jenis_dosen = 'Pembimbing 1';
                    $persetujuan_lta -> status_dosen = $persetujuan_lta ->status_dosen_pembimbing_1;

                } else if ($persetujuan_lta->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $persetujuan_lta->jenis_dosen = 'Pembimbing 2';
                    $persetujuan_lta -> status_dosen = $persetujuan_lta ->status_dosen_pembimbing_2;
                } else {
                    $persetujuan_lta->jenis_dosen = 'Belum diplot';
                    $persetujuan_lta->status_dosen = 'Belum diplot';
                }

                $persetujuan_lta -> status_dokumen_color = $this->getStatusColor($persetujuan_lta->file_status_lta);
                $persetujuan_lta -> status_dosen_color = $this->getStatusColor($persetujuan_lta->status_dosen);

            }

            // data
            $data = ['rs_persetujuan_lta' => $rs_persetujuan_lta, 'nama' => $nama];
            // view
            return view('dosen.persetujuan-lta.index', $data);
        } else {
            return view('dosen/persetujuan-lta', $data);
        }
    }

}
