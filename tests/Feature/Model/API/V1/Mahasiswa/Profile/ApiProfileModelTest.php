<?php

namespace Tests\Unit\Models\Api\Mahasiswa\Profile;

use Tests\TestCase;
use App\Models\Api\Mahasiswa\Profile\ApiProfileModel;

class ApiProfileModelTest extends TestCase
{

    protected $token;
    protected $user_id;

    protected function setUp(): void
    {
        parent::setUp();

        // Login untuk mendapatkan token
        $loginPayload = [
            'nomor_induk' => '21120120130125',
            'password' => 'mahasiswa123',
        ];

        $loginResponse = $this->json('POST', '/api/v1/auth/login/', $loginPayload);

        // Ambil token dari respons login
        $this->token = $loginResponse->json('data.api_token');
        $this->user_id = $loginResponse->json('data.user_id');
    }

    /** @test */
    public function test_it_can_get_data_by_id()
    {

        // Panggil method getById dari ApiProfileModel
        $data = ApiProfileModel::getById($this->user_id);

        // Pastikan data tidak kosong
        $this->assertNotNull($data);

        // Pastikan data adalah instance dari objek
        $this->assertIsObject($data);
    }

    /** @test */
    public function test_it_can_update_data()
    {
        // Parameter untuk update data
        $params = [
            'user_name' => 'New Username', // Ganti dengan nilai yang diharapkan
            // Tambahkan parameter lain jika diperlukan
        ];

        // Panggil method update dari ApiProfileModel
        $result = ApiProfileModel::update($this->user_id, $params);

        // Pastikan hasilnya adalah true atau tidak false
        $this->assertTrue($result !== false);
    }
}
