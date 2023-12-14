<?php

namespace App\Http\Controllers\Superadmin\Settings;

use App\Http\Controllers\TimCapstone\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Superadmin\Settings\Role;

class RoleController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        Role::authorize('R');

        // get data with pagination
        $rs_role = Role::getAllPaginate();
        // data
        $data = ['rs_role' => $rs_role];
        // view
        return view('admin.settings.role.index', $data );
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
        Role::authorize('R');

        // data request
        $role_name = $request->role_name;
        // new search or reset
        if($request->action == 'search') {
            // get data with pagination
            $rs_role = Role::getAllSearch($role_name);
            // data
            $data = ['rs_role' => $rs_role, 'role_name'=>$role_name];
            // view
            return view('admin.settings.role.index', $data );
        }
        else {
            return redirect('/admin/settings/role');
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
        Role::authorize('C');

        //view
        return view('admin.settings.role.add');
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
        Role::authorize('C');

        // Validate & auto redirect when fail
        $rules = [
            'role_name' => 'required|max:100',
            'role_description' => 'required',
            'role_permission' => 'required|min:4'
        ];
        $this->validate($request, $rules );

        // params
        $params =[
            'role_id' => Role::makeShortId(),
            'role_name' => $request->role_name,
            'role_description' => $request->role_description,
            'role_permission' => $request->role_permission,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (Role::insert($params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/settings/role');
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/settings/role/add');
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
        Role::authorize('U');

        // validate
        if($id == '01') {
            // flash message
            session()->flash('danger', 'Data tidak dapat diubah.');
            return redirect('/admin/settings/role');
        }

        // get data
        $role = Role::getById($id);

        // if exist
        if(!empty($role)) {
            //view
            return view('admin.settings.role.edit', ['role'=>$role]);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/settings/role');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editProcess(Request $request)
    {
        // authorize
        Role::authorize('U');

        // Validate & auto redirect when fail
        $rules = [
            'role_name' => 'required|max:100',
            'role_description' => 'required',
            'role_permission' => 'required|min:4'
        ];
        $this->validate($request, $rules );

        // params
        $params =[
            'role_name' => $request->role_name,
            'role_description' => $request->role_description,
            'role_permission' => $request->role_permission,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (Role::update($request->role_id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/settings/role');
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/settings/role/edit/'.$request->role_id);
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
        Role::authorize('D');

        // validate
        if($id == '01') {
            // flash message
            session()->flash('danger', 'Data tidak dapat dihapus.');
            return redirect('/admin/settings/role');
        }

        // get data
        $role = Role::getById($id);

        // if exist
        if(!empty($role)) {
            $total_user = Role::getTotalUserByRoleId($id);
            if(intval($total_user) > 0) {
                 // flash message
                 session()->flash('danger', 'Data gagal dihapus.Role '.$role->role_name.' masih memiliki '.$total_user.' user!');
                 return redirect('/admin/settings/role');
            }

            // process
            if(Role::delete($id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/settings/role');
            }
            else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/settings/role');
            }
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/settings/role');
        }
    }
}
