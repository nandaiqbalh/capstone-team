<?php

namespace App\Http\Controllers\Superadmin\Settings;

use App\Http\Controllers\TimCapstone\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Superadmin\Settings\Smtp;

class SmtpController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        Smtp::authorize('U');

        // get data
        $smtp = Smtp::getById('01');

        // data
        $data = ['smtp'=>$smtp];

        //view
        return view('admin.settings.smtp.edit', $data);
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
        Smtp::authorize('U');

        // Validate & auto redirect when fail
        $rules = [
            'email_id' => 'required',
            'email_name' => 'required',
            'email_address' => 'required',
            'smtp_host' => 'required',
            'smtp_port' => 'required',
            'smtp_username' => 'required',
            'smtp_password' => 'required',
            'use_smtp' => 'required',
            'use_authorization' => 'required',

        ];
        $this->validate($request, $rules );

        // params
        $params =[
            'email_name' => $request->email_name,
            'email_address' => $request->email_address,
            'smtp_host' => $request->smtp_host,
            'smtp_port' => $request->smtp_port,
            'smtp_username' => $request->smtp_username,
            'smtp_password' => $request->smtp_password,
            'use_smtp' => $request->use_smtp,
            'use_authorization' => $request->use_authorization,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (Smtp::update($request->email_id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/settings/smtp');
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/settings/smtp');
        }
    }


}
