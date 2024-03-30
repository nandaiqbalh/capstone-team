<?php

namespace App\Http\Controllers\Dosen\Bimbingan_Saya;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Dosen\Bimbingan_Saya\BimbinganSayaModel;
use Illuminate\Support\Facades\Hash;


class BimbinganSayaController extends BaseController
{


    public function index()
    {
        // get data with pagination
        $rs_bimbingan_saya = BimbinganSayaModel::getDataWithPagination();
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
        return view('dosen.bimbingan-saya.index', $data);
    }

    public function detailBimbinganSaya($id)
    {

        // get data with pagination
        $kelompok = BimbinganSayaModel::getDataById($id);
        $rs_mahasiswa = BimbinganSayaModel::getMahasiswa($kelompok->id);

        // check
        if (empty($kelompok)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/dosen/bimbingan-saya');
        }

        // data
        $data = [
            'kelompok' => $kelompok,
            'rs_mahasiswa' => $rs_mahasiswa,
        ];

        // view
        return view('dosen.bimbingan-saya.detail', $data);
    }

    public function terimaBimbinganSaya(Request $request, $id)
    {

        $rs_bimbingan_saya = BimbinganSayaModel::getDataWithPagination();

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
        if (BimbinganSayaModel::update($id, $params)) {
            $rs_bimbingan_saya_updated = BimbinganSayaModel::getDataWithPagination();
            foreach ($rs_bimbingan_saya_updated as $bimbingan_saya) {
                if ($bimbingan_saya->id == $id) {
                    if ($bimbingan_saya->status_dosen_pembimbing_1 == "Persetujuan Dosbing Berhasil!" && $bimbingan_saya->status_dosen_pembimbing_2 == "Persetujuan Dosbing Berhasil!") {
                        $paramsStatusKelompok = ['status_kelompok' => 'Menunggu Validasi Kelompok!'];
                    }
                }
            }

            BimbinganSayaModel::update($id, $paramsStatusKelompok);
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/dosen/bimbingan-saya');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/dosen/bimbingan-saya');
        }
    }


    public function tolakBimbinganSaya(Request $request, $id)
    {


        $rs_bimbingan_saya = BimbinganSayaModel::getDataWithPagination();

        $jenis_dosen = ""; // Move the declaration outside the loop

        foreach ($rs_bimbingan_saya as $bimbingan) {
            if ($bimbingan ->id == $id) {
                if ($bimbingan->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 1';
                    $params = [
                        'status_dosen_pembimbing_1' => 'Persetujuan Dosbing Gagal!',
                        'status_kelompok' => 'Menunggu Penetapan Dosbing!',
                    ];
                    break;
                } else if ($bimbingan->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 2';
                    $params = [
                        'status_dosen_pembimbing_2' => 'Persetujuan Dosbing Gagal!',
                        'status_kelompok' => 'Menunggu Penetapan Dosbing!',
                    ];
                    break;
                }

            }

        }

        // dd($params);

        // process
        if (BimbinganSayaModel::update($id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/dosen/bimbingan-saya');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/dosen/bimbingan-saya');
        }
    }

    public function detailMahasiswa($user_id)
    {

        // get data with pagination
        $mahasiswa = BimbinganSayaModel::getDataMahasiswaById($user_id);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }
        $rs_peminatan = BimbinganSayaModel::peminatanMahasiswa($user_id);

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
        return view('dosen.bimbingan-saya.detail-mahasiswa', $data);
    }



    public function search(Request $request)
    {
        // data request
        $nama = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_kelompok = BimbinganSayaModel::getDataSearch($nama);
            // data
            $data = ['rs_kelompok' => $rs_kelompok, 'nama' => $nama];
            // view
            return view('dosen.bimbingan-saya.index', $data);
        } else {
            return view('dosen/bimbingan-saya', $data);
        }
    }
}
