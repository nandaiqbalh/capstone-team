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





    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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



    /**
     * Update the specified resource in storage. update status dosen 1
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function terimaBimbinganSaya(Request $request, $id)
    {

        $rs_bimbingan_saya = BimbinganSayaModel::getDataWithPagination();

        $jenis_dosen = ""; // Move the declaration outside the loop

        foreach ($rs_bimbingan_saya as $bimbingan) {
            if ($bimbingan ->id == $id) {
                if ($bimbingan->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 1';
                    $params = [
                        'status_dosen_pembimbing_1' => 'disetujui',
                    ];
                    break;
                } else if ($bimbingan->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 2';
                    $params = [
                        'status_dosen_pembimbing_2' => 'disetujui',
                    ];
                    break;
                }
            }
        }


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
     /**
     * Update the specified resource in storage. update status dosen 2
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tolakBimbinganSaya(Request $request, $id)
    {


        $rs_bimbingan_saya = BimbinganSayaModel::getDataWithPagination();

        $jenis_dosen = ""; // Move the declaration outside the loop

        foreach ($rs_bimbingan_saya as $bimbingan) {
            if ($bimbingan ->id == $id) {
                if ($bimbingan->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 1';
                    $params = [
                        'status_dosen_pembimbing_1' => 'tidak disetujui',
                    ];
                    break;
                } else if ($bimbingan->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 2';
                    $params = [
                        'status_dosen_pembimbing_2' => 'tidak disetujui',
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

    /**
     * Search data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {

        // data request
        $nama = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_ch = BimbinganSayaModel::getDataSearch($nama);
            // data
            $data = ['rs_ch' => $rs_ch, 'nama' => $nama];
            // view
            return view('dosen.settings.contoh-halaman.index', $data);
        } else {
            return redirect('/admin/settings/contoh-halaman');
        }
    }
}
