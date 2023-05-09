<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Settings\Account;
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
        $data = ['account'=>$account];
    
        //view
        return view('admin.settings.account.edit', $data);
    }

   /**
     * Crop Image.
     *
     * @return \Illuminate\Http\Response
     */
    public function ImgCrop(Request $request)
    {
        // return $request;
        $path = public_path($this->upload_path);
        $file = $request->file('user_img');
        $new_image_name = Str::slug(Auth::user()->user_name,'-').'-'.uniqid().'.jpg';
        
        // unlink image
        
        $old_img = public_path($this->upload_path).Auth::user()->user_img_name;
        if(file_exists($old_img) && Auth::user()->user_img_name != 'default.png') {
            unlink($old_img);
        }

        $upload = $file->move($path, $new_image_name);
        if($upload){
            $params =[
                'user_img_path' => $this->upload_path,
                'user_img_name' => $new_image_name,
                'modified_by'   => Auth::user()->user_id,
                'modified_date'  => date('Y-m-d H:i:s')
            ];
            Account::update(Auth::user()->user_id, $params);
            return response()->json(['status'=>1, 'msg'=>'Foto berhasil diunggah.', 'name'=>$new_image_name]);
        }else{
            return response()->json(['status'=>0, 'msg'=>'Upload foto gagal']);
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
        $this->validate($request, $rules );

        // $new_file_name = $request->old_user_img_name;

        // // cek file
        // if($request->hasFile('user_img')) {
            
        //     $file = $request->file('user_img');
        //     // namafile
        //     $file_extention = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        //     $new_file_name = Str::slug($request->user_name,'-').'-'.uniqid().'.'.$file_extention;

        //     // upload path
        //     $upload_path = $this->upload_path;

        //     // upload process
        //     if(!$file->move(public_path($upload_path), $new_file_name)) {
        //         // flash message
        //         session()->flash('danger', 'File gagal di upload.');
        //         return redirect('/admin/settings/account');
        //     }

        //     $old_img = public_path($this->upload_path).$request->old_user_img_name;
        //     if(file_exists($old_img) && $request->old_user_img_name != 'default.png') {
        //         unlink($old_img);
        //     }
        // }

        // params
        $params =[
            'user_name' => $request->user_name,
            // 'user_img_path' => $this->upload_path,
            // 'user_img_name' => $new_file_name,
            // 'nik' => $request->id_pengguna,
            'no_telp' => $request->no_telp,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (Account::update($request->user_id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/settings/account');
        }
        else {
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
        $this->validate($request, $rules );

        // cek current password
        if(!Hash::check($request->current_password, Auth::user()->user_password)) {
            // flash message
            session()->flash('danger', 'Password saat ini salah.');
            return redirect('/admin/settings/account');
        }

        // bandingkan password baru
        if( $request->new_password != $request->repeat_new_password) {
            // flash message
            session()->flash('danger', 'Password baru tidak sesuai.');
            return redirect('/admin/settings/account');
        }

        // params
        $params =[
            'user_password' => Hash::make($request->new_password),
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (Account::update($request->user_id, $params)) {
            // flash message
            session()->flash('success', 'Password berhasil disimpan.');
            return redirect('/logout');
        }
        else {
            // flash message
            session()->flash('danger', 'Password gagal disimpan.');
            return redirect('/admin/settings/account');
        }
    }


}