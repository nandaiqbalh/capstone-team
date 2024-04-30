<?php

namespace App\Http\Controllers\Dosen\PengujianProposal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Dosen\PengujianProposal\PengujianProposalModel;
use Illuminate\Support\Facades\Hash;


class PengujianProposalController extends BaseController
{
    public function index()
    {
        // get data with pagination
        $rs_pengujian_proposal = PengujianProposalModel::getDataWithPagination();

        foreach ($rs_pengujian_proposal as $pengujian_prososal) {
            if ($pengujian_prososal->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                $pengujian_prososal->jenis_dosen = 'Pembimbing 2';
                $pengujian_prososal -> status_dosen = $pengujian_prososal ->status_dosen_pembimbing_2;

            } else if ($pengujian_prososal->id_dosen_penguji_1 == Auth::user()->user_id) {
                $pengujian_prososal->jenis_dosen = 'Penguji 1';
                $pengujian_prososal -> status_dosen = $pengujian_prososal ->status_dosen_penguji_1;

            } else if ($pengujian_prososal->id_dosen_penguji_2 == Auth::user()->user_id) {
                $pengujian_prososal->jenis_dosen = 'Penguji 2';
                $pengujian_prososal -> status_dosen = $pengujian_prososal ->status_dosen_penguji_2;

            } else {
                $pengujian_prososal->jenis_dosen = 'Belum Diplot';
                $pengujian_prososal->status_dosen = 'Belum Diplot';
            }
            $pengujian_prososal -> status_penguji1_color = $this->getStatusColor($pengujian_prososal->status_dosen_penguji_1);
            $pengujian_prososal -> status_penguji2_color = $this->getStatusColor($pengujian_prososal->status_dosen_penguji_2);
            $pengujian_prososal -> status_pembimbing1_color = $this->getStatusColor($pengujian_prososal->status_dosen_pembimbing_1);
            $pengujian_prososal -> status_pembimbing2_color = $this->getStatusColor($pengujian_prososal->status_dosen_pembimbing_2);

        }


        foreach ($rs_pengujian_proposal as $pengujian_proposal) {
            if ($pengujian_proposal != null) {
                $waktuSidang = strtotime($pengujian_proposal->waktu);

                $pengujian_proposal->hari_sidang = strftime('%A', $waktuSidang);
                $pengujian_proposal->hari_sidang = $this->convertDayToIndonesian($pengujian_proposal->hari_sidang);
                $pengujian_proposal->tanggal_sidang = date('d-m-Y', $waktuSidang);
                $pengujian_proposal->waktu_sidang = date('H:i:s', $waktuSidang);

                $waktuSelesai = strtotime($pengujian_proposal->waktu_selesai);
                $pengujian_proposal->waktu_selesai = date('H:i:s', $waktuSelesai);
            }
        }
        // data
        $data = ['rs_pengujian_proposal' => $rs_pengujian_proposal];
        // view
        return view('dosen.pengujian-proposal.index', $data);
    }

    public function detailPengujianProposalSaya($id)
    {

        // get data with pagination
        $kelompok = PengujianProposalModel::getDataById($id);
        $rs_topik = PengujianProposalModel::getTopik();
        $rs_mahasiswa = PengujianProposalModel::listKelompokMahasiswa($id);
        $rs_dosbing = PengujianProposalModel::getAkunDosbingKelompok($id);
        $rs_penguji_proposal = PengujianProposalModel::getAkunPengujiProposalKelompok($id);

        // get jadwal sidang
        $jadwal_sidang = PengujianProposalModel::getJadwalSidangProposal($id);
        if($jadwal_sidang != null){
            $waktuSidang = strtotime($jadwal_sidang->waktu);

            $jadwal_sidang->hari_sidang = strftime('%A', $waktuSidang);
            $jadwal_sidang->hari_sidang = $this->convertDayToIndonesian($jadwal_sidang->hari_sidang);
            $jadwal_sidang->tanggal_sidang = date('d-m-Y', $waktuSidang);
            $jadwal_sidang->waktu_sidang = date('H:i:s', $waktuSidang);

            $waktuSelesai = strtotime($jadwal_sidang->waktu_selesai);
            $jadwal_sidang->waktu_selesai = date('H:i:s', $waktuSelesai);

        }

        // penguji avaliable
        $rs_penguji = PengujianProposalModel::getDosenPengujiProposal($id);

        $rs_ruang_sidang = PengujianProposalModel::getRuangSidang();

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
            return redirect('/tim-capstone/kelompok');
        }

