<?php

namespace App\Http\Controllers\TimCapstone\UploadFile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\UploadFile\UploadFileModel;
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
        // authorize
        UploadFileModel::authorize('R');

        // get data kelompok
        $file_mhs = UploadFileModel::fileMHS();

        // data
        $data = [
            'file_mhs'  => $file_mhs,
        ];


        // dd($data);
        // view
        return view('admin.upload-file.detail', $data);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadMakalahProcess(Request $request)
    {
        // dd($request['angkatan']);

        // authorize
        UploadFileModel::authorize('C');


        // upload path
        $upload_path = '/file/mahasiswa/makalah';
        // UPLOAD FOTO
        if ($request->hasFile('makalah')) {

            $file = $request->file('makalah');
            // namafile
            $file_extention = pathinfo($file->getClientOriginalName(),
                PATHINFO_EXTENSION
            );
            $new_file_name  = 'makalah'.Str::slug($request->nama_mahasiswa, '-') . '-' . uniqid() . '.' . $file_extention;

            // cek folder
            if (!is_dir(public_path($upload_path))) {
                mkdir(public_path($upload_path), 0755, true);
            }

            // upload process
            if (!$file->move(public_path($upload_path), $new_file_name)) {
                // flash message
                session()->flash('danger', 'Makalah gagal di upload.');
                return redirect()->back()->withInput();
            }

            $params = [
                'file_name_makalah' => $new_file_name,
                'file_path_makalah' => $upload_path
            ];
            UploadFileModel::uploadFileMHS($request->id_kel_mhs,$params);

        }


        // flash message
        session()->flash('success', 'Data berhasil disimpan.');
        return back();
    }

    public function uploadLaporanProcess(Request $request)
    {
        // dd($request['angkatan']);

        // authorize
        UploadFileModel::authorize('C');


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
            $new_file_name  = 'laporan_ta' . Str::slug($request->nama_mahasiswa, '-') . '-' . uniqid() . '.' . $file_extention;

            // cek folder
            if (!is_dir(public_path($upload_path))) {
                mkdir(public_path($upload_path), 0755, true);
            }

            // upload process
            if (!$file->move(public_path($upload_path), $new_file_name)) {
                // flash message
                session()->flash('danger', 'Laporan gagal di upload.');
                return redirect()->back()->withInput();
            }

            $params = [
                'file_name_laporan_ta' => $new_file_name,
                'file_path_laporan_ta' => $upload_path
            ];
            UploadFileModel::uploadFileMHS($request->id_kel_mhs, $params);
        }


        // flash message
        session()->flash('success', 'Data berhasil disimpan.');
        return back();
    }

    // c series

    public function uploadC100Process(Request $request)
    {
        // dd($request['angkatan']);

        // authorize
        UploadFileModel::authorize('C');


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
            $new_file_name  = 'c100' . Str::slug($request->nama_mahasiswa, '-') . '-' . uniqid() . '.' . $file_extention;

            // cek folder
            if (!is_dir(public_path($upload_path))) {
                mkdir(public_path($upload_path), 0755, true);
            }

            // upload process
            if (!$file->move(public_path($upload_path), $new_file_name)) {
                // flash message
                session()->flash('danger', 'Laporan gagal di upload.');
                return redirect()->back()->withInput();
            }

            $params = [
                'file_name_c100' => $new_file_name,
                'file_path_c100' => $upload_path
            ];
            UploadFileModel::uploadFileKel($request->id_kelompok, $params);
        }


        // flash message
        session()->flash('success', 'Data berhasil disimpan.');
        return back();
    }
    public function uploadC200Process(Request $request)
    {
        // dd($request['angkatan']);

        // authorize
        UploadFileModel::authorize('C');


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
            $new_file_name  = 'c200' . Str::slug($request->nama_mahasiswa, '-') . '-' . uniqid() . '.' . $file_extention;

            // cek folder
            if (!is_dir(public_path($upload_path))) {
                mkdir(public_path($upload_path), 0755, true);
            }

            // upload process
            if (!$file->move(public_path($upload_path), $new_file_name)) {
                // flash message
                session()->flash('danger', 'Laporan gagal di upload.');
                return redirect()->back()->withInput();
            }

            $params = [
                'file_name_c200' => $new_file_name,
                'file_path_c200' => $upload_path
            ];
            UploadFileModel::uploadFileKel($request->id_kelompok, $params);
        }


        // flash message
        session()->flash('success', 'Data berhasil disimpan.');
        return back();
    }
    public function uploadC300Process(Request $request)
    {
        // dd($request['angkatan']);

        // authorize
        UploadFileModel::authorize('C');


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
            $new_file_name  = 'c300' . Str::slug($request->nama_mahasiswa, '-') . '-' . uniqid() . '.' . $file_extention;

            // cek folder
            if (!is_dir(public_path($upload_path))) {
                mkdir(public_path($upload_path), 0755, true);
            }

            // upload process
            if (!$file->move(public_path($upload_path), $new_file_name)) {
                // flash message
                session()->flash('danger', 'Laporan gagal di upload.');
                return redirect()->back()->withInput();
            }

            $params = [
                'file_name_c300' => $new_file_name,
                'file_path_c300' => $upload_path
            ];
            UploadFileModel::uploadFileKel($request->id_kelompok, $params);
        }


        // flash message
        session()->flash('success', 'Data berhasil disimpan.');
        return back();
    }
    public function uploadC400Process(Request $request)
    {
        // dd($request['angkatan']);

        // authorize
        UploadFileModel::authorize('C');


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
            $new_file_name  = 'c400' . Str::slug($request->nama_mahasiswa, '-') . '-' . uniqid() . '.' . $file_extention;

            // cek folder
            if (!is_dir(public_path($upload_path))) {
                mkdir(public_path($upload_path), 0755, true);
            }

            // upload process
            if (!$file->move(public_path($upload_path), $new_file_name)) {
                // flash message
                session()->flash('danger', 'Laporan gagal di upload.');
                return redirect()->back()->withInput();
            }

            $params = [
                'file_name_c400' => $new_file_name,
                'file_path_c400' => $upload_path
            ];
            UploadFileModel::uploadFileKel($request->id_kelompok, $params);
        }


        // flash message
        session()->flash('success', 'Data berhasil disimpan.');
        return back();
    }
    public function uploadC500Process(Request $request)
    {
        // dd($request['angkatan']);

        // authorize
        UploadFileModel::authorize('C');


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
            $new_file_name  = 'c500' . Str::slug($request->nama_mahasiswa, '-') . '-' . uniqid() . '.' . $file_extention;

            // cek folder
            if (!is_dir(public_path($upload_path))) {
                mkdir(public_path($upload_path), 0755, true);
            }

            // upload process
            if (!$file->move(public_path($upload_path), $new_file_name)) {
                // flash message
                session()->flash('danger', 'Laporan gagal di upload.');
                return redirect()->back()->withInput();
            }

            $params = [
                'file_name_c500' => $new_file_name,
                'file_path_c500' => $upload_path
            ];
            UploadFileModel::uploadFileKel($request->id_kelompok, $params);
        }


        // flash message
        session()->flash('success', 'Data berhasil disimpan.');
        return back();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addPunyaKelompokProcess(Request $request)
    {

        // dd($request);
        // authorize
        UploadFileModel::authorize('C');

        // addKelompok

        $params = [
            "id_siklus" => $request->id_siklus,
            "judul_ta" => $request->judul_ta,
            "id_topik" => $request->id_topik,
            "status_kelompok" => "menunggu persetujuan",
            "id_dosen_pembimbing_1" => $request->dosbing_1,
            "id_dosen_pembimbing_2" => $request->dosbing_2,
        ];
        UploadFileModel::insertKelompok($params);
        $id_kelompok = DB::getPdo()->lastInsertId();

        $paramsDosen1 = [
            "id_kelompok" => $id_kelompok,
            "id_dosen" => $request->dosbing_1,
            "status_dosen" => "pembimbing 1",
            "status_persetujuan" => "menunggu persetujuan",
        ];
        UploadFileModel::insertDosenKelompok($paramsDosen1);
        $paramsDosen2 = [
            "id_kelompok" => $id_kelompok,
            "id_dosen" => $request->dosbing_2,
            "status_dosen" => "pembimbing 2",
            "status_persetujuan" => "menunggu persetujuan",
        ];
        UploadFileModel::insertDosenKelompok($paramsDosen2);


        // params mahasiswa 1
        $params1 = [
            "angkatan" => $request->angkatan1,
            "ipk" => $request->ipk1,
            "sks" => $request->no_telp1,
            'no_telp' => $request->sks1,
            "alamat" => $request->alamat1,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        $update_mahasiswa1 = UploadFileModel::updateMahasiswa($request->nama1, $params1);
        if ($update_mahasiswa1) {
            $params11 = [
                "id_siklus" => $request->id_siklus,
                'id_kelompok' => $id_kelompok,
                'id_mahasiswa' => $request->nama1,
                'id_topik_mhs' => $request->id_topik,
            ];
            UploadFileModel::insertKelompokMHS($params11);
        }

        // params mahasiswa 2
        $params2 = [
            // 'user_id' => Auth::user()->user_id,
            "angkatan" => $request->angkatan2,
            "ipk" => $request->ipk2,
            "sks" => $request->no_telp2,
            'no_telp' => $request->sks2,
            "alamat" => $request->alamat2,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        $update_mahasiswa2 = UploadFileModel::updateMahasiswa($request->nama2, $params2);
        if ($update_mahasiswa2) {
            $params22 = [
                "id_siklus" => $request->id_siklus,
                'id_kelompok' => $id_kelompok,
                'id_mahasiswa' => $request->nama2,
                'id_topik_mhs' => $request->id_topik,
            ];
            UploadFileModel::insertKelompokMHS($params22);
        }

        // params mahasiswa 3
        $params3 = [
            // 'user_id' => Auth::user()->user_id,
            "angkatan" => $request->angkatan3,
            "ipk" => $request->ipk3,
            "sks" => $request->no_telp3,
            'no_telp' => $request->sks3,
            "alamat" => $request->alamat3,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        $update_mahasiswa3 = UploadFileModel::updateMahasiswa($request->nama3, $params3);
        if ($update_mahasiswa3) {
            $params33 = [
                "id_siklus" => $request->id_siklus,
                'id_kelompok' => $id_kelompok,
                'id_mahasiswa' => $request->nama3,
                'id_topik_mhs' => $request->id_topik,
            ];
            UploadFileModel::insertKelompokMHS($params33);
        }
        return redirect('/mahasiswa/kelompok');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailMahasiswa($user_id)
    {
        // authorize
        UploadFileModel::authorize('R');

        // get data with pagination
        $mahasiswa = UploadFileModel::getDataById($user_id);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }

        // data
        $data = ['mahasiswa' => $mahasiswa];

        // view
        return view('admin.mahasiswa.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editMahasiswa($user_id)
    {
        // authorize
        UploadFileModel::authorize('U');

        // get data
        $mahasiswa = UploadFileModel::getDataById($user_id);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }

        // data
        $data = ['mahasiswa' => $mahasiswa];

        // view
        return view('admin.mahasiswa.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editMahasiswaProcess(Request $request)
    {
        // authorize
        UploadFileModel::authorize('U');

        // Validate & auto redirect when fail
        $rules = [
            'nama' => 'required',
            "nim" => 'required',
            "angkatan" => 'required',
            "ipk" => 'required',
            "sks" => 'required',
            "alamat" => 'required',
        ];
        $this->validate($request, $rules);

        // params
        $params = [
            'user_name' => $request->nama,
            "nomor_induk" => $request->nim,
            "angkatan" => $request->angkatan,
            "ipk" => $request->ipk,
            "sks" => $request->sks,
            "alamat" => $request->alamat,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (UploadFileModel::update($request->user_id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/mahasiswa');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/mahasiswa/edit/' . $request->user_id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteMahasiswaProcess($user_id)
    {
        // authorize
        UploadFileModel::authorize('D');

        // get data
        $mahasiswa = UploadFileModel::getDataById($user_id);

        // if exist
        if (!empty($mahasiswa)) {
            // process
            if (UploadFileModel::delete($user_id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/mahasiswa');
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/settings/contoh-halaman');
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/settings/contoh-halaman');
        }
    }

    /**
     * Search data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchMahasiswa(Request $request)
    {
        // authorize
        UploadFileModel::authorize('R');
        // data request
        $user_name = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_mahasiswa = UploadFileModel::getDataSearch($user_name);
            // dd($rs_mahasiswa);
            // data
            $data = ['rs_mahasiswa' => $rs_mahasiswa, 'nama' => $user_name];
            // view
            return view('admin.mahasiswa.index', $data);
        } else {
            return redirect('/admin/mahasiswa');
        }
    }
}
