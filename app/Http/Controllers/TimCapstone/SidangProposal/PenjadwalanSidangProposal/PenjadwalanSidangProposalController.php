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

        $rs_kelompok = PenjadwalanSidangProposalModel::getDataWithPagination();

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

        // get jadwal sidang
        $jadwal_sidang = PenjadwalanSidangProposalModel::getJadwalSidangProposal($id);
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
        $rs_penguji = PenjadwalanSidangProposalModel::getDosenPengujiProposal($id);

        $rs_ruang_sidang = PenjadwalanSidangProposalModel::getRuangSidang();


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
            'rs_ruang_sidang' => $rs_ruang_sidang,
            'jadwal_sidang' => $jadwal_sidang,

        ];
        // dd($data);

        // view
        return view('tim_capstone.sidang-proposal.penjadwalan-sidang-proposal.detail', $data);
    }

    private function convertDayToIndonesian($day)
    {
        $dayMappings = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        return array_key_exists($day, $dayMappings) ? $dayMappings[$day] : $day;
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

            if ($kelompok_updated->id_dosen_penguji_1 != null && $kelompok_updated->id_dosen_penguji_2 != null) {
                $paramsStatusKelompok = [
                    'status_kelompok' => "Penguji Proposal Ditetapkan!",
                    'status_dosen_pembimbing_2' => "Menunggu Persetujuan Pembimbing!"
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
                'status_kelompok' => "C100 Telah Disetujui!",
                'status_dosen_pembimbing_2' => "Menyetujui Dokumen C100!"

            ];
        } else if ($id_dosen == $kelompok -> id_dosen_penguji_2) {
            $params = [
                'id_dosen_penguji_2' => null,
                'status_dosen_penguji_2' => null,
                'status_kelompok' => "C100 Telah Disetujui!",
                'status_dosen_pembimbing_2' => "Menyetujui Dokumen C100!"
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

    public function addJadwalProcess(Request $request)
    {
        // Check for overlapping schedule
        $overlap = PenjadwalanSidangProposalModel::checkOverlap($request->waktu, $request->waktu_selesai, $request->ruangan_id);
        if ($overlap) {
            session()->flash('danger', 'Ruangan tersebut sudah terjadwal pada waktu yang sama.');
            return back()->withInput();
        }

        // Check if the start time is not earlier than the end time
        if ($request->waktu >= $request->waktu_selesai) {
            session()->flash('danger', 'Waktu mulai harus lebih awal dari waktu selesai.');
            return back()->withInput();
        }

        $overlapPembimbing2 = PenjadwalanSidangProposalModel::checkOverlapPembimbing2($request->waktu, $request->waktu_selesai, $request->id_dosen_penguji_1);
        if ($overlapPembimbing2) {
            session()->flash('danger', 'Dosen pembimbing 1 sudah terjadwal pada waktu yang sama.');
            return back()->withInput();
        }
        // Check for overlapping schedule with penguji 1
        $overlapPenguji1 = PenjadwalanSidangProposalModel::checkOverlapPenguji1($request->waktu, $request->waktu_selesai, $request->id_dosen_pembimbing_2);
        if ($overlapPenguji1) {
            session()->flash('danger', 'Dosen penguji 1 sudah terjadwal pada waktu yang sama.');
            return back()->withInput();
        }

        // Check for overlapping schedule with penguji 2
        $overlapPenguji2 = PenjadwalanSidangProposalModel::checkOverlapPenguji2($request->waktu, $request->waktu_selesai, $request->id_dosen_penguji_2);
        if ($overlapPenguji2) {
            session()->flash('danger', 'Dosen penguji 2 sudah terjadwal pada waktu yang sama.');
            return back()->withInput();
        }

        // Insert or update jadwal sidang proposal
        $params = [
            'siklus_id' => $request->siklus_id,
            'id_kelompok' => $request->id_kelompok,
            'waktu' => $request->waktu,
            'waktu_selesai' => $request->waktu_selesai,
            'id_dosen_pembimbing_2' => $request->id_dosen_pembimbing_2,
            'id_dosen_penguji_1' => $request->id_dosen_penguji_1,
            'id_dosen_penguji_2' => $request->id_dosen_penguji_2,
            'ruangan_id' => $request->ruangan_id,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => now()
        ];

        // Check if the entry already exists for the given id_kelompok
        $existingJadwal = PenjadwalanSidangProposalModel::getJadwalSidangProposal($request->id_kelompok);

        // If the entry exists, update it; otherwise, insert a new record
        if ($existingJadwal != null) {
            $update = PenjadwalanSidangProposalModel::updateJadwalSidangProposal($request->id_kelompok, $params);
            if ($update) {
                $paramsStatusKelompok = [
                    'status_kelompok' => 'Menunggu Persetujuan Penguji!',
                    'status_dosen_pembimbing_2' => "Menunggu Persetujuan Pembimbing!",
                    'status_dosen_penguji_1' => "Menunggu Persetujuan Penguji!",
                    'status_dosen_penguji_2' => "Menunggu Persetujuan Penguji!",
                ];

                PenjadwalanSidangProposalModel::updateKelompok($request->id_kelompok, $paramsStatusKelompok);

                session()->flash('success', 'Data berhasil diperbarui.');
                return redirect('/admin/penjadwalan-sidang-proposal');
            } else {
                session()->flash('danger', 'Gagal memperbarui data.');
                return back()->withInput();
            }
        } else {
            $insert = PenjadwalanSidangProposalModel::insertJadwalSidangProposal($params);
            if ($insert) {
                $paramsStatusKelompok = [
                    'status_kelompok' => 'Menunggu Persetujuan Penguji!',
                    'status_dosen_pembimbing_2' => "Menunggu Persetujuan Pembimbing!",
                    'status_dosen_penguji_1' => "Menunggu Persetujuan Penguji!",
                    'status_dosen_penguji_2' => "Menunggu Persetujuan Penguji!",
                ];

                PenjadwalanSidangProposalModel::updateKelompok($request->id_kelompok, $paramsStatusKelompok);

                session()->flash('success', 'Data berhasil disimpan.');
                return redirect('/admin/penjadwalan-sidang-proposal');
            } else {
                session()->flash('danger', 'Data gagal disimpan.');
                return back()->withInput();
            }
        }
    }

}
