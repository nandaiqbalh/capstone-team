<?php

namespace App\Http\Controllers\Superadmin\Settings;

use App\Http\Controllers\TimCapstone\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Superadmin\Settings\Menu;

class MenuController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        Menu::authorize('R');

        // get data with pagination
        $rs_menu = Menu::getAllPaginate();
        // data
        $data = ['rs_menu' => $rs_menu];
        // view
        return view('admin.settings.menu.index', $data );
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
        Menu::authorize('R');

        // data request
        $menu_name = $request->menu_name;
        // new search or reset
        if($request->action == 'search') {
            // get data with pagination
            $rs_menu = Menu::getAllSearch($menu_name);
            // data
            $data = ['rs_menu' => $rs_menu, 'menu_name'=>$menu_name];
            // view
            return view('admin.settings.menu.index', $data );
        }
        else {
            return redirect('/admin/settings/menu');
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
        Menu::authorize('C');

        // get data
        $rs_menu = Menu::getAll();

        // data
        $data = [
            'rs_menu' => $rs_menu,
        ];
        //view
        return view('admin.settings.menu.add', $data);
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
        Menu::authorize('C');

        // Validate & auto redirect when fail
        $rules = [
            'parent_menu_id' => 'required',
            'menu_name' => 'required',
            'menu_description' => 'required',
            'menu_url' => 'required',
            'menu_sort' => 'required',
            'menu_group' => 'required',
            'menu_active' => 'required',
            'menu_display' => 'required',
        ];
        $this->validate($request, $rules );

        // cek if new menu
        if($request->parent_menu_id == 'parent') {
            $parent_menu_id = NULL;
        }
        else {
            $parent_menu_id = $request->parent_menu_id;
        }

        $id = Menu::makeShortId();
        $menu_id = Menu::makeShortMenuId();

        // params
        $params =[
            'id' => $id,
            'menu_id' => $menu_id,
            'role_id' => '01',
            'parent_menu_id' => $parent_menu_id,
            'menu_name' => $request->menu_name,
            'menu_description' => $request->menu_description,
            'menu_url' => $request->menu_url,
            'menu_sort' => $request->menu_sort,
            'menu_group' => $request->menu_group,
            'menu_icon' => $request->menu_icon,
            'menu_active' => $request->menu_active,
            'menu_display' => $request->menu_display,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (Menu::insert($params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/settings/menu');
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/settings/menu/add');
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
        Menu::authorize('U');

        // get data
        $menu = Menu::getById($id);
        $rs_menu = Menu::getAll();

        // data
        $data = [
            'menu'  => $menu,
            'rs_menu' => $rs_menu,
        ];
        //view
        return view('admin.settings.menu.edit', $data);
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
        Menu::authorize('U');

        // Validate & auto redirect when fail
        $rules = [
            'menu_id' => 'required',
            'parent_menu_id' => 'required',
            'menu_name' => 'required',
            'menu_description' => 'required',
            'menu_url' => 'required',
            'menu_sort' => 'required',
            'menu_group' => 'required',
            'menu_active' => 'required',
            'menu_display' => 'required',
        ];
        $this->validate($request, $rules );

        // cek if new menu
        if($request->parent_menu_id == 'parent') {
            $parent_menu_id = NULL;
        }
        else {
            $parent_menu_id = $request->parent_menu_id;
        }

        $params =[
            'parent_menu_id' => $parent_menu_id,
            'menu_name' => $request->menu_name,
            'menu_description' => $request->menu_description,
            'menu_url' => $request->menu_url,
            'menu_sort' => $request->menu_sort,
            'menu_group' => $request->menu_group,
            'menu_icon' => $request->menu_icon,
            'menu_active' => $request->menu_active,
            'menu_display' => $request->menu_display,
            'created_by' => Auth::user()->user_id,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (Menu::update($request->menu_id,$params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/settings/menu');
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/settings/menu/edit/'.$request->menu_id);
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
        Menu::authorize('D');

        // get data
        $menu = Menu::getById($id);

        // if exist
        if(!empty($menu)) {
            // cek sub menu
            if(Menu::cekSubMenu($id)){
                // process
                if(Menu::delete($id)) {
                    // flash message
                    session()->flash('success', 'Data berhasil dihapus.');
                    return redirect('/admin/settings/menu');
                }
                else {
                    // flash message
                    session()->flash('danger', 'Data gagal dihapus.');
                    return redirect('/admin/settings/menu');
                }
            }
            else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus,silahkan hapus sub-menu terlebih dahulu.');
                return redirect('/admin/settings/menu');
            }
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/settings/menu');
        }
    }

    /**
     * Show the form for add the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function roleMenu($id)
    {
        // authorize
        Menu::authorize('C');

        // get data
        $rs_role = Menu::getRole();
        $rs_role_menu = Menu::getRoleMenu($id);
        $menu = Menu::getMenuById($id);

        // data
        $data = [
            'menu'  => $menu,
            'rs_role' => $rs_role,
            'rs_role_menu' => $rs_role_menu->toArray()
        ];

        //view
        return view('admin.settings.menu.rolemenu', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function roleMenuProcess(Request $request)
    {
        // get data
        $menu = Menu::getById($request->menu_id);
        $rs_menu = Menu::getAll();

        // authorize
        Menu::authorize('C');

        // cek if new menu
        $parent_menu_id = $request->parent_menu_id == 'parent' ? NULL : $request->parent_menu_id;

        // data
        $role_id = $request->role_id;

        // delete role menu
        Menu::delete($request->menu_id);

        if (!empty($role_id)) {
            // looping insert
            foreach ($role_id as $roleId) {
                // params
                $params = [
                    'role_id' => $roleId,
                    'menu_id' => $menu->menu_id,
                    'parent_menu_id' => $parent_menu_id,
                    'menu_name' => $menu->menu_name,
                    'menu_description' => $menu->menu_description,
                    'menu_url' => $menu->menu_url,
                    'menu_sort' => $menu->menu_sort,
                    'menu_group' => $menu->menu_group,
                    'menu_icon' => $menu->menu_icon,
                    'menu_active' => $menu->menu_active,
                    'menu_display' => $menu->menu_display,
                    'created_by' => Auth::user()->user_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'modified_by' => Auth::user()->user_id,
                    'modified_date' => date('Y-m-d H:i:s'),
                ];

                // upsert
                Menu::insert($params);
            }
        }

        // flash message
        session()->flash('success', 'Data berhasil disimpan.');
        return redirect('/admin/settings/menu');
    }

}
