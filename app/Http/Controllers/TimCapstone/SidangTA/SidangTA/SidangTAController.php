<?php

namespace App\Http\Controllers\TimCapstone\SidangTA\SidangTA;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\SidangTA\SidangTA\SidangTAModel;
use Illuminate\Support\Facades\Hash;

class SidangTAController extends BaseController
{

    public function index()
    {
        // get data with pagination
        $rs_jadwal_periode_sidang_ta = SidangTAModel::getDataPeriodeWithPagination();
        // data
        $data = ['rs_jadwal_periode_sidang_ta' => $rs_jadwal_periode_sidang_ta];
        // view
        return view('tim_capstone.sidang-ta.sidang-ta.index', $data);
    }

    public function addPeriodeSidangTA()
    {
        // view
        return view('tim_capstone.sidang-ta.sidang-ta.add');
    }

    public function addPeriodeSidangTAProcess(Request $request)
    {
        // Validate & auto redirect when fail
        $rules = [
            'nama_periode' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ];

        $messages = [
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
        ];

        $this->validate($request, $rules, $messages);

        // Params
        $params = [
            'nama_periode' => $request->nama_periode,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'created_by' => Auth::user()->user_id,
            'created_date' => now()->format('Y-m-d H:i:s'),
        ];

        // Process
        $insert_periode_sidang_ta = SidangTAModel::insertjadwal_periode_sidang_ta($params);

        if ($insert_periode_sidang_ta) {
            // Flash message for success
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/sidang-ta');
        } else {
            // Flash message for failure
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/siklus/sidang-ta/add')->withInput();
        }
    }

    public function editPeriodeSidangTA($id)
    {

        // get data
        $periode_sidang_ta = SidangTAModel::getDataPeriodeById($id);

        // check
        if (empty($periode_sidang_ta)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/sidang-ta');
        }

        // data
        $data = ['periode_sidang_ta' => $periode_sidang_ta];

        // view
        return view('tim_capstone.sidang-ta.sidang-ta.edit', $data);
    }

    public function editPeriodeSidangTAProcess(Request $request)
    {
        // Validate & auto redirect when fail
        $rules = [
            'nama_periode' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ];

        $messages = [
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
        ];

        $this->validate($request, $rules, $messages);

        // Params
        $params = [
            'nama_periode' => $request->nama_periode,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'modified_by' => Auth::user()->user_id,
            'modified_date' => now()->format('Y-m-d H:i:s'),
        ];

        // Process
        if (SidangTAModel::update($request->id, $params)) {
            // Flash message for success
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/sidang-ta');
        } else {
            // Flash message for failure
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/sidang-ta/' . $request->id);
        }
    }


