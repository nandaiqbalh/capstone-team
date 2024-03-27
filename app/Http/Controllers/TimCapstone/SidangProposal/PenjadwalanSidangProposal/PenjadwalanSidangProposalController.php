<?php

namespace App\Http\Controllers\TimCapstone\SidangProposal\PenjadwalanSidangProposal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\SidangProposal\PenjadwalanSidangProposal\PenjadwalanSidangProposalModel;
use Illuminate\Support\Facades\Hash;


class PenjadwalanSidangProposalController extends BaseController
{
    public function index()
    {

        // get data with pagination
        $rs_kelompok = PenjadwalanSidangProposalModel::getDataWithPagination();
        // dd($rs_kelompok);
        // data
        $data = ['rs_kelompok' => $rs_kelompok];
        // view
        return view('tim_capstone.sidang-proposal.penjadwalan-sidang-proposal.index', $data);
    }

    public function detailKelompok($id)
    {

        // get data with pagination
        $kelompok = PenjadwalanSidangProposalModel::getDataById($id);
        $rs_topik = PenjadwalanSidangProposalModel::getTopik();
        $rs_mahasiswa = PenjadwalanSidangProposalModel::listKelompokMahasiswa($id);
        $rs_dosbing = PenjadwalanSidangProposalModel::getAkunDosbingKelompok($id);
        $rs_penguji_proposal = PenjadwalanSidangProposalModel::getAkunPengujiProposalKelompok($id);

        // penguji avaliable
        $rs_penguji = PenjadwalanSidangProposalModel::getDosenPengujiProposal($id);


        // dd($rs_penguji);


        foreach ($rs_dosbing as $dosbing) {

            if ($dosbing->user_id == $kelompok->id_dosen_pembimbing_1) {
                $dosbing->jenis_dosen = 'Pembimbing 1';
                $dosbing->status_dosen = $kelompok->status_dosen_pembimbing_1;
            } else if ($dosbing->user_id == $kelompok->id_dosen_pembimbing_2) {
                $dosbing->jenis_dosen = 'Pembimbing 2';
                $dosbing->status_dosen = $kelompok->status_dosen_pembimbing_2;
            }

        }

        foreach ($rs_penguji_proposal as $penguji_proposal) {

            if ($penguji_proposal->user_id == $kelompok->id_dosen_penguji_1) {
                $penguji_proposal->jenis_dosen = 'Penguji 1';
                $penguji_proposal->status_dosen = $kelompok->status_dosen_penguji_1;
            } else if ($penguji_proposal->user_id == $kelompok->id_dosen_penguji_2) {
                $penguji_proposal->jenis_dosen = 'Penguji 2';
                $penguji_proposal->status_dosen = $kelompok->status_dosen_penguji_2;
            }

        }

        // check
        if (empty($kelompok)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/kelompok');
        }

        // data
        $data = [
            'kelompok' => $kelompok,
            'rs_topik' => $rs_topik,
            'rs_mahasiswa' => $rs_mahasiswa,
            'rs_dosbing' => $rs_dosbing,
            'rs_penguji_proposal' => $rs_penguji_proposal,
            'rs_penguji' => $rs_penguji,

        ];
        // dd($data);

        // view
        return view('tim_capstone.sidang-proposal.penjadwalan-sidang-proposal.detail', $data);
    }


