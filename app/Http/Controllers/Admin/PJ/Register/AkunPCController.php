<?php

namespace App\Http\Controllers\Admin\PJ\Register;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Admin\PJ\Register\AkunPCModel;



class AkunPCController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        AkunPCModel::authorize('R');

        // get data with pagination 
        // Penamaan Backend pake inggris Front pake indo
        $rs_branch_account = AkunPCModel::getDataWithPagination();
        // data
        $data = ['rs_branch_account' => $rs_branch_account];
        // view
        return view('admin.pj.register.akun-rs.index', $data );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        // authorize
        AkunPCModel::authorize('C');

        // view
        return view('admin.pj.register.akun-rs.add');
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
        AkunPCModel::authorize('C');

        $pass = Str::random(8);
        // params
        $user_id = AkunPCModel::makeMicrotimeID();
        $params =[
            'user_id' => $user_id,
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
            'user_active' => 1,
            'user_password' => Hash::make($pass),
            'user_img_path'=> '/img/user/',
            'user_img_name'=> 'default.png',
            'nik'=> $request->nik,
            'no_telp'=> $request->no_telp,
            'branch_id'=> Auth::user()->branch_id,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        AkunPCModel::insert($params);
        // insert role user
        $params =[
            'role_id' => $request->role_id,
            'user_id' => $user_id,
        ];
        AkunPCModel::insert_role_user($params);

        // params mail
        $data = [
            'title' => 'Pemberitahuan akun baru',
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
            'user_password' => $pass,
            'user_role'=> AkunPCModel::getByRole($request->role_id)->position,
            'login_url'=> env('APP_URL').'/auth/login',
            'email_type'=> 'new-account'
        ];
        // send mail
        if(parent::sendMail($data)) {
            // flash message
            session()->flash('success', 'Akun berhasil ditambahkan.');
            return redirect('/admin/pj/register/akun/');
        }
        
        else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/pj/register/akun/add')->withInput();
        }
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
        AkunPCModel::authorize('R');

        // get data with pagination
        $branch_account = AkunPCModel::getDataById($id);

        // check
        if(empty($branch_account)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/pj/register/akun');
        }

        // data
        $data = ['ch' => $branch_account];

        // view
        return view('admin.pj.register.akun-rs.detail', $data );
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
        AkunPCModel::authorize('U');

        // get data 
        $branch_account = AkunPCModel::getDataById($id);

        // check
        if(empty($branch_account)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/pj/register/akun');
        }
        
        // data
        $data = ['branch_account' => $branch_account];

        // view
        return view('admin.pj.register.akun-rs.edit', $data );
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
        AkunPCModel::authorize('U');

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
        if (AkunPCModel::update($request->user_id, $params)) {

            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/pj/register/akun');
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/pj/register/akun/edit/'.$request->user_id);
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
        AkunPCModel::authorize('U');

        $params =[
            'user_id' => $id,
            'user_active' => '0',
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];
        // get data
        $akun_rs = AkunPCModel::getDataById($id);

        // if exist
        if(!empty($akun_rs)) {
            // process
            if(AkunPCModel::update($id,$params)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/pj/register/akun');
            }
            else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/pj/register/akun');
            }
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/pj/register/akun');
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
        AkunPCModel::authorize('R');

        // data request
        $search = $request->search;
        
        // new search or reset
        if($request->action == 'search') {
            // get data with pagination
            $rs_branch_account = AkunPCModel::getDataSearch($search);
            // data
            $data = ['rs_branch_account' => $rs_branch_account, 'search'=>$search];
            // view
            return view('admin.pj.register.akun-rs.index', $data );
        }
        else {
            return redirect('/admin/pj/register/akun');
        }
    }
}
