<?php

namespace App\Http\Controllers\TimCapstone\Broadcast;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\Broadcast\BroadcastModel;
use Illuminate\Support\Facades\Hash;


class BroadcastController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //coba comment

    public function index()
    {
        // dd(BroadcastModel::getData());

        // get data with pagination
        $rs_broadcast = BroadcastModel::getDataWithPagination();
        // data
        $data = ['rs_broadcast' => $rs_broadcast];
        // view
        return view('tim_capstone.broadcast.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addBroadcast()
    {
        // view
        return view('tim_capstone.broadcast.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addBroadcastProcess(Request $request)
    {

        // Validate & auto redirect when fail
        $rules = [
            'nama_event' => 'required',
            'tgl_mulai' => 'required',
            'tgl_selesai' => 'required',
        ];
        $this->validate($request, $rules);


        // params

        $params = [
            'nama_event' => $request->nama_event,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'keterangan' => $request->keterangan,
            'link_pendukung' => $request->link_pendukung,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        $insert_broadcast = BroadcastModel::insertbroadcast($params);
        if ($insert_broadcast) {

            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/broadcast');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/broadcast/add')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailBroadcast($id)
    {

        // get data with pagination
        $broadcast = BroadcastModel::getDataById($id);

        // check
        if (empty($broadcast)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/broadcast');
        }

        // data
        $data = ['broadcast' => $broadcast];

        // view
        return view('tim_capstone.broadcast.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editBroadcast($id)
    {

        // get data
        $broadcast = BroadcastModel::getDataById($id);

        // check
        if (empty($broadcast)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/broadcast');
        }

        // data
        $data = ['broadcast' => $broadcast];

        // view
        return view('tim_capstone.broadcast.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editBroadcastProcess(Request $request)
    {

        // Validate & auto redirect when fail
        $rules = [
            'nama_event' => 'required',
            'tgl_mulai' => 'required',
            'tgl_selesai' => 'required',
        ];
        $this->validate($request, $rules);

        // params
        $params = [
            'nama_event' => $request->nama_event,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'keterangan' => $request->keterangan,
            'link_pendukung' => $request->link_pendukung,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s'),
        ];

        // process
        if (BroadcastModel::update($request->id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/broadcast');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/broadcast/edit/' . $request->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteBroadcastProcess($id)
    {
        // get data
        $broadcast = BroadcastModel::getDataById($id);

        // if exist
        if (!empty($broadcast)) {
            // process
            if (BroadcastModel::delete($id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/broadcast');
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/broadcast');
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/broadcast');
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

        // data request
        $user_name = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_broadcast = BroadcastModel::getDataSearch($user_name);
            // dd($rs_broadcast);
            // data
            $data = ['rs_broadcast' => $rs_broadcast, 'nama' => $user_name];
            // view
            return view('tim_capstone.broadcast.index', $data);
        } else {
            return redirect('/admin/broadcast');
        }
    }
}
