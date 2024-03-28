<?php

namespace App\Http\Controllers\TimCapstone\UploadFile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\UploadFile\UploadFileModel;
use App\Models\Mahasiswa\Kelompok_Mahasiswa\MahasiswaKelompokModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UploadFileController extends BaseController
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
        $file_mhs = UploadFileModel::fileMHS();
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
        return view('tim_capstone.upload-file.detail', $data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadMakalahProcess(Request $request)
    {


        // upload path
        $upload_path = '/file/mahasiswa/makalah';
        // UPLOAD FOTO
        if ($request->hasFile('makalah')) {

            $file = $request->file('makalah');
            // namafile
            $file_extention = pathinfo($file->getClientOriginalName(),
                PATHINFO_EXTENSION
            );
            $new_file_name = 'makalah-' . Str::slug(Auth::user() ->user_name, '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

            $existingFile = UploadFileModel::fileMHS($request->id_mahasiswa);
            // Check if the file exists
            if ($existingFile ->file_name_makalah != null) {
                // Construct the file path
                $filePath = public_path($existingFile->file_path_makalah . '/' . $existingFile->file_name_makalah);

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
                session()->flash('danger', 'Makalah gagal di upload!');
                return redirect()->back()->withInput();
            }

            $params = [
                'file_name_makalah' => $new_file_name,
                'file_path_makalah' => $upload_path
            ];
            UploadFileModel::uploadFileMHS($request->id_kel_mhs,$params);

        }


        // flash message
        session()->flash('success', 'Berhasil mengunggah dokumen!');
        return back();
    }

    public function uploadLaporanProcess(Request $request)
    {

        // upload path
        $upload_path = '/file/mahasiswa/laporan-ta';
        // UPLOAD FOTO
        if ($request->hasFile('laporan_ta')) {

            $file = $request->file('laporan_ta');
            // namafile
            $file_extention = pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_EXTENSION
            );
            $new_file_name = 'laporan_ta-' . Str::slug(Auth::user() ->user_name, '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

            $existingFile = UploadFileModel::fileMHS($request->id_mahasiswa);
            // Check if the file exists
            if ($existingFile ->file_name_laporan_ta !=null) {
                // Construct the file path
                $filePath = public_path($existingFile->file_path_laporan_ta . '/' . $existingFile->file_name_laporan_ta);

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
                'file_name_laporan_ta' => $new_file_name,
                'file_path_laporan_ta' => $upload_path
            ];
            UploadFileModel::uploadFileMHS($request->id_kel_mhs, $params);
        }


        // flash message
        session()->flash('success', 'Berhasil mengunggah dokumen!');
        return back();
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

            $existingFile = UploadFileModel::getKelompokFile($kelompok->id);
            $new_file_name = 'c100-' . Str::slug($existingFile->nomor_kelompok , '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

            $siklus = UploadFileModel::getSiklusKelompok($existingFile->id);

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
                $uploadFile = UploadFileModel::uploadFileKel($kelompok->id, $params);

                if ($uploadFile) {
                    $statusParam = [
                        'status_kelompok' => 'C100 Telah Disetujui!',
                        'status_dosen_pembimbing_1' => 'Menyetujui Dokumen C100!',
                        'status_dosen_pembimbing_2' => 'Menyetujui Dokumen C100!',
                    ];

                    UploadFileModel::uploadFileKel($kelompok->id, $statusParam);
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


        // upload path
        $upload_path = '/file/kelompok/c200';
        // UPLOAD FOTO
        if ($request->hasFile('c200')) {

            $file = $request->file('c200');
            // namafile
            $file_extention = pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_EXTENSION
            );

            $kelompok = MahasiswaKelompokModel::pengecekan_kelompok_mahasiswa(Auth::user()->user_id);

            $existingFile = UploadFileModel::getKelompokFile($kelompok->id);
            $new_file_name = 'c200-' . Str::slug($existingFile->nomor_kelompok , '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Check if the file exists
            if ($existingFile -> file_name_c200!=null) {
                if ($existingFile -> file_name_c100 != null) {
                    // Construct the file path
                    $filePath = public_path($existingFile->file_path_c200 . '/' . $existingFile->file_name_c200);

                    // Check if the file exists before attempting to delete
                    if (file_exists($filePath)) {
                        // Attempt to delete the file
                        if (!unlink($filePath)) {
                            // Return failure response if failed to delete the existing file
                            session()->flash('danger', 'Gagal menghapus dokumen lama!');
                            return redirect()->back()->withInput();
                        }
                    }
                } else{
                    session()->flash('danger', 'Gagal mengunggah! Lengkapi terlebih dahulu Dokumen C100!');
                    return redirect()->back()->withInput();
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
                'file_name_c200' => $new_file_name,
                'file_path_c200' => $upload_path
            ];
            $uploadFile = UploadFileModel::uploadFileKel($kelompok->id, $params);

            if ($uploadFile) {
                $statusParam = [
                    'status_kelompok' => 'C200 Telah Disetujui!',
                    'status_dosen_pembimbing_1' => 'Menyetujui Dokumen C200!',
                    'status_dosen_pembimbing_2' => 'Menyetujui Dokumen C200!',
                ];

                UploadFileModel::uploadFileKel($kelompok->id, $statusParam);
            } else {
                return redirect()->back()->with('danger', 'Gagal. Dokumen gagal diunggah!');
            }
        }


        // flash message
        session()->flash('success', 'Berhasil mengunggah dokumen!');
        return back();
    }
    public function uploadC300Process(Request $request)
    {


        // upload path
        $upload_path = '/file/kelompok/c300';
        // UPLOAD FOTO
        if ($request->hasFile('c300')) {

            $file = $request->file('c300');
            // namafile
            $file_extention = pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_EXTENSION
            );

            $kelompok = MahasiswaKelompokModel::pengecekan_kelompok_mahasiswa(Auth::user()->user_id);

            $existingFile = UploadFileModel::getKelompokFile($kelompok->id);
            $new_file_name = 'c300-' . Str::slug($existingFile->nomor_kelompok , '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Check if the file exists
            if ($existingFile -> file_name_c300 !=null) {
                if ($existingFile -> file_name_c200 != null) {

                    // Construct the file path
                    $filePath = public_path($existingFile->file_path_c300 . '/' . $existingFile->file_name_c300);

                    // Check if the file exists before attempting to delete
                    if (file_exists($filePath)) {
                        // Attempt to delete the file
                        if (!unlink($filePath)) {
                            session()->flash('danger', 'Gagal menghapus dokumen lama!');
                            return redirect()->back()->withInput();
                        }
                    }

                }else{
                    session()->flash('danger', 'Gagal mengunggah! Lengkapi terlebih dahulu Dokumen C200!');
                    return redirect()->back()->withInput();
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
                'file_name_c300' => $new_file_name,
                'file_path_c300' => $upload_path
            ];
            $uploadFile = UploadFileModel::uploadFileKel($kelompok->id, $params);

            if ($uploadFile) {
                $statusParam = [
                    'status_kelompok' => 'C300 Telah Disetujui!',
                    'status_dosen_pembimbing_1' => 'Menyetujui Dokumen C300!',
                    'status_dosen_pembimbing_2' => 'Menyetujui Dokumen C300!',
                ];

                UploadFileModel::uploadFileKel($kelompok->id, $statusParam);
            } else {
                return redirect()->back()->with('danger', 'Gagal. Dokumen gagal diunggah!');
            }
         }


        // flash message
        session()->flash('success', 'Berhasil mengunggah dokumen!');
        return back();
    }
    public function uploadC400Process(Request $request)
    {


        // upload path
        $upload_path = '/file/kelompok/c400';
        // UPLOAD FOTO
        if ($request->hasFile('c400')) {

            $file = $request->file('c400');
            // namafile
            $file_extention = pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_EXTENSION
            );

            $kelompok = MahasiswaKelompokModel::pengecekan_kelompok_mahasiswa(Auth::user()->user_id);

            $existingFile = UploadFileModel::getKelompokFile($kelompok->id);
            $new_file_name = 'c400-' . Str::slug($existingFile->nomor_kelompok , '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Check if the file exists
            if ($existingFile -> file_name_c400 !=null) {
                if ($existingFile -> file_name_c300 != null) {

                    // Construct the file path
                    $filePath = public_path($existingFile->file_path_c400 . '/' . $existingFile->file_name_c400);

                    // Check if the file exists before attempting to delete
                    if (file_exists($filePath)) {
                        // Attempt to delete the file
                        if (!unlink($filePath)) {
                            // Return failure response if failed to delete the existing file
                            session()->flash('danger', 'Gagal menghapus dokumen lama!');
                            return redirect()->back()->withInput();
                        }
                    }
                }else{
                    session()->flash('danger', 'Gagal mengunggah! Lengkapi terlebih dahulu Dokumen C300!');
                    return redirect()->back()->withInput();
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
                'file_name_c400' => $new_file_name,
                'file_path_c400' => $upload_path
            ];
            $uploadFile = UploadFileModel::uploadFileKel($kelompok->id, $params);

            if ($uploadFile) {
                $statusParam = [
                    'status_kelompok' => 'C400 Telah Disetujui!',
                    'status_dosen_pembimbing_1' => 'Menyetujui Dokumen C400!',
                    'status_dosen_pembimbing_2' => 'Menyetujui Dokumen C400!',
                ];

                UploadFileModel::uploadFileKel($kelompok->id, $statusParam);
            } else {
                return redirect()->back()->with('danger', 'Gagal. Dokumen gagal diunggah!');
            }

        }


        // flash message
        session()->flash('success', 'Berhasil mengunggah dokumen!');
        return back();
    }
    public function uploadC500Process(Request $request)
    {


        // upload path
        $upload_path = '/file/kelompok/c500';
        // UPLOAD FOTO
        if ($request->hasFile('c500')) {

            $file = $request->file('c500');
            // namafile
            $file_extention = pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_EXTENSION
            );

            $kelompok = MahasiswaKelompokModel::pengecekan_kelompok_mahasiswa(Auth::user()->user_id);

            $existingFile = UploadFileModel::getKelompokFile($kelompok->id);
            $new_file_name = 'c500-' . Str::slug($existingFile->nomor_kelompok , '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Check if the file exists
            if ($existingFile -> file_name_c500 !=null) {
                if ($existingFile -> file_name_c400 != null) {

                    // Construct the file path
                    $filePath = public_path($existingFile->file_path_c500 . '/' . $existingFile->file_name_c500);

                    // Check if the file exists before attempting to delete
                    if (file_exists($filePath)) {
                        // Attempt to delete the file
                        if (!unlink($filePath)) {
                            // Return failure response if failed to delete the existing file
                            session()->flash('danger', 'Gagal menghapus dokumen lama!');
                            return redirect()->back()->withInput();
                        }
                    }

                }else{
                    session()->flash('danger', 'Gagal mengunggah! Lengkapi terlebih dahulu Dokumen C400!');
                    return redirect()->back()->withInput();
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
                'file_name_c500' => $new_file_name,
                'file_path_c500' => $upload_path
            ];
            $uploadFile = UploadFileModel::uploadFileKel($kelompok->id, $params);

            if ($uploadFile) {
                $statusParam = [
                    'status_kelompok' => 'C500 Telah Disetujui!',
                    'status_dosen_pembimbing_1' => 'Menyetujui Dokumen C500!',
                    'status_dosen_pembimbing_2' => 'Menyetujui Dokumen C500!',
                ];

                UploadFileModel::uploadFileKel($kelompok->id, $statusParam);
            } else {
                return redirect()->back()->with('danger', 'Gagal. Dokumen gagal diunggah!');
            }
        }

        // flash message
        session()->flash('success', 'Berhasil mengunggah dokumen!');
        return back();
    }

}
