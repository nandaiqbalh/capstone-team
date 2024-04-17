<?php

namespace Tests\Unit\Models\Api\Mahasiswa\TugasAkhir;

use Tests\TestCase;
use App\Models\Api\Mahasiswa\TugasAkhir\ApiTugasAkhirModel;

class ApiTugasAkhirModelTest extends TestCase
{

    protected $token;
    protected $user_id;

    protected function setUp(): void
    {
        parent::setUp();

        // Login untuk mendapatkan token
        $loginPayload = [
            'nomor_induk' => '21120120130058',
            'password' => 'mahasiswa123',
        ];

        $loginResponse = $this->json('POST', '/api/v1/auth/login/', $loginPayload);

        // Ambil token dari respons login
        $this->token = $loginResponse->json('data.api_token');
        $this->user_id = $loginResponse->json('data.user_id');
    }

    /** @test */
    public function test_it_can_get_data()
    {
        // Panggil method getData dari ApiTugasAkhirModel
        $data = ApiTugasAkhirModel::getData();

        // Pastikan data tidak kosong
        $this->assertNotEmpty($data);

        // Pastikan data adalah instance dari koleksi (collection)
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $data);

    }

    /** @test */
    public function test_it_can_get_sidang_tugas_akhir_by_mahasiswa()
    {

        // Panggil method sidangTugasAkhirByMahasiswa dari ApiTugasAkhirModel
        $data = ApiTugasAkhirModel::sidangTugasAkhirByMahasiswa($this->user_id);

        // Pastikan data tidak kosong
        $this->assertNotNull($data);

        // Pastikan data adalah instance dari objek
        $this->assertIsObject($data);
    }

    /** @test */
    public function test_it_can_update_kelompok_mhs()
    {
        // Parameter untuk update
        $params = [
            'id_mahasiswa' => $this->user_id
        ];

        // Panggil method updateKelompokMHS dari ApiTugasAkhirModel
        $result = ApiTugasAkhirModel::updateKelompokMHS($this->user_id, $params);

        // Pastikan hasilnya adalah true atau tidak false
        $this->assertTrue($result !== false);
    }

    /** @test */
    public function test_it_can_cek_status_pendaftaran_sidang_ta()
    {
        // Panggil method cekStatusPendaftaranSidangTA dari ApiTugasAkhirModel
        $data = ApiTugasAkhirModel::cekStatusPendaftaranSidangTA($this->user_id);

        // Pastikan data tidak kosong
        $this->assertNotNull($data);

        // Pastikan data adalah instance dari objek
        $this->assertIsObject($data);
    }

    /** @test */
    public function test_it_can_get_periode_available()
    {
        // Panggil method getPeriodeAvailable dari ApiTugasAkhirModel
        $data = ApiTugasAkhirModel::getPeriodeAvailable();

        // Pastikan data tidak kosong
        $this->assertNotNull($data);

        // Pastikan data adalah instance dari objek
        $this->assertIsObject($data);
    }

    /** @test */
    public function test_it_can_get_status_pendaftaran()
    {

        // Panggil method getStatusPendaftaran dari ApiTugasAkhirModel
        $data = ApiTugasAkhirModel::getStatusPendaftaran($this->user_id);

        // Pastikan data tidak kosong
        $this->assertNotNull($data);

        // Pastikan data adalah instance dari objek
        $this->assertIsObject($data);
    }

    /** @test */
    public function test_it_can_do_pengecekan_kelompok_mahasiswa()
    {

        // Panggil method pengecekan_kelompok_mahasiswa dari ApiTugasAkhirModel
        $data = ApiTugasAkhirModel::pengecekan_kelompok_mahasiswa($this->user_id);

        // Pastikan data tidak kosong
        $this->assertNotNull($data);

        // Pastikan data adalah instance dari objek
        $this->assertIsObject($data);
    }
}
