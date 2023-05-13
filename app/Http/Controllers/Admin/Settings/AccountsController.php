<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Admin\Settings\Accounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

use Illuminate\Support\Facades\Mail;
// use App\Mail\SendMail;

class AccountsController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        Accounts::authorize('R');

        // get data with pagination
        $rs_accounts = Accounts::getAllPaginate();
        // data
        $data = [
            'rs_accounts'   => $rs_accounts,
        ];

        // return
        return view('admin.settings.accounts.index', $data);
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
        Accounts::authorize('R');

        // data request
        $user_name = $request->user_name;
        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_accounts = Accounts::getAllSearch($user_name, $request->branch_id);
            // data
            $data = [
                'rs_accounts'   => $rs_accounts,
                'user_name'     => $user_name,
                'branch_id'     => $request->branch_id,
                'rs_branch'     => Accounts::getBranch()
            ];
            // view
            return view('admin.settings.accounts.index', $data);
        } else {
            return redirect('/admin/settings/accounts');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        // authorize
        Accounts::authorize('C');

        // get data
        $rs_role = Accounts::getRole();
        // generate password
        $password = Str::random(7) . mt_rand(1, 9);

        $data = [
            'rs_role' => $rs_role,
            'password' => $password,
        ];

        // return
        return view('admin.settings.accounts.add', $data);
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
        Accounts::authorize('C');

        // Validate & auto redirect when fail
        $rules = [
            'user_name' => 'required',
            'user_email' => 'required|email',
            'user_password' => ['required', Password::min(8)],
            'user_active' => 'required',
            'role_id' => 'required',
            'nomor_induk' => 'required|digits_between:6,11|numeric',
        ];
        $this->validate($request, $rules);

        // cek email
        $email = Accounts::getByEmail($request->user_email);
        if (!empty($email)) {
            // flash message
            session()->flash('danger', 'Email sudah digunakan.');
            return redirect('/admin/settings/accounts/add')->withInput();
        }

        // params
        $user_id = Accounts::makeMicrotimeID();
        $params = [
            'user_id' => $user_id,
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
            'user_password' => Hash::make($request->user_password),
            'user_active' => $request->user_active,
            'user_img_path' => '/img/user/',
            'user_img_name' => 'default.png',
            'nomor_induk' => $request->nomor_induk,
            'no_telp' => $request->no_telp,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (Accounts::insert($params)) {
            // insert role user
            $params = [
                'role_id' => $request->role_id,
                'user_id' => $user_id,
            ];
            Accounts::insert_role_user($params);

            // send mail
            // $this->sendMail($user_id, $request->user_password);

            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/settings/accounts');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/settings/accounts/add')->withInput();
        }
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
        Accounts::authorize('U');

        // get data
        $account = Accounts::getById($id);
        $rs_role = Accounts::getRole();

        $data = [
            'account' => $account,
            'rs_role' => $rs_role,
        ];

        // if exist
        if (!empty($account)) {
            //view
            return view('admin.settings.accounts.edit', $data);
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/settings/accounts');
        }
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
        Accounts::authorize('U');

        // Validate & auto redirect when fail
        $rules = [
            'user_id' => 'required',
            'user_name' => 'required',
            'user_active' => 'required',
            'role_id' => 'required',
            'nomor_induk' => 'required|digits_between:6,11|numeric',
        ];
        $this->validate($request, $rules);

        // params
        $params = [
            'user_name' => $request->user_name,
            'user_active' => $request->user_active,
            'nomor_induk' => $request->nomor_induk,
            'no_telp' => $request->no_telp,
            'branch_id' => $request->branch_id,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (Accounts::update($request->user_id, $params)) {

            // get data by id
            $account = Accounts::getById($request->user_id);
            // cek apakah role_id lama dgn role_id di request baru sama/tidak
            if ($account->role_id != $request->role_id) {
                // update role baru
                $params = [
                    'role_id' => $request->role_id,
                ];
                Accounts::update_role_user($request->user_id, $params);
            }

            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/settings/accounts');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/settings/accounts/edit/' . $request->user_id);
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
        Accounts::authorize('D');

        // get data
        $account = Accounts::getById($id);

        // if exist
        if (!empty($account)) {
            // delete image
            $img_path = public_path($account->user_img_path) . $account->user_img_name;
            if (!empty($account->user_img_name) && file_exists($img_path)) {
                unlink($img_path);
            }

            // process
            if (Accounts::delete($id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/settings/accounts');
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/settings/accounts');
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/settings/accounts');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPassword($id)
    {
        // authorize
        Accounts::authorize('U');

        // get data
        $account = Accounts::getById($id);
        // cek
        if ($account->role_id != '01') {
            redirect()->back();
        }

        $data = [
            'account' => $account,
        ];

        // if exist
        if (!empty($account)) {
            //view
            return view('admin.settings.accounts.edit-password', $data);
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/settings/accounts');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPasswordProcess(Request $request)
    {
        // authorize
        Accounts::authorize('U');

        // Validate & auto redirect when fail
        $rules = [
            'user_id' => 'required',
            'new_password' => 'required|min:8|max:20',
            'repeat_new_password' => 'required|min:8|max:20',

        ];
        $this->validate($request, $rules);

        // bandingkan password baru
        if ($request->new_password != $request->repeat_new_password) {
            // flash message
            session()->flash('danger', 'Password baru tidak sesuai.');
            return redirect('/admin/settings/accounts/edit_password/' . $request->user_id)->withInput();
        }

        // params
        $params = [
            'user_password' => Hash::make($request->new_password),
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (Accounts::update($request->user_id, $params)) {
            // flash message
            session()->flash('success', 'Password berhasil disimpan.');
            return redirect('/admin/settings/accounts');
        } else {
            // flash message
            session()->flash('danger', 'Password gagal disimpan.');
            return redirect('/admin/settings/accounts/edit_password/' . $request->user_id)->withInput();
        }
    }
}
