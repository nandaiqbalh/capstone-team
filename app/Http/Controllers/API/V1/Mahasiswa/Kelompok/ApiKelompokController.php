<?php

namespace App\Http\Controllers\API\V1\Mahasiswa\Kelompok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Mahasiswa\Kelompok\ApiKelompokModel;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class ApiKelompokController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Check if the token is still valid
            JWTAuth::checkOrFail();

            // Get the user from the JWT token in the request headers
            $jwtUser = JWTAuth::parseToken()->authenticate();

            $user = ApiKelompokModel::getAkunByID($jwtUser->user_id);
            $userBelumBerkelompok = ApiKelompokModel::getAkunBelumPunyaKelompok($jwtUser->user_id);

            // Check if the user exists
            if ($user != null && $user->user_active == 1) {
                // Data
                try {
                    // get data kelompok
                    $kelompok = ApiKelompokModel::pengecekan_kelompok_mahasiswa($user->user_id);

                    // dd($kelompok);
                    $getAkun = ApiKelompokModel::getAkunByID($user->user_id);

                    $userImageUrl = $this->getProfileImageUrl($getAkun);
                    // Add the user_img_url to the user object
                    $getAkun->user_img_url = $userImageUrl;

                    if ($kelompok == null) {
                        // belum memiliki kelompok
                        $data = [
                            'kelompok' => $kelompok,
                            'getAkun' => $getAkun,
                            'rs_mahasiswa' => null,
                            'rs_dosbing' => null,
                            'rs_dospeng' => null,
                            'rs_dospeng_ta' => null,
                        ];
                        $response = $this->failureResponse('Belum mendaftar capstone!');

                    } else {
                        // sudah mendaftar kelompok (baik secara individu maupun secara kelompok)
                        // check apakah siklusnya masih aktif atau tidak
                        $dataPendaftaranMhs = ApiKelompokModel::getDataPendaftaranMhs($user -> user_id);
                        $isSiklusAktif = ApiKelompokModel::checkApakahSiklusMasihAktif($dataPendaftaranMhs -> id_siklus, $user ->user_id);
                        if($isSiklusAktif -> status == 'tidak aktif'){
                            // siklus sudah tidak aktif
                            $kelompok ->id_siklus = 0;
                            $data = [
                                'kelompok' => $kelompok,
                                'getAkun' => $getAkun,
                                'rs_mahasiswa' => null,
                                'rs_dosbing' => null,
                                'rs_dospeng' => null,
                                'rs_dospeng_ta' => null,
                            ];

                            $response = $this->failureResponse('Siklus capstone sudah tidak aktif!', $data);

                        } else {

                            // mahasiswa
                        $rs_mahasiswa = ApiKelompokModel::listKelompokMahasiswa($kelompok->id_kelompok);

                        foreach ($rs_mahasiswa as $key => $mahasiswa) {
                            $userImageUrl = $this->getProfileImageUrl($mahasiswa);
                            // Add the user_img_url to the user object
                            $mahasiswa->user_img_url = $userImageUrl;
                        }

                        // dosbing
                        $rs_dosbing = ApiKelompokModel::getAkunDosbingKelompok($kelompok->id_kelompok);
                        foreach ($rs_dosbing as $key => $dosbing) {
                            $userImageUrl = $this->getProfileImageUrl($dosbing);
                            // Add the user_img_url to the user object
                            $dosbing->user_img_url = $userImageUrl;
                        }

                        $rs_dospeng = ApiKelompokModel::getAkunDospengKelompok($kelompok->id_kelompok);
                        foreach ($rs_dospeng as $key => $dospeng) {
                            $userImageUrl = $this->getProfileImageUrl($dospeng);
                            // Add the user_img_url to the user object
                            $dospeng->user_img_url = $userImageUrl;
                        }

                        $rs_dospeng_ta = ApiKelompokModel::getAkunDospengTa($user -> user_id);
                        foreach ($rs_dospeng_ta as $key => $dospeng_ta) {
                            $userImageUrl = $this->getProfileImageUrl($dospeng_ta);
                            // Add the user_img_url to the user object
                            $dospeng_ta->user_img_url = $userImageUrl;
                        }

                        // data
                        $data = [
                            'kelompok' => $kelompok,
                            'getAkun' => $getAkun,
                            'rs_mahasiswa' => $rs_mahasiswa,
                            'rs_dosbing' => $rs_dosbing,
                            'rs_dospeng' => $rs_dospeng,
                            'rs_dospeng_ta' => $rs_dospeng_ta,
                        ];
                        $response = $this->successResponse('Berhasil mendapatkan data!', $data);
                    }
                    }
                } catch (\Exception $e) {
                    $response = $this->failureResponse('Gagal mendapatkan data!');
                }
            } else if($userBelumBerkelompok != null && $userBelumBerkelompok->user_active == 1){
                try {
                    // get data kelompok
                    $kelompok = ApiKelompokModel::pengecekan_kelompok_mahasiswa($userBelumBerkelompok->user_id);

                    // dd($kelompok);
                    $getAkun = ApiKelompokModel::getAkunBelumPunyaKelompok($userBelumBerkelompok->user_id);

                    $userImageUrl = $this->getProfileImageUrl($getAkun);
                    // Add the user_img_url to the user object
                    $getAkun->user_img_url = $userImageUrl;

                    if ($kelompok == null) {
                        // belum memiliki kelompok
                        $data = [
                            'kelompok' => $kelompok,
                            'getAkun' => $getAkun,
                            'rs_mahasiswa' => null,
                            'rs_dosbing' => null,
                            'rs_dospeng' => null,
                            'rs_dospeng_ta' => null,
                        ];
                        $response = $this->failureResponse('Belum mendaftar capstone!');

                    } else {
                        // sudah mendaftar kelompok (baik secara individu maupun secara kelompok)
                        // check apakah siklusnya masih aktif atau tidak
                        $dataPendaftaranMhs = ApiKelompokModel::getDataPendaftaranMhs($userBelumBerkelompok -> user_id);
                        $isSiklusAktif = ApiKelompokModel::checkApakahSiklusMasihAktif($dataPendaftaranMhs -> id_siklus, $userBelumBerkelompok ->user_id);
                        if($isSiklusAktif -> status == 'tidak aktif'){
                            // siklus sudah tidak aktif
                            $kelompok ->id_siklus = 0;
                            $data = [
                                'kelompok' => $kelompok,
                                'getAkun' => $getAkun,
                                'rs_mahasiswa' => null,
                                'rs_dosbing' => null,
                                'rs_dospeng' => null,
                                'rs_dospeng_ta' => null,
                            ];

                            $response = $this->failureResponse('Siklus capstone sudah tidak aktif!', $data);

                        } else {

                            // mahasiswa
                        $rs_mahasiswa = ApiKelompokModel::listKelompokMahasiswa($kelompok->id_kelompok);

                        foreach ($rs_mahasiswa as $key => $mahasiswa) {
                            $userImageUrl = $this->getProfileImageUrl($mahasiswa);
                            // Add the user_img_url to the user object
                            $mahasiswa->user_img_url = $userImageUrl;
                        }

                        // dosbing
                        $rs_dosbing = ApiKelompokModel::getAkunDosbingKelompok($kelompok->id_kelompok);
                        foreach ($rs_dosbing as $key => $dosbing) {
                            $userImageUrl = $this->getProfileImageUrl($dosbing);
                            // Add the user_img_url to the user object
                            $dosbing->user_img_url = $userImageUrl;
                        }

                        $rs_dospeng = ApiKelompokModel::getAkunDospengKelompok($kelompok->id_kelompok);
                        foreach ($rs_dospeng as $key => $dospeng) {
                            $userImageUrl = $this->getProfileImageUrl($dospeng);
                            // Add the user_img_url to the user object
                            $dospeng->user_img_url = $userImageUrl;
                        }

                        $rs_dospeng_ta = ApiKelompokModel::getAkunDospengTa($userBelumBerkelompok -> user_id);
                        foreach ($rs_dospeng_ta as $key => $dospeng_ta) {
                            $userImageUrl = $this->getProfileImageUrl($dospeng_ta);
                            // Add the user_img_url to the user object
                            $dospeng_ta->user_img_url = $userImageUrl;
                        }

                        // data
                        $data = [
                            'kelompok' => $kelompok,
                            'getAkun' => $getAkun,
                            'rs_mahasiswa' => $rs_mahasiswa,
                            'rs_dosbing' => $rs_dosbing,
                            'rs_dospeng' => $rs_dospeng,
                            'rs_dospeng_ta' => $rs_dospeng_ta,
                        ];
                        $response = $this->successResponse('Berhasil mendapatkan data!', $data);
                    }
                }
                } catch (\Exception $e) {
                    $response = $this->failureResponse('Gagal mendapatkan data!');
                }
            } else {
                $response = $this->failureResponse('Pengguna tidak ditemukan!');
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $response = $this->failureResponse('Gagal mendapatkan data!');
        }

        return response()->json($response);
    }

    public function addKelompokProcess(Request $request)
    {
        // Check if the token is still valid
        JWTAuth::checkOrFail();

        // Get the user from the JWT token in the request headers
        $jwtUser = JWTAuth::parseToken()->authenticate();

        $user = ApiKelompokModel::getAkunBelumPunyaKelompok($jwtUser->user_id);

        // Check if the user exists
        if ($user != null && $user->user_active == 1) {
            // validation params
            $requiredParams = ['angkatan', 'email', 'jenis_kelamin', 'ipk', 'sks', 'no_telp', 'id_siklus', 's', 'e', 'c', 'm', 'ews', 'bac', 'smb', 'smc'];
            foreach ($requiredParams as $param) {
                if (!$request->has($param) || empty($request->input($param))) {
                    $response = $this->failureResponse("Parameter '$param' kosong atau belum diisi!");
                }
            }

            try {
                // params
                $params = [
                    "angkatan" => $request->angkatan,
                    "user_name" => $request->user_name,
                    "nomor_induk" => $request->nomor_induk,
                    "user_email" => $request->email,
                    "jenis_kelamin" => $request->jenis_kelamin,
                    "ipk" => $request->ipk,
                    "sks" => $request->sks,
                    'no_telp' => $request->no_telp,
                    'modified_by'   => $user->user_id,
                    'modified_date'  => now(),
                ];

                // process
                $update_mahasiswa = ApiKelompokModel::updateMahasiswa($user->user_id, $params);

                if ($update_mahasiswa) {
                    // Retrieve topik and peminatan data
                    $topik1 = ApiKelompokModel::getTopikById($request->ews);
                    $topik2 = ApiKelompokModel::getTopikById($request->bac);
                    $topik3 = ApiKelompokModel::getTopikById($request->smb);
                    $topik4 = ApiKelompokModel::getTopikById($request->smc);

                    $peminatan1 = ApiKelompokModel::getPeminatanById($request->s);
                    $peminatan2 = ApiKelompokModel::getPeminatanById($request->e);
                    $peminatan3 = ApiKelompokModel::getPeminatanById($request->c);
                    $peminatan4 = ApiKelompokModel::getPeminatanById($request->m);

                    // Insert kelompok mhs
                    $params2 = [
                        'usulan_judul_capstone' => $request->judul_capstone,
                        'id_siklus' => $request->id_siklus,
                        'id_mahasiswa' => $user->user_id,
                        'status_individu' => 'Menunggu Validasi Kelompok!',
                        'id_topik_individu1' => $topik1->id,
                        'id_topik_individu2' => $topik2->id,
                        'id_topik_individu3' => $topik3->id,
                        'id_topik_individu4' => $topik4->id,
                        'id_peminatan_individu1' => $peminatan1->id,
                        'id_peminatan_individu2' => $peminatan2->id,
                        'id_peminatan_individu3' => $peminatan3->id,
                        'id_peminatan_individu4' => $peminatan4->id,
                        'created_by'   => $user->user_id,
                         'created_date'  => now(),
                    ];

                    ApiKelompokModel::insertKelompokMHS($params2);

                    $response = $this->successResponse('Berhasil mendaftar capstone!', 'Berhasil!');
                } else {
                    $response = $this->failureResponse('Gagal mendaftar capstone!');
                }
            } catch (\Exception $e) {
                $response = $this->failureResponse('Gagal mendaftar capstone!');
            }
        } else {
            $response = $this->failureResponse('Pengguna tidak ditemukan!');
        }
        return response()->json($response);
    }


    public function addPunyaKelompokProcess(Request $request)
    {
        // Check if the token is still valid
        JWTAuth::checkOrFail();

        // Get the user from the JWT token in the request headers
        $jwtUser = JWTAuth::parseToken()->authenticate();

        $user = ApiKelompokModel::getAkunBelumPunyaKelompok($jwtUser->user_id);

        // Check if the user exists
        if ($user != null && $user->user_active == 1) {
            // validation params
            $requiredParams = [
                'judul_capstone', 'id_siklus', 'id_topik', 'dosbing_1', 'dosbing_2',
                'angkatan1', 'email1', 'jenis_kelamin1', 'ipk1', 'sks1', 'no_telp1',
                'user_id2','angkatan2', 'email2', 'jenis_kelamin2', 'ipk2', 'sks2', 'no_telp2',
                'user_id3','angkatan3', 'email3', 'jenis_kelamin3', 'ipk3', 'sks3', 'no_telp3',
            ];

             // params mahasiswa 2
             $params2 = [
                "angkatan" => $request->angkatan2,
                "user_email" => $request->email2,
                "jenis_kelamin" => $request->jenis_kelamin2,
                "ipk" => $request->ipk2,
                "sks" => $request->sks2,
                'no_telp' => $request->no_telp2,
                'modified_by'   => $user->user_id,
                'modified_date'  => date('Y-m-d H:i:s')
            ];

            foreach ($requiredParams as $param) {
                if (!$request->has($param) || empty($request->input($param))) {
                    $response = $this->failureResponse("Parameter '$param' kosong atau belum diisi!");
                }
            }

            if ($request->dosbing_1 == $request->dosbing_2) {
                $response = $this->failureResponse("Dosen pembimbing tidak boleh sama!");
            }

            if ($request->user_id1 == $request->user_id2 || $request->user_id1 == $request->user_id3 || $request->user_id2 == $request->user_id3) {
                $response = $this->failureResponse("Mahasiswa tidak boleh sama!");
            }

            try {

                if (ApiKelompokModel::isAccountExist($request -> user_id2) && ApiKelompokModel::isAccountExist($request ->user_id3)) {
                    // addKelompok
                    $params = [
                        "id_siklus" => $request->id_siklus,
                        "judul_capstone" => $request->judul_capstone,
                        "id_topik" => $request->id_topik,
                        "status_kelompok" => 'Menunggu Persetujuan Anggota!',
                        "id_dosen_pembimbing_1" => $request->dosbing_1,
                        "status_dosen_pembimbing_1" =>'Menunggu Persetujuan!',
                        "id_dosen_pembimbing_2" => $request->dosbing_2,
                        "status_dosen_pembimbing_2" =>'Menunggu Persetujuan!',
                        'created_by' => $user->user_id,
                        'created_date' => now()
                    ];

                    ApiKelompokModel::insertKelompok($params);
                    $id_kelompok = DB::getPdo()->lastInsertId();

                    // params mahasiswa 1
                    $params1 = [
                        "angkatan" => $request->angkatan1,
                        "ipk" => $request->ipk1,
                        "user_email" => $request->email1,
                        "jenis_kelamin" => $request->jenis_kelamin1,
                        "sks" => $request->sks1,
                        'no_telp' => $request->no_telp1,
                        'modified_by'   => $user->user_id,
                        'modified_date'  => date('Y-m-d H:i:s')
                    ];

                    // process
                    $update_mahasiswa1 = ApiKelompokModel::updateMahasiswa($user->user_id, $params1);
                    if ($update_mahasiswa1) {
                        $params11 = [
                            "id_siklus" => $request->id_siklus,
                            'id_kelompok' => $id_kelompok,
                            'id_mahasiswa' => $user->user_id,
                            'status_individu' => 'Menyetujui Kelompok!',
                            'usulan_judul_capstone' => $request -> judul_capstone,
                            'id_topik_mhs' => $request->id_topik,
                            'created_by'   => $user->user_id,
                            'created_date'  => date('Y-m-d H:i:s')
                        ];
                        ApiKelompokModel::insertKelompokMHS($params11);
                    }

                    // params mahasiswa 2
                    $params2 = [
                        "angkatan" => $request->angkatan2,
                        "user_email" => $request->email2,
                        "jenis_kelamin" => $request->jenis_kelamin2,
                        "ipk" => $request->ipk2,
                        "sks" => $request->sks2,
                        'no_telp' => $request->no_telp2,
                        'modified_by'   => $user->user_id,
                        'modified_date'  => date('Y-m-d H:i:s')
                    ];

                    // process
                    $update_mahasiswa2 = ApiKelompokModel::updateMahasiswa($request->user_id2, $params2);
                    if ($update_mahasiswa2) {
                        $params22 = [
                            "id_siklus" => $request->id_siklus,
                            'id_kelompok' => $id_kelompok,
                            'id_mahasiswa' => $request->user_id2,
                            'status_individu' => 'Didaftarkan!',
                            'usulan_judul_capstone' => $request -> judul_capstone,
                            'id_topik_mhs' => $request->id_topik,
                            'created_by'   => $user->user_id,
                            'created_date'  => date('Y-m-d H:i:s')
                        ];
                        ApiKelompokModel::insertKelompokMHS($params22);
                    }

                    // params mahasiswa 3
                    $params3 = [
                        "angkatan" => $request->angkatan3,
                        "user_email" => $request->email3,
                        "jenis_kelamin" => $request->jenis_kelamin3,
                        "ipk" => $request->ipk3,
                        "sks" => $request->sks3,
                        'no_telp' => $request->no_telp3,
                        'modified_by'   => $user->user_id,
                        'modified_date'  => date('Y-m-d H:i:s')
                    ];

                    // process
                    $update_mahasiswa3 = ApiKelompokModel::updateMahasiswa($request->user_id3, $params3);
                    if ($update_mahasiswa3) {
                        $params33 = [
                            "id_siklus" => $request->id_siklus,
                            'id_kelompok' => $id_kelompok,
                            'id_mahasiswa' => $request->user_id3,
                            'status_individu' => 'Didaftarkan!',
                            'usulan_judul_capstone' => $request -> judul_capstone,
                            'id_topik_mhs' => $request->id_topik,
                            'created_by'   => $user->user_id,
                            'created_date'  => date('Y-m-d H:i:s')
                        ];
                        ApiKelompokModel::insertKelompokMHS($params33);
                    }
                        $response = $this->successResponse("Berhasil mendaftar capstone!", null);
                    } else {
                        $response = $this->failureResponse("Pastikan semua mahasiswa merupakan mahasiswa aktif!");
                    }

            } catch (\Exception $e) {
                $response = $this->failureResponse("Gagal mendaftar capstone!");

            }
        } else {
            // User not found or api_token is null
            $response = $this->failureResponse("Pengguna tidak ditemukan!");
        }
        return response()->json($response);
    }


    public function tolakKelompok(Request $request)
    {
        // Check if the token is still valid
        JWTAuth::checkOrFail();

        // Get the user from the JWT token in the request headers
        $jwtUser = JWTAuth::parseToken()->authenticate();

        $user = ApiKelompokModel::getAkunByID($jwtUser->user_id);

        // Check if the user exists
        if ($user != null && $user->user_active == 1) {

            try {

                // process
                $update_kelompok_mhs = ApiKelompokModel::deleteKelompokMhs($user->user_id);

                $response = $this->successResponse('Berhasil menolak kelompok!', 'Berhasil!');

            } catch (\Exception $e) {
                $response = $this->failureResponse('Gagal menolak!');
            }
        } else {
            $response = $this->failureResponse('Pengguna tidak ditemukan!');
        }
        return response()->json($response);
    }

    public function terimaKelompok(Request $request)
    {
        // Check if the token is still valid
        JWTAuth::checkOrFail();

        // Get the user from the JWT token in the request headers
        $jwtUser = JWTAuth::parseToken()->authenticate();

        $user = ApiKelompokModel::getAkunByID($jwtUser->user_id);

        // Check if the user exists
        if ($user != null && $user->user_active == 1) {
            try {
                // params
                $params = [
                    "status_individu" => "Menyetujui Kelompok!",
                ];

                // process
                $update_kelompok_mhs = ApiKelompokModel::updateKelompokMHS($user->user_id, $params);

                // Inisialisasi variabel untuk menyimpan status setuju semua atau tidak
                $semuaSetuju = true;

                if ($update_kelompok_mhs) {
                    $kelompok = ApiKelompokModel::pengecekan_kelompok_mahasiswa($user->user_id);
                    $rs_mahasiswa = ApiKelompokModel::listKelompokMahasiswa($kelompok->id_kelompok);

                    foreach ($rs_mahasiswa as $key => $mahasiswa) {
                        // Jika status individu bukan "menyetujui kelompok", set variabel $semuaSetuju menjadi false
                        if ($mahasiswa->status_individu !== "Menyetujui Kelompok!") {
                            $semuaSetuju = false;
                            // Jika salah satu mahasiswa tidak setuju, Anda bisa langsung keluar dari loop
                            break;
                        }
                    }

                    // Jika semua mahasiswa setuju dengan kelompok, lakukan aksi
                    if ($semuaSetuju) {
                        $paramKelompok = [
                            "status_kelompok" => "Menunggu Validasi Kelompok!",
                        ];
                        $update_kelompok = ApiKelompokModel::updateKelompok($kelompok ->id, $paramKelompok);
                    }
                }
                $response = $this->successResponse('Berhasil menyetujui kelompok!', 'Berhasil!');

            } catch (\Exception $e) {
                dd($e);
                $response = $this->failureResponse('Gagal menyetujui!');
            }
        } else {
            $response = $this->failureResponse('Pengguna tidak ditemukan!');
        }
        return response()->json($response);
    }



    private function getProfileImageUrl($user)
    {
        if (!empty($user->user_img_name)) {
            $imageUrl = url($user->user_img_path . $user->user_img_name);
        } else {
            $imageUrl = url('img/user/defff.jpg');
        }

        return $imageUrl;
    }

     // Fungsi untuk menangani respons sukses
     private function successResponse($statusMessage, $data)
     {
         return [
             'success' => true,
             'status' => $statusMessage,
             'data' => $data,
         ];
     }

     // Fungsi untuk menangani respons kegagalan
     private function failureResponse($statusMessage, $data = null)
     {
         return [
             'success' => false,
             'status' => $statusMessage,
             'data' => $data,
         ];
     }

}
