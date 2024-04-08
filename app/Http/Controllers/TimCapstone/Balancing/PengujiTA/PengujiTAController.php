<?php

namespace App\Http\Controllers\TimCapstone\Dosen;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\Dosen\DosenModel;
use Illuminate\Support\Facades\Hash;


class DosenController extends BaseController
{

    public function index()
    {
        // get data with pagination
        $dt_dosen = DosenModel::getDataWithPagination();
        // data
        $data = ['dt_dosen' => $dt_dosen];
        // view
        return view('tim_capstone.dosen.index', $data);
    }


    public function toAktifKetersediaan1($id,) {
        // Temukan dosen berdasarkan ID
        $dosen = DosenModel::getDataById($id);

        if (!$dosen) {
            // Jika dosen tidak ditemukan, kembalikan respons error
            return back()->with('success', 'Dosen tidak ditemukan!');
        }

        $param = [
            'dosbing1' => 1,
        ];

        $update_dosen = DosenModel::update($id, $param);

        if ($update_dosen) {
             // Kembalikan respons berhasil
            return back()->with('success', 'Berhasil mengubah ketersediaan!');
        } else{
            return back()->with('danger', 'Gagal mengubah ketersediaan!');
        }

    }

    public function toAktifKetersediaan2($id,) {
        // Temukan dosen berdasarkan ID
        $dosen = DosenModel::getDataById($id);

        if (!$dosen) {
            // Jika dosen tidak ditemukan, kembalikan respons error
            return back()->with('success', 'Dosen tidak ditemukan!');
        }

        $param = [
            'dosbing2' => 1,
        ];

        $update_dosen = DosenModel::update($id, $param);

        if ($update_dosen) {
             // Kembalikan respons berhasil
            return back()->with('success', 'Berhasil mengubah ketersediaan!');
        } else{
            return back()->with('danger', 'Gagal mengubah ketersediaan!');
        }

    }

    public function toInaktifKetersediaan1($id,) {
        // Temukan dosen berdasarkan ID
        $dosen = DosenModel::getDataById($id);

        if (!$dosen) {
            // Jika dosen tidak ditemukan, kembalikan respons error
            return back()->with('success', 'Dosen tidak ditemukan!');
        }

        $param = [
            'dosbing1' => 0,
        ];

        $update_dosen = DosenModel::update($id, $param);

        if ($update_dosen) {
             // Kembalikan respons berhasil
            return back()->with('success', 'Berhasil mengubah ketersediaan!');
        } else{
            return back()->with('danger', 'Gagal mengubah ketersediaan!');
        }

    }

    public function toInaktifKetersediaan2($id,) {
        // Temukan dosen berdasarkan ID
        $dosen = DosenModel::getDataById($id);

        if (!$dosen) {
            // Jika dosen tidak ditemukan, kembalikan respons error
            return back()->with('success', 'Dosen tidak ditemukan!');
        }

        $param = [
            'dosbing2' => 0,
        ];

        $update_dosen = DosenModel::update($id, $param);

        if ($update_dosen) {
             // Kembalikan respons berhasil
            return back()->with('success', 'Berhasil mengubah ketersediaan!');
        } else{
            return back()->with('danger', 'Gagal mengubah ketersediaan!');
        }

    }

