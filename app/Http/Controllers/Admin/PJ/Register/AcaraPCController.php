<?php

namespace App\Http\Controllers\Admin\PJ\Register;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Admin\PJ\Register\AcaraPCModel;
use Illuminate\Support\Facades\DB;



class AcaraPCController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        AcaraPCModel::authorize('R');

        // get data with pagination 
        // Penamaan Backend pake inggris Front pake indo
        $rs_branch_event = AcaraPCModel::getDataEventWithPagination();
        // data
        $data = ['rs_branch_event' => $rs_branch_event];
        // view
        return view('admin.pj.register.acara.index', $data );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        // authorize
        AcaraPCModel::authorize('C');

        // get user by branch id 
        $rs_user_branch = AcaraPCModel::getUserBranch();

        $data = [
            'rs_user_branch' => $rs_user_branch
        ];

        // view
        return view('admin.pj.register.acara.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addProcess(Request $request)
    {
        // authorize
        AcaraPCModel::authorize('C');

        // Validate & auto redirect when fail
        $rules = [
            'nama' => 'required',
            'venue' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
            'deskripsi' =>'required',
            'penanggung_jawab'=>'required',
            'poster' => 'image|mimes:jpeg,jpg,png|max:10000'
        ];
        $this->validate($request, $rules);
        // upload path
        $upload_path = '/img/acara/poster/';
        // UPLOAD FOTO
        if ($request->hasFile('poster')) {

            $file = $request->file('poster');
            // namafile
            $file_extention = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $new_file_name  = Str::slug($request->nama, '-') . '-' . uniqid() . '.' . $file_extention;

            // cek folder
            if (!is_dir(public_path($upload_path))) {
                mkdir(public_path($upload_path), 0755, true);
            }

            // upload process
            if (!$file->move(public_path($upload_path), $new_file_name)) {
                // flash message
                session()->flash('danger', 'Poster gagal diupload.');
                return redirect()->back()->withInput();
            }
        }
        // params
        $params = [
            'branch_id'         => Auth::user()->branch_id,
            'name'              => $request->nama,
            'pj_id'             => $request->penanggung_jawab,
            'venue'             => $request->venue,
            'description'       => $request->deskripsi,
            'date_start'        => $request->tanggal_mulai,
            'date_end'          => $request->tanggal_selesai,
            'status'            => 'belum berjalan',
            'img_event_path'    => $upload_path,
            'img_event_name'    => $new_file_name,
            'created_by'        => Auth::user()->user_id,
            'created_date'      => date('Y-m-d H:i:s')
        ];

        // process
        if (AcaraPCModel::insertEvent($params)) {
            // flash message
            $id_event = DB::getPdo()->lastInsertId();
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/pj/register/acara/add-detail/'. $id_event);
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return back()->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addDetailProcessGuest(Request $request)
    {
        // authorize
        AcaraPCModel::authorize('C');

        // Validate & auto redirect when fail
        $rules = [
            'nama' => 'required',
            'guest_star_img' => 'image|mimes:jpeg,jpg,png|max:10000'
        ];
        $this->validate($request, $rules);
        // upload path
        $upload_path = '/img/acara/guest/';
        // UPLOAD FOTO
        if ($request->hasFile('guest_star_img')) {

            $file = $request->file('guest_star_img');
            // namafile
            $file_extention = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $new_file_name  = Str::slug($request->nama, '-') . '-' . uniqid() . '.' . $file_extention;

            // cek folder
            if (!is_dir(public_path($upload_path))) {
                mkdir(public_path($upload_path), 0755, true);
            }

            // upload process
            if (!$file->move(public_path($upload_path), $new_file_name)) {
                // flash message
                session()->flash('danger', 'Poster gagal diupload.');
                return redirect()->back()->withInput();
            }
        }
        // params
        $params = [
            'event_id'          => $request->event_id,
            'name'              => $request->nama,
            'img_path'          => $upload_path,
            'img_name'          => $new_file_name,
            'description'              => $request->description,
            'created_by'        => Auth::user()->user_id,
            'created_date'      => date('Y-m-d H:i:s')
        ];

        // process
        if (AcaraPCModel::insertEventGuest($params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/pj/register/acara/add-detail/' . $request->event_id);
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return back()->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addDetailProcessTicket(Request $request)
    {
        // authorize
        AcaraPCModel::authorize('C');

        // Validate & auto redirect when fail
        $rules = [
            'nama' => 'required',
            'harga' => 'required',
        ];
        $this->validate($request, $rules);
        // params
        $params = [
            'event_id'         => $request->event_id,
            'name'              => $request->nama,
            'harga'              => $request->harga,
            'description'              => $request->description,
            'created_by'        => Auth::user()->user_id,
            'created_date'      => date('Y-m-d H:i:s')
        ];

        // process
        if (AcaraPCModel::insertEventTicket($params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/pj/register/acara/add-detail/' . $request->event_id);
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return back()->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addDetailProcessRundown(Request $request)
    {
        // authorize
        AcaraPCModel::authorize('C');

        // Validate & auto redirect when fail
        $rules = [
            'nama' => 'required',
            'day' => 'required',
            'start' => 'required',
            'end' => 'required',
        ];
        $this->validate($request, $rules);

        // params
        $params = [
            'event_id'          => $request->event_id,
            'name'              => $request->nama,
            'day'               => $request->day,
            'start'             => $request->start,
            'end'               => $request->end,
            'created_by'        => Auth::user()->user_id,
            'created_date'      => date('Y-m-d H:i:s')
        ];

        // process
        if (AcaraPCModel::insertEventRundown($params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/pj/register/acara/add-detail/' . $request->event_id);
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return back()->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addDetail($id)
    {
        // authorize
        AcaraPCModel::authorize('C');

        // get user by branch id 
        $event = AcaraPCModel::getDataEventById($id);
        $rs_event_gs = AcaraPCModel::getDataGuestStar($id);
        $rs_event_ticket = AcaraPCModel::getDataTicket($id);
        $rs_event_rundown = AcaraPCModel::getDataRundown($id);
        // guest rundown ticket
        // dd($event);

        $data = [
            'event' => $event,
            'rs_event_gs' => $rs_event_gs,
            'rs_event_ticket' => $rs_event_ticket,
            'rs_event_rundown' => $rs_event_rundown,
        ];

        // view
        return view('admin.pj.register.acara.add-detail', $data);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        // authorize
        AcaraPCModel::authorize('C');

        // get user by branch id 
        $event = AcaraPCModel::getDataEventById($id);
        $rs_event_gs = AcaraPCModel::getDataGuestStar($id);
        $rs_event_ticket = AcaraPCModel::getDataTicket($id);
        $rs_event_rundown = AcaraPCModel::getDataRundown($id);
        // guest rundown ticket
        // dd($event);

        $data = [
            'event' => $event,
            'rs_event_gs' => $rs_event_gs,
            'rs_event_ticket' => $rs_event_ticket,
            'rs_event_rundown' => $rs_event_rundown,
        ];
        // view
        return view('admin.pj.register.acara.detail', $data );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // authorize
        AcaraPCModel::authorize('U');

        // get data 
        $branch_event = AcaraPCModel::getDataEventById($id);

        // check
        if(empty($branch_event)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/pj/register/acara');
        }
        
        // data
        $data = ['branch_event' => $branch_event];

        // view
        return view('admin.pj.register.acara.edit', $data );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editProcess(Request $request)
    {
        // authorize
        AcaraPCModel::authorize('U');

        // Validate & auto redirect when fail
        $rules = [
            'user_id' => 'required',
            'user_name' => 'required',
            'nik' => 'required|digits_between:6,11|numeric',
        ];
        $this->validate($request, $rules );

        // params
        $params =[
            'user_name' => $request->user_name,
            'nik'=> $request->nik,
            'no_telp'=> $request->no_telp,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (AcaraPCModel::update($request->user_id, $params)) {

            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/pj/register/acara');
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/pj/register/acara/edit/'.$request->user_id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteProcess($id)
    {
        // authorize
        AcaraPCModel::authorize('U');

        // get data
        $acara_rs = AcaraPCModel::getDataEventById($id);

        // if exist
        if(!empty($acara_rs)) {
            // process
            if(AcaraPCModel::deleteGuest($id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/pj/register/acara');
            }
            else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/pj/register/acara');
            }
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/pj/register/acara');
        }
    
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteGuestProcess($id)
    {
        // authorize
        AcaraPCModel::authorize('U');

        // get data
        $acara_rs = AcaraPCModel::getDataGuestEventById($id);

        // if exist
        if (!empty($acara_rs)) {
            // process
            if (AcaraPCModel::deleteGuest($id)) {
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteTicketProcess($id)
    {
        // authorize
        AcaraPCModel::authorize('U');

        // get data
        $acara_rs = AcaraPCModel::getDataTicketEventById($id);

        // if exist
        if (!empty($acara_rs)) {
            // process
            if (AcaraPCModel::deleteTicket($id)) {
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteRundownProcess($id)
    {
        // authorize
        AcaraPCModel::authorize('U');

        // get data
        $acara_rs = AcaraPCModel::getDataRundownEventById($id);

        // if exist
        if (!empty($acara_rs)) {
            // process
            if (AcaraPCModel::deleteRundown($id)) {
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

    /**
     * Search data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // authorize
        AcaraPCModel::authorize('R');

        // data request
        $search = $request->search;
        
        // new search or reset
        if($request->action == 'search') {
            // get data with pagination
            $rs_branch_event = AcaraPCModel::getDataEventSearch($search);
            // data
            $data = ['rs_branch_event' => $rs_branch_event, 'search'=>$search];
            // view
            return view('admin.pj.register.acara.index', $data );
        }
        else {
            return redirect('/admin/pj/register/acara');
        }
    }
}
