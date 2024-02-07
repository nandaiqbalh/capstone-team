<?php

namespace App\Http\Controllers\API\V1\Mahasiswa\Kelompok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Mahasiswa\Profile\ApiAccountModel;
use App\Models\Api\Mahasiswa\Kelompok\ApiKelompokSayaModel;
use Illuminate\Support\Facades\DB;



class ApiKelompokSayaController extends Controller
{
    public function index(Request $request)
    {
        // Get api_token from the request body
        $apiToken = $request->input('api_token');

        // Check if api_token is provided
        if (empty($apiToken)) {
            $response = [
                'status' => false,
                'message' => 'Sesi anda telah berakhir, silahkan masuk terlebih dahulu.',
                'data' => null,
            ];
            return response()->json($response); // 400 Bad Request
        }

        $userId = $request->input('user_id');
        $user = ApiAccountModel::getById($userId);

        // Check if the user exists
        if ($user != null) {
            // Attempt to authenticate the user based on api_token
            if ($user->api_token != null) {
                // Authorize
                $isAuthorized = ApiAccountModel::authorize('R', $userId);

                if (!$isAuthorized) {
                    $response = [
                        'status' => false,
                        'message' => 'Akses tidak sah!',
                        'data' => null,
                    ];
                    return response()->json($response);
                } else {
                    // Check if the provided api_token matches the user's api_token
                    if ($user->api_token == $apiToken) {
                        // Data
                        try {

                            // get data kelompok
                            $kelompok = ApiKelompokSayaModel::pengecekan_kelompok_mahasiswa($user -> user_id);
                            $rs_siklus = ApiKelompokSayaModel::getSiklusAktif();
                            $rs_topik = ApiKelompokSayaModel::getTopik();
                            // dd($kelompok);

                            if ($kelompok == null) {
                                // belum memiliki kelompok
                                $getAkun = ApiKelompokSayaModel::getAkunByID($user ->user_id);

                                // data
                                $data = [

                                    'kelompok' => $kelompok,
                                    'getAkun' => $getAkun,
                                    'rs_mahasiswa' => null,
                                    'rs_dosbing' => null,
                                    'rs_dospeng' => null,
                                    'rs_siklus' => $rs_siklus,
                                    'rs_topik' => $rs_topik,

                                ];
                            } else {

                                // sudah mendaftar kelompok (baik secara individu maupun secara kelompok)

                                // adds
                                $getAkun = ApiKelompokSayaModel::getAkunByID($user ->user_id);

                                // mahasiswa
                                $rs_mahasiswa = ApiKelompokSayaModel::listKelompokMahasiswa($kelompok->id_kelompok);

                                 foreach ($rs_mahasiswa as $key =>$mahasiswa ) {

                                     $storagePath = null;

                                     // dd( $broadcast);
                                     if ($mahasiswa->user_img_name != "" && $mahasiswa->user_img_name != null) {
                                         $storagePath = public_path($mahasiswa->user_img_path . $mahasiswa->user_img_name);

                                         if (file_exists($storagePath)) {
                                             $base64Image = base64_encode(file_get_contents($storagePath));
                                             $rs_mahasiswa[$key]->user_img_path = $base64Image;
                                         } else {
                                             $rs_mahasiswa[$key]->user_img_path = null;
                                         }
                                     } else {
                                         $rs_mahasiswa[$key]->user_img_path = null;
                                     }

                                 }

                                 // dosbing
                                 $rs_dosbing = ApiKelompokSayaModel::getAkunDosbingKelompok($kelompok->id_kelompok);

                                 foreach ($rs_dosbing as $key =>$dosbing ) {

                                     $storagePath = null;

                                     // dd( $broadcast);
                                     if ($dosbing->user_img_name != "" && $dosbing->user_img_name != null) {
                                         $storagePath = public_path($dosbing->user_img_path . $dosbing->user_img_name);

                                         if (file_exists($storagePath)) {
                                             $base64Image = base64_encode(file_get_contents($storagePath));
                                             $rs_dosbing[$key]->user_img_path = $base64Image;
                                         } else {
                                             $rs_dosbing[$key]->user_img_path = null;
                                         }
                                     } else {
                                         $rs_dosbing[$key]->user_img_path = null;
                                     }

                                 }

                                 // dospeng
                                 $rs_dospeng = ApiKelompokSayaModel::getAkunDospengKelompok($kelompok->id_kelompok);

                                 foreach ($rs_dospeng as $key =>$dospeng ) {

                                     $storagePath = null;

                                     // dd( $broadcast);
                                     if ($dospeng->user_img_name != "" && $dospeng->user_img_name != null) {
                                         $storagePath = public_path($dospeng->user_img_path . $dospeng->user_img_name);

                                         if (file_exists($storagePath)) {
                                             $base64Image = base64_encode(file_get_contents($storagePath));
                                             $rs_dospeng[$key]->user_img_path = $base64Image;
                                         } else {
                                             $rs_dospeng[$key]->user_img_path = null;
                                         }
                                     } else {
                                         $rs_dospeng[$key]->user_img_path = null;
                                     }

                                 }

                                // data
                                $data = [
                                    'kelompok' => $kelompok,
                                    'getAkun' => $getAkun,
                                    'rs_mahasiswa' => $rs_mahasiswa,
                                    'rs_dosbing' => $rs_dosbing,
                                    'rs_dospeng' => $rs_dospeng,
                                    'rs_siklus' => $rs_siklus,
                                    'rs_topik' => $rs_topik,

                                ];
                            }

                            return response()->json(['status' => true, 'message' => "Berhasil mendapatkan data.", 'data' => $data]);
                        } catch (\Exception $e) {
                            return response()->json(['status' => false, 'message' => $e->getMessage()]);
                        }

                    } else {
                        $response = [
                            'status' => false,
                            'message' => 'Gagal! Anda telah masuk melalui perangkat lain.',
                            'data' => null,
                        ];
                        return response()->json($response); // 401 Unauthorized
                    }
                }
            }
        } else {
            // User not found or api_token is null
            $response = [
                'status' => false,
                'message' => 'Pengguna tidak ditemukan!',
                'data' => null,
            ];
            return response()->json($response); // 401 Unauthorized
        }
    }

