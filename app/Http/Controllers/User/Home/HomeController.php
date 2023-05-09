<?php

namespace App\Http\Controllers\User\Home;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\BaseController;
use App\Models\User\Home\HomeModel;



class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // get data with pagination 
        $rs_branch_event = HomeModel::getDataEventWithPaginationIndex();
        // data
        $data = ['rs_branch_event' => $rs_branch_event];
        // dd($data);
        // view
        return view('user.client.index', $data );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function event()
    {

        // get data with pagination 
        $rs_branch_event = HomeModel::getDataEventWithPagination();
        // data
        $data = ['rs_branch_event' => $rs_branch_event];
        // dd($data);
        // view
        return view('user.client.acara', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function eventDetail($id)
    {

        // get data with pagination 
        $branch_event = HomeModel::getDataEventById($id);
        $rs_event_gs = HomeModel::getDataGuestStar($id);
        $rs_event_ticket = HomeModel::getDataTicket($id);
        $rs_event_rundown = HomeModel::getDataRundown($id);
        // guest rundown ticket
        // dd($event);

        $data = [
            'branch_event' => $branch_event,
            'rs_event_gs' => $rs_event_gs,
            'rs_event_ticket' => $rs_event_ticket,
            'rs_event_rundown' => $rs_event_rundown,
        ];
        dd($branch_event);
        // view
        return view('user.client.detail-acara', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function eventTicketBuy($ticket_id)
    {

        // get data with pagination 
        $branch_event = HomeModel::getDataTicketEventById($ticket_id);
        $event_ticket = HomeModel::getDataTicketDetail($ticket_id);

        $data = [
            'branch_event' => $branch_event,
            'event_ticket' => $event_ticket,
        ];
        // view
        return view('user.client.ticket', $data);
    }

    public function pesanTicket(Request $request)
    {


        $invoice_code = rand(1, 1000);
        $transaction_id = explode(" ",microtime())[1];
        $params = [
            'event_id' => $request->event_id,
            'ticket_id' => $request->ticket_id,
            'transaction_code' => $transaction_id,
            'name' => $request->name,
            'email' => $request->email,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tempat_lahir' => $request->tempat_lahir,
            'wa' => $request->wa,
            'alamat' => $request->alamat,
            'gender' => $request->gender,
            'pekerjaan' => $request->pekerjaan,
            'qty' => $request->qty,
            'confirmed_status' => "belum dibayar",
            'created_by'   => "pembeli",
            'created_date'  => date('Y-m-d H:i:s'),
            'invoice_code' => $invoice_code,
            'harga' => $request->harga * $request->qty + $invoice_code,
        ];
        // dd($params);
        // process
        // HomeModel::insert($params);

        // params mail
        // $data = [
        //     'title' => 'Pemberitahuan akun baru',
        //     'user_name' => $request->user_name,
        //     'user_email' => $request->user_email,
        //     'user_password' => $pass,
        //     'user_role' => HomeModel::getByRole($request->role_id)->position,
        //     'login_url' => env('APP_URL') . '/auth/login',
        //     'email_type' => 'new-account'
        // ];
        // parent::sendMail($data);
        // send mail
        if (HomeModel::insertTicketSell($params)) {
            // flash message
            // get data with pagination 
            $branch_event = HomeModel::getDataTicketEventById($request->ticket_id);
            $event_ticket = HomeModel::getDataTicketDetail($request->ticket_id);

            $data = [
                'branch_event' => $branch_event,
                'event_ticket' => $event_ticket,
                'transaction_id' => $transaction_id,
                'name' => $request->name,
                'email' => $request->email,
                'qty' => $request->qty,
                'harga' => $request->harga * $request->qty + $invoice_code,
            ];

            // view
            dd($data);
            return view('user.client.konfirmasi-ticket', $data);
            
        } else {
            // flash message
            session()->flash('danger', 'Gagal Memesan Ticket.');
            return back();
        }
    }


















    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        // view
        return view('user.client.add');
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
        HomeModel::authorize('C');

        // Validate & auto redirect when fail
        $rules = [
            'user_name' => 'required',
            'user_email' => 'required|email',
            'role_id' => 'required',
            'nik' => 'required|digits_between:6,11|numeric',
        ];
        $this->validate($request, $rules );

        // cek email
        $role_check = HomeModel::getByRole($request->role_id);
        if(!empty($role_check)) {
             // flash message
             session()->flash('danger', 'Jabatan sudah terdaftar.');
             return redirect('/User/checker/register/client/add')->withInput();
        }
        $pass = Str::random(8);
        // params
        $user_id = HomeModel::makeMicrotimeID();
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
        HomeModel::insert($params);
        // insert role user
        $params =[
            'role_id' => $request->role_id,
            'user_id' => $user_id,
        ];
        HomeModel::insert_role_user($params);

        // params mail
        $data = [
            'title' => 'Pemberitahuan akun baru',
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
            'user_password' => $pass,
            'user_role'=> HomeModel::getByRole($request->role_id)->position,
            'login_url'=> env('APP_URL').'/auth/login',
            'email_type'=> 'new-account'
        ];
        // send mail
        if(parent::sendMail($data)) {
            // flash message
            session()->flash('success', 'Akun berhasil ditambahkan.');
            return redirect('/User/checker/register/client/');
        }
        
        else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/User/checker/register/client/add')->withInput();
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
        HomeModel::authorize('R');

        // get data with pagination
        $branch_account = HomeModel::getDataById($id);

        // check
        if(empty($branch_account)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/User/checker/register/client');
        }

        // data
        $data = ['ch' => $branch_account];

        // view
        return view('user.client.detail', $data );
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
        HomeModel::authorize('U');

        // get data 
        $branch_account = HomeModel::getDataById($id);

        // check
        if(empty($branch_account)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/User/checker/register/client');
        }
        
        // data
        $data = ['branch_account' => $branch_account];

        // view
        return view('user.client.edit', $data );
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
        HomeModel::authorize('U');

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
        if (HomeModel::update($request->user_id, $params)) {

            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/User/checker/register/client');
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/User/checker/register/client/edit/'.$request->user_id);
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
        HomeModel::authorize('U');

        $params =[
            'user_id' => $id,
            'user_active' => '0',
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];
        // get data
        $akun_rs = HomeModel::getDataById($id);

        // if exist
        if(!empty($akun_rs)) {
            // process
            if(HomeModel::update($id,$params)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/User/checker/register/client');
            }
            else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/User/checker/register/client');
            }
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/User/checker/register/client');
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
        HomeModel::authorize('R');

        // data request
        $search = $request->search;
        
        // new search or reset
        if($request->action == 'search') {
            // get data with pagination
            $rs_branch_account = HomeModel::getDataSearch($search);
            // data
            $data = ['rs_branch_account' => $rs_branch_account, 'search'=>$search];
            // view
            return view('user.client.index', $data );
        }
        else {
            return redirect('/User/checker/register/client');
        }
    }
}
