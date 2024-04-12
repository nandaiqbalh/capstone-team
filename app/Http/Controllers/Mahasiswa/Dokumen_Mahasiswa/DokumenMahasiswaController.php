<?php

namespace App\Http\Controllers\Mahasiswa\Dokumen_Mahasiswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Mahasiswa\Dokumen_Mahasiswa\DokumenMahasiswaModel;
use App\Models\Mahasiswa\Kelompok_Mahasiswa\MahasiswaKelompokModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DokumenMahasiswaController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //coba comment

    public function index()
    {


        // get data kelompok
        $file_mhs = DokumenMahasiswaModel::fileMHS();
        $kelompok = MahasiswaKelompokModel::pengecekan_kelompok_mahasiswa(Auth::user()->user_id);
        $akun_mahasiswa = MahasiswaKelompokModel::getAkunByID(Auth::user()->user_id);

        // data
        $data = [
            'file_mhs'  => $file_mhs,
            'kelompok'  => $kelompok,
            'akun_mahasiswa'  => $akun_mahasiswa,
        ];


        // dd($data);
        // view
        return view('mahasiswa.dokumen-mahasiswa.detail', $data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadMakalahProcess(Request $request)
    {
        $upload_path = '/file/mahasiswa/makalah';

        // Cek apakah file laporan_ta sudah diunggah sebelumnya
        $existingFile = DokumenMahasiswaModel::fileMHS($request->id_mahasiswa);
        if ($existingFile->file_name_laporan_ta == null) {
            session()->flash('danger', 'Gagal mengunggah! Lengkapi terlebih dahulu Dokumen Laporan TA!');
            return redirect()->back()->withInput();
        }

        // Pastikan ada file makalah yang diunggah
        if ($request->hasFile('makalah')) {
            $file = $request->file('makalah');
            $file_extension = $file->getClientOriginalExtension();
            $new_file_name = 'makalah-' . Str::slug(Auth::user()->user_name, '-') . '-' . uniqid() . '.' . $file_extension;

            // Hapus file makalah yang sudah ada jika ada
            if ($existingFile->file_name_makalah != null) {
                $file_path_makalah = public_path($existingFile->file_path_makalah . '/' . $existingFile->file_name_makalah);
                if (file_exists($file_path_makalah)) {
                    if (!unlink($file_path_makalah)) {
                        session()->flash('danger', 'Gagal menghapus dokumen lama!');
                        return redirect()->back()->withInput();
                    }
                }
            }

            // Pastikan folder upload ada, jika tidak, buat folder baru
            if (!is_dir(public_path($upload_path))) {
                mkdir(public_path($upload_path), 0755, true);
            }

            // Proses upload makalah
            if (!$file->move(public_path($upload_path), $new_file_name)) {
                session()->flash('danger', 'Makalah gagal diupload!');
                return redirect()->back()->withInput();
            }

            // Update informasi file makalah pada database
            $params = [
                'file_name_makalah' => $new_file_name,
                'file_path_makalah' => $upload_path,
                'file_status_mka' => 'Menunggu Persetujuan Makalah TA!',
                'file_status_mka_dosbing1' => 'Menunggu Persetujuan Makalah TA!',
                'file_status_mka_dosbing2' => 'Menunggu Persetujuan Makalah TA!',
                'status_individu' => 'Menunggu Persetujuan Makalah TA!',
            ];
            $uploadFileMhs = DokumenMahasiswaModel::uploadFileMHS($request->id_kel_mhs, $params);

            if ($uploadFileMhs) {
                session()->flash('success', 'Berhasil mengunggah dokumen!');
                return redirect()->back();

            } else {
                session()->flash('danger', 'Gagal mengunggah! Dokumen Makalah TA tidak ditemukan!');
                return redirect()->back()->withInput();
            }
        }

        session()->flash('danger', 'Gagal mengunggah! Dokumen makalah tidak ditemukan!');
        return redirect()->back()->withInput();
    }

    public function uploadLaporanProcess(Request $request)
    {
        $upload_path = '/file/mahasiswa/laporan-ta';

        // Cek apakah file laporan_ta diunggah
        if ($request->hasFile('laporan_ta')) {
            $file = $request->file('laporan_ta');

            // Cek keberadaan capstone sebelum mengunggah laporan_ta
            $dokumenKelompok = DokumenMahasiswaModel::pengecekan_kelompok_mahasiswa(Auth::user()->user_id);
            if ($dokumenKelompok->file_name_c500 == null) {
                session()->flash('danger', 'Gagal mengunggah! Lengkapi terlebih dahulu Dokumen Capstone!');
                return redirect()->back()->withInput();
            }

            // Hapus file laporan_ta yang sudah ada jika ada
            $existingFile = DokumenMahasiswaModel::fileMHS($request->id_mahasiswa);
            if ($existingFile->file_name_laporan_ta != null) {
                $file_path_laporan_ta = public_path($existingFile->file_path_laporan_ta . '/' . $existingFile->file_name_laporan_ta);
                if (file_exists($file_path_laporan_ta)) {
                    if (!unlink($file_path_laporan_ta)) {
                        session()->flash('danger', 'Gagal menghapus dokumen lama!');
                        return redirect()->back()->withInput();
                    }
                }
            }

            // Pastikan folder upload ada, jika tidak, buat folder baru
            if (!is_dir(public_path($upload_path))) {
                mkdir(public_path($upload_path), 0755, true);
            }

            // Proses upload laporan_ta
            $file_extension = $file->getClientOriginalExtension();
            $new_file_name = 'laporan_ta-' . Str::slug(Auth::user()->user_name, '-') . '-' . uniqid() . '.' . $file_extension;

            if (!$file->move(public_path($upload_path), $new_file_name)) {
                session()->flash('danger', 'Laporan gagal diupload!');
                return redirect()->back()->withInput();
            }

            // Update informasi file laporan_ta pada database
            $params = [
                'file_name_laporan_ta' => $new_file_name,
                'file_path_laporan_ta' => $upload_path,
                'file_status_lta' => 'Menunggu Persetujuan Laporan TA!',
                'file_status_lta_dosbing1' => 'Menunggu Persetujuan Laporan TA!',
                'file_status_lta_dosbing2' => 'Menunggu Persetujuan Laporan TA!',
                'status_individu' => 'Menunggu Persetujuan Laporan TA!',
            ];

            $uploadFileMhs = DokumenMahasiswaModel::uploadFileMHS($request->id_kel_mhs, $params);

            if ($uploadFileMhs) {
                session()->flash('success', 'Berhasil mengunggah dokumen!');
                return redirect()->back();
            } else {
                session()->flash('danger', 'Gagal mengunggah! Dokumen laporan_ta tidak ditemukan!');
                return redirect()->back()->withInput();
            }

        }
        session()->flash('danger', 'Gagal mengunggah! Dokumen laporan_ta tidak ditemukan!');
        return redirect()->back()->withInput();
    }


    // c series

    public function uploadC100Process(Request $request)
    {
        // upload path
        $upload_path = '/file/kelompok/c100';
        // UPLOAD FOTO
        if ($request->hasFile('c100')) {

            $file = $request->file('c100');
            // namafile
            $file_extention = pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_EXTENSION
            );
            $kelompok = MahasiswaKelompokModel::pengecekan_kelompok_mahasiswa(Auth::user()->user_id);

            $existingFile = DokumenMahasiswaModel::getKelompokFile($kelompok->id);
            $new_file_name = 'c100-' . Str::slug($existingFile->nomor_kelompok , '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

            $siklus = DokumenMahasiswaModel::getSiklusKelompok($existingFile->id_siklus);

            if($siklus != null){
                // Check if the file exists
                if ($existingFile -> file_name_c100 !=null) {
                    // Construct the file path
                    $filePath = public_path($existingFile->file_path_c100 . '/' . $existingFile->file_name_c100);

                    // Check if the file exists before attempting to delete
                    if (file_exists($filePath)) {
                        // Attempt to delete the file
                        if (!unlink($filePath)) {
                            // Return failure response if failed to delete the existing file
                            session()->flash('danger', 'Gagal menghapus dokumen lama!');
                                return redirect()->back()->withInput();
                        }
                    }
                }

                // cek folder
                if (!is_dir(public_path($upload_path))) {
                    mkdir(public_path($upload_path), 0755, true);
                }

                // upload process
                if (!$file->move(public_path($upload_path), $new_file_name)) {
                    // flash message
                    session()->flash('danger', 'Laporan gagal di upload!');
                    return redirect()->back()->withInput();
                }

                $params = [
                    'file_name_c100' => $new_file_name,
                    'file_path_c100' => $upload_path
                ];
                $uploadFile = DokumenMahasiswaModel::uploadFileKel($kelompok->id, $params);

                if ($uploadFile) {
                    $statusParam = [
                        'status_kelompok' => 'Menunggu Persetujuan C100!',
                        'file_status_c100' => 'Menunggu Persetujuan C100!',
                        'file_status_c100_dosbing1' => 'Menunggu Persetujuan C100!',
                        'file_status_c100_dosbing2' => 'Menunggu Persetujuan C100!',
                        'status_dosen_pembimbing_1' => 'Menunggu Persetujuan C100!',
                        'status_dosen_pembimbing_2' => 'Menunggu Persetujuan C100!',
                    ];

                    DokumenMahasiswaModel::uploadFileKel($kelompok->id, $statusParam);
                } else {
                    return redirect()->back()->with('danger', 'Gagal. Dokumen gagal diunggah!');
                }
            } else {

                return redirect()->back()->with('danger', 'Gagal. Sudah melewati batas waktu unggah dokumen C100!');

            }

        }


        session()->flash('success', 'Berhasil mengunggah dokumen!');
        return back();
    }

    public function uploadC200Process(Request $request)
    {
        $upload_path = '/file/kelompok/c200';

        $kelompok = MahasiswaKelompokModel::pengecekan_kelompok_mahasiswa(Auth::user()->user_id);
        $existingFile = DokumenMahasiswaModel::getKelompokFile($kelompok->id);

        // Pastikan sudah ada file C100 sebelum mengunggah C200
        if ($existingFile->file_name_c100 == null) {
            session()->flash('danger', 'Gagal mengunggah! Lengkapi terlebih dahulu Dokumen C100!');
            return redirect()->back()->withInput();
        }

        if ($request->hasFile('c200')) {
            $file = $request->file('c200');
            $file_extension = $file->getClientOriginalExtension();
            $new_file_name = 'c200-' . Str::slug($existingFile->nomor_kelompok, '-') . '-' . uniqid() . '.' . $file_extension;

            // Hapus file C200 yang sudah ada jika ada
            if ($existingFile->file_name_c200 != null) {
                $file_path_c200 = public_path($existingFile->file_path_c200 . '/' . $existingFile->file_name_c200);
                if (file_exists($file_path_c200)) {
                    if (!unlink($file_path_c200)) {
                        session()->flash('danger', 'Gagal menghapus dokumen lama C200!');
                        return redirect()->back()->withInput();
                    }
                }
            }

            // Pastikan folder upload ada, jika tidak, buat folder baru
            if (!is_dir(public_path($upload_path))) {
                mkdir(public_path($upload_path), 0755, true);
            }

            // Lakukan proses upload file C200
            if (!$file->move(public_path($upload_path), $new_file_name)) {
                session()->flash('danger', 'Gagal mengunggah dokumen C200!');
                return redirect()->back()->withInput();
            }

            // Update informasi file C200 pada database
            $params = [
                'file_name_c200' => $new_file_name,
                'file_path_c200' => $upload_path
            ];
            $uploadFile = DokumenMahasiswaModel::uploadFileKel($kelompok->id, $params);

            if (!$uploadFile) {
                return redirect()->back()->with('danger', 'Gagal. Dokumen C200 gagal diunggah!');
            }

            // Update status kelompok dan dosen pembimbing terkait
            $params = [
                'file_name_laporan_ta' => $new_file_name,
                'file_path_laporan_ta' => $upload_path,
                'file_status_lta' => 'Menunggu Persetujuan Laporan TA!',
                'file_status_lta_dosbing1' => 'Menunggu Persetujuan Laporan TA!',
                'file_status_lta_dosbing2' => 'Menunggu Persetujuan Laporan TA!',
                'status_individu' => 'Menunggu Persetujuan Laporan TA!',
            ];
            $uploadFileMhs = DokumenMahasiswaModel::uploadFileMHS($request->id_kel_mhs, $params);

            if ($uploadFileMhs) {
                session()->flash('success', 'Berhasil mengunggah dokumen!');
                return redirect()->back();

            } else {
                session()->flash('danger', 'Gagal mengunggah! Dokumen Laporan TA tidak ditemukan!');
                return redirect()->back()->withInput();
            }
        }

        session()->flash('danger', 'Gagal mengunggah! Dokumen C200 tidak ditemukan!');
        return redirect()->back()->withInput();
    }


    public function uploadC300Process(Request $request)
    {
        $upload_path = '/file/kelompok/c300';

        $kelompok = MahasiswaKelompokModel::pengecekan_kelompok_mahasiswa(Auth::user()->user_id);
        $existingFile = DokumenMahasiswaModel::getKelompokFile($kelompok->id);

        // Pastikan sudah ada file C200 sebelum mengunggah C300
        if ($existingFile->file_name_c200 == null) {
            session()->flash('danger', 'Gagal mengunggah! Lengkapi terlebih dahulu Dokumen C200!');
            return redirect()->back()->withInput();
        }

        if ($request->hasFile('c300')) {
            $file = $request->file('c300');
            $file_extension = $file->getClientOriginalExtension();
            $new_file_name = 'c300-' . Str::slug($existingFile->nomor_kelompok, '-') . '-' . uniqid() . '.' . $file_extension;

            // Hapus file C300 yang sudah ada jika ada
            if ($existingFile->file_name_c300 != null) {
                $file_path_c300 = public_path($existingFile->file_path_c300 . '/' . $existingFile->file_name_c300);
                if (file_exists($file_path_c300)) {
                    if (!unlink($file_path_c300)) {
                        session()->flash('danger', 'Gagal menghapus dokumen lama C300!');
                        return redirect()->back()->withInput();
                    }
                }
            }

            // Pastikan folder upload ada, jika tidak, buat folder baru
            if (!is_dir(public_path($upload_path))) {
                mkdir(public_path($upload_path), 0755, true);
            }

            // Lakukan proses upload file C300
            if (!$file->move(public_path($upload_path), $new_file_name)) {
                session()->flash('danger', 'Gagal mengunggah dokumen C300!');
                return redirect()->back()->withInput();
            }

            // Update informasi file C300 pada database
            $params = [
                'file_name_c300' => $new_file_name,
                'file_path_c300' => $upload_path
            ];
            $uploadFile = DokumenMahasiswaModel::uploadFileKel($kelompok->id, $params);

            if (!$uploadFile) {
                return redirect()->back()->with('danger', 'Gagal. Dokumen C300 gagal diunggah!');
            }

            // Update status kelompok dan dosen pembimbing terkait
            $statusParam = [
                'status_kelompok' => 'Menunggu Persetujuan C300!',
                'file_status_c300' => 'Menunggu Persetujuan C300!',
                'file_status_c300_dosbing1' => 'Menunggu Persetujuan C300!',
                'file_status_c300_dosbing2' => 'Menunggu Persetujuan C300!',
                'status_dosen_pembimbing_1' => 'Menunggu Persetujuan C300!',
                'status_dosen_pembimbing_2' => 'Menunggu Persetujuan C300!',
            ];
            DokumenMahasiswaModel::uploadFileKel($kelompok->id, $statusParam);

            session()->flash('success', 'Berhasil mengunggah dokumen C300!');
            return redirect()->back();
        }

        session()->flash('danger', 'Gagal mengunggah! Dokumen C300 tidak ditemukan!');
        return redirect()->back()->withInput();
    }

    public function uploadC400Process(Request $request)
    {
        $upload_path = '/file/kelompok/c400';

        $kelompok = MahasiswaKelompokModel::pengecekan_kelompok_mahasiswa(Auth::user()->user_id);
        $existingFile = DokumenMahasiswaModel::getKelompokFile($kelompok->id);

        // Pastikan sudah ada file C300 sebelum mengunggah C400
        if ($existingFile->file_name_c300 == null) {
            session()->flash('danger', 'Gagal mengunggah! Lengkapi terlebih dahulu Dokumen C300!');
            return redirect()->back()->withInput();
        }

        if ($request->hasFile('c400')) {
            $file = $request->file('c400');
            $file_extension = $file->getClientOriginalExtension();
            $new_file_name = 'c400-' . Str::slug($existingFile->nomor_kelompok, '-') . '-' . uniqid() . '.' . $file_extension;

            // Hapus file C400 yang sudah ada jika ada
            if ($existingFile->file_name_c400 != null) {
                $file_path_c400 = public_path($existingFile->file_path_c400 . '/' . $existingFile->file_name_c400);
                if (file_exists($file_path_c400)) {
                    if (!unlink($file_path_c400)) {
                        session()->flash('danger', 'Gagal menghapus dokumen lama C400!');
                        return redirect()->back()->withInput();
                    }
                }
            }

            // Pastikan folder upload ada, jika tidak, buat folder baru
            if (!is_dir(public_path($upload_path))) {
                mkdir(public_path($upload_path), 0755, true);
            }

            // Lakukan proses upload file C400
            if (!$file->move(public_path($upload_path), $new_file_name)) {
                session()->flash('danger', 'Gagal mengunggah dokumen C400!');
                return redirect()->back()->withInput();
            }

            // Update informasi file C400 pada database
            $params = [
                'file_name_c400' => $new_file_name,
                'file_path_c400' => $upload_path
            ];
            $uploadFile = DokumenMahasiswaModel::uploadFileKel($kelompok->id, $params);

            if (!$uploadFile) {
                return redirect()->back()->with('danger', 'Gagal. Dokumen C400 gagal diunggah!');
            }

            // Update status kelompok dan dosen pembimbing terkait
            $statusParam = [
                'status_kelompok' => 'Menunggu Persetujuan C400!',
                'file_status_c400' => 'Menunggu Persetujuan C400!',
                'file_status_c400_dosbing1' => 'Menunggu Persetujuan C400!',
                'file_status_c400_dosbing2' => 'Menunggu Persetujuan C400!',
                'status_dosen_pembimbing_1' => 'Menunggu Persetujuan C400!',
                'status_dosen_pembimbing_2' => 'Menunggu Persetujuan C400!',
            ];
            DokumenMahasiswaModel::uploadFileKel($kelompok->id, $statusParam);

            session()->flash('success', 'Berhasil mengunggah dokumen C400!');
            return redirect()->back();
        }

        session()->flash('danger', 'Gagal mengunggah! Dokumen C400 tidak ditemukan!');
        return redirect()->back()->withInput();
    }


    public function uploadC500Process(Request $request)
    {
        $upload_path = '/file/kelompok/c500';

        $kelompok = MahasiswaKelompokModel::pengecekan_kelompok_mahasiswa(Auth::user()->user_id);
        $existingFile = DokumenMahasiswaModel::getKelompokFile($kelompok->id);

        // Pastikan sudah ada file C400 sebelum mengunggah C500
        if ($existingFile->file_name_c400 == null) {
            session()->flash('danger', 'Gagal mengunggah! Lengkapi terlebih dahulu Dokumen C400!');
            return redirect()->back()->withInput();
        }

        if ($request->hasFile('c500')) {
            $file = $request->file('c500');
            $file_extension = $file->getClientOriginalExtension();
            $new_file_name = 'c500-' . Str::slug($existingFile->nomor_kelompok, '-') . '-' . uniqid() . '.' . $file_extension;

            // Hapus file C500 yang sudah ada jika ada
            if ($existingFile->file_name_c500 != null) {
                $file_path_c500 = public_path($existingFile->file_path_c500 . '/' . $existingFile->file_name_c500);
                if (file_exists($file_path_c500)) {
                    if (!unlink($file_path_c500)) {
                        session()->flash('danger', 'Gagal menghapus dokumen lama C500!');
                        return redirect()->back()->withInput();
                    }
                }
            }

            // Pastikan folder upload ada, jika tidak, buat folder baru
            if (!is_dir(public_path($upload_path))) {
                mkdir(public_path($upload_path), 0755, true);
            }

            // Lakukan proses upload file C500
            if (!$file->move(public_path($upload_path), $new_file_name)) {
                session()->flash('danger', 'Gagal mengunggah dokumen C500!');
                return redirect()->back()->withInput();
            }

            // Update informasi file C500 pada database
            $params = [
                'file_name_c500' => $new_file_name,
                'file_path_c500' => $upload_path
            ];
            $uploadFile = DokumenMahasiswaModel::uploadFileKel($kelompok->id, $params);

            if (!$uploadFile) {
                return redirect()->back()->with('danger', 'Gagal. Dokumen C500 gagal diunggah!');
            }

            // Update status kelompok dan dosen pembimbing terkait
            $statusParam = [
                'status_kelompok' => 'Menunggu Persetujuan C500!',
                'file_status_c500' => 'Menunggu Persetujuan C500!',
                'file_status_c500_dosbing1' => 'Menunggu Persetujuan C500!',
                'file_status_c500_dosbing2' => 'Menunggu Persetujuan C500!',
                'status_dosen_pembimbing_1' => 'Menunggu Persetujuan C500!',
                'status_dosen_pembimbing_2' => 'Menunggu Persetujuan C500!',
            ];
            DokumenMahasiswaModel::uploadFileKel($kelompok->id, $statusParam);

            session()->flash('success', 'Berhasil mengunggah dokumen C500!');
            return redirect()->back();
        }

        session()->flash('danger', 'Gagal mengunggah! Dokumen C500 tidak ditemukan!');
        return redirect()->back()->withInput();
    }



}
