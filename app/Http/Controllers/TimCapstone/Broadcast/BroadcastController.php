<?php

namespace App\Http\Controllers\TimCapstone\Broadcast;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\Broadcast\BroadcastModel;
use Illuminate\Support\Facades\Hash;


class BroadcastController extends BaseController
{
    // path store in database
    protected $upload_path = '/../../img/broadcast/';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //coba comment

    public function index()
    {
        // dd(BroadcastModel::getData());

        // get data with pagination
        $rs_broadcast = BroadcastModel::getDataWithPagination();
        // data
        $data = ['rs_broadcast' => $rs_broadcast];
        // view
        return view('tim_capstone.broadcast.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addBroadcast()
    {
        // view
        return view('tim_capstone.broadcast.add');
    }


    // public function ImgCrop(Request $request)
    // {
    //     $path = public_path($this->upload_path);
    //     $file = $request->file('broadcast_image');

    //     if ($file) {
    //         // Buat nama file baru dengan menggunakan slug dari judul atau dengan cara lain sesuai kebutuhan aplikasi Anda
    //         $new_image_name = Str::slug($request->nama_event, '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

    //         // Pindahkan file baru ke direktori penyimpanan
    //         $upload = $file->move($path, $new_image_name);

    //         if ($upload) {
    //             // Return success response
    //             return response()->json(['status' => 1, 'msg' => 'Foto berhasil diunggah.', 'name' => $new_image_name]);
    //         } else {
    //             // Return error response jika pengunggahan gagal
    //             return response()->json(['status' => 0, 'msg' => 'Upload foto gagal']);
    //         }
    //     } else {
    //         // Return error response jika file tidak ditemukan
    //         return response()->json(['status' => 0, 'msg' => 'File tidak ditemukan']);
    //     }
    // }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addBroadcastProcess(Request $request)
    {
        // // Define $imagePath variable
        // $imagePath = '';

        // Validate & auto redirect when fail
        $rules = [
            'nama_event' => 'required',
            'tgl_mulai' => 'required',
            'tgl_selesai' => 'required',
            'broadcast_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',

        ];
        $this->validate($request, $rules);

        $file = $request->file('broadcast_image');

        // Buat nama file baru dengan menggunakan slug dari nama event
        $new_image_name = Str::slug($request->nama_event, '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Simpan gambar ke dalam direktori upload
        $file->move(public_path($this->upload_path), $new_image_name);

        // Parameters
        $params = [
            'nama_event' => $request->nama_event,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'keterangan' => $request->keterangan,
            'link_pendukung' => $request->link_pendukung,
            'broadcast_image_name' => $new_image_name, // Gunakan nama file baru yang telah dihasilkan
            'broadcast_image_path' => $this->upload_path, // Gunakan path direktori upload yang telah ditentukan
            'created_by'   => Auth::user()->user_id,
            'created_date'  => now(), // You can directly use now() function to get current date and time
        ];

        // Process
        $insert_broadcast = BroadcastModel::insertbroadcast($params);
        if ($insert_broadcast) {
            // Flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/broadcast');
        } else {
            // Flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/broadcast/add')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailBroadcast($id)
    {

        // get data with pagination
        $broadcast = BroadcastModel::getDataById($id);

        // check
        if (empty($broadcast)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/broadcast');
        }

        // data
        $data = ['broadcast' => $broadcast];

        // view
        return view('tim_capstone.broadcast.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editBroadcast($id)
    {
        // get data
        $broadcast = BroadcastModel::getDataById($id);

        // check
        if (empty($broadcast)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/broadcast');
        }


        // data
        $data = ['broadcast' => $broadcast];


        // view
        return view('tim_capstone.broadcast.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function editBroadcastProcess(Request $request)
    {
        // Validate & auto redirect when fail
        $rules = [
            'nama_event' => 'required',
            'tgl_mulai' => 'required',
            'tgl_selesai' => 'required',
            'broadcast_image' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // Validate the image file
        ];
        $this->validate($request, $rules);

        // // Validasi apakah file gambar baru dipilih
        // if ($request->hasFile('broadcast_image')) {
        //     // Mengambil file gambar baru
        //     $image = $request->file('broadcast_image');
        //     // Menyimpan gambar baru ke dalam folder /img/broadcast/ di server
        //     $imageName = time() . '.' . $image->getClientOriginalExtension();
        //     $image->move(public_path('img/broadcast'), $imageName);
        //     // Mengupdate path gambar di database
        //     $broadcast->broadcast_image = '/img/broadcast/' . $imageName;
        // }

        // params
        $params = [
            'nama_event' => $request->nama_event,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'keterangan' => $request->keterangan,
            'link_pendukung' => $request->link_pendukung,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s'),
        ];

        // process
        if (BroadcastModel::update($request->id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/broadcast');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/broadcast/edit/' . $request->id);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function deleteBroadcastProcess($id)
    {
        // get data
        $broadcast = BroadcastModel::getDataById($id);

        // if exist
        if (!empty($broadcast)) {
            // Delete associated image from storage if it exists
            if (!empty($broadcast->broadcast_image_name)) {
                $imagePath = public_path($broadcast->broadcast_image_path . $broadcast->broadcast_image_name);

                // Check if the file exists and it is a regular file before attempting deletion
                if (file_exists($imagePath) && is_file($imagePath)) {
                    if (!unlink($imagePath)) {
                        // flash message
                        session()->flash('danger', 'Gagal menghapus gambar.');
                        return redirect('/admin/broadcast');
                    }
                }
            }

            // Delete broadcast data
            if (BroadcastModel::delete($id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
        }

        return redirect('/admin/broadcast');
    }

    /**
     * Search data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchMahasiswa(Request $request)
    {

        // data request
        $user_name = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_broadcast = BroadcastModel::getDataSearch($user_name);
            // dd($rs_broadcast);
            // data
            $data = ['rs_broadcast' => $rs_broadcast, 'nama' => $user_name];
            // view
            return view('tim_capstone.broadcast.index', $data);
        } else {
            return redirect('/admin/broadcast');
        }
    }
}