    public function addDosenKelompok(Request $request)
    {
        // get kelompok
        $id_kelompok = $request->id_kelompok;
        $kelompok = PenjadwalanSidangProposalModel::getKelompokById($id_kelompok);


        // check if the selected position is 'penguji 1'
        if ($request->status_dosen == "penguji 1") {
            // check if penguji 1 slot is available and not the same as the selected dosen
            if ($kelompok->id_dosen_penguji_1 == null && $kelompok->id_dosen_penguji_2 != $request->id_dosen) {
                $params = [
                    'id_dosen_penguji_1' => $request->id_dosen,
                    'status_dosen_penguji_1' => 'Menunggu Persetujuan Penguji!',
                ];

                if ($request->id_dosen == $kelompok -> id_dosen_pembimbing_1 || $request->id_dosen == $kelompok -> id_dosen_pembimbing_2) {
                    session()->flash('danger', 'Dosen penguji tidak boleh sama dengan dosen pembimbing!');
                    return back();
                }

            } else {
                session()->flash('danger', 'Posisi/dosen sudah terisi!');
                return back();
            }
        }

        // check if the selected position is 'penguji 2'
        if ($request->status_dosen == "penguji 2") {
            // check if penguji 2 slot is available and not the same as the selected dosen
            if ($kelompok->id_dosen_penguji_2 == null && $kelompok->id_dosen_penguji_1 != $request->id_dosen) {
                $params = [
                    'id_dosen_penguji_2' => $request->id_dosen,
                    'status_dosen_penguji_2' => 'Menunggu Persetujuan Penguji!',
                ];

                if ($request->id_dosen == $kelompok -> id_dosen_pembimbing_1 || $request->id_dosen == $kelompok -> id_dosen_pembimbing_2) {
                    session()->flash('danger', 'Dosen penguji tidak boleh sama dengan dosen pembimbing!');
                    return back();
                }
            } else {
                session()->flash('danger', 'Posisi/dosen sudah terisi!');
                return back();
            }
        }

        if (PenjadwalanSidangProposalModel::updateKelompok($id_kelompok, $params)) {
            // update status kelompok if both pembimbing slots are filled

            $kelompok_updated = PenjadwalanSidangProposalModel::getKelompokById($id_kelompok);

            if ($kelompok_updated->id_dosen_pembimbing_1 != null && $kelompok_updated->id_dosen_pembimbing_2 != null) {
                $paramsStatusKelompok = [
                    'status_kelompok' => "Menunggu Persetujuan Penguji!"
                ];

                PenjadwalanSidangProposalModel::updateKelompok($id_kelompok, $paramsStatusKelompok);
            }
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return back();
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return back();
        }
    }

    public function deleteDosenKelompok($id_dosen, $id_kelompok)
    {

        $kelompok = PenjadwalanSidangProposalModel::getKelompokById($id_kelompok);

        $params ="";

        if ($id_dosen == $kelompok -> id_dosen_penguji_1) {
            $params = [
                'id_dosen_penguji_1' => null,
                'status_dosen_penguji_1' => null,
                'status_kelompok' => "Menunggu Persetujuan Penguji!"
            ];
        } else if ($id_dosen == $kelompok -> id_dosen_penguji_2) {
            $params = [
                'id_dosen_penguji_2' => null,
                'status_dosen_penguji_2' => null,
                'status_kelompok' => "Menunggu Persetujuan Penguji!"
            ];
        } else {
            $params = [

            ];
        }

        $dosen = PenjadwalanSidangProposalModel::updateKelompok($id_kelompok, $params);

        // if exist
        if (!empty($dosen)) {
            // process
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return back();
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return back();
        }
    }



    public function deleteKelompokProcess($id)
    {

        // get data
        $kelompok = PenjadwalanSidangProposalModel::getDataById($id);

        // if exist
        if (!empty($kelompok)) {
            $cekMhs=PenjadwalanSidangProposalModel::getKelompokMhsAll($kelompok->id);
            foreach ($cekMhs as $key => $mhs) {
                PenjadwalanSidangProposalModel::deleteKelompokMhs($mhs->id_mahasiswa);
            }

            if (PenjadwalanSidangProposalModel::deleteJadwalSidangProposal($kelompok->id)) {
                if (PenjadwalanSidangProposalModel::deleteKelompok($kelompok->id)) {
                    // flash message
                    session()->flash('success', 'Data berhasil dihapus.');
                    return back();
                } else {
                    // flash message
                    session()->flash('danger', 'Data gagal dihapus.');
                    return back();
                }
            } else {
                if (PenjadwalanSidangProposalModel::deleteKelompok($kelompok->id)) {
                    // flash message
                    session()->flash('success', 'Data berhasil dihapus.');
                    return back();
                } else {
                    // flash message
                    session()->flash('danger', 'Data gagal dihapus.');
                    return back();
                }
            }
            // process

        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return back();
        }
    }



    public function editKelompokProcess(Request $request)
    {

        // Validate & auto redirect when fail
        $rules = [
            'id' => 'required',
        ];

        $this->validate($request, $rules);

        // params
        $params = [
            "id_topik" => $request->topik,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (PenjadwalanSidangProposalModel::updateKelompok($request->id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return back();
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return back();
        }
    }

}
