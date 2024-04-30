<?php

namespace App\Http\Controllers\Dosen\KelompokBimbingan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Dosen\KelompokBimbingan\KelompokBimbinganModel;
use Illuminate\Support\Facades\Hash;


class KelompokBimbinganController extends BaseController
{


    public function index()
    {
        // get data with pagination
        $rs_bimbingan_saya = KelompokBimbinganModel::getDataWithPagination();

        foreach ($rs_bimbingan_saya as $bimbingan) {
            if ($bimbingan->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                $bimbingan->jenis_dosen = 'Pembimbing 1';
                $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_1;

            } else if ($bimbingan->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                $bimbingan->jenis_dosen = 'Pembimbing 2';
                $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_2;

            } else {
                $bimbingan->jenis_dosen = 'Belum Diplot';
            }
            $bimbingan -> status_dosen_color = $this->getStatusColor($bimbingan->status_dosen);
            $bimbingan -> status_kelompok_color = $this->getStatusColor($bimbingan->status_kelompok);


        }
        // data
        $data = ['rs_bimbingan_saya' => $rs_bimbingan_saya];
        // view
        return view('dosen.kelompok-bimbingan.index', $data);
    }

    public function detailKelompokBimbingan($id)
    {

        // get data with pagination
        $kelompok = KelompokBimbinganModel::getDataById($id);
        $rs_dosbing = KelompokBimbinganModel::getAkunDosbingKelompok($id);
        $rs_mahasiswa = KelompokBimbinganModel::getMahasiswa($kelompok->id);

        // check
        if (empty($kelompok)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/dosen/kelompok-bimbingan');
        }


        foreach ($rs_mahasiswa as $mahasiswa) {
            $mahasiswa -> status_mahasiswa_color = $this->getStatusColor($mahasiswa->status_individu);

        }

        foreach ($rs_dosbing as $dosbing) {

            if ($dosbing->user_id == $kelompok->id_dosen_pembimbing_1) {
                $dosbing->jenis_dosen = 'Pembimbing 1';
                $dosbing->status_dosen = $kelompok->status_dosen_pembimbing_1;
            } else if ($dosbing->user_id == $kelompok->id_dosen_pembimbing_2) {
                $dosbing->jenis_dosen = 'Pembimbing 2';
                $dosbing->status_dosen = $kelompok->status_dosen_pembimbing_2;
            }

        }

      // status color
      $kelompok -> status_kelompok_color = $this->getStatusColor($kelompok->status_kelompok);
      $kelompok -> status_sidang_color = $this->getStatusColor($kelompok->status_sidang_proposal);
      $kelompok -> status_dosbing1_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_1);
      $kelompok -> status_dosbing2_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_2);

      $kelompok -> status_c100_color = $this->getStatusColor($kelompok->file_status_c100);
      $kelompok -> status_c200_color = $this->getStatusColor($kelompok->file_status_c200);
      $kelompok -> status_c300_color = $this->getStatusColor($kelompok->file_status_c300);
      $kelompok -> status_c400_color = $this->getStatusColor($kelompok->file_status_c400);
      $kelompok -> status_c500_color = $this->getStatusColor($kelompok->file_status_c500);
      $kelompok -> status_sempro_color = $this->getStatusColor($kelompok->status_sidang_proposal);
      $kelompok -> status_expo_color = $this->getStatusColor($kelompok->status_expo);

        // data
        $data = [
            'kelompok' => $kelompok,
            'rs_mahasiswa' => $rs_mahasiswa,
            'rs_dosbing' => $rs_dosbing,
        ];

        // view
        return view('dosen.kelompok-bimbingan.detail', $data);
    }

    public function terimaKelompokBimbingan(Request $request, $id)
    {

        $rs_bimbingan_saya = KelompokBimbinganModel::getDataWithPagination();

        $jenis_dosen = ""; // Move the declaration outside the loop

        foreach ($rs_bimbingan_saya as $bimbingan) {
            if ($bimbingan ->id == $id) {
                if ($bimbingan->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 1';
                    $params = [
                        'status_dosen_pembimbing_1' => 'Dosbing Setuju',
                    ];
                    break;
                } else if ($bimbingan->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 2';
                    $params = [
                        'status_dosen_pembimbing_2' => 'Dosbing Setuju',
                    ];
                    break;
                }
            }
        }

        // process
        if (KelompokBimbinganModel::update($id, $params)) {

            $paramsUpdated = [];
            $bimbingan_saya_updated = KelompokBimbinganModel::getDataById($id);

            if ($bimbingan_saya_updated->id == $id) {
                if ($bimbingan_saya_updated->status_dosen_pembimbing_1 == "Dosbing Setuju" &&
                    $bimbingan_saya_updated->status_dosen_pembimbing_2 == "Dosbing Setuju") {
                    $status_kelompok = 'Menunggu Persetujuan Tim Capstone';
                } elseif ($bimbingan_saya_updated->status_dosen_pembimbing_1 == "Dosbing Diplot Tim Capstone" &&
                          $bimbingan_saya_updated->status_dosen_pembimbing_2 == "Dosbing Setuju") {
                    $status_kelompok = 'Menunggu Persetujuan Tim Capstone';
                } elseif ($bimbingan_saya_updated->status_dosen_pembimbing_1 == "Dosbing Setuju" &&
                          $bimbingan_saya_updated->status_dosen_pembimbing_2 == "Dosbing Diplot Tim Capstone") {
                    $status_kelompok = 'Menunggu Persetujuan Tim Capstone';
                } elseif ($bimbingan_saya_updated->status_dosen_pembimbing_1 == "Dosbing Diplot Tim Capstone" &&
                          $bimbingan_saya_updated->status_dosen_pembimbing_2 == "Dosbing Diplot Tim Capstone") {
                    $status_kelompok = 'Menunggu Persetujuan Tim Capstone';
                } else {
                    $status_kelompok = 'Menunggu Persetujuan Dosbing';
                }

                KelompokBimbinganModel::update($id, ['status_kelompok' => $status_kelompok]);

            }
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/dosen/kelompok-bimbingan');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/dosen/kelompok-bimbingan');
        }
    }


    public function tolakKelompokBimbingan(Request $request, $id)
    {

        $rs_bimbingan_saya = KelompokBimbinganModel::getDataWithPagination();

        $jenis_dosen = ""; // Move the declaration outside the loop

        foreach ($rs_bimbingan_saya as $bimbingan) {
            if ($bimbingan ->id == $id) {
                if ($bimbingan->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 1';
                    $params = [
                        'status_dosen_pembimbing_1' => 'Dosbing Tidak Setuju',
                    ];
                    break;
                } else if ($bimbingan->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 2';
                    $params = [
                        'status_dosen_pembimbing_2' => 'Dosbing Tidak Setuju',
                    ];
                    break;
                }

            }

        }

        // process
        if (KelompokBimbinganModel::update($id, $params)) {

            $paramsUpdated = [];
            $bimbingan_saya_updated = KelompokBimbinganModel::getDataById($id);

            if ($bimbingan_saya_updated->id == $id) {
                if ($bimbingan_saya_updated->status_dosen_pembimbing_1 == "Dosbing Tidak Setuju" &&
                    $bimbingan_saya_updated->status_dosen_pembimbing_2 == "Dosbing Tidak Setuju") {

                    $paramsUpdated = ['status_kelompok' => 'Dosbing Tidak Setuju'];
                    // Update status kelompok
                    KelompokBimbinganModel::update($id, $paramsUpdated);
                } else {
                    $paramsUpdated = ['status_kelompok' => 'Menunggu Penetapan Dosbing'];

                    KelompokBimbinganModel::update($id, $paramsUpdated);

                }

            }
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/dosen/kelompok-bimbingan');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/dosen/kelompok-bimbingan');
        }
    }

    public function search(Request $request)
    {
        // data request
        $nama = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_bimbingan_saya = KelompokBimbinganModel::getDataSearch($nama);
            foreach ($rs_bimbingan_saya as $bimbingan) {
                if ($bimbingan->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $bimbingan->jenis_dosen = 'Pembimbing 1';
                    $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_1;

                } else if ($bimbingan->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $bimbingan->jenis_dosen = 'Pembimbing 2';
                    $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_2;

                } else {
                    $bimbingan->jenis_dosen = 'Belum Diplot';
                }
                $bimbingan -> status_dosen_color = $this->getStatusColor($bimbingan->status_dosen);
                $bimbingan -> status_kelompok_color = $this->getStatusColor($bimbingan->status_kelompok);

            }
            // data
            $data = ['rs_bimbingan_saya' => $rs_bimbingan_saya, 'nama' => $nama];
            // view
            return view('dosen.kelompok-bimbingan.index', $data);
        } else {
            return view('dosen.kelompok-bimbingan.index');
        }
    }

    public function getKelompokBimbinganFilterStatus(Request $request)
    {
        // data request
        $status = $request->status;

        // new search or reset
        if ($request->action == 'filter') {
            // get data with pagination
            $rs_bimbingan_saya = KelompokBimbinganModel::getKelompokBimbinganStatus($status);
            foreach ($rs_bimbingan_saya as $bimbingan) {
                if ($bimbingan->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $bimbingan->jenis_dosen = 'Pembimbing 1';
                    $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_1;

                } else if ($bimbingan->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $bimbingan->jenis_dosen = 'Pembimbing 2';
                    $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_2;

                } else {
                    $bimbingan->jenis_dosen = 'Belum Diplot';
                }
                $bimbingan -> status_dosen_color = $this->getStatusColor($bimbingan->status_dosen);
                $bimbingan -> status_kelompok_color = $this->getStatusColor($bimbingan->status_kelompok);
            }
            // data
            $data = ['rs_bimbingan_saya' => $rs_bimbingan_saya, 'status' => $status];

            // view
            return view('dosen.kelompok-bimbingan.index', $data);
        } else {
            return view('dosen/kelompok-bimbingan', $data);
        }
    }

    public function detailMahasiswa($user_id)
    {

        // get data with pagination
        $mahasiswa = KelompokBimbinganModel::getDataMahasiswaBimbinganById($user_id);

        $mahasiswa -> status_individu_color = $this->getStatusColor($mahasiswa->status_individu);
        $mahasiswa -> status_kelompok_color = $this->getStatusColor($mahasiswa->status_kelompok);
        $mahasiswa -> status_tugas_akhir_color = $this->getStatusColor($mahasiswa->status_tugas_akhir);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/tim-capstone/mahasiswa');
        }
        $rs_peminatan = KelompokBimbinganModel::peminatanMahasiswa($user_id);

        foreach ($rs_peminatan as $key => $peminatan) {
            if ($peminatan->id == $mahasiswa->id_peminatan_individu1) {
                $peminatan->prioritas = "Prioritas 1";
            } else if($peminatan->id == $mahasiswa->id_peminatan_individu2) {
                $peminatan->prioritas = "Prioritas 2";
            }else if($peminatan->id == $mahasiswa->id_peminatan_individu3) {
                $peminatan->prioritas = "Prioritas 3";
            }else if($peminatan->id == $mahasiswa->id_peminatan_individu4) {
                $peminatan->prioritas = "Prioritas 4";
            } else {
                $peminatan->prioritas = "Belum memilih";

            }
        }
        // dd($mahasiswa);
        // data
        $data = [
            'mahasiswa' => $mahasiswa,
            'rs_peminatan'=>$rs_peminatan
        ];

        // view
        return view('dosen.mahasiswa-bimbingan.detail-mahasiswa', $data);
    }

}
