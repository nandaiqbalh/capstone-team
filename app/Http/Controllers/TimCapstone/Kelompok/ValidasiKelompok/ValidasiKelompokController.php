<?php

namespace App\Http\Controllers\TimCapstone\Kelompok\ValidasiKelompok;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\Kelompok\ValidasiKelompok\ValidasiKelompokModel;
use Illuminate\Support\Facades\Hash;


class ValidasiKelompokController extends BaseController
{
    public function index()
    {

        // get data with pagination
        $rs_kelompok = ValidasiKelompokModel::getDataWithPagination();

        foreach ($rs_kelompok as $kelompok) {

            $kelompok -> status_kelompok_color = $this->getStatusColor($kelompok->status_kelompok);
            $kelompok -> status_dosbing1_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_1);
            $kelompok -> status_dosbing2_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_2);

        }
        // data
        $data = ['rs_kelompok' => $rs_kelompok, ];
        // view
        return view('tim_capstone.kelompok.validasi-kelompok.index', $data);
    }

    public function detailKelompok($id)
    {

        // get data with pagination
        $kelompok = ValidasiKelompokModel::getDataById($id);
        $rs_topik = ValidasiKelompokModel::getTopik();
        $rs_mahasiswa = ValidasiKelompokModel::listKelompokMahasiswa($id);
        $rs_mahasiswa_nokel = ValidasiKelompokModel::listKelompokMahasiswaNokel($kelompok->id_topik);
        $rs_dosbing = ValidasiKelompokModel::getAkunDosbingKelompok($id);
        $rs_dosbing1 = ValidasiKelompokModel::getDataDosbing1();
        $rs_dosbing2 = ValidasiKelompokModel::getDataDosbing2();

        foreach ($rs_dosbing as $dosbing) {

            if ($dosbing->user_id == $kelompok->id_dosen_pembimbing_1) {
                $dosbing->jenis_dosen = 'Pembimbing 1';
                $dosbing->status_dosen = $kelompok->status_dosen_pembimbing_1;
            } else if ($dosbing->user_id == $kelompok->id_dosen_pembimbing_2) {
                $dosbing->jenis_dosen = 'Pembimbing 2';
                $dosbing->status_dosen = $kelompok->status_dosen_pembimbing_2;
            }

        }

        $rs_peminatan = ValidasiKelompokModel::getPeminatan();

        foreach ($rs_mahasiswa_nokel as $key => $mahasiswa) {
            foreach ($rs_topik as $key => $topik) {
                if ($topik->id == $mahasiswa->id_topik_individu1) {
                    $mahasiswa->prioritas_topik = $topik->nama;
                    break; // Exit the loop once the first match is found
                } else {
                    $mahasiswa->prioritas_topik = "Belum memilih";
                }
            }

            foreach ($rs_peminatan as $key => $peminatan) {
                if ($peminatan->id == $mahasiswa->id_peminatan_individu1) {
                    $mahasiswa->prioritas_peminatan = $peminatan->nama_peminatan;
                    break; // Exit the loop once the first match is found
                } else {
                    $mahasiswa->prioritas_peminatan = "Belum memilih";
                }
            }
        }

        $kelompok -> status_kelompok_color = $this->getStatusColor($kelompok->status_kelompok);
        $kelompok -> status_dosbing1_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_1);
        $kelompok -> status_dosbing2_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_2);


        // check
        if (empty($kelompok)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/tim-capstone/kelompok');
        }

        // data
        $data = [
            'kelompok' => $kelompok,
            'rs_topik' => $rs_topik,
            'rs_mahasiswa' => $rs_mahasiswa,
            'rs_mahasiswa_nokel' => $rs_mahasiswa_nokel,
            'rs_dosbing' => $rs_dosbing,
            'rs_dosbing1' => $rs_dosbing1,
            'rs_dosbing2' => $rs_dosbing2,
        ];
        // dd($data);

        // view
        return view('tim_capstone.kelompok.validasi-kelompok.detail', $data);
    }

    public function addMahasiswaKelompok(Request $request)
    {

        // params
        $params = [
            'id_kelompok' => $request->id_kelompok,
            'modified_by'   => Auth::user()->user_id,
            'status_individu' => "Menyetujui Kelompok!",
            'modified_date'  => date('Y-m-d H:i:s')
        ];
        // dd($params);
        // process
        if (ValidasiKelompokModel::updateKelompokMHS($request->id_mahasiswa_nokel, $params)) {

            $rs_mahasiswa = ValidasiKelompokModel::getKelompokMhsAll($request->id_kelompok);
            $semuaSetuju = true;

            foreach ($rs_mahasiswa as $key => $mahasiswa) {
                // Jika status individu bukan "menyetujui kelompok", set variabel $semuaSetuju menjadi false
                if ($mahasiswa->status_individu !== "Menyetujui Kelompok!") {
                    $semuaSetuju = false;
                    // Jika salah satu mahasiswa tidak setuju, Anda bisa langsung keluar dari loop
                    break;
                }
            }

            // Jika semua mahasiswa setuju dengan kelompok, lakukan aksi
            if ($semuaSetuju) {
                $paramKelompok = [
                    "status_kelompok" => "Menunggu Persetujuan Dosbing!",
                ];
                $update_kelompok = ValidasiKelompokModel::updateKelompok($request->id_kelompok, $paramKelompok);
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


    public function deleteMahasiswaKelompokProcess($id_mahasiswa, $id)
    {

        // get data
        $mahasiswa = ValidasiKelompokModel::getKelompokMhs($id_mahasiswa, $id);

         // params
         $params = [
            'id_kelompok' => NULL,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // if exist
        if (!empty($mahasiswa)) {
            // process
            if (ValidasiKelompokModel::updateKelompokMHS($mahasiswa->id_mahasiswa, $params)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return back();
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return back();
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return back();
        }
    }


    public function addDosenKelompok(Request $request)
    {
        // get kelompok
        $id_kelompok = $request->id_kelompok;
        $kelompok = ValidasiKelompokModel::getKelompokById($id_kelompok);

        // check if the selected position is 'pembimbing 1'
        if ($request->status_dosen == "pembimbing 1") {
            // check if pembimbing 1 slot is available and not the same as the selected dosen
            if ($kelompok->id_dosen_pembimbing_1 == null && $kelompok->id_dosen_pembimbing_2 != $request->id_dosen) {
                $params = [
                    'id_dosen_pembimbing_1' => $request->id_dosen,
                    'status_dosen_pembimbing_1' => 'Dosbing Diplot Tim Capstone!',
                ];
            } else {
                session()->flash('danger', 'Posisi/dosen sudah terisi!');
                return back();
            }
        }

        // check if the selected position is 'pembimbing 2'
        if ($request->status_dosen == "pembimbing 2") {
            // check if pembimbing 2 slot is available and not the same as the selected dosen
            if ($kelompok->id_dosen_pembimbing_2 == null && $kelompok->id_dosen_pembimbing_1 != $request->id_dosen) {
                $params = [
                    'id_dosen_pembimbing_2' => $request->id_dosen,
                    'status_dosen_pembimbing_2' => 'Dosbing Diplot Tim Capstone!',
                ];
            } else {
                session()->flash('danger', 'Posisi/dosen sudah terisi!');
                return back();
            }
        }

        if (ValidasiKelompokModel::updateKelompok($id_kelompok, $params)) {
            // update status kelompok if both pembimbing slots are filled

            $kelompok_updated = ValidasiKelompokModel::getKelompokById($id_kelompok);

            if ($kelompok_updated->id_dosen_pembimbing_1 != null && $kelompok_updated->id_dosen_pembimbing_2 != null) {
                $paramsStatusKelompok = [
                    'status_kelompok' => "Menunggu Persetujuan Tim Capstone!"
                ];

                ValidasiKelompokModel::updateKelompok($id_kelompok, $paramsStatusKelompok);
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

    public function deleteDosenKelompokProcess($id_dosen, $id_kelompok)
    {

        $kelompok = ValidasiKelompokModel::getKelompokById($id_kelompok);

        $params ="";

        if ($id_dosen == $kelompok -> id_dosen_pembimbing_1) {
            $params = [
                'id_dosen_pembimbing_1' => null,
                'status_dosen_pembimbing_1' => null,
                'status_kelompok' => "Menunggu Penetapan Dosbing!"
            ];
        } else if ($id_dosen == $kelompok -> id_dosen_pembimbing_2) {
            $params = [
                'id_dosen_pembimbing_2' => null,
                'status_dosen_pembimbing_2' => null,
                'status_kelompok' => "Menunggu Penetapan Dosbing!"
            ];
        } else {
            $params = [

            ];

        }

        // dd($params);
        // get data
        $dosen = ValidasiKelompokModel::updateKelompok($id_kelompok, $params);

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

    public function setujuiKelompokProcess(Request $request)
    {
        $rules = [
            'id' => 'required',
        ];

        $this->validate($request, $rules);

        // Ambil data kelompok berdasarkan ID
        $kelompok = ValidasiKelompokModel::getKelompokById($request->id);

        if (!$kelompok) {
            return redirect()->back()->with('danger', 'Kelompok tidak ditemukan.');
        }


        if ($kelompok->id_dosen_pembimbing_1 == null) {
            return redirect()->back()->with('danger', 'Kelompok belum memiliki dosen pembimbing 1!');
        }

        if ($kelompok->id_dosen_pembimbing_2 == null) {
            return redirect()->back()->with('danger', 'Kelompok belum memiliki dosen pembimbing 2!');
        }

        $siklus = ValidasiKelompokModel::getSiklusById($kelompok->id_siklus);

        // Ambil kode siklus dari variabel siklus
        $kodeSiklus = $siklus->kode_siklus;

        if (!$kodeSiklus) {
            return redirect()->back()->with('danger', 'Gagal mendapatkan kode siklus.');
        }

        // Ambil nomor kelompok terakhir dari tabel kelompok
        $lastNomorKelompok = DB::table('kelompok')
            ->where('nomor_kelompok', 'like', $kodeSiklus . 'K%')
            ->max(DB::raw('CAST(SUBSTRING(nomor_kelompok, CHAR_LENGTH("' . $kodeSiklus . 'K") + 1) AS UNSIGNED)'));
        // Mengambil nomor terbesar setelah karakter 'K'

        // Jika tidak ada nomor kelompok sebelumnya, mulai dari 0
        $nextNumber = $lastNomorKelompok !== null ? $lastNomorKelompok + 1 : 1;

        // Format nomor kelompok dengan kode siklus dan nomor urut
        $nomor_kelompok = $kodeSiklus . 'K' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

        // Parameter untuk update data kelompok
        $params = [
            "nomor_kelompok" => $nomor_kelompok,
            "id_topik" => $request->topik,
            "status_kelompok" => "Kelompok Telah Disetujui!",
            "status_dosen_pembimbing_1" => "Dosbing Setuju!",
            "status_dosen_pembimbing_2" => "Dosbing Setuju!",
            'modified_by' => Auth::user()->user_id,
            'modified_date' => now()->format('Y-m-d H:i:s')
        ];

        // Proses update data kelompok
        if (ValidasiKelompokModel::updateKelompok($request->id, $params)) {

            $paramsMhsBasedOnKelompok = [
                "status_individu" => "Kelompok Telah Disetujui!",
            ];

            if (ValidasiKelompokModel::updateKelompokMHSBasedOnKelompok($request->id, $paramsMhsBasedOnKelompok)) {
                session()->flash('success', 'Data berhasil disimpan.');
            } else {
                session()->flash('danger', 'Gagal mengubah status mahasiswa.');
            }
            // Flash message sukses
        } else {
            // Flash message gagal
            session()->flash('danger', 'Data gagal disimpan.');
        }

        return redirect()->back();
    }

    public function editKelompokProcess(Request $request)
    {
        $rules = [
            'id' => 'required',
        ];


        // Parameter untuk update data kelompok
        $params = [
            "id_topik" => $request->topik,
            'modified_by' => Auth::user()->user_id,
            'modified_date' => now()->format('Y-m-d H:i:s')
        ];

        // Proses update data kelompok
        if (ValidasiKelompokModel::updateKelompok($request->id, $params)) {
            // Flash message sukses
            session()->flash('success', 'Data berhasil disimpan.');
        } else {
            // Flash message gagal
            session()->flash('danger', 'Data gagal disimpan.');
        }

        return redirect()->back();
    }



    public function deleteKelompokProcess($id)
    {

        // get data
        $kelompok = ValidasiKelompokModel::getDataById($id);

        // if exist
        if (!empty($kelompok)) {
            $cekMhs=ValidasiKelompokModel::getKelompokMhsAll($kelompok->id);
            foreach ($cekMhs as $key => $mhs) {
                ValidasiKelompokModel::deleteKelompokMhs($mhs->id_mahasiswa);
            }

            if (ValidasiKelompokModel::deleteJadwalSidangProposal($kelompok->id)) {
                if (ValidasiKelompokModel::deleteKelompok($kelompok->id)) {
                    // flash message
                    session()->flash('success', 'Data berhasil dihapus.');
                    return back();
                } else {
                    // flash message
                    session()->flash('danger', 'Data gagal dihapus.');
                    return back();
                }
            } else {
                if (ValidasiKelompokModel::deleteKelompok($kelompok->id)) {
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

}