    public function addDosen()
    {
        // view
        return view('tim_capstone.dosen.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addDosenProcess(Request $request)
    {

        // Validate & auto redirect when fail
        $rules = [
            'nama' => 'required',
            "nip" => 'required',
            // "alamat" => 'required',
        ];
        $this->validate($request, $rules);


        // params
        // default passwordnya mahasiswa123
        $user_id = DosenModel::makeMicrotimeID();
        $params = [
            'user_id' => $user_id,
            'user_name' => $request->nama,
            "nomor_induk" => $request->nip,
            'role_id' =>  $request->role_id,
            'user_password' => Hash::make('dosen12345'),
            // "alamat" => $request->alamat,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        $insert_dosen = DosenModel::insertdosen($params);
        if ($insert_dosen) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/dosen');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/dosen/add')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailDosen($id)
    {

        // get data with pagination
        $dosen = DosenModel::getDataById($id);

        // check
        if (empty($dosen)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/dosen');
        }

        // data
        $data = ['dosen' => $dosen];

        // view
        return view('tim_capstone.dosen.detail', $data);
    }

    public function editDosen($user_id)
    {

        // get data
        $dosen = DosenModel::getDataById($user_id);

        // check
        if (empty($dosen)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/dosen');
        }

        // data
        $data = ['dosen' => $dosen];

        // view
        return view('tim_capstone.dosen.edit', $data);
    }

    public function editDosenProcess(Request $request)
    {

        // Validate & auto redirect when fail
        $rules = [
            'nama' => 'required',
            "nip" => 'required' ,
            // "alamat" => 'required',
        ];
        $this->validate($request, $rules);

        // params
        $params = [
            'user_name' => $request->nama,
            "nomor_induk" => $request->nip,
            'role_id' =>  $request->role,
            // "alamat" => $request->alamat,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (DosenModel::update($request->user_id, $params)) {

            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/dosen');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/dosen/edit/' . $request->user_id);
        }
    }

    public function deleteDosenProcess($id)
    {
        // get data
        $dosen = DosenModel::getDataById($id);

        // if exist
        if (!empty($dosen)) {
            // process
            if (DosenModel::delete($id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/dosen');
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/dosen');
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/settings/contoh-halaman');
        }
    }

    public function searchDosen(Request $request)
    {
        // data request
        $user_name = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $dt_dosen = DosenModel::getDataSearch($user_name);
            // dd($rs_mahasiswa);
            // data
            $data = ['dt_dosen' => $dt_dosen, 'nama' => $user_name];
            // view
            return view('tim_capstone.dosen.index', $data);
        } else {
            return redirect('/admin/dosen');
        }
    }

    public function balancingDosbingKelompok()
    {
        // get data with pagination
        $dt_dosen = DosenModel::getDataBalancingDosbingKelompok();
        $rs_siklus = DosenModel::getSiklusAktif();

        // data
        $data = [
            'dt_dosen' => $dt_dosen,
            'rs_siklus' => $rs_siklus,
        ];
        // view
        return view('tim_capstone.dosen.balancing.dosbing-kelompok.index', $data);
    }

    public function filterBalancingDosbingKelompok(Request $request)
    {
        // data request
        $id_siklus = $request->id_siklus;

        // new search or reset
        if ($request->action == 'search') {
            $dt_dosen = DosenModel::getDataBalancingDosbingKelompokFilterSiklus($id_siklus);
            $rs_siklus = DosenModel::getSiklusAktif();

            // data
            $data = [
                'dt_dosen' => $dt_dosen,
                'rs_siklus' => $rs_siklus,
            ];
            // view
            return view('tim_capstone.dosen.balancing.dosbing-kelompok.index', $data);
        } else {
            return redirect('/admin/balancing-dosbing-kelompok');
        }
    }

    public function detailBalancingDosbingKelompok($user_id)
    {
        // get data with pagination
        $rs_bimbingan = DosenModel::getDataBimbinganDosbingKelompok($user_id);

        foreach ($rs_bimbingan as $bimbingan) {
            if ($bimbingan->id_dosen_pembimbing_1 == $user_id) {
                $bimbingan->jenis_dosen = 'Pembimbing 1';
                $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_1;

            } else if ($bimbingan->id_dosen_pembimbing_2 == $user_id) {
                $bimbingan->jenis_dosen = 'Pembimbing 2';
                $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_2;

            } else {
                $bimbingan->jenis_dosen = 'Belum diplot';
            }
        }
        // data
        $data = ['rs_bimbingan' => $rs_bimbingan];
        // view
        return view('tim_capstone.dosen.balancing.dosbing-kelompok.detail', $data);
    }

    public function searchBalancingDosbingKelompok(Request $request)
    {
        // data request
        $user_name = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $dt_dosen = DosenModel::searchBalancingDosbingKelompok($user_name);
            $rs_siklus = DosenModel::getSiklusAktif();

            // data
            $data = [
                'dt_dosen' => $dt_dosen,
                'rs_siklus' => $rs_siklus,
                'nama' => $user_name
            ];
            // view
            return view('tim_capstone.dosen.balancing.dosbing-kelompok.index', $data);
        } else {
            return redirect('/admin/dosen');
        }
    }

    public function balancingDosbingMahasiswa()
    {
        // get data with pagination
        $dt_dosen = DosenModel::getDataBalancingDosbingMahasiswa();
        $rs_siklus = DosenModel::getSiklusAktif();

        dd($dt_dosen);
        // data
        $data = [
            'dt_dosen' => $dt_dosen,
            'rs_siklus' => $rs_siklus,
        ];
        // view
        return view('tim_capstone.dosen.balancing.dosbing-mahasiswa.index', $data);
    }

    public function filterBalancingDosbingMahasiswa(Request $request)
    {
        // data request
        $id_siklus = $request->id_siklus;

        // new search or reset
        if ($request->action == 'search') {
            $dt_dosen = DosenModel::getDataBalancingDosbingMahasiswaFilterSiklus($id_siklus);
            $rs_siklus = DosenModel::getSiklusAktif();

            // data
            $data = [
                'dt_dosen' => $dt_dosen,
                'rs_siklus' => $rs_siklus,
            ];
            // view
            return view('tim_capstone.dosen.balancing.dosbing-mahasiswa.index', $data);
        } else {
            return redirect('/admin/balancing-dosbing-mahasiswa');
        }
    }

    public function detailBalancingDosbingMahasiswa($user_id)
    {
        // get data with pagination
        $rs_bimbingan = DosenModel::getDataBimbinganDosbingMahasiswa($user_id);

        // data
        $data = ['rs_bimbingan' => $rs_bimbingan];
        // view
        return view('tim_capstone.dosen.balancing.dosbing-mahasiswa.detail', $data);
    }

    public function balancingPengujiProposal()
    {
        // get data with pagination
        $dt_dosen = DosenModel::getDataBalancingPengujiProposal();
        $rs_siklus = DosenModel::getSiklusAktif();

        // data
        $data = [
            'dt_dosen' => $dt_dosen,
            'rs_siklus' => $rs_siklus,
        ];
        // view
        return view('tim_capstone.dosen.balancing.penguji-proposal.index', $data);
    }

    public function filterBalancingPengujiProposal(Request $request)
    {
        // data request
        $id_siklus = $request->id_siklus;

        // new search or reset
        if ($request->action == 'search') {
            $dt_dosen = DosenModel::getDataBalancingPengujiProposalFilterSiklus($id_siklus);
            $rs_siklus = DosenModel::getSiklusAktif();

            // data
            $data = [
                'dt_dosen' => $dt_dosen,
                'rs_siklus' => $rs_siklus,
            ];
            // view
            return view('tim_capstone.dosen.balancing.penguji-proposal.index', $data);
        } else {
            return redirect('/admin/balancing-penguji-proposal');
        }
    }

    public function detailBalancingPengujiProposal($user_id)
    {
        // get data with pagination
        $rs_penguji_proposal = DosenModel::getDataPengujianProposal($user_id);

        foreach ($rs_penguji_proposal as $penguji_proposal) {
            if ($penguji_proposal->id_dosen_penguji_1 == $user_id) {
                $penguji_proposal->jenis_dosen = 'Pembimbing 1';
                $penguji_proposal -> status_dosen = $penguji_proposal ->status_dosen_penguji_1;

            } else if ($penguji_proposal->id_dosen_penguji_2 == $user_id) {
                $penguji_proposal->jenis_dosen = 'Pembimbing 2';
                $penguji_proposal -> status_dosen = $penguji_proposal ->status_dosen_penguji_2;

            } else {
                $penguji_proposal->jenis_dosen = 'Belum diplot';
            }
        }
        // data
        $data = ['rs_penguji_proposal' => $rs_penguji_proposal];
        // view
        return view('tim_capstone.dosen.balancing.penguji-proposal.detail', $data);
    }




}