        $kelompok -> status_kelompok_color = $this->getStatusColor($kelompok->status_kelompok);
        $kelompok -> status_dokumen_color = $this->getStatusColor($kelompok->file_status_c100);
        $kelompok -> status_sidang_color = $this->getStatusColor($kelompok->status_sidang_proposal);

        $kelompok -> status_penguji1_color = $this->getStatusColor($kelompok->status_dosen_penguji_1);
        $kelompok -> status_penguji2_color = $this->getStatusColor($kelompok->status_dosen_penguji_2);
        $kelompok -> status_pembimbing1_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_1);
        $kelompok -> status_pembimbing2_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_2);

        // data
        $data = [
            'kelompok' => $kelompok,
            'rs_topik' => $rs_topik,
            'rs_mahasiswa' => $rs_mahasiswa,
            'rs_dosbing' => $rs_dosbing,
            'rs_penguji_proposal' => $rs_penguji_proposal,
            'rs_penguji' => $rs_penguji,
            'rs_ruang_sidang' => $rs_ruang_sidang,
            'jadwal_sidang' => $jadwal_sidang,

        ];
        // dd($data);

        // view
        return view('dosen.pengujian-proposal.detail', $data);
    }

    public function tolakPengujianProposalSaya(Request $request, $id)
    {

        $rs_pengujian_proposal = PengujianProposalModel::getDataWithPagination();

        $params = []; // Initialize $params outside the loop

        $jenis_dosen = ""; // Move the declaration outside the loop

        foreach ($rs_pengujian_proposal as $pengujian_proposal) {
            if ($pengujian_proposal->id_kelompok == $id) {
                if ($pengujian_proposal->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 2';
                    $params = [
                        'status_dosen_pembimbing_2' => 'Pembimbing Tidak Setuju!',
                    ];
                    break;
                } else if ($pengujian_proposal->id_dosen_penguji_1 == Auth::user()->user_id) {
                    $jenis_dosen = 'Penguji 1';
                    $params = [
                        'status_dosen_penguji_1' => 'Penguji Tidak Setuju!',
                    ];
                    break;
                } else if ($pengujian_proposal->id_dosen_penguji_2 == Auth::user()->user_id) {
                    $jenis_dosen = 'Penguji 2';
                    $params = [
                        'status_dosen_penguji_2' => 'Penguji Tidak Setuju!',
                    ];
                    break;
                }
            }
        }

        // Check if $params is still empty, indicating no matching condition was met
        if (empty($params)) {
            session()->flash('danger', 'No matching condition found for the user.');
            return redirect('/dosen/pengujian-proposal');
        }

        // process
        if (PengujianProposalModel::updateKelompok($id, $params)) {

            $paramsUpdated = [];
            $pengujian_proposal_updated = PengujianProposalModel::getDataById($id);

            if ($pengujian_proposal_updated->id == $id) {
                if ($pengujian_proposal_updated->status_dosen_pembimbing_2 == "Penguji Tidak Setuju!" &&
                    $pengujian_proposal_updated->status_dosen_penguji_1 == "Penguji Tidak Setuju!" &&
                    $pengujian_proposal_updated->status_dosen_penguji_2 == "Penguji Tidak Setuju!") {

                    $paramsUpdated = ['status_sidang_proposal' => 'Penguji Tidak Setuju!'];
                    // Update status kelompok
                    PengujianProposalModel::updateKelompok($id, $paramsUpdated);
                } else {
                    $paramsUpdated = ['status_sidang_proposal' => 'Menunggu Persetujuan Penguji!'];

                    PengujianProposalModel::updateKelompok($id, $paramsUpdated);

                }

            }
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/dosen/pengujian-proposal');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/dosen/pengujian-proposal');
        }
    }

    public function terimaPengujianProposalSaya(Request $request, $id)
    {
        $rs_pengujian_proposal = PengujianProposalModel::getDataWithPagination();
        $params = [];

        foreach ($rs_pengujian_proposal as $pengujian_proposal) {
            if ($pengujian_proposal->id_kelompok == $id) {
                if ($pengujian_proposal->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $params = ['status_dosen_pembimbing_2' => 'Pembimbing Setuju!'];
                    break;
                } else if ($pengujian_proposal->id_dosen_penguji_1 == Auth::user()->user_id) {
                    $params = ['status_dosen_penguji_1' => 'Penguji Setuju!'];
                    break;
                } else if ($pengujian_proposal->id_dosen_penguji_2 == Auth::user()->user_id) {
                    $params = ['status_dosen_penguji_2' => 'Penguji Setuju!'];
                    break;
                }
            }
        }

        // Check if $params is still empty, indicating no matching condition was met
        if (empty($params)) {
            session()->flash('danger', 'Tidak ditemukan kondisi yang cocok untuk pengguna.');
            return redirect('/dosen/pengujian-proposal');
        }

       // Process update
        if (PengujianProposalModel::updateKelompok($id, $params)) {
            $paramsUpdated = [];
            $pengujian_proposal_updated = PengujianProposalModel::getDataById($id);

            if ($pengujian_proposal_updated->id == $id) {
                if ($pengujian_proposal_updated->status_dosen_pembimbing_2 == "Pembimbing Setuju!" &&
                    $pengujian_proposal_updated->status_dosen_penguji_1 == "Penguji Setuju!" &&
                    $pengujian_proposal_updated->status_dosen_penguji_2 == "Penguji Setuju!") {

                    $paramsUpdated = ['status_kelompok' => 'Dijadwalkan Sidang Proposal!', 'status_sidang_proposal'=>"Dijadwalkan Sidang Proposal!"];
                    // Update status kelompok
                    PengujianProposalModel::updateKelompok($id, $paramsUpdated);
                } else {
                    $paramsUpdated = ['status_sidang_proposal' => 'Menunggu Persetujuan Penguji!'];

                    PengujianProposalModel::updateKelompok($id, $paramsUpdated);

                }

            }


            // Flash message for success
            session()->flash('success', 'Data berhasil disimpan.');
        } else {
            // Flash message for failure
            session()->flash('danger', 'Data gagal disimpan.');
        }

        return redirect('/dosen/pengujian-proposal');
    }


    public function detailMahasiswa($user_id)
    {

        // get data with pagination
        $mahasiswa = PengujianProposalModel::getDataMahasiswaById($user_id);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/tim-capstone/mahasiswa');
        }
        $rs_peminatan = PengujianProposalModel::peminatanMahasiswa($user_id);

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
        return view('dosen.pengujian-proposal.detail-mahasiswa', $data);
    }



    public function search(Request $request)
    {
        // data request
        $nama = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_pengujian_proposal = PengujianProposalModel::getDataSearch($nama);

            foreach ($rs_pengujian_proposal as $pengujian_prososal) {
                if ($pengujian_prososal->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $pengujian_prososal->jenis_dosen = 'Pembimbing 2';
                    $pengujian_prososal -> status_dosen = $pengujian_prososal ->status_dosen_pembimbing_2;

                } else if ($pengujian_prososal->id_dosen_penguji_1 == Auth::user()->user_id) {
                    $pengujian_prososal->jenis_dosen = 'Penguji 1';
                    $pengujian_prososal -> status_dosen = $pengujian_prososal ->status_dosen_penguji_1;

                } else if ($pengujian_prososal->id_dosen_penguji_2 == Auth::user()->user_id) {
                    $pengujian_prososal->jenis_dosen = 'Penguji 2';
                    $pengujian_prososal -> status_dosen = $pengujian_prososal ->status_dosen_penguji_2;

                } else {
                    $pengujian_prososal->jenis_dosen = 'Belum Diplot';
                    $pengujian_prososal->status_dosen = 'Belum Diplot';
                }
                $pengujian_prososal -> status_penguji1_color = $this->getStatusColor($pengujian_prososal->status_dosen_penguji_1);
                $pengujian_prososal -> status_penguji2_color = $this->getStatusColor($pengujian_prososal->status_dosen_penguji_2);
                $pengujian_prososal -> status_pembimbing1_color = $this->getStatusColor($pengujian_prososal->status_dosen_pembimbing_1);
                $pengujian_prososal -> status_pembimbing2_color = $this->getStatusColor($pengujian_prososal->status_dosen_pembimbing_2);

            }


            foreach ($rs_pengujian_proposal as $pengujian_proposal) {
                if ($pengujian_proposal != null) {
                    $waktuSidang = strtotime($pengujian_proposal->waktu);

                    $pengujian_proposal->hari_sidang = strftime('%A', $waktuSidang);
                    $pengujian_proposal->hari_sidang = $this->convertDayToIndonesian($pengujian_proposal->hari_sidang);
                    $pengujian_proposal->tanggal_sidang = date('d-m-Y', $waktuSidang);
                    $pengujian_proposal->waktu_sidang = date('H:i:s', $waktuSidang);

                    $waktuSelesai = strtotime($pengujian_proposal->waktu_selesai);
                    $pengujian_proposal->waktu_selesai = date('H:i:s', $waktuSelesai);
                }
            }
            // data
            $data = ['rs_pengujian_proposal' => $rs_pengujian_proposal, 'nama' => $nama];
            // view
            return view('dosen.pengujian-proposal.index', $data);
        } else {
            return view('dosen/pengujian-proposal', $data);
        }
    }
}
