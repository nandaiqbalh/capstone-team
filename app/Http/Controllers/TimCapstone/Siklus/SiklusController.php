<?php

namespace App\Http\Controllers\TimCapstone\Siklus;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\Siklus\SiklusModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class SiklusController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //coba comment

    public function index()
    {
        // dd(SiklusModel::getData());

        // get data with pagination
        $dt_siklus = SiklusModel::getDataWithPagination();
        // data
        $data = ['dt_siklus' => $dt_siklus];
        // view
        return view('tim_capstone.siklus.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addSiklus()
    {
        // view
        return view('tim_capstone.siklus.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addSiklusProcess(Request $request)
    {
        // Aturan validasi
        $rules = [
            'tahun_ajaran' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'pendaftaran_mulai' => 'required|date',
            'pendaftaran_selesai' => 'required|date|after:pendaftaran_mulai',
            'batas_submit_c100' => 'required|date|after:pendaftaran_selesai',
            'status' => 'required',
        ];

        // Pesan validasi kustom dalam bahasa Indonesia
        $messages = [
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Tanggal mulai harus berupa tanggal yang valid.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date' => 'Tanggal selesai harus berupa tanggal yang valid.',
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
            'pendaftaran_mulai.required' => 'Tanggal pendaftaran mulai wajib diisi.',
            'pendaftaran_mulai.date' => 'Tanggal pendaftaran mulai harus berupa tanggal yang valid.',
            'pendaftaran_selesai.required' => 'Tanggal pendaftaran selesai wajib diisi.',
            'pendaftaran_selesai.date' => 'Tanggal pendaftaran selesai harus berupa tanggal yang valid.',
            'pendaftaran_selesai.after' => 'Tanggal pendaftaran selesai harus setelah tanggal pendaftaran mulai.',
            'batas_submit_c100.required' => 'Batas submit C100 wajib diisi.',
            'batas_submit_c100.date' => 'Batas submit C100 harus berupa tanggal yang valid.',
            'batas_submit_c100.after' => 'Batas submit C100 harus setelah tanggal pendaftaran selesai.',
            'status.required' => 'Status wajib diisi.',
        ];

        // Validasi request data
        $validator = Validator::make($request->all(), $rules, $messages);

        // Periksa jika validasi gagal
        if ($validator->fails()) {
            return redirect('/admin/siklus/add')
                ->withErrors($validator)
                ->withInput();
        }

        // Persiapkan parameter untuk penyisipan
        $params = [
            'tahun_ajaran' => $request->tahun_ajaran,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'pendaftaran_mulai' => $request->pendaftaran_mulai,
            'pendaftaran_selesai' => $request->pendaftaran_selesai,
            'batas_submit_c100' => $request->batas_submit_c100,
            'status' => $request->status,
            'created_by' => Auth::user()->user_id,
            'created_date' => now()->toDateTimeString()
        ];

        // Insert data siklus
        $insert_siklus = SiklusModel::insertSiklus($params);

        // Periksa jika penyisipan berhasil
        if ($insert_siklus) {
            // Pesan flash untuk sukses
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/siklus');
        } else {
            // Pesan flash untuk gagal
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/siklus/add')->withInput();
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailSiklus($id)
    {

        // get data with pagination
        $siklus = SiklusModel::getDataById($id);

        // check
        if (empty($siklus)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/siklus');
        }

        // data
        $data = ['siklus' => $siklus];

        // view
        return view('tim_capstone.siklus.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editSiklus($id)
    {

        // get data
        $siklus = SiklusModel::getDataById($id);

        // check
        if (empty($siklus)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/siklus');
        }

        // data
        $data = ['siklus' => $siklus];

        // view
        return view('tim_capstone.siklus.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editSiklusProcess(Request $request)
    {
        // Aturan validasi
        $rules = [
            'tahun_ajaran' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'pendaftaran_mulai' => 'required|date',
            'pendaftaran_selesai' => 'required|date|after:pendaftaran_mulai',
            'batas_submit_c100' => 'required|date|after:pendaftaran_selesai',
            'status' => 'required',
        ];

        // Pesan validasi kustom dalam bahasa Indonesia
        $messages = [
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Tanggal mulai harus berupa tanggal yang valid.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date' => 'Tanggal selesai harus berupa tanggal yang valid.',
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
            'pendaftaran_mulai.required' => 'Tanggal pendaftaran mulai wajib diisi.',
            'pendaftaran_mulai.date' => 'Tanggal pendaftaran mulai harus berupa tanggal yang valid.',
            'pendaftaran_selesai.required' => 'Tanggal pendaftaran selesai wajib diisi.',
            'pendaftaran_selesai.date' => 'Tanggal pendaftaran selesai harus berupa tanggal yang valid.',
            'pendaftaran_selesai.after' => 'Tanggal pendaftaran selesai harus setelah tanggal pendaftaran mulai.',
            'batas_submit_c100.required' => 'Batas submit C100 wajib diisi.',
            'batas_submit_c100.date' => 'Batas submit C100 harus berupa tanggal yang valid.',
            'batas_submit_c100.after' => 'Batas submit C100 harus setelah tanggal pendaftaran selesai.',
            'status.required' => 'Status wajib diisi.',
        ];

        // Validasi request data
        $validator = Validator::make($request->all(), $rules, $messages);

        // Periksa jika validasi gagal
        if ($validator->fails()) {
            return redirect('/admin/siklus/edit/' . $request->id)
                ->withErrors($validator)
                ->withInput();
        }

        // Persiapkan parameter untuk pembaruan
        $params = [
            'tahun_ajaran' => $request->tahun_ajaran,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'pendaftaran_mulai' => $request->pendaftaran_mulai,
            'pendaftaran_selesai' => $request->pendaftaran_selesai,
            'batas_submit_c100' => $request->batas_submit_c100,
            'status' => $request->status,
            'modified_by' => Auth::user()->user_id,
            'modified_date' => now()->toDateTimeString()
        ];

        // Proses pembaruan data siklus
        if (SiklusModel::update($request->id, $params)) {
            // Pesan flash untuk sukses
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/siklus');
        } else {
            // Pesan flash untuk gagal
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/siklus/edit/' . $request->id)->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteSiklusProcess($id)
    {
        try {
            // Hapus entitas terkait dengan siklus secara berurutan
            SiklusModel::deletependaftaranExpo($id);
            SiklusModel::deleteJadwalExpo($id);
            SiklusModel::deleteJadwalSidangProposal($id);
            SiklusModel::deleteKelompok($id);
            SiklusModel::deleteKelompokMhs($id);

            // Hapus siklus itu sendiri
            if (SiklusModel::delete($id)) {
                // Flash message sukses jika penghapusan berhasil
                session()->flash('success', 'Data siklus dan entitas terkait berhasil dihapus.');
            } else {
                // Flash message gagal jika penghapusan siklus gagal
                session()->flash('danger', 'Gagal menghapus data siklus.');
            }
        } catch (\Exception $e) {
            // Tangani kesalahan dan beri pesan sesuai
            session()->flash('danger', 'Terjadi kesalahan dalam menghapus data siklus.');
        }

        // Redirect kembali ke halaman siklus setelah selesai penghapusan
        return redirect('/admin/siklus');
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
        $nama = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_ch = SiklusModel::getDataSearch($nama);
            // data
            $data = ['rs_ch' => $rs_ch, 'nama' => $nama];
            // view
            return view('tim_capstone.settings.contoh-halaman.index', $data);
        } else {
            return redirect('/admin/settings/contoh-halaman');
        }
    }
}