    public function addKelompokProcess(Request $request)
    {
        // Get api_token from the request body
        $apiToken = $request->input('api_token');

        // Check if api_token is provided
        if (empty($apiToken)) {
            $response = [
                'status' => false,
                'message' => 'Sesi anda telah berakhir, silahkan masuk terlebih dahulu.',
                'data' => null,
            ];
            return response()->json($response); // 400 Bad Request
        }

        $userId = $request->input('user_id');
        $user = ApiAccountModel::getById($userId);

        // Check if the user exists
        if ($user != null) {
            // Attempt to authenticate the user based on api_token
            if ($user->api_token != null) {
                // Authorize
                $isAuthorized = ApiAccountModel::authorize('C', $userId);

                if (!$isAuthorized) {
                    $response = [
                        'status' => false,
                        'message' => 'Akses tidak sah!',
                        'data' => null,
                    ];
                    return response()->json($response);
                } else {
                    // Check if the provided api_token matches the user's api_token
                    if ($user->api_token == $apiToken) {

                        // validasi params
                        $requiredParams = ['angkatan', 'email', 'jenis_kelamin', 'ipk', 'sks', 'no_telp', 'alamat', 'id_siklus', 's', 'e', 'c', 'm'];
                        foreach ($requiredParams as $param) {
                            if (!$request->has($param) || empty($request->input($param))) {
                                $response = [
                                    'status' => false,
                                    'message' => "Parameter '$param' kosong atau belum diisi.",
                                    'data' => null,
                                ];
                                return response()->json($response); // 400 Bad Request
                            }
                        }
                        // Data
                        try {
                            // params
                            $params = [
                                "angkatan" => $request->angkatan,
                                "user_email" => $request->email,
                                "jenis_kelamin" => $request->jenis_kelamin,
                                "ipk" => $request->ipk,
                                "sks" => $request->sks,
                                'no_telp' => $request->no_telp,
                                "alamat" => $request->alamat,
                                'modified_by'   => $user->user_id,
                                'modified_date'  => now(),
                            ];

                            // process
                            $update_mahasiswa = ApiKelompokSayaModel::updateMahasiswa($user->user_id, $params);

                            if ($update_mahasiswa) {
                                $params2 = [
                                    "id_siklus" => $request->id_siklus,
                                    'id_mahasiswa' => $user->user_id,
                                    'status_individu' => 'menunggu persetujuan',
                                ];
                                ApiKelompokSayaModel::insertKelompokMHS($params2);
                                $insert_id = DB::getPdo()->lastInsertId();

                                $paramS = [
                                    'id_mahasiswa' => $user->user_id,
                                    'id_kel_mhs' => $insert_id,
                                    'peminatan' => 'Software & Database',
                                    'prioritas' => $request->s,
                                ];

                                ApiKelompokSayaModel::insertPeminatan($paramS);

                                $paramE = [
                                    'id_mahasiswa' => $user->user_id,
                                    'id_kel_mhs' => $insert_id,
                                    'peminatan' => 'Embedded System & Robotics',
                                    'prioritas' => $request->e,
                                ];

                                ApiKelompokSayaModel::insertPeminatan($paramE);

                                $paramC = [
                                    'id_mahasiswa' => $user->user_id,
                                    'id_kel_mhs' => $insert_id,
                                    'peminatan' => 'Computer Network & Security',
                                    'prioritas' => $request->c,
                                ];

                                ApiKelompokSayaModel::insertPeminatan($paramC);

                                $paramM = [
                                    'id_mahasiswa' => $user->user_id,
                                    'id_kel_mhs' => $insert_id,
                                    'peminatan' => 'Multimedia & Game',
                                    'prioritas' => $request->m,
                                ];

                                ApiKelompokSayaModel::insertPeminatan($paramM);

                                $rs_topik = ApiKelompokSayaModel::getTopik();
                                foreach ($rs_topik as $key => $value) {
                                    $param = [
                                        'id_mahasiswa'  => $user->user_id,
                                        'id_kel_mhs'    => $insert_id,
                                        'id_topik'     => $value->id,
                                        'prioritas' => $request[$value->id],
                                        'created_by'   => $user->user_id,
                                        'created_date'  => date('Y-m-d H:i:s')
                                    ];

                                    ApiKelompokSayaModel::insertTopikMHS($param);
                                }

                                // response
                                return response()->json(['status' => true, 'message' => 'Data berhasil disimpan.']);
                            } else {
                                // response
                                return response()->json(['status' => false, 'message' => 'Data gagal disimpan.']);
                            }
                        } catch (\Exception $e) {
                            // response for unexpected errors
                            return response()->json(['status' => false, 'message' => $e->getMessage()]);

                        }


                    } else {
                        $response = [
                            'status' => false,
                            'message' => 'Gagal! Anda telah masuk melalui perangkat lain.',
                            'data' => null,
                        ];
                        return response()->json($response); // 401 Unauthorized
                    }
                }
            }
        } else {
            // User not found or api_token is null
            $response = [
                'status' => false,
                'message' => 'Pengguna tidak ditemukan!',
                'data' => null,
            ];
            return response()->json($response); // 401 Unauthorized
        }
    }

