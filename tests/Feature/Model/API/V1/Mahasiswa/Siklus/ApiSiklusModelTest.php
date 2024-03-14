<?php

namespace Tests\Unit\Models\Api\Mahasiswa\Siklus;

use Tests\TestCase;
use App\Models\Api\Mahasiswa\Siklus\ApiSiklusModel;

class ApiSiklusModelTest extends TestCase
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

        // Panggil method getById dari ApiSiklusModel
        $data = ApiSiklusModel::getAkunByID($this->user_id);

        // Pastikan data tidak kosong
        $this->assertNotNull($data);

        // Pastikan data adalah instance dari objek
        $this->assertIsObject($data);
    }

    /** @test */
    public function test_it_can_get_active_siklus()
    {
        // Panggil method getSiklusAktif dari ApiSiklusModel
        $data = ApiSiklusModel::getSiklusAktif();

        // Pastikan data tidak kosong
        $this->assertNotEmpty($data);

        // Pastikan data adalah instance dari koleksi (collection)
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $data);


    }

    /** @test */
    public function test_it_can_get_periode_pendaftaran_siklus()
    {
        // Panggil method getPeriodePendaftaranSiklus dari ApiSiklusModel
        $data = ApiSiklusModel::getPeriodePendaftaranSiklus();

        // Pastikan data tidak kosong
        $this->assertNotEmpty($data);

        // Pastikan data adalah instance dari koleksi (collection)
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $data);


    }
}
