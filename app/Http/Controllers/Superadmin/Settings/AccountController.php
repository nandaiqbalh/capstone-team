<?php

namespace App\Http\Controllers\Superadmin\Settings;

use App\Http\Controllers\TimCapstone\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Superadmin\Settings\Account;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountController extends BaseController
{
    // path store in database
    protected $upload_path = '/img/user/';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        Account::authorize('U');

        // get data
        $account = Account::getById(Auth::user()->user_id);

        // data
        $data = ['account' => $account];

        //view
        return view('tim_capstone.settings.account.edit', $data);
    }

    /**
     * Crop Image.
     *
     * @return \Illuminate\Http\Response
     */
    public function ImgCrop(Request $request)
    {
        $path = public_path($this->upload_path);
        $file = $request->file('user_img');

        // Periksa apakah pengguna sudah memiliki foto sebelumnya
        $account = Account::getById(Auth::user()->user_id);

        if ($account->user_img_name && $account->user_img_name != 'default.png') {
            // Pengguna telah mengunggah foto sebelumnya, lakukan update
            $new_image_name = Str::slug(Auth::user()->user_name, '-') . '-' . uniqid() . '.jpg';

            // Hapus foto lama
            $old_img = public_path($this->upload_path) . $account->user_img_name;
            if (file_exists($old_img)) {
                unlink($old_img);
            }
        } else {
            // Pengguna belum pernah mengunggah foto, buat nama file baru
            $new_image_name = Str::slug(Auth::user()->user_name, '-') . '-' . uniqid() . '.jpg';
        }

        // Pindahkan file baru
        $upload = $file->move($path, $new_image_name);

        if ($upload) {
            $params = [
                'user_img_path' => $this->upload_path,
                'user_img_name' => $new_image_name,
                'modified_by'   => Auth::user()->user_id,
                'modified_date' => date('Y-m-d H:i:s')
            ];

            // Perbarui informasi foto pengguna
            Account::update(Auth::user()->user_id, $params);

            return response()->json(['status' => 1, 'msg' => 'Foto berhasil diunggah.', 'name' => $new_image_name]);
        } else {
            return response()->json(['status' => 0, 'msg' => 'Upload foto gagal']);
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
        Account::authorize('U');

        // Validate & auto redirect when fail
        $rules = [
            'user_id' => 'required',
            // 'id_pengguna' => 'required|digits_between:6,11|numeric',
            'user_name' => 'required',
            'no_telp' => 'required|digits_between:10,13|numeric',
            'user_img' => 'image|mimes:jpeg,jpg,png|max:5120'

        ];
        $this->validate($request, $rules);



        // params
        $params = [
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
            'no_telp' => $request->no_telp,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (Account::update($request->user_id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/settings/account');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/settings/account');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPassword(Request $request)
    {
        // authorize
        Account::authorize('U');

        // Validate & auto redirect when fail
        $rules = [
            'user_id' => 'required',
            'current_password' => 'required|min:8|max:20',
            'new_password' => 'required|min:8|max:20',
            'repeat_new_password' => 'required|min:8|max:20',

        ];
        $this->validate($request, $rules);

        // cek current password
        if (!Hash::check($request->current_password, Auth::user()->user_password)) {
            // flash message
            session()->flash('danger', 'Password saat ini salah.');
            return redirect('/admin/settings/account');
        }

        // bandingkan password baru
        if ($request->new_password != $request->repeat_new_password) {
            // flash message
            session()->flash('danger', 'Password baru tidak sesuai.');
            return redirect('/admin/settings/account');
        }

        // params
        $params = [
            'user_password' => Hash::make($request->new_password),
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (Account::update($request->user_id, $params)) {
            // flash message
            session()->flash('success', 'Password berhasil disimpan.');
            return redirect('/logout');
        } else {
            // flash message
            session()->flash('danger', 'Password gagal disimpan.');
            return redirect('/admin/settings/account');
        }
    }
}
