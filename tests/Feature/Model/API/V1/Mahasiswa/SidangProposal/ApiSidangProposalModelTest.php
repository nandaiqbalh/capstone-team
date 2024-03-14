<?php

namespace Tests\Unit\Models\Api\Mahasiswa\SidangProposal;

use Tests\TestCase;
use App\Models\Api\Mahasiswa\SidangProposal\ApiSidangProposalModel;

class ApiSidangProposalModelTest extends TestCase
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
    public function test_it_can_get_sidang_proposal_by_kelompok()
    {
        // Isi dengan ID kelompok yang valid
        $idKelompok = 40;

        // Panggil method sidangProposalByKelompok dari ApiSidangProposalModel
        $data = ApiSidangProposalModel::sidangProposalByKelompok($idKelompok);

        // Pastikan data tidak kosong
        $this->assertNotNull($data);

        // Pastikan data adalah instance dari objek
        $this->assertIsObject($data);
    }

    /** @test */
    public function test_it_can_do_pengecekan_kelompok_mahasiswa()
    {
        // Panggil method pengecekan_kelompok_mahasiswa dari ApiSidangProposalModel
        $data = ApiSidangProposalModel::pengecekan_kelompok_mahasiswa($this->user_id);

        // Pastikan data tidak kosong
        $this->assertNotNull($data);

        // Pastikan data adalah instance dari objek
        $this->assertIsObject($data);
    }

    /** @test */
    public function test_it_can_check_if_siklus_still_active()
    {
        $id_siklus = 8;

        // Panggil method checkApakahSiklusMasihAktif dari ApiSidangProposalModel
        $data = ApiSidangProposalModel::checkApakahSiklusMasihAktif($id_siklus);

        // Pastikan data tidak kosong
        $this->assertNotNull($data);

        // Pastikan data adalah instance dari objek
        $this->assertIsObject($data);
    }
}
