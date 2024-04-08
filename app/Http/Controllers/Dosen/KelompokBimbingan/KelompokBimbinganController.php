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
                $bimbingan->jenis_dosen = 'Belum diplot';
            }
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
        $rs_mahasiswa = KelompokBimbinganModel::getMahasiswa($kelompok->id);

        // check
        if (empty($kelompok)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/dosen/kelompok-bimbingan');
        }

        // data
        $data = [
            'kelompok' => $kelompok,
            'rs_mahasiswa' => $rs_mahasiswa,
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
                        'status_dosen_pembimbing_1' => 'Persetujuan Dosbing Berhasil!',
                    ];
                    break;
                } else if ($bimbingan->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 2';
                    $params = [
                        'status_dosen_pembimbing_2' => 'Persetujuan Dosbing Berhasil!',
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
                if ($bimbingan_saya_updated->status_dosen_pembimbing_1 == "Persetujuan Dosbing Berhasil!" &&
                    $bimbingan_saya_updated->status_dosen_pembimbing_2 == "Persetujuan Dosbing Berhasil!") {

                    $paramsUpdated = ['status_kelompok' => 'Menunggu Validasi Kelompok!'];
                    // Update status kelompok
                    KelompokBimbinganModel::update($id, $paramsUpdated);
                } else {
                    $paramsUpdated = ['status_kelompok' => 'Menunggu Persetujuan Dosbing!'];

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


    public function tolakKelompokBimbingan(Request $request, $id)
    {


        $rs_bimbingan_saya = KelompokBimbinganModel::getDataWithPagination();

        $jenis_dosen = ""; // Move the declaration outside the loop

        foreach ($rs_bimbingan_saya as $bimbingan) {
            if ($bimbingan ->id == $id) {
                if ($bimbingan->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 1';
                    $params = [
                        'status_dosen_pembimbing_1' => 'Persetujuan Dosbing Gagal!',
                    ];
                    break;
                } else if ($bimbingan->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 2';
                    $params = [
                        'status_dosen_pembimbing_2' => 'Persetujuan Dosbing Gagal!',
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
                if ($bimbingan_saya_updated->status_dosen_pembimbing_1 == "Persetujuan Dosbing Gagal!" &&
                    $bimbingan_saya_updated->status_dosen_pembimbing_2 == "Persetujuan Dosbing Gagal!") {

                    $paramsUpdated = ['status_kelompok' => 'Persetujuan Dosbing Gagal!'];
                    // Update status kelompok
                    KelompokBimbinganModel::update($id, $paramsUpdated);
                } else {
                    $paramsUpdated = ['status_kelompok' => 'Menunggu Penetapan Dosbing!'];

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

    public function detailMahasiswa($user_id)
    {

        // get data with pagination
        $mahasiswa = KelompokBimbinganModel::getDataMahasiswaById($user_id);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
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
        return view('dosen.kelompok-bimbingan.detail-mahasiswa', $data);
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
                    $bimbingan->jenis_dosen = 'Belum diplot';
                }
            }
            // data
            $data = ['rs_bimbingan_saya' => $rs_bimbingan_saya, 'nama' => $nama];
            // view
            return view('dosen.kelompok-bimbingan.index', $data);
        } else {
            return view('dosen/kelompok-bimbingan', $data);
        }
    }

    public function getKelompokBimbinganFilterStatus(Request $request)
    {
        // data request
        $status = $request->status;

        // new search or reset
        if ($request->action == 'search') {
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
                    $bimbingan->jenis_dosen = 'Belum diplot';
                }
            }
            // data
            $data = ['rs_bimbingan_saya' => $rs_bimbingan_saya, 'status' => $status];
            // view
            return view('dosen.kelompok-bimbingan.index', $data);
        } else {
            return view('dosen/kelompok-bimbingan', $data);
        }
    }
}
