<?php

namespace App\Http\Controllers\TimCapstone\Balancing\PengujiProposal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\Balancing\PengujiProposal\PengujiProposalModel;
use Illuminate\Support\Facades\Hash;


class PengujiProposalController extends BaseController
{
    public function balancingPengujiProposal()
    {
        // get data with pagination
        $dt_dosen = PengujiProposalModel::getDataBalancingPengujiProposal();
        $rs_siklus = PengujiProposalModel::getSiklusAktif();

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
        if ($request->action == 'filter') {
            $dt_dosen = PengujiProposalModel::getDataBalancingPengujiProposalFilterSiklus($id_siklus);
            $rs_siklus = PengujiProposalModel::getSiklusAktif();
            $siklus = PengujiProposalModel::getSiklusById($id_siklus);

            // data
            $data = [
                'dt_dosen' => $dt_dosen,
                'rs_siklus' => $rs_siklus,
                'siklus' => $siklus,
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
        $rs_penguji_proposal = PengujiProposalModel::getDataPengujianProposal($user_id);

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

    public function searchBalancingPengujiProposal(Request $request)
    {
        // data request
        $user_name = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $dt_dosen = PengujiProposalModel::searchBalancingPengujiProposal($user_name);
            $rs_siklus = PengujiProposalModel::getSiklusAktif();

            // data
            $data = [
                'dt_dosen' => $dt_dosen,
                'rs_siklus' => $rs_siklus,
                'nama' => $user_name
            ];
            // view
            return view('tim_capstone.dosen.balancing.penguji-proposal.index', $data);
        } else {
            return redirect('/admin/dosen');
        }
    }


}
