<?php

namespace Tests\Unit\Models\Api\Mahasiswa\Expo;

use App\Models\Api\Mahasiswa\Expo\ApiExpoModel;
use Tests\TestCase;

class ApiExpoModelTest extends TestCase
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
    public function test_it_can_get_data_expo()
    {
        // Panggil method getDataExpo dari ApiExpoModel
        $data = ApiExpoModel::getDataExpo();

        // Pastikan data tidak kosong
        $this->assertNotEmpty($data);

        // Pastikan data adalah instance dari objek
        $this->assertIsObject($data);

    }

    /** @test */
    public function test_it_can_get_kelengkapan_expo()
    {
        // Panggil method kelengkapanExpo dari ApiExpoModel
        $data = ApiExpoModel::kelengkapanExpo($this->user_id);

        // Pastikan data tidak kosong
        $this->assertNotEmpty($data);

        // Pastikan data adalah instance dari objek
        $this->assertIsObject($data);

    }

    /** @test */
    public function test_it_can_get_id_kelompok()
    {
        // Panggil method idKelompok dari ApiExpoModel
        $data = ApiExpoModel::idKelompok($this->user_id);

        // Pastikan data tidak kosong
        $this->assertNotNull($data);

        // Pastikan data adalah tipe yang diharapkan (integer)
        $this->assertIsInt($data);
    }

    /** @test */
    public function test_it_can_update_kelompok_mhs()
    {
        // Parameter untuk diupdate
        $params = [
            'id_mahasiswa' => $this->user_id,
        ];

        // Panggil method updateKelompokMHS dari ApiExpoModel
        $result = ApiExpoModel::updateKelompokMHS($this->user_id, $params);

        // Pastikan hasilnya adalah true atau tidak false
        $this->assertTrue($result !== false);
    }

    /** @test */
    public function test_it_can_do_pengecekan_kelompok_mahasiswa()
    {
        // Panggil method pengecekan_kelompok_mahasiswa dari ApiExpoModel
        $data = ApiExpoModel::pengecekan_kelompok_mahasiswa($this->user_id);

        // Pastikan data tidak kosong
        $this->assertNotEmpty($data);

        // Pastikan data adalah instance dari objek
        $this->assertIsObject($data);
    }

    /** @test */
    public function test_it_can_update_kelompok_by_id()
    {
        // Parameter untuk update data
        $id_kelompok = 40; // Ganti dengan ID kelompok yang valid
        $params = [
            'judul_capstone' => 'Judul Example Testing', // Ganti dengan data yang valid
        ];

        // Panggil method updateKelompokById dari ApiExpoModel
        $result = ApiExpoModel::updateKelompokById($id_kelompok, $params);

        // Pastikan hasilnya adalah true atau tidak false
        $this->assertTrue($result !== false);
    }
}
