<?php

namespace App\Http\Controllers\TimCapstone\SidangTA\PeriodeSidangTA;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\SidangTA\PeriodeSidangTA\PeriodeSidangTAModel;
use Illuminate\Support\Facades\Hash;

class PeriodeSidangTAController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get data with pagination
        $rs_jadwal_periode_sidang_ta = PeriodeSidangTAModel::getDataWithPagination();
        // data
        $data = ['rs_jadwal_periode_sidang_ta' => $rs_jadwal_periode_sidang_ta];
        // view
        return view('tim_capstone.sidang-ta.periode-sidang-ta.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addPeriodeSidangTA()
    {
        // view
        return view('tim_capstone.sidang-ta.periode-sidang-ta.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addPeriodeSidangTAProcess(Request $request)
    {
        // Validate & auto redirect when fail
        $rules = [
            'nama_periode' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
        ];
        $this->validate($request, $rules);

        // params

        $params = [
            'nama_periode' => $request->nama_periode,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        $insert_periode_sidang_ta = PeriodeSidangTAModel::insertjadwal_periode_sidang_ta($params);
        if ($insert_periode_sidang_ta) {

            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/periode-sidang-ta');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/siklus/periode-sidang-ta/add')->withInput();
        }
    }

    public function terimaMahasiswa($id)
    {
        // Params
        $params = ['status' => 'Menunggu Penjadwalan Sidang TA!'];

        // Get data pendaftaran
        $dataPendaftarSidangTA = PeriodeSidangTAModel::getDataMahasiswa($id);

        if ($dataPendaftarSidangTA) { // Periksa apakah data pendaftaran ditemukan
            // Process
            if (PeriodeSidangTAModel::updatePendaftaranSidangTA($id, $params)) {
                $paramKelompok = [
                    'status_tugas_akhir' => "Menunggu Penjadwalan Sidang TA!",
                    'status_individu' => "Menunggu Penjadwalan Sidang TA!"];
                if (PeriodeSidangTAModel::updateKelompokMhs($id, $paramKelompok)) {
                    // Flash message for success
                    session()->flash('success', 'Data berhasil disimpan.');
                    return back();
                } else {
                    // Flash message for failure
                    session()->flash('danger', 'Gagal memperbarui status mahasiswa.');
                    return back();
                }
            } else {
                // Flash message for failure
                session()->flash('danger', 'Gagal memperbarui status pendaftaran.');
                return back();
            }
        } else {
            // Flash message for failure
            session()->flash('danger', 'Data pendaftaran tidak ditemukan.');
            return back();
        }
    }

    public function tolakMahasiswa($id)
    {
        // Params
        $params = ['status' => 'Berkas TA Tidak Disetujui!'];

        // Get data pendaftaran
        $dataPendaftarSidangTA = PeriodeSidangTAModel::getDataMahasiswa($id);

        if ($dataPendaftarSidangTA) { // Periksa apakah data pendaftaran ditemukan
            // Process
            if (PeriodeSidangTAModel::updatePendaftaranSidangTA($id, $params)) {
                $paramKelompok = [
                    'status_tugas_akhir' => "Berkas TA Tidak Disetujui!",
                    'status_individu' => "Berkas TA Tidak Disetujui!"];
                if (PeriodeSidangTAModel::updateKelompokMhs($id, $paramKelompok)) {
                    // Flash message for success
                    session()->flash('success', 'Data berhasil disimpan.');
                    return back();
                } else {
                    // Flash message for failure
                    session()->flash('danger', 'Gagal memperbarui status mahasiswa.');
                    return back();
                }
            } else {
                // Flash message for failure
                session()->flash('danger', 'Gagal memperbarui status pendaftaran.');
                return back();
            }
        } else {
            // Flash message for failure
            session()->flash('danger', 'Data pendaftaran tidak ditemukan.');
            return back();
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPeriodeSidangTA($id)
    {

        // get data
        $periode_sidang_ta = PeriodeSidangTAModel::getDataById($id);

        // check
        if (empty($periode_sidang_ta)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/periode-sidang-ta');
        }

        // data
        $data = ['periode_sidang_ta' => $periode_sidang_ta];

        // view
        return view('tim_capstone.sidang-ta.periode-sidang-ta.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPeriodeSidangTAProcess(Request $request)
    {
        // Validate & auto redirect when fail
        $rules = [
            'nama_periode' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
        ];
        $this->validate($request, $rules);

        // params
        $params = [
            'nama_periode' => $request->nama_periode,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (PeriodeSidangTAModel::update($request->id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/periode-sidang-ta');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/periode-sidang-ta' . $request->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePeriodeSidangTAProcess($id)
    {
        // get data
        $periode_sidang_ta =PeriodeSidangTAModel::getDataById($id);

        // if exist
        if (!empty($periode_sidang_ta)) {
            // process
            if (PeriodeSidangTAModel::delete($id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/periode-sidang-ta');
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/periode-sidang-ta');
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/periode-sidang-ta');
        }
    }

    public function detailPeriodeSidangTA($id)
    {
        // Get data with pagination
        $sidang_ta = PeriodeSidangTAModel::getDataById($id);

        // Check
        if (empty($sidang_ta)) {
            // Flash message
            session()->flash('danger', 'Belum ada mahasiswa yang mendaftar!');
            return redirect('/admin/periode-sidang-ta');
        }

        $rs_pendaftar_sidangta = PeriodeSidangTAModel::getPendaftarSidangTA($id);

        foreach ($rs_pendaftar_sidangta as $pendaftar_sidangta) {
            $pendaftar_sidangta->color_sidangta = $this->getStatusColor($pendaftar_sidangta->status_tugas_akhir);
            $pendaftar_sidangta->status_color_penguji1 = $this->getStatusColor($pendaftar_sidangta->status_dosen_penguji_ta1);
            $pendaftar_sidangta->status_color_penguji2 = $this->getStatusColor($pendaftar_sidangta->status_dosen_penguji_ta2);
        }

        // Data
        $data = [
            'sidang_ta' => $sidang_ta,
            'rs_pendaftar_sidangta' => $rs_pendaftar_sidangta
        ];

        // View
        return view('tim_capstone.sidang-ta.periode-sidang-ta.detail', $data);
    }

    public function detailPenjadwalanSidangTA($id)
    {
        // get data with pagination
        $mahasiswa = PeriodeSidangTAModel::pengecekan_kelompok_mahasiswa($id);
        $rs_dosbing = PeriodeSidangTAModel::getAkunDosbingKelompok($mahasiswa->id_kelompok);
        $rs_penguji_ta = PeriodeSidangTAModel::getAkunPengujiTAKelompok($id);

        if(PeriodeSidangTAModel::countMahasiswaJadwal($mahasiswa->id_kelompok) == 0){
            $rs_mahasiswa = PeriodeSidangTAModel::listMahasiswaSendiri($id, $mahasiswa->id_kelompok);
        } else {
            $rs_mahasiswa = PeriodeSidangTAModel::listMahasiswa($mahasiswa->id_kelompok);
        }
        // get jadwal sidang
        $jadwal_sidang = PeriodeSidangTAModel::getJadwalSidangTA($id);
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
        $rs_penguji = PeriodeSidangTAModel::getDosenPengujiTA();

        $rs_ruang_sidang = PeriodeSidangTAModel::getRuangSidang();

        // dd($rs_penguji);


        foreach ($rs_dosbing as $dosbing) {

            if ($dosbing->user_id == $mahasiswa->id_dosen_pembimbing_1) {
                $dosbing->jenis_dosen = 'Pembimbing 1';
                $dosbing->status_dosen = $mahasiswa->status_dosen_pembimbing_1;
            } else if ($dosbing->user_id == $mahasiswa->id_dosen_pembimbing_2) {
                $dosbing->jenis_dosen = 'Pembimbing 2';
                $dosbing->status_dosen = $mahasiswa->status_dosen_pembimbing_2;
            }

        }

        foreach ($rs_penguji_ta as $penguji_ta) {
            if ($penguji_ta->user_id == $mahasiswa->id_dosen_penguji_ta1) {
                $penguji_ta->jenis_dosen = 'Penguji 1';
                $penguji_ta->status_dosen = $mahasiswa->status_dosen_penguji_ta1;
            } else if ($penguji_ta->user_id == $mahasiswa->id_dosen_penguji_ta2) {
                $penguji_ta->jenis_dosen = 'Penguji 2';
                $penguji_ta->status_dosen = $mahasiswa->status_dosen_penguji_ta2;
            }
        }

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }

        $mahasiswa -> status_kelompok_color = $this->getStatusColor($mahasiswa->status_kelompok);
        $mahasiswa -> status_dokumen_color = $this->getStatusColor($mahasiswa->file_status_c100);
        $mahasiswa -> status_sidang_color = $this->getStatusColor($mahasiswa->status_tugas_akhir);

        $mahasiswa -> status_penguji1_color = $this->getStatusColor($mahasiswa->status_dosen_penguji_1);
        $mahasiswa -> status_penguji2_color = $this->getStatusColor($mahasiswa->status_dosen_penguji_2);
        $mahasiswa -> status_pembimbing1_color = $this->getStatusColor($mahasiswa->status_dosen_pembimbing_1);
        $mahasiswa -> status_pembimbing2_color = $this->getStatusColor($mahasiswa->status_dosen_pembimbing_2);

        // data
        $data = [
            'mahasiswa' => $mahasiswa,
            'rs_dosbing' => $rs_dosbing,
            'rs_mahasiswa' => $rs_mahasiswa,
            'rs_penguji_ta' => $rs_penguji_ta,
            'rs_penguji' => $rs_penguji,
            'rs_ruang_sidang' => $rs_ruang_sidang,
            'jadwal_sidang' => $jadwal_sidang,

        ];
        // dd($data);

        dd($mahasiswa);
        // view
        return view('tim_capstone.sidang-ta.periode-sidang-ta.penjadwalan', $data);
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
        $nama_periode = $request->nama_periode;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_ch =PeriodeSidangTAMOdel::getDataSearch($nama);
            // data
            $data = ['rs_ch' => $rs_ch, 'nama_periode' => $nama_periode];
            // view
            return view('tim_capstone.settings.contoh-halaman.index', $data);
        } else {
            return redirect('/admin/settings/contoh-halaman');
        }
    }

    public function addDosenKelompok(Request $request)
    {
        // get kelompok
        $id_mahasiswa = $request->id_mahasiswa;
        $kelompok_mhs = PeriodeSidangTAModel::getKelompokMhsById($id_mahasiswa);
        $kelompok = PeriodeSidangTAModel::pengecekan_kelompok_mahasiswa($id_mahasiswa);

        // check if the selected position is 'penguji 1'
        if ($request->status_dosen == "penguji 1") {
            // check if penguji 1 slot is available and not the same as the selected dosen
            if ($kelompok_mhs->id_dosen_penguji_ta1 == null && $kelompok_mhs->id_dosen_penguji_ta2 != $request->id_dosen) {
                $params = [
                    'id_dosen_penguji_ta1' => $request->id_dosen,
                    'status_dosen_penguji_ta1' => 'Menunggu Persetujuan Penguji!',
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
            if ($kelompok_mhs->id_dosen_penguji_ta2 == null && $kelompok_mhs->id_dosen_penguji_ta1 != $request->id_dosen) {
                $params = [
                    'id_dosen_penguji_ta2' => $request->id_dosen,
                    'status_dosen_penguji_ta2' => 'Menunggu Persetujuan Penguji!',
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


        if (PeriodeSidangTAModel::updateKelompokMhs($id_mahasiswa, $params)) {
            // update status kelompok if both pembimbing slots are filled

            $kelompok_mhs_updated = PeriodeSidangTAModel::getKelompokMhsById($id_mahasiswa);

            if ($kelompok_mhs_updated->id_dosen_penguji_ta1 != null && $kelompok_mhs_updated->id_dosen_penguji_ta2 != null) {
                $paramsStatusKelompok = [
                    'status_tugas_akhir' => "Menunggu Persetujuan Penguji!",
                ];

                PeriodeSidangTAModel::updateKelompokMhs($id_mahasiswa, $paramsStatusKelompok);
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

    public function deleteDosenKelompok($id_dosen, $id_mahasiswa)
    {

        $kelompok_mhs = PeriodeSidangTAModel::getKelompokMhsById($id_mahasiswa);
        $kelompok = PeriodeSidangTAModel::pengecekan_kelompok_mahasiswa($id_mahasiswa);

        $params ="";

        if ($id_dosen == $kelompok_mhs -> id_dosen_penguji_ta1) {
            $params = [
                'id_dosen_penguji_ta1' => null,
                'status_dosen_penguji_ta1' => null,

            ];
        } else if ($id_dosen == $kelompok_mhs -> id_dosen_penguji_ta2) {
            $params = [
                'id_dosen_penguji_ta2' => null,
                'status_dosen_penguji_ta2' => null,
            ];
        } else {
            $params = [

            ];
        }

        $dosen = PeriodeSidangTAModel::updateKelompokMhs($id_mahasiswa, $params);

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

        // Memasukkan atau memperbarui jadwal sidang proposal
        $params = [
            'id_mahasiswa' => $request->id_mahasiswa,
            'id_kelompok' => $request->id_kelompok,
            'waktu' => $request->waktu,
            'waktu_selesai' => $request->waktu_selesai,
            'id_dosen_penguji_ta1' => $request->id_dosen_penguji_ta1,
            'id_dosen_penguji_ta2' => $request->id_dosen_penguji_ta2,
            'ruangan_id' => $request->ruangan_id,
            'created_by' => Auth::user()->user_id,
            'created_date' => now()
        ];

        // Mendapatkan data jadwal sidang proposal berdasarkan id_kelompok
        $existingJadwal = PeriodeSidangTAModel::getJadwalSidangProposal($request->id_kelompok);

        // Jika data sudah ada, lakukan update; jika tidak, lakukan insert
        if ($existingJadwal != null) {
            // Melakukan update jadwal sidang proposal
            $update = PeriodeSidangTAModel::updateJadwalSidangProposal($existingJadwal->id, $params);
            if ($update) {
                session()->flash('success', 'Data berhasil diperbarui.');
            } else {
                session()->flash('danger', 'Gagal memperbarui data.');
            }
        } else {
             // Validasi pilihan dosen penguji
            if ($request->id_dosen_penguji_ta1 == null || $request->id_dosen_penguji_ta2 == null) {
                session()->flash('danger', 'Dosen penguji 1 dan penguji 2 harus dipilih.');
                return back()->withInput();
            }

            // Validasi overlapping schedule
            $overlap = PeriodeSidangTAModel::checkOverlap($request->waktu, $request->waktu_selesai, $request->ruangan_id);
            if ($overlap) {
                session()->flash('danger', 'Ruangan tersebut sudah terjadwal pada waktu yang sama.');
                return back()->withInput();
            }

            // Validasi waktu mulai dan selesai
            if ($request->waktu >= $request->waktu_selesai) {
                session()->flash('danger', 'Waktu mulai harus lebih awal dari waktu selesai.');
                return back()->withInput();
            }
            // Melakukan insert jadwal sidang proposal baru
            $insert = PeriodeSidangTAModel::insertJadwalSidangProposal($params);
            if ($insert) {
                session()->flash('success', 'Data berhasil disimpan.');
            } else {
                session()->flash('danger', 'Data gagal disimpan.');
            }
        }

        // Update status kelompok
        $paramsStatusKelompok = [
            'status_sidang_proposal' => 'Menunggu Persetujuan Penguji!',
            'status_dosen_pembimbing_2' => 'Menunggu Persetujuan Pembimbing!',
            'status_dosen_penguji_ta1' => 'Menunggu Persetujuan Penguji!',
            'status_dosen_penguji_ta2' => 'Menunggu Persetujuan Penguji!'
        ];
        PeriodeSidangTAModel::updateKelompok($request->id_kelompok, $paramsStatusKelompok);

        return redirect('/admin/periode-sidang-ta');
    }
}
