<?php

namespace App\Http\Controllers\Superadmin\Settings;

use App\Http\Controllers\TimCapstone\BaseController;
use Illuminate\Http\Request;
use App\Models\Superadmin\Settings\RestApiModel;

class RestApiController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        RestApiModel::authorize('R');

        $rs_user_api_active = RestApiModel::getUserActiveApi();
        $data = [
            'rs_user_api_active' => $rs_user_api_active
        ];
        return view('tim_capstone.settings.rest-api.index', $data);
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
        RestApiModel::authorize('R');

        // data request
        $user_name = $request->user_name;
        // new search or reset
        if($request->action == 'search') {
            // get data with pagination
            $rs_user_api_active = RestApiModel::getUserActiveApiSearch($user_name);
            // data
            $data = ['rs_user_api_active' => $rs_user_api_active, 'user_name'=>$user_name];
            // view
            return view('tim_capstone.settings.rest-api.index', $data );
        }
        else {
            return redirect('/admin/settings/rest-api');
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
        RestApiModel::authorize('D');

        // process
        if(RestApiModel::delete($id)) {
            // flash message
            session()->flash('success', 'Data berhasil dihapus.');
            return redirect('/admin/settings/rest-api');
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal dihapus.');
            return redirect('/admin/settings/rest-api');
        }
    }
}
