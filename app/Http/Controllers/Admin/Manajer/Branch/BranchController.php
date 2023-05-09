<?php

namespace App\Http\Controllers\Admin\Manajer\Branch;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Manajer\Branch\BranchModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class BranchController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        BranchModel::authorize('R');

        // get data with pagination
        $rs_branch = BranchModel::getAllPaginate();

        // data
        $data = [
            'rs_branch' => $rs_branch,
            'rs_user'=> []
        ];

        // get user account by branch
        foreach ($rs_branch as $key => $value) {
            // add to data
            $data['rs_user'] += [
                $value->id => BranchModel::getUserByBranchId($value->id)
            ];
        }

        // view
        return view('admin.manajer.branch.branch.index', $data );
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
        BranchModel::authorize('R');

        // data request
        $search_string= $request->search_string;
        // new search or reset
        if($request->action == 'search') {
            // get data with pagination
            $rs_branch = BranchModel::getAllSearch($search_string);
            // data
            $data = [
                'rs_branch' => $rs_branch, 
                'search_string'=>$search_string,
                'rs_user'=> []
            ];

            // get user account by branch
            foreach ($rs_branch as $key => $value) {
                // add to data
                $data['rs_user'] += [
                    $value->id => BranchModel::getUserByBranchId($value->id)
                ];
            }

            // view
            return view('admin.manajer.branch.branch.index', $data );
        }
        else {
            return redirect('/admin/manajer/cabang');
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
        BranchModel::authorize('C');

        $data = [
            'rs_region'=> BranchModel::getMasterRegion(),
            'rs_province'=> BranchModel::getMasterProvince()
        ];

        //view
        return view('admin.manajer.branch.branch.add', $data);
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
        BranchModel::authorize('C');
        // cek data
        $branch = BranchModel::getByName($request->name);
        if(!empty($branch)) {
            // flash message
            session()->flash('danger', 'Data gagal disimpan. '.$request->name.' sudah terdaftar!');
            return redirect()->back()->withInput();
        }
        // params
        $params =[
            'name'          => $request->name,
            'address'       => $request->address,
            'no_telp'       => $request->no_telp,
            'no_rekening'       => $request->no_rekening,
            'bank_rekening'       => $request->bank_rekening,
            'an_rekening'       => $request->an_rekening,
            'created_by'    => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (BranchModel::insert($params)) {

            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/manajer/cabang/add');
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect()->back()->withInput();
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
        BranchModel::authorize('U');

        // get data
        $branch = BranchModel::getById($id);
    
        // if exist
        if(!empty($branch)) {
            $data = [
                'branch'=>$branch,
                'rs_region'=> BranchModel::getMasterRegion(),
                'rs_province'=> BranchModel::getMasterProvince()
            ];
            //view
            return view('admin.manajer.branch.branch.edit', $data);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/manajer/cabang');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editProcess(Request $request)
    {
        // authorize
        BranchModel::authorize('U');

        
        // params
        $params =[
            'name'          => $request->name,
            'address'       => $request->address,
            'no_telp'       => $request->no_telp,
            'no_rekening'       => $request->no_rekening,
            'bank_rekening'       => $request->bank_rekening,
            'an_rekening'       => $request->an_rekening,
            'modified_by'    => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (BranchModel::update($request->id,$params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect()->back();
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        // authorize
        BranchModel::authorize('U');

        // get data
        $branch = BranchModel::getById($id);
        
        // if exist
        if(!empty($branch)) {
            $data = [
                'branch'=>$branch
            ];
            //view
            return view('admin.manajer.branch.branch.detail', $data);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/manajer/cabang');
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
        BranchModel::authorize('D');

        // get data
        $branch = BranchModel::getById($id);

        // if exist
        if(!empty($branch)) {

            $param = [
                'data_status'   => '0',
                'modified_by'    => Auth::user()->user_id,
                'modified_date'  => date('Y-m-d H:i:s')
            ];
            // process
            if(BranchModel::update($id, $param)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/manajer/cabang');
            }
            else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/manajer/cabang');
            }
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('admin/manajer/cabang');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ajaxCityByProvince($id)
    {
        // authorize
        BranchModel::authorize('R');

        // get data
        $province_code = BranchModel::getProvinceCodeById($id);
        $rs_city = BranchModel::getMasterCityByProvince($province_code);
    
        // response
        $response = [
            "status"=> true,
            "message"=> 'OK.',
            "data"=> [
                'rs_city'=> $rs_city
            ]
        ];
        return response()->json($response)->setStatusCode(200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addAkun($id)
    {
        // authorize
        BranchModel::authorize('U');

        // get data
        $branch = BranchModel::getById($id);
        
        // if exist
        if(!empty($branch)) {
            // get user
            $rs_user = BranchModel::getUserByBranchId($id);
            // get user checker
            $user_checker = $rs_user->where('role_id','02');
            // cek jumlah akun checker by branch
            if(count($user_checker) == 0){
                $data = [
                    'branch'=>$branch,
                ];
                //view
                return view('admin.manajer.branch.branch.add-akun', $data);
            }
            else {
                // flash message
                session()->flash('danger', 'Akun Checker di '.$branch->name.' sudah ada.');
                return redirect('/admin/manajer/cabang');
            }
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/manajer/cabang');
        }
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addAkunProcess(Request $request)
    {
        // authorize
        BranchModel::authorize('C');

        // Validate & auto redirect when fail
        $rules = [
            'user_name' => 'required',
            'user_email' => 'required|email',
            'no_telp' => 'required',
            'id_pengguna' => 'required|digits_between:6,15|numeric',
        ];
        $this->validate($request, $rules );

        // cek nik
        $nik = BranchModel::getUserByNik($request->id_pengguna);
        if(!empty($nik)) {
             // flash message
             session()->flash('danger', 'ID Pengguna sudah terdaftar.');
             return redirect()->back()->withInput();
        }

        // cek email
        $email = BranchModel::getUserByEmail($request->user_email);
        if(!empty($email)) {
             // flash message
             session()->flash('danger', 'Email sudah terdaftar.');
             return redirect()->back()->withInput();
        }

        // params
        $user_id = BranchModel::makeMicrotimeID();
        $password = Str::random(8);
        $params =[
            'user_id' => $user_id,
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
            'user_password' => Hash::make($password),
            'user_active' => '1',
            'user_img_path'=> '/img/user/',
            'user_img_name'=> 'default.png',
            'nik'=> $request->id_pengguna,
            'no_telp'=> $request->no_telp,
            'branch_id'=> $request->branch_id,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (BranchModel::insert_app_user($params)) {
            // insert role user
            $params =[
                'role_id' => '02',
                'user_id' => $user_id,
            ];

            BranchModel::insert_role_user($params);

            // params mail
            $data = [
                'title'=> 'Pemberitahuan Akun Baru',
                'user_name' => $request->user_name,
                'user_email' => $request->user_email,
                'user_password' => $password,
                'user_role'=> 'Checker',
                'login_url'=> env('APP_URL').'/login',
                'email_type'=> 'new-account'
            ];

            // send mail
            if(parent::sendMail($data)) {
                // flash message
                session()->flash('success', 'Akun berhasil dibuat.');
                return redirect('/admin/manajer/cabang');
            }
            else {
                // hapus akun
                BranchModel::delete_app_user($user_id);
                // flash message
                session()->flash('danger', 'Akun gagal dibuat. Email gagal terkirim!');
                return redirect()->back()->withInput();
            }

        }
        else {
            // flash message
            session()->flash('danger', 'Akun gagal dibuat!.');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editAkun($id)
    {
        // authorize
        BranchModel::authorize('U');

        // get data
        $user = BranchModel::getUserById($id);
        $branch = BranchModel::getById($user->branch_id);
    
        // if exist
        if(!empty($user)) {
            $data = [
                'user'=>$user,
                'branch'=> $branch
            ];
            //view
            return view('admin.manajer.branch.branch.edit-akun', $data);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/manajer/cabang');
        }
    }

     /**
     * change resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editAkunProcess(Request $request)
    {
        // authorize
        BranchModel::authorize('C');


        // cek nik
        if($request->old_id_pengguna != $request->id_pengguna) {
            $nik = BranchModel::getUserByNik($request->id_pengguna);
            if(!empty($nik)) {
                 // flash message
                 session()->flash('danger', 'ID Pengguna sudah terdaftar.');
                 return redirect()->back()->withInput();
            }
        }

        // cek email
        if($request->old_user_email != $request->user_email) {
            $email = BranchModel::getUserByEmail($request->user_email);
            if(!empty($email)) {
                 // flash message
                 session()->flash('danger', 'Email sudah terdaftar.');
                 return redirect()->back()->withInput();
            }
        }

        // params
        $params =[
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
            'nik'=> $request->id_pengguna,
            'no_telp'=> $request->no_telp,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (BranchModel::update_app_user($request->user_id, $params)) {
            // flash message
            session()->flash('success', 'Perubahan data berhasil disimpan.');
            return redirect('/admin/manajer/cabang/akun/edit'.'/'.$request->user_id);

        }
        else {
            // flash message
            session()->flash('danger', 'Perubahan data gagal disimpan.');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteAkunProcess($id)
    {
        // authorize
        BranchModel::authorize('D');

        // get data
        $user = BranchModel::getUserById($id);

        // if exist
        if(!empty($user)) {
            // process
            if(BranchModel::update_app_user($id, ['user_active'=> '0'])) {
                // flash message
                session()->flash('success', 'Akun Checker berhasil dihapus.');
                return redirect('/admin/manajer/cabang');
            }
            else {
                // flash message
                session()->flash('danger', 'Akun Checker gagal dihapus.');
                return redirect('/admin/manajer/cabang');
            }
        }
        else {
            // flash message
            session()->flash('danger', 'Akun Checker tidak ditemukan.');
            return redirect('admin/manajer/cabang');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function importProcess(Request $request)
    {
        // authorize
        BranchModel::authorize('C');

        // Validate & auto redirect when fail
        $rules = [
            'excel_file' => 'required|mimes:xlsx'
        ];
        $this->validate($request, $rules );
        
        // cek file
        if($request->hasFile('excel_file')) {
            // set reader excel .xlsx
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            // load file yang diupload
            $spreadsheet = $reader->load($request->file('excel_file'));
            // baca sheet aktif
            // array
            $sheet_data = $spreadsheet->getActiveSheet()->toArray();

            // -----------------------------------------------------------
            // validasi
            // ambil header
            $sheet_header = $sheet_data[0];
            
            // cek header sesuai format excel masing-masing impor data
            if(strtolower($sheet_header[0]) != 'no.' || strtolower($sheet_header[1]) != 'id rumah sakit' || strtolower($sheet_header[2]) != 'nama rumah sakit' || strtolower($sheet_header[3]) != 'kelas' || strtolower($sheet_header[4]) != 'regional' || strtolower($sheet_header[5]) != 'provinsi' || strtolower($sheet_header[6]) != 'kota/kabupaten' || strtolower($sheet_header[7]) != 'alamat' || strtolower($sheet_header[8]) != 'telepon') {
                // response
                $response = [
                    "status"=> false,
                    "message"=> 'Format tidak sesuai!'
                ];
                return response()->json($response)->setStatusCode(200);
            }

            // looping
            // data berhasil insert
            $data_true = [];
            // data gagal insert
            $data_false = [];
            foreach ($sheet_data as $key => $value) {
                // skip header
                if($key == 0) {
                    continue;
                }

                // cek jika null maka skip
                if(empty($value[1])) {
                    // skip
                    continue;
                }

                // cek di db sudah ada atau belum
                if(!empty(BranchModel::getByName('Hermina '.$value[2]))) {
                    // skip
                    continue;
                }

                // cek regional
                $regional = BranchModel::getRegionById($value[4]);
                if(empty($regional)) {
                    // skip
                    continue;
                }

                // cek nama provinsi
                $provinsi = BranchModel::getProvinceByName($value[5]);
                if(empty($provinsi)){
                    // skip
                    continue;
                }

                // cek nama kota/kabupaten
                $city = BranchModel::getCityByName($value[6]);
                if(empty($city)){
                    // skip
                    continue;
                }

                $data = [
                    'id'=> '',
                    'id_branch'=>$value[1],
                    'name'=> 'Hermina '.$value[2],
                    'class'=>$value[3],
                    'region_name'=>$regional->name,
                    'province_id'=>$provinsi->id,
                    'city_id'=>$city->id,
                    'address'=>$value[7],
                    'no_telp'=> $value[8],
                    'created_by'   => Auth::user()->user_id,
                    'created_date'  => date('Y-m-d H:i:s')
                ];

                // insert
                if(BranchModel::insert_or_ignore($data)) {
                    array_push($data_true, $data);
                }
                else {
                    array_push($data_false, $data);
                }
            }

            // cek params
            if(count($data_true) > 0) {

                // response
                $response = [
                    "status"=> true,
                    "message"=> 'Data berhasil diimpor.'
                ];
                return response()->json($response)->setStatusCode(200);
            }
            else {
                // response
                $response = [
                    "status"=> false,
                    "message"=> 'Data sudah tersimpan.'
                ];
                return response()->json($response)->setStatusCode(200);
            }

        }
        else {
            // response
            $response = [
                "status"=> false,
                "message"=> 'Silahkan upload file excel!'
            ];
            return response()->json($response)->setStatusCode(200);
        }
    }

}