    public function addPunyaKelompokProcess(Request $request)
    {
        // Get api_token from the request body
        $apiToken = $request->input('api_token');

        // Check if api_token is provided
        if (empty($apiToken)) {
            $response = [
                'status' => false,
                'message' => 'Sesi anda telah berakhir, silahkan masuk terlebih dahulu.',
                'data' => null,
            ];
            return response()->json($response); // 400 Bad Request
        }

        $userId = $request->input('user_id');
        $user = ApiAccountModel::getById($userId);

        // Check if the user exists
        if ($user != null) {
            // Attempt to authenticate the user based on api_token
            if ($user->api_token != null) {
                // Authorize
                $isAuthorized = ApiAccountModel::authorize('C', $userId);

                if (!$isAuthorized) {
                    $response = [
                        'status' => false,
                        'message' => 'Akses tidak sah!',
                        'data' => null,
                    ];
                    return response()->json($response);
                } else {
                    // Check if the provided api_token matches the user's api_token
                    if ($user->api_token == $apiToken) {
                        // validasi params
                        $requiredParams = [
                            'angkatan', 'judul_capstone', 'id_topik', 'dosbing_1', 'dosbing_2',
                            'angkatan1', 'email1', 'jenis_kelamin1', 'ipk1', 'sks1', 'no_telp1', 'alamat1',
                            'angkatan2', 'email2', 'jenis_kelamin2', 'ipk2', 'sks2', 'no_telp2', 'alamat2',
                            'angkatan3', 'email3', 'jenis_kelamin3', 'ipk3', 'sks3', 'no_telp3', 'alamat3',
                        ];

                        foreach ($requiredParams as $param) {
                            if (!$request->has($param) || empty($request->input($param))) {
                                $response = [
                                    'status' => false,
                                    'message' => "Parameter '$param' kosong atau belum diisi.",
                                    'data' => null,
                                ];
                                return response()->json($response); // 400 Bad Request
                            }
                        }
                        // Data
                        try {
                            if ($request->dosbing_1 == $request->dosbing_2) {
                                return response()->json(['status' => false, 'message' => 'Dosen tidak boleh sama!']);
                            }

                            if ($request->nama1 == $request->nama2 || $request->nama1 == $request->nama3 || $request->nama2 == $request->nama3) {
                                return response()->json(['status' => false, 'message' => 'Mahasiswa tidak boleh sama!']);
                            }

                            // addKelompok
                            $params = [
                                "id_siklus" => $request->id_siklus,
                                "judul_capstone" => $request->judul_capstone,
                                "id_topik" => $request->id_topik,
                                "status_kelompok" => "menunggu persetujuan",
                                "id_dosen_pembimbing_1" => $request->dosbing_1,
                                "id_dosen_pembimbing_2" => $request->dosbing_2,
                            ];
                            ApiKelompokSayaModel::insertKelompok($params);
                            $id_kelompok = DB::getPdo()->lastInsertId();

                            $paramsDosen1 = [
                                "id_kelompok" => $id_kelompok,
                                "id_dosen" => $request->dosbing_1,
                                "status_dosen" => "pembimbing 1",
                                "status_persetujuan" => "menunggu persetujuan",
                            ];
                            ApiKelompokSayaModel::insertDosenKelompok($paramsDosen1);
                            $paramsDosen2 = [
                                "id_kelompok" => $id_kelompok,
                                "id_dosen" => $request->dosbing_2,
                                "status_dosen" => "pembimbing 2",
                                "status_persetujuan" => "menunggu persetujuan",
                            ];
                            ApiKelompokSayaModel::insertDosenKelompok($paramsDosen2);


                            // params mahasiswa 1
                            $params1 = [
                                "angkatan" => $request->angkatan1,
                                "ipk" => $request->ipk1,
                                "user_email" => $request->email1,
                                "jenis_kelamin" => $request->jenis_kelamin1,
                                "sks" => $request->sks1,
                                'no_telp' => $request->no_telp1,
                                "alamat" => $request->alamat1,
                                'modified_by'   => $user->user_id,
                                'modified_date'  => date('Y-m-d H:i:s')
                            ];

                            // process
                            $update_mahasiswa1 = ApiKelompokSayaModel::updateMahasiswa($user->user_id, $params1);
                            if ($update_mahasiswa1) {
                                $params11 = [
                                    "id_siklus" => $request->id_siklus,
                                    'id_kelompok' => $id_kelompok,
                                    'id_mahasiswa' => $user->user_id,
                                    'id_topik_mhs' => $request->id_topik,
                                ];
                                ApiKelompokSayaModel::insertKelompokMHS($params11);
                            }

                            // params mahasiswa 2
                            $params2 = [
                                "angkatan" => $request->angkatan2,
                                "user_email" => $request->email2,
                                "jenis_kelamin" => $request->jenis_kelamin2,
                                "ipk" => $request->ipk2,
                                "sks" => $request->sks2,
                                'no_telp' => $request->no_telp2,
                                "alamat" => $request->alamat2,
                                'modified_by'   => $user->user_id,
                                'modified_date'  => date('Y-m-d H:i:s')
                            ];

                            // process
                            $update_mahasiswa2 = ApiKelompokSayaModel::updateMahasiswa($request->nama2, $params2);
                            if ($update_mahasiswa2) {
                                $params22 = [
                                    "id_siklus" => $request->id_siklus,
                                    'id_kelompok' => $id_kelompok,
                                    'id_mahasiswa' => $request->nama2,
                                    'id_topik_mhs' => $request->id_topik,
                                ];
                                ApiKelompokSayaModel::insertKelompokMHS($params22);
                            }

                            // params mahasiswa 3
                            $params3 = [
                                "angkatan" => $request->angkatan3,
                                "user_email" => $request->email3,
                                "jenis_kelamin" => $request->jenis_kelamin3,
                                "ipk" => $request->ipk3,
                                "sks" => $request->sks3,
                                'no_telp' => $request->no_telp3,
                                "alamat" => $request->alamat3,
                                'modified_by'   => $user->user_id,
                                'modified_date'  => date('Y-m-d H:i:s')
                            ];

                            // process
                            $update_mahasiswa3 = ApiKelompokSayaModel::updateMahasiswa($request->nama3, $params3);
                            if ($update_mahasiswa3) {
                                $params33 = [
                                    "id_siklus" => $request->id_siklus,
                                    'id_kelompok' => $id_kelompok,
                                    'id_mahasiswa' => $request->nama3,
                                    'id_topik_mhs' => $request->id_topik,
                                ];
                                ApiKelompokSayaModel::insertKelompokMHS($params33);
                            }
                            // response
                            return response()->json(['status' => true, 'message' => 'Data berhasil disimpan.']);
                        } catch (\Exception $e) {
                            // response for unexpected errors
                            return response()->json(['status' => false, 'message' => $e->getMessage()]);
                        }

                    } else {
                        $response = [
                            'status' => false,
                            'message' => 'Gagal! Anda telah masuk melalui perangkat lain.',
                            'data' => null,
                        ];
                        return response()->json($response); // 401 Unauthorized
                    }
                }
            }
        } else {
            // User not found or api_token is null
            $response = [
                'status' => false,
                'message' => 'Pengguna tidak ditemukan!',
                'data' => null,
            ];
            return response()->json($response); // 401 Unauthorized
        }
    }

}
