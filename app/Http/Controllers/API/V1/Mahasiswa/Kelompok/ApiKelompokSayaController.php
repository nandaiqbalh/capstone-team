<?php

namespace App\Http\Controllers\API\V1\Mahasiswa\Kelompok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Mahasiswa\Profile\ApiAccountModel;
use App\Models\Api\Mahasiswa\Kelompok\ApiKelompokSayaModel;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class ApiKelompokSayaController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Check if the token is still valid
            JWTAuth::checkOrFail();

            // Get the user from the JWT token in the request headers
            $jwtUser = JWTAuth::parseToken()->authenticate();

            $user = ApiAccountModel::getById($jwtUser->user_id);

            // Check if the user exists
            if ($user != null && $user->user_active == 1) {
                // Data
                try {
                    // get data kelompok
                    $kelompok = ApiKelompokSayaModel::pengecekan_kelompok_mahasiswa($user->user_id);

                    // dd($kelompok);
                    $getAkun = ApiKelompokSayaModel::getAkunByID($user->user_id);

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

                        $response = [
                            'status' => 'Belum mendaftar capstone',
                            'success' => false,
                            'data' => $data,
                        ];

                        // dd($data);

                    } else {
                        // sudah mendaftar kelompok (baik secara individu maupun secara kelompok)

                        // check apakah siklusnya masih aktif atau tidak
                        $isSiklusAktif = ApiKelompokSayaModel::checkApakahSiklusMasihAktif($kelompok -> id_siklus);
                        if($isSiklusAktif == null){
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

                            $response = [
                                'status' => 'Siklus capstone sudah tidak aktif',
                                'success' => false,
                                'data' => $data,
                            ];
                        } else {

                            // mahasiswa
                        $rs_mahasiswa = ApiKelompokSayaModel::listKelompokMahasiswa($kelompok->id_kelompok);

                        foreach ($rs_mahasiswa as $key => $mahasiswa) {
                            $userImageUrl = $this->getProfileImageUrl($mahasiswa);
                            // Add the user_img_url to the user object
                            $mahasiswa->user_img_url = $userImageUrl;
                        }

                        // dosbing
                        $rs_dosbing = ApiKelompokSayaModel::getAkunDosbingKelompok($kelompok->id_kelompok);
                        foreach ($rs_dosbing as $key => $dosbing) {
                            $userImageUrl = $this->getProfileImageUrl($dosbing);
                            // Add the user_img_url to the user object
                            $dosbing->user_img_url = $userImageUrl;
                        }

                        $rs_dospeng = ApiKelompokSayaModel::getAkunDospengKelompok($kelompok->id_kelompok);
                        foreach ($rs_dospeng as $key => $dospeng) {
                            $userImageUrl = $this->getProfileImageUrl($dospeng);
                            // Add the user_img_url to the user object
                            $dospeng->user_img_url = $userImageUrl;
                        }

                        $rs_dospeng_ta = ApiKelompokSayaModel::getAkunDospengTa($user -> user_id);
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

                        $response = [
                            'status' => 'Berhasil mendapatkan data.',
                            'success' => true,
                            'data' => $data,
                        ];
                        }
                    }


                } catch (\Exception $e) {
                    $response = [
                        'status' => 'Gagal mendaftar capstone!' ,
                        'success' => false,
                        'data' => null,
                    ];
                }
            } else {
                $response = [
                    'status' => 'Pengguna tidak ditemukan!',
                    'success' => false,
                    'data' => null,
                ];
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $response = [
                'status' =>'Gagal mendaftar capstone!' ,
                'success' => false,
                'data' => null,
            ];
        }

        return response()->json($response);
    }

    public function updateStatusKelompokForward()
    {
        try {
            // Check if the token is still valid
            JWTAuth::checkOrFail();

            // Get the user from the JWT token in the request headers
            $jwtUser = JWTAuth::parseToken()->authenticate();

            $user = ApiAccountModel::getById($jwtUser->user_id);

            // Check if the user exists and is active
            if ($user && $user->user_active == 1) {
                // Retrieve kelompok data
                try {
                    $kelompok = ApiKelompokSayaModel::pengecekan_kelompok_mahasiswa($user->user_id);

                    if ($kelompok == null) {
                        // User doesn't have a kelompok
                        $response = [
                            'status' => 'Belum mendaftar capstone!',
                            'success' => false,
                            'data' => null,
                        ];
                    } else {
                        // Check if the capstone cycle is still active
                        $isSiklusAktif = ApiKelompokSayaModel::checkApakahSiklusMasihAktif($kelompok->id_siklus);

                        if ($isSiklusAktif == null) {
                            // Capstone cycle is no longer active
                            $kelompok->id_siklus = 0;
                            $response = [
                                'status' => 'Siklus capstone sudah tidak aktif',
                                'success' => false,
                                'data' => null,
                            ];
                        } else {
                            // Attempt to update kelompok status
                            if ($this->updateStatusForward($kelompok)) {
                                // Retrieve updated kelompok data
                                $kelompokUpdated = ApiKelompokSayaModel::pengecekan_kelompok_mahasiswa($user->user_id);

                                // Data response
                                $data = [
                                    'kelompok' => $kelompokUpdated,
                                ];

                                // Response message
                                $response = [
                                    'status' => 'Berhasil mengubah status kelompok!',
                                    'success' => true,
                                    'data' => $data,
                                ];
                            } else {
                                $response = [
                                    'status' => 'Gagal mengubah status kelompok. Status saat ini tidak valid untuk operasi ini.',
                                    'success' => false,
                                    'data' => null,
                                ];
                            }
                        }
                    }
                } catch (\Exception $e) {
                    $response = [
                        'status' => 'Gagal mendapatkan data!',
                        'success' => false,
                        'data' => null,
                    ];
                }
            } else {
                $response = [
                    'status' => 'Pengguna tidak ditemukan atau tidak aktif!',
                    'success' => false,
                    'data' => null,
                ];
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $response = [
                'status' => 'Gagal mendapatkan data!',
                'success' => false,
                'data' => null,
            ];
        }

        return response()->json($response);
    }

    public function updateStatusKelompokBackward()
    {
        try {
            // Check if the token is still valid
            JWTAuth::checkOrFail();

            // Get the user from the JWT token in the request headers
            $jwtUser = JWTAuth::parseToken()->authenticate();

            $user = ApiAccountModel::getById($jwtUser->user_id);

            // Check if the user exists and is active
            if ($user && $user->user_active == 1) {
                // Retrieve kelompok data
                try {
                    $kelompok = ApiKelompokSayaModel::pengecekan_kelompok_mahasiswa($user->user_id);

                    if ($kelompok == null) {
                        // User doesn't have a kelompok
                        $response = [
                            'status' => 'Belum mendaftar capstone!',
                            'success' => false,
                            'data' => null,
                        ];
                    } else {
                        // Check if the capstone cycle is still active
                        $isSiklusAktif = ApiKelompokSayaModel::checkApakahSiklusMasihAktif($kelompok->id_siklus);

                        if ($isSiklusAktif == null) {
                            // Capstone cycle is no longer active
                            $kelompok->id_siklus = 0;
                            $response = [
                                'status' => 'Siklus capstone sudah tidak aktif',
                                'success' => false,
                                'data' => null,
                            ];
                        } else {
                            // Attempt to update kelompok status forward
                            if ($this->updateStatusBackward($kelompok)) {
                                // Retrieve updated kelompok data
                                $kelompokUpdated = ApiKelompokSayaModel::pengecekan_kelompok_mahasiswa($user->user_id);

                                // Data response
                                $data = [
                                    'kelompok' => $kelompokUpdated,
                                ];

                                // Response message
                                $response = [
                                    'status' => 'Berhasil mengubah status kelompok!',
                                    'success' => true,
                                    'data' => $data,
                                ];
                            } else {
                                $response = [
                                    'status' => 'Gagal mengubah status kelompok. Status saat ini tidak valid untuk operasi ini.',
                                    'success' => false,
                                    'data' => null,
                                ];
                            }
                        }
                    }
                } catch (\Exception $e) {
                    $response = [
                        'status' => 'Gagal mendapatkan data!',
                        'success' => false,
                        'data' => null,
                    ];
                }
            } else {
                $response = [
                    'status' => 'Pengguna tidak ditemukan atau tidak aktif!',
                    'success' => false,
                    'data' => null,
                ];
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $response = [
                'status' => 'Gagal mendapatkan data!',
                'success' => false,
                'data' => null,
            ];
        }

        return response()->json($response);
    }

    public function addKelompokProcess(Request $request)
    {
        // Check if the token is still valid
        JWTAuth::checkOrFail();

        // Get the user from the JWT token in the request headers
        $jwtUser = JWTAuth::parseToken()->authenticate();

        $user = ApiAccountModel::getById($jwtUser->user_id);

        // Check if the user exists
        if ($user != null && $user->user_active == 1) {
            // validation params
            $requiredParams = ['angkatan', 'email', 'jenis_kelamin', 'ipk', 'sks', 'no_telp', 'id_siklus', 's', 'e', 'c', 'm', 'ews', 'bac', 'smb', 'smc'];
            foreach ($requiredParams as $param) {
                if (!$request->has($param) || empty($request->input($param))) {
                    $response = [
                        'success' => false,
                        'status' => "Parameter '$param' kosong atau belum diisi.",
                        'data' => null,
                    ];
                    return response()->json($response); // 400 Bad Request
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
                $update_mahasiswa = ApiKelompokSayaModel::updateMahasiswa($user->user_id, $params);

                if ($update_mahasiswa) {
                    // Retrieve topik and peminatan data
                    $topik1 = ApiKelompokSayaModel::getTopikById($request->ews);
                    $topik2 = ApiKelompokSayaModel::getTopikById($request->bac);
                    $topik3 = ApiKelompokSayaModel::getTopikById($request->smb);
                    $topik4 = ApiKelompokSayaModel::getTopikById($request->smc);

                    $peminatan1 = ApiKelompokSayaModel::getPeminatanById($request->s);
                    $peminatan2 = ApiKelompokSayaModel::getPeminatanById($request->e);
                    $peminatan3 = ApiKelompokSayaModel::getPeminatanById($request->c);
                    $peminatan4 = ApiKelompokSayaModel::getPeminatanById($request->m);

                    // Insert kelompok data
                    $params2 = [
                        'usulan_judul_capstone' => $request->judul_capstone,
                        'id_siklus' => $request->id_siklus,
                        'id_mahasiswa' => $user->user_id,
                        'status_individu' => 'Pendaftaran Capstone Sedang Divalidasi',
                        'id_topik_individu1' => $topik1->id,
                        'id_topik_individu2' => $topik2->id,
                        'id_topik_individu3' => $topik3->id,
                        'id_topik_individu4' => $topik4->id,
                        'id_peminatan_individu1' => $peminatan1->id,
                        'id_peminatan_individu2' => $peminatan2->id,
                        'id_peminatan_individu3' => $peminatan3->id,
                        'id_peminatan_individu4' => $peminatan4->id,
                    ];

                    ApiKelompokSayaModel::insertKelompokMHS($params2);

                    $response = [
                        'success' => true,
                        'status' => 'Berhasil mendaftar capstone!',
                        'data' => null,
                    ];
                    // response
                    return response()->json($response);
                } else {
                    // response
                    $response = [
                        'success' => false,
                        'status' => 'Gagal mendaftar capstone!',
                        'data' => null,
                    ];
                    return response()->json($response);
                }
            } catch (\Exception $e) {
                // response for unexpected errors
                $response = [
                    'status' =>'Gagal mendaftar capstone!' ,
                    'success' => false,
                    'data' => null,
                ];
                return response()->json($response);
            }
        } else {
            // User not found or api_token is null
            $response = [
                'success' => false,
                'status' => 'Pengguna tidak ditemukan!',
                'data' => null,
            ];
            return response()->json($response); // 401 Unauthorized
        }
    }


    public function addPunyaKelompokProcess(Request $request)
    {
        // Check if the token is still valid
        JWTAuth::checkOrFail();

        // Get the user from the JWT token in the request headers
        $jwtUser = JWTAuth::parseToken()->authenticate();

        $user = ApiAccountModel::getById($jwtUser->user_id);

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
                    $response = [
                        'status' => "Parameter '$param' kosong atau belum diisi.",
                        'success' => false,
                        'data' => null,
                    ];
                    return response()->json($response); // 400 Bad Request
                }
            }

            if ($request->dosbing_1 == $request->dosbing_2) {
                $response = [
                    'status' => "Dosen pembimbing tidak boleh sama!",
                    'success' => false,
                    'data' => null,
                ];
                return response()->json($response); // 400 Bad Request
            }

            if ($request->user_id1 == $request->user_id2 || $request->user_id1 == $request->user_id3 || $request->user_id2 == $request->user_id3) {
                $response = [
                    'status' => 'Mahasiswa tidak boleh sama!',
                    'success' => false,
                    'data' => null,
                ];
                return response()->json($response); // 400 Bad Request
            }

            try {

                if (ApiKelompokSayaModel::isAccountExist($request -> user_id2) && ApiKelompokSayaModel::isAccountExist($request ->user_id3)) {
                    // addKelompok
                    $params = [
                        "id_siklus" => $request->id_siklus,
                        "judul_capstone" => $request->judul_capstone,
                        "id_topik" => $request->id_topik,
                        "status_kelompok" => 'Pendaftaran Capstone Sedang Divalidasi',
                        "id_dosen_pembimbing_1" => $request->dosbing_1,
                        "status_dosen_pembimbing_1" =>'Menunggu Persetujuan',
                        "id_dosen_pembimbing_2" => $request->dosbing_2,
                        "status_dosen_pembimbing_2" =>'Menunggu Persetujuan',
                    ];

                    ApiKelompokSayaModel::insertKelompok($params);
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
                    $update_mahasiswa1 = ApiKelompokSayaModel::updateMahasiswa($user->user_id, $params1);
                    if ($update_mahasiswa1) {
                        $params11 = [
                            "id_siklus" => $request->id_siklus,
                            'id_kelompok' => $id_kelompok,
                            'id_mahasiswa' => $user->user_id,
                            'status_individu' => 'Pendaftaran Capstone Sedang Divalidasi',
                            'usulan_judul_capstone' => $request -> judul_capstone,
                            'id_topik_mhs' => $request->id_topik,
                            'created_by'   => $user->user_id,
                            'created_date'  => date('Y-m-d H:i:s')
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
                        'modified_by'   => $user->user_id,
                        'modified_date'  => date('Y-m-d H:i:s')
                    ];

                    // process
                    $update_mahasiswa2 = ApiKelompokSayaModel::updateMahasiswa($request->user_id2, $params2);
                    if ($update_mahasiswa2) {
                        $params22 = [
                            "id_siklus" => $request->id_siklus,
                            'id_kelompok' => $id_kelompok,
                            'id_mahasiswa' => $request->user_id2,
                            'status_individu' => 'Pendaftaran Capstone Sedang Divalidasi',
                            'usulan_judul_capstone' => $request -> judul_capstone,
                            'id_topik_mhs' => $request->id_topik,
                            'created_by'   => $user->user_id,
                            'created_date'  => date('Y-m-d H:i:s')
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
                        'modified_by'   => $user->user_id,
                        'modified_date'  => date('Y-m-d H:i:s')
                    ];

                    // process
                    $update_mahasiswa3 = ApiKelompokSayaModel::updateMahasiswa($request->user_id3, $params3);
                    if ($update_mahasiswa3) {
                        $params33 = [
                            "id_siklus" => $request->id_siklus,
                            'id_kelompok' => $id_kelompok,
                            'id_mahasiswa' => $request->user_id3,
                            'status_individu' => 'Pendaftaran Capstone Sedang Divalidasi',
                            'usulan_judul_capstone' => $request -> judul_capstone,
                            'id_topik_mhs' => $request->id_topik,
                            'created_by'   => $user->user_id,
                            'created_date'  => date('Y-m-d H:i:s')
                        ];
                        ApiKelompokSayaModel::insertKelompokMHS($params33);
                    }

                    $response = [
                        'success' => true,
                        'status' => 'Berhasil mendaftar capstone!',
                        'data' => null,
                    ];
                    // response
                    return response()->json($response);

                    } else {
                        // ada pengguna yang tidak valid
                        $response = [
                            'success' => false,
                            'status' => 'Pastikan semua mahasiswa merupakan mahasiswa aktif!',
                            'data' => null,
                        ];

                        return response()->json($response);
                    }

            } catch (\Exception $e) {
                // response for unexpected errors
                $response = [
                    'success' => false,
                    'status' =>'Gagal mendaftar capstone!' ,
                    'data' => null,
                ];

                return response()->json($response);
            }
        } else {
            // User not found or api_token is null
            $response = [
                'success' => false,
                'status' => 'Pengguna tidak ditemukan!',
                'data' => null,
            ];
            return response()->json($response); // 401 Unauthorized
        }
    }

    private function updateStatusForward($kelompok)
    {
        $statusMapping = [
            'Pendaftaran Capstone Valid' => 'Mengerjakan Dokumen C100',
            'Mengerjakan Dokumen C100' => 'Dokumen C100 Disetujui Dosen Pembimbing',
            'Dijadwalkan Sidang Proposal' => 'Lulus Sidang Proposal',
            'Lulus Sidang Proposal' => 'Dokumen C100 Valid Setelah Sidang',
            'Dokumen C100 Valid Setelah Sidang' => 'Pengerjaan Dokumen C200 dan Dokumen C300',
            'Pengerjaan Dokumen C200 dan Dokumen C300' => 'Dokumen C200 dan C300 Disetujui Dosen Pembimbing',
            'Dokumen C200 dan C300 Valid' => 'Mengerjakan Project',
            'Mengerjakan Project' => 'Project Disetujui Dosen Pembimbing',
            'Project Disetujui Dosen Pembimbing' => 'Mengerjakan C400',
            'Mengerjakan C400' => 'Dokumen C400 Disetujui Dosen Pembimbing',
            'Dokumen C400 Disetujui Dosen Pembimbing' => 'Mengerjakan C500',
            'Mengerjakan C500' => 'Dokumen C500 Disetujui Dosen Pembimbing',
            'Dokumen C500 Disetujui Dosen Pembimbing' => 'Dalam Peninjauan Pendaftaran Expo',
            'Disetujui Mengikuti Expo' => 'Lulus Expo',
        ];

        if (array_key_exists($kelompok->status_kelompok, $statusMapping)) {
            $newStatus = $statusMapping[$kelompok->status_kelompok];

            // Update status kelompok
            ApiKelompokSayaModel::updateKelompokById($kelompok->id, ['status_kelompok' => $newStatus]);

            // Update status individu for each member in the kelompokMhs collection
            $statusIndividuMapping = [
                'Pendaftaran Capstone Valid' => 'Mengerjakan Capstone',
            ];

            $kelompokMhs = ApiKelompokSayaModel::getAllKelompokMhs($kelompok->id);

            foreach ($kelompokMhs as $anggota) {
                if (array_key_exists($anggota->status_individu, $statusIndividuMapping)) {
                    $newStatusIndividu = $statusIndividuMapping[$anggota->status_individu];

                    // Update status individu for each member
                    ApiKelompokSayaModel::updateKelompokMhs($kelompok->id, ['status_individu' => $newStatusIndividu]);
                }
            }

            return true;
        }

        return false;
    }


    private function updateStatusBackward($kelompok)
    {
        $statusMapping = [
            'Mengerjakan Dokumen C100' => 'Pendaftaran Capstone Valid',
            'Dokumen C100 Disetujui Dosen Pembimbing' => 'Mengerjakan Dokumen C100',
            'Lulus Sidang Proposal' => 'Dijadwalkan Sidang Proposal',
            'Dokumen C100 Valid Setelah Sidang' => 'Lulus Sidang Proposal',
            'Pengerjaan Dokumen C200 dan Dokumen C300' => 'Dokumen C100 Valid Setelah Sidang',
            'Dokumen C200 dan C300 Disetujui Dosen Pembimbing' => 'Pengerjaan Dokumen C200 dan Dokumen C300',
            'Mengerjakan Project' => 'Dokumen C200 dan C300 Valid',
            'Project Disetujui Dosen Pembimbing' => 'Mengerjakan Project',
            'Mengerjakan C400' => 'Project Disetujui Dosen Pembimbing',
            'Dokumen C400 Disetujui Dosen Pembimbing' => 'Mengerjakan C400',
            'Mengerjakan C500' => 'Dokumen C400 Disetujui Dosen Pembimbing',
            'Dokumen C500 Disetujui Dosen Pembimbing' => 'Mengerjakan C500',
            'Dalam Peninjauan Pendaftaran Expo' => 'Dokumen C500 Disetujui Dosen Pembimbing',
            'Lulus Expo' => 'Disetujui Mengikuti Expo',
        ];

        if (array_key_exists($kelompok->status_kelompok, $statusMapping)) {
            $newStatus = $statusMapping[$kelompok->status_kelompok];

            // Update status kelompok
            ApiKelompokSayaModel::updateKelompokById($kelompok->id, ['status_kelompok' => $newStatus]);

            // Update status individu for each member in the kelompokMhs collection
            $statusIndividuMapping = [
                'Mengerjakan Capstone' => 'Pendaftaran Capstone Valid',
            ];

            $kelompokMhs = ApiKelompokSayaModel::getAllKelompokMhs($kelompok->id);

            foreach ($kelompokMhs as $anggota) {
                if (array_key_exists($anggota->status_individu, $statusIndividuMapping)) {
                    $newStatusIndividu = $statusIndividuMapping[$anggota->status_individu];

                    // Update status individu for each member
                    ApiKelompokSayaModel::updateKelompokMhs($kelompok->id, ['status_individu' => $newStatusIndividu]);
                }
            }

            return true;
        }

        return false;
    }



    private function getProfileImageUrl($user)
    {
        if (!empty($user->user_img_name)) {
            $imageUrl = url($user->user_img_path . $user->user_img_name);
        } else {
            $imageUrl = url('img/user/default_profile.jpg');
        }

        return $imageUrl;
    }

}
