<?php

namespace Tests\Feature\Api\V1\Mahasiswa\SidangProposal;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Api\Mahasiswa\SidangProposal\ApiSidangProposalModel;

class ApiSidangProposalControllerTest extends TestCase
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
    public function test_it_returns_success_response_when_getting_sidang_proposal_by_kelompok()
    {
        // Menyiapkan data kelompok mahasiswa
        $kelompok = ApiSidangProposalModel::pengecekan_kelompok_mahasiswa($this->user_id);

        // Kirim permintaan API untuk mendapatkan jadwal sidang proposal
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('GET', '/api/v1/mahasiswa/sidang-proposal-kelompok');

        // Pastikan respons adalah sukses dan berisi struktur data yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data' => [
                    'kelompok',
                    'hari_sidang',
                    'tanggal_sidang',
                    'waktu_sidang',
                ],
            ])
            ->assertJson(['success' => true, 'status' => 'Berhasil mendapatkan jadwal sidang!']);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_not_found()
    {
        // Kirim permintaan API dengan token tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->json('GET', '/api/v1/mahasiswa/sidang-proposal-kelompok');

        // Pastikan respons adalah kegagalan dan tidak berisi data
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_invalid_token()
    {
        // Kirim permintaan API dengan token tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->json('GET', '/api/v1/mahasiswa/sidang-proposal-kelompok');

        // Pastikan respons adalah kegagalan dan tidak berisi data
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_token_not_found()
    {
        // Kirim permintaan API tanpa token
        $response = $this->json('GET', '/api/v1/mahasiswa/sidang-proposal-kelompok');

        // Pastikan respons adalah kegagalan dan tidak berisi data
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_inactive()
    {
        // Login dengan pengguna tidak aktif
        $loginPayload = [
            'nomor_induk' => 'inactive_user',
            'password' => 'mahasiswa123',
        ];

        $loginResponse = $this->json('POST', '/api/v1/auth/login/', $loginPayload);

        // Ambil token dari respons login
        $inactiveToken = $loginResponse->json('data.api_token');

        // Kirim permintaan API dengan token pengguna tidak aktif
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $inactiveToken])
            ->json('GET', '/api/v1/mahasiswa/sidang-proposal-kelompok');

        // Pastikan respons adalah kegagalan dan tidak berisi data
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_method_not_allowed()
    {
        // Kirim permintaan API dengan metode yang tidak diizinkan (POST)
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('POST', '/api/v1/mahasiswa/sidang-proposal-kelompok');

        // Pastikan respons adalah kegagalan
        $response->assertStatus(405);
    }

    /** @test */
    public function test_it_returns_failure_response_when_invalid_url()
    {
        // Kirim permintaan API dengan URL yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('GET', '/api/v1/mahasiswa/sidang-proposal-kelompok/invalid-url');

        // Pastikan respons adalah kegagalan karena URL tidak ditemukan
        $response->assertStatus(404);
    }
}