    public function deletePeriodeSidangTAProcess($id)
    {
        // get data
        $periode_sidang_ta = SidangTAModel::getDataPeriodeById($id);

        $rs_mahasiswa_sidang_ta = SidangTAModel::getMahasiswaSidangTA($id);

        foreach ($rs_mahasiswa_sidang_ta as $mahasiswa_sidang_ta) {

            $paramsKelompokMhs = [
                'status_tugas_akhir' => 'Belum Mendaftar Sidang TA!',
                'is_mendaftar_sidang' => '0',
                'status_individu' => 'Lulus Expo Project!',
                'status_dosen_penguji_ta1' => NULL,
                'status_dosen_penguji_ta2' => NULL,
                'id_dosen_penguji_ta1' => NULL,
                'id_dosen_penguji_ta2' => NULL
            ];

            SidangTAModel::updateKelompokMhs($mahasiswa_sidang_ta->id_mahasiswa, $paramsKelompokMhs);

        }
        // if exist
        if (!empty($periode_sidang_ta)) {
            // process
            if (SidangTAModel::delete($id)) {

                SidangTAModel::deleteJadwalSidangTA($id);

                SidangTAModel::deletePendaftaranSidangTA($id);
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/sidang-ta');
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/sidang-ta');
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/sidang-ta');
        }
    }

    public function terimaMahasiswa($id)
    {
        // Params
        $params = ['status' => 'Menunggu Penjadwalan Sidang TA!'];

        // Get data pendaftaran
        $dataPendaftarSidangTA = SidangTAModel::getDataMahasiswa($id);

        if ($dataPendaftarSidangTA) { // Periksa apakah data pendaftaran ditemukan
            // Process
            if (SidangTAModel::updatePendaftaranSidangTA($id, $params)) {
                $paramKelompok = [
                    'status_tugas_akhir' => "Menunggu Penjadwalan Sidang TA!",
                    'status_individu' => "Menunggu Penjadwalan Sidang TA!"];
                if (SidangTAModel::updateKelompokMhs($id, $paramKelompok)) {
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
        $params = ['status' => 'Pendaftaran Sidang Tidak Disetujui!'];

        // Get data pendaftaran
        $dataPendaftarSidangTA = SidangTAModel::getDataMahasiswa($id);

        if ($dataPendaftarSidangTA) { // Periksa apakah data pendaftaran ditemukan
            // Process
            if (SidangTAModel::updatePendaftaranSidangTA($id, $params)) {
                $paramKelompok = [
                    'status_tugas_akhir' => "Pendaftaran Sidang Tidak Disetujui!",
                    'status_individu' => "Pendaftaran Sidang Tidak Disetujui!",
                    'status_dosen_penguji_ta1' => NULL,
                    'status_dosen_penguji_ta2' => NULL,
                    'id_dosen_penguji_ta1' => NULL,
                    'id_dosen_penguji_ta2' => NULL
                ];
                if (SidangTAModel::updateKelompokMhs($id, $paramKelompok)) {
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

    public function detailPeriodeSidangTA($id)
    {
        // Get data with pagination
        $sidang_ta = SidangTAModel::getDataPeriodeById($id);
        $rs_pendaftar_sidangta = SidangTAModel::getPendaftarSidangTA($id);

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
        return view('tim_capstone.sidang-ta.sidang-ta.detail', $data);
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
            $rs_ch =SidangTAMOdel::getDataSearch($nama);
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
        $id_mahasiswa = $request->id_mahasiswa;
        $status_dosen = $request->status_dosen;
        $id_dosen = $request->id_dosen;

        // Get kelompok mahasiswa
        $kelompok_mhs = SidangTAModel::getKelompokMhsById($id_mahasiswa);

        if (!$kelompok_mhs) {
            session()->flash('danger', 'Kelompok mahasiswa tidak ditemukan.');
            return back();
        }

        // Get kelompok information
        $kelompok = SidangTAModel::getIdKelompok($id_mahasiswa);

        if (!$kelompok) {
            session()->flash('danger', 'Kelompok tidak ditemukan.');
            return back();
        }

        // Check if the selected dosen is also a pembimbing
        if ($id_dosen == $kelompok->id_dosen_pembimbing_1 || $id_dosen == $kelompok->id_dosen_pembimbing_2) {
            session()->flash('danger', 'Dosen penguji tidak boleh sama dengan dosen pembimbing.');
            return back();
        }

        // Determine which parameter to update based on the status_dosen
        $params = [];
        if ($status_dosen == "penguji 1") {
            if ($kelompok_mhs->id_dosen_penguji_ta1 != null) {
                session()->flash('danger', 'Posisi penguji 1 sudah terisi.');
                return back();
            }
            $params = [
                'id_dosen_penguji_ta1' => $id_dosen,
                'status_dosen_penguji_ta1' => 'Menunggu Persetujuan Penguji!',
            ];
        } elseif ($status_dosen == "penguji 2") {
            if ($kelompok_mhs->id_dosen_penguji_ta2 != null) {
                session()->flash('danger', 'Posisi penguji 2 sudah terisi.');
                return back();
            }
            $params = [
                'id_dosen_penguji_ta2' => $id_dosen,
                'status_dosen_penguji_ta2' => 'Menunggu Persetujuan Penguji!',
            ];
        } else {
            session()->flash('danger', 'Status dosen tidak valid.');
            return back();
        }

        // Get all members of the kelompok
        $rs_anggota_kelompok = SidangTAModel::getAnggotaKelompok($kelompok->id);

        $all_dosen_assigned = true;

        foreach ($rs_anggota_kelompok as $anggota_kelompok) {
            if ($anggota_kelompok->is_mendaftar_sidang != 0 && $anggota_kelompok->status_tugas_akhir != "Menunggu Persetujuan Pendaftaran Sidang!" && $anggota_kelompok->status_tugas_akhir != "Pendaftaran Sidang Tidak Disetujui!") {

                $rs_periodeSekarang = SidangTAModel::getDataPendaftaranSidangTA($id_mahasiswa);
                $rs_periodeLalu= SidangTAModel::getDataPendaftaranSidangTA($anggota_kelompok->id_mahasiswa);

                foreach ($rs_periodeLalu as $periodeLalu) {
                    foreach ($rs_periodeSekarang as $periodeSekarang) {
                        if ($periodeSekarang->id_periode == $periodeLalu->id_periode) {
                            // Update each mahasiswa in the kelompok with the specified dosen
                            $add_dosen = SidangTAModel::updateKelompokMhs($anggota_kelompok->id_mahasiswa, $params);
                            session()->flash('success', 'Data dosen berhasil ditambahkan.');

                        } else {
                            $add_dosen = SidangTAModel::updateKelompokMhs($id_mahasiswa, $params);
                            session()->flash('success', 'Data dosen berhasil ditambahkan.');

                        }
                    }
                }
            } else {
                continue;
            }
        }

        return back();
    }



    public function deleteDosenKelompok($id_dosen, $id_mahasiswa)
    {
        // Get kelompok mahasiswa
        $kelompok_mhs = SidangTAModel::getKelompokMhsById($id_mahasiswa);
        if (!$kelompok_mhs) {
            session()->flash('danger', 'Kelompok mahasiswa tidak ditemukan.');
            return back();
        }

        // Get kelompok information
        $kelompok = SidangTAModel::getIdKelompok($id_mahasiswa);
        if (!$kelompok) {
            session()->flash('danger', 'Kelompok tidak ditemukan.');
            return back();
        }

        $params = [];

        if ($id_dosen == $kelompok_mhs->id_dosen_penguji_ta1) {
            $params = [
                'id_dosen_penguji_ta1' => null,
                'status_dosen_penguji_ta1' => null,
            ];
        } elseif ($id_dosen == $kelompok_mhs->id_dosen_penguji_ta2) {
            $params = [
                'id_dosen_penguji_ta2' => null,
                'status_dosen_penguji_ta2' => null,
            ];
        } else {
            session()->flash('danger', 'Dosen tidak terkait dengan kelompok mahasiswa.');
            return back();
        }

        // Get all members of the kelompok
        $rs_anggota_kelompok = SidangTAModel::getAnggotaKelompok($kelompok->id);

        $success = false;

        foreach ($rs_anggota_kelompok as $anggota_kelompok) {
            // Check if the member is eligible for dosen update
            if ($anggota_kelompok->is_mendaftar_sidang != 0 && $anggota_kelompok->status_tugas_akhir != "Menunggu Persetujuan Pendaftaran Sidang!" && $anggota_kelompok->status_tugas_akhir != "Pendaftaran Sidang Tidak Disetujui!") {
                // Prepare update parameters including status_tugas_akhir
                $rs_periodeSekarang = SidangTAModel::getDataPendaftaranSidangTA($id_mahasiswa);
                $rs_periodeLalu= SidangTAModel::getDataPendaftaranSidangTA($anggota_kelompok->id_mahasiswa);

                foreach ($rs_periodeLalu as $periodeLalu) {
                    foreach ($rs_periodeSekarang as $periodeSekarang) {
                        if ($periodeSekarang->id_periode == $periodeLalu->id_periode) {
                            // Update each mahasiswa in the kelompok with the specified dosen
                            $update_params = array_merge($params, ['status_tugas_akhir' => "Menunggu Penjadwalan Sidang TA!"]);

                            // Ensure we're not passing the same value for update
                            $existing_data = [
                                'id_dosen_penguji_ta1' => $kelompok_mhs->id_dosen_penguji_ta1,
                                'id_dosen_penguji_ta2' => $kelompok_mhs->id_dosen_penguji_ta2,
                                'status_dosen_penguji_ta1' => $kelompok_mhs->status_dosen_penguji_ta1,
                                'status_dosen_penguji_ta2' => $kelompok_mhs->status_dosen_penguji_ta2,
                                'status_tugas_akhir' => $kelompok_mhs->status_tugas_akhir,
                            ];

                            // Check if the update parameters differ from existing data
                            $update_required = false;
                            foreach ($update_params as $key => $value) {
                                if ($existing_data[$key] !== $value) {
                                    $update_required = true;
                                    break;
                                }
                            }

                            if ($update_required) {
                                // Update each mahasiswa in the kelompok with the specified dosen deletion
                                $dosen = SidangTAModel::updateKelompokMhs($anggota_kelompok->id_mahasiswa, $update_params);
                                session()->flash('success', 'Data dosen berhasil dihapus dari mahasiswa.');

                            } else {
                                // No update is required
                                $success = true; // Mark as success since no update was needed
                            }
                        } else {
                            $update_params = array_merge($params, ['status_tugas_akhir' => "Menunggu Penjadwalan Sidang TA!"]);

                            // Ensure we're not passing the same value for update
                            $existing_data = [
                                'id_dosen_penguji_ta1' => $kelompok_mhs->id_dosen_penguji_ta1,
                                'id_dosen_penguji_ta2' => $kelompok_mhs->id_dosen_penguji_ta2,
                                'status_dosen_penguji_ta1' => $kelompok_mhs->status_dosen_penguji_ta1,
                                'status_dosen_penguji_ta2' => $kelompok_mhs->status_dosen_penguji_ta2,
                                'status_tugas_akhir' => $kelompok_mhs->status_tugas_akhir,
                            ];

                            // Check if the update parameters differ from existing data
                            $update_required = false;
                            foreach ($update_params as $key => $value) {
                                if ($existing_data[$key] !== $value) {
                                    $update_required = true;
                                    break;
                                }
                            }

                            if ($update_required) {
                                // Update each mahasiswa in the kelompok with the specified dosen deletion
                                $dosen = SidangTAModel::updateKelompokMhs($id_mahasiswa, $update_params);
                                session()->flash('success', 'Data dosen berhasil dihapus dari mahasiswa.');

                            } else {
                                // No update is required
                                $success = true; // Mark as success since no update was needed
                            }
                        }
                    }
                }

            } else {
                // Skip members who do not meet the criteria
                continue;
            }
        }

        return back();
    }



    public function detailPenjadwalanSidangTA($id, $id_periode)
    {
        // get data with pagination
        $mahasiswa = SidangTAModel::pengecekan_kelompok_mahasiswa($id);
        $rs_dosbing = SidangTAModel::getAkunDosbingKelompok($mahasiswa->id_kelompok);
        $rs_penguji_ta = SidangTAModel::getAkunPengujiTAKelompok($id);
        $rs_mahasiswa = SidangTAModel::listMahasiswaSendiri($id, $mahasiswa->id_kelompok);


        // get jadwal sidang
        $jadwal_sidang = SidangTAModel::getJadwalSidangTA($id);
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
        $rs_penguji = SidangTAModel::getDosenPengujiTA();

        $rs_ruang_sidang = SidangTAModel::getRuangSidang();


        foreach ($rs_dosbing as $dosbing) {

            if ($dosbing->user_id == $mahasiswa->id_dosen_pembimbing_1) {
                $dosbing->jenis_dosen = 'Pembimbing 1';
                $dosbing->status_dosen = $mahasiswa->status_dosen_pembimbing_1;
            } else if ($dosbing->user_id == $mahasiswa->id_dosen_pembimbing_2) {
                $dosbing->jenis_dosen = 'Pembimbing 2';
                $dosbing->status_dosen = $mahasiswa->status_dosen_pembimbing_2;
            }

            $dosbing -> status_pembimbing1_color = $this->getStatusColor($mahasiswa->status_dosen_pembimbing_1);
            $dosbing -> status_pembimbing2_color = $this->getStatusColor($mahasiswa->status_dosen_pembimbing_2);

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

        $mahasiswa -> status_penguji1_color = $this->getStatusColor($mahasiswa->status_dosen_penguji_ta1);
        $mahasiswa -> status_penguji2_color = $this->getStatusColor($mahasiswa->status_dosen_penguji_ta2);

        // periode
        $periode_sidang_ta = SidangTAModel::getDataPeriodeById($id_periode);

        // data
        $data = [
            'mahasiswa' => $mahasiswa,
            'periode_sidang_ta' => $periode_sidang_ta,
            'rs_dosbing' => $rs_dosbing,
            'rs_mahasiswa' => $rs_mahasiswa,
            'rs_penguji_ta' => $rs_penguji_ta,
            'rs_penguji' => $rs_penguji,
            'rs_ruang_sidang' => $rs_ruang_sidang,
            'jadwal_sidang' => $jadwal_sidang,

        ];

        // view
        return view('tim_capstone.sidang-ta.sidang-ta.penjadwalan', $data);
    }

    public function addJadwalProcess(Request $request)
    {
        // Validate input data
        $validatedData = $request->validate([
            'id_mahasiswa' => 'required',
            'id_kelompok' => 'required',
            'id_ruangan' => 'required',
            'id_periode' => 'required',
            'waktu' => 'required',
            'waktu_selesai' => 'required',
            'id_dosen_penguji_ta1' => 'required',
            'id_dosen_penguji_ta2' => 'required',
        ]);

        $overlap = SidangTAModel::checkOverlap($request->waktu, $request->waktu_selesai, $request->id_ruangan, $request ->id_kelompok);

        if ($overlap != null) {
            session()->flash('danger', 'Ruangan tersebut sudah terjadwal pada waktu yang sama.');
            return back()->withInput();
        }

        if (empty($request->id_dosen_penguji_ta1) || empty($request->id_dosen_penguji_ta2)) {
            session()->flash('danger', 'Dosen penguji 1 dan penguji 2 harus dipilih.');
            return back()->withInput();
        }

        // Validate start and end time
        if ($request->waktu >= $request->waktu_selesai) {
            session()->flash('danger', 'Waktu mulai harus lebih awal dari waktu selesai.');
            return back()->withInput();
        }

        $id_mahasiswa = $request->id_mahasiswa;

        // Get anggota kelompok
        $rs_anggota_kelompok = SidangTAModel::getAnggotaKelompok($request->id_kelompok);

        foreach ($rs_anggota_kelompok as $anggota_kelompok) {
            if (($anggota_kelompok->is_mendaftar_sidang == 1 && $anggota_kelompok->status_tugas_akhir == "Menunggu Penjadwalan Sidang TA!") || $anggota_kelompok->status_tugas_akhir == "Menunggu Persetujuan Penguji!"|| $anggota_kelompok->status_tugas_akhir == "Telah Dijadwalkan Sidang TA!") {
                $rs_periodeSekarang = SidangTAModel::getDataPendaftaranSidangTA($id_mahasiswa);
                $rs_periodeLalu = SidangTAModel::getDataPendaftaranSidangTA($anggota_kelompok->id_mahasiswa);

                foreach ($rs_periodeLalu as $periodeLalu) {
                    foreach ($rs_periodeSekarang as $periodeSekarang) {
                        if ($periodeSekarang->id_periode == $periodeLalu->id_periode) {
                            $params = [
                                'id_mahasiswa' => $anggota_kelompok->id_mahasiswa,
                                'id_kelompok' => $request->id_kelompok,
                                'id_ruangan' => $request->id_ruangan,
                                'id_kelompok_mhs' => $anggota_kelompok->id,
                                'id_periode' => $request->id_periode,
                                'waktu' => $request->waktu,
                                'waktu_selesai' => $request->waktu_selesai,
                                'id_dosen_penguji_ta1' => $request->id_dosen_penguji_ta1,
                                'id_dosen_penguji_ta2' => $request->id_dosen_penguji_ta2,
                                'created_by' => Auth::user()->user_id,
                                'created_date' => now()
                            ];

                            $paramsStatusKelompokMhs = [
                                'status_tugas_akhir' => "Menunggu Persetujuan Penguji!",
                                'status_dosen_penguji_ta1' => 'Menunggu Persetujuan Penguji!',
                                'status_dosen_penguji_ta2' => 'Menunggu Persetujuan Penguji!'
                            ];

                            SidangTAModel::updateKelompokMhs($anggota_kelompok->id_mahasiswa, $paramsStatusKelompokMhs);

                            // Check existing jadwal sidang TA for this kelompok
                            $existingJadwal = SidangTAModel::getJadwalSidangTA($anggota_kelompok->id_mahasiswa);

                            if ($existingJadwal) {

                                $overlapPenguji1 = SidangTAModel::checkOverlapPenguji1($request->waktu, $request->waktu_selesai, $request->id_dosen_penguji_ta1, $request->id_kelompok);
                                if ($overlapPenguji1 != null) {
                                    session()->flash('danger', 'Dosen Penguji 1 sudah terjadwal pada waktu yang sama.');
                                    return back()->withInput();
                                }

                                $overlapPenguji2 = SidangTAModel::checkOverlapPenguji2($request->waktu, $request->waktu_selesai, $request->id_dosen_penguji_ta2, $request->id_kelompok);
                                if ($overlapPenguji2 != null) {
                                    session()->flash('danger', 'Dosen Penguji 2 sudah terjadwal pada waktu yang sama.');
                                    return back()->withInput();
                                }

                                // Update existing jadwal sidang TA
                                $update = SidangTAModel::updateJadwalSidangTA($existingJadwal->id, $params);
                                if ($update) {
                                    session()->flash('success', 'Data berhasil diperbarui.');
                                } else {
                                    session()->flash('danger', 'Gagal memperbarui data.');
                                }
                            } else {

                                // Validasi overlapping schedule

                                $overlapPenguji1 = SidangTAModel::checkOverlapPenguji1($request->waktu, $request->waktu_selesai, $request->id_dosen_penguji_ta1, $request->id_kelompok);
                                if ($overlapPenguji1 != null) {
                                    session()->flash('danger', 'Dosen Penguji 1 sudah terjadwal pada waktu yang sama.');
                                    return back()->withInput();
                                }

                                $overlapPenguji2 = SidangTAModel::checkOverlapPenguji2($request->waktu, $request->waktu_selesai, $request->id_dosen_penguji_ta2, $request->id_kelompok);
                                if ($overlapPenguji2 != null) {
                                    session()->flash('danger', 'Dosen Penguji 2 sudah terjadwal pada waktu yang sama.');
                                    return back()->withInput();
                                }


                                // Insert new jadwal sidang TA
                                $insert = SidangTAModel::insertJadwalSidangTA($params);

                                if ($insert) {
                                    session()->flash('success', 'Data berhasil disimpan.');
                                } else {
                                    session()->flash('danger', 'Data gagal disimpan.');
                                }
                            }
                        } else {
                            continue;
                        }
                    }
                }
            } else {
                continue;
            }
        }

        return redirect('/admin/sidang-ta');
    }



}
