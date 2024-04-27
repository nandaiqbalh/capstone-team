<?php

namespace App\Http\Controllers\Superadmin\Settings;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Superadmin\Settings\Accounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Imports\UsersImport;
use Excel;
use Illuminate\Support\Facades\Validator;

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


        // get data with pagination
        $rs_accounts = Accounts::getAllPaginate();
        // data
        $data = [
            'rs_accounts'   => $rs_accounts,
        ];

        // return
        return view('tim_capstone.settings.accounts.index', $data);
    }

    /**
     * Search data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {


        $user_name = $request->user_name;

        $rs_accounts = Accounts::getAllSearch($user_name);

        // data request
        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            // data
            $data = [
                'rs_accounts'   => $rs_accounts,
                'user_name'     => $user_name,

            ];
            // view
            return view('tim_capstone.settings.accounts.index', $data);
        } else {
            return redirect('/admin/settings/accounts');
        }
    }

    public function import(Request $request)
    {
        try {
            // Validasi file
            $validator = Validator::make($request->all(), [
                'user_file' => 'required|file|mimes:xls,xlsx'
            ]);

            // Cek apakah validasi gagal
            if ($validator->fails()) {
                // Flash message untuk file tidak valid
                session()->flash('danger', 'File harus berupa file Excel (xls, xlsx).');
                return redirect('/admin/settings/accounts/add');
            }

            // Inisialisasi array untuk menyimpan failed rows
            $failedRows = [];

            // Import data
            $import = Excel::import(new UsersImport($failedRows), $request->file('user_file'));

            // Check if import was successful
            if ($import) {
                if (!empty($failedRows)) {
                    return redirect('/admin/settings/accounts')->withInput()->with('failedRows', $failedRows);

                } else {
                    session()->flash('success', 'Data berhasil disimpan.');
                    return redirect('/admin/settings/accounts');
                }

            } else {
                // Flash message untuk import gagal
                session()->flash('danger', 'Data gagal disimpan.');

                // Pass failed rows data to the view
                return redirect('/admin/settings/accounts/add')->withInput()->with('failedRows', $failedRows);
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // Tangani pengecualian ValidationException
            $failures = $e->failures();

            $failedRows = [];
            foreach ($failures as $failure) {
                // Menambahkan username dari baris yang gagal ke array failedRows
                $failedRows[] = $failure->values()['user_name']; // Atur kunci kolom yang sesuai
            }

            // Flash message untuk import gagal
            session()->flash('danger', 'Data gagal diimpor. Pastikan semua baris data valid.');

            // Redirect ke halaman tambah dengan informasi baris yang gagal
            return redirect('/admin/settings/accounts/add')->withInput()->with('failedRows', $failedRows);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {

        // get data
        $rs_role = Accounts::getRole();
        // generate password
        $password = Str::random(7) . mt_rand(1, 9);

        $data = [
            'rs_role' => $rs_role,
            'password' => $password,
        ];

        // return
        return view('tim_capstone.settings.accounts.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addProcess(Request $request)
    {
        // Validate & auto redirect when fail
        $rules = [
            'user_name' => 'required',
            'user_password' => ['required', 'min:8'],
            'user_active' => 'required',
            'role_id' => 'required',
            'jenis_kelamin' => 'required',
            'nomor_induk' => 'required|unique:app_user,nomor_induk',
        ];

        // Check if role_id is '03'
        if ($request->role_id === '03') {
            $rules['angkatan'] = 'required';
        } else {
            $rules['angkatan'] = 'nullable';
        }

        $this->validate($request, $rules);

        // Check if role_id is '02' or '04' and angkatan is not allowed
        if (in_array($request->role_id, ['02', '04']) && $request->filled('angkatan')) {
            // Flash message
            session()->flash('danger', 'Role tersebut tidak boleh memiliki angkatan.');
            return redirect('/admin/settings/accounts/add')->withInput();
        }

        // Params
        $user_id = Accounts::makeMicrotimeID();
        $params = [
            'user_id' => $user_id,
            'role_id' => $request->role_id,
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
            'user_password' => Hash::make($request->user_password),
            'user_active' => $request->user_active,
            'nomor_induk' => $request->nomor_induk,
            'no_telp' => $request->no_telp,
            'created_by' => Auth::user()->user_id,
            'created_date' => now(),
        ];

        // Process
        if (Accounts::insert($params)) {
            // Flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/settings/accounts');
        } else {
            // Flash message
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
            return view('tim_capstone.settings.accounts.edit', $data);
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
        // Validate & auto redirect when fail
        $rules = [
            'user_id' => 'required',
            'user_name' => 'required',
            'user_active' => 'required',
            'role_id' => 'required',
        ];

        // Check if role_id is '03'
        if ($request->role_id === '03') {
            $rules['angkatan'] = 'required';
        } else {
            $rules['angkatan'] = 'nullable';
        }

        // Add validation rule for nomor_induk if it's changed
        $account = Accounts::getById($request->user_id);
        if ($request->nomor_induk != $account->nomor_induk) {
            $rules['nomor_induk'] = 'required|unique:app_user,nomor_induk';
        } else {
            $rules['nomor_induk'] = 'required';
        }

        // Check if role_id is '02' or '04' and angkatan is not allowed
        if (in_array($request->role_id, ['02', '04']) && $request->filled('angkatan')) {
            // Flash message
            session()->flash('danger', 'Role tersebut tidak boleh memiliki angkatan.');
            return redirect('/admin/settings/accounts/edit/' . $request->user_id)->withInput();
        }

        $this->validate($request, $rules);

        // Params
        $params = [
            'user_name' => $request->user_name,
            'user_active' => $request->user_active,
            'nomor_induk' => $request->nomor_induk,
            'no_telp' => $request->no_telp,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => now(),
        ];

        // Process
        if (Accounts::update($request->user_id, $params)) {
            // Check if old role_id is different from new role_id
            if ($account->role_id != $request->role_id) {
                // Update new role
                $params = [
                    'role_id' => $request->role_id,
                ];
                Accounts::update($request->user_id, $params);
            }

            // Flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/settings/accounts');
        } else {
            // Flash message
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
            return view('tim_capstone.settings.accounts.edit-password', $data);
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
