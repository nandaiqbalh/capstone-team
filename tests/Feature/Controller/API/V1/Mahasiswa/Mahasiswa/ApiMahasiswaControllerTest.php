<?php

namespace Tests\Feature\Api\V1\Mahasiswa\Mahasiswa;

use Tests\TestCase;
use App\Models\Api\Mahasiswa\Mahasiswa\ApiMahasiswaModel;
use Illuminate\Foundation\Testing\WithFaker;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class ApiMahasiswaControllerTest extends TestCase
{
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        // Melakukan login untuk mendapatkan token
        $loginPayload = [
            'nomor_induk' => '21120120130124',
            'password' => 'mahasiswa123',
        ];

        $loginResponse = $this->json('POST', '/api/v1/auth/login/', $loginPayload);

        // Mengambil token dari response login
        $this->token = $loginResponse->json('data.api_token');
    }

    /** @test */
    public function test_it_returns_data_mahasiswa_when_authenticated_user_is_active()
    {
        // Hit the endpoint with the generated token
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('GET', '/api/v1/mahasiswa/data-mahasiswa');

        // Assert the response is successful and has the expected structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data' => [
                    'rs_mahasiswa',
                ],
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_is_not_authenticated()
    {
        // Hit the endpoint without a token
        $response = $this->json('GET', '/api/v1/mahasiswa/data-mahasiswa');

        // Assert the response indicates failure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_inactive()
    {
        // Menonaktifkan pengguna yang sedang diuji
        $user = User::where('nomor_induk', '21120120130124')->first();
        $user->update(['user_active' => 0], ['timestamps' => false]);

        // Mengirimkan permintaan API untuk mendapatkan jadwal data-mahasiswa
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/data-mahasiswa/');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);

        // Mengaktifkan kembali pengguna setelah pengujian selesai
        $user->update(['user_active' => 1], ['timestamps' => false]);
    }


    /** @test */
    public function test_it_returns_not_found_response_for_invalid_url()
    {
        // Hit the endpoint with an invalid URL
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('GET', '/api/v1/mahasiswa/invalid-url');

        // Assert the response indicates that the URL is not found
        $response->assertStatus(404);
    }

    /** @test */
    public function test_it_returns_method_not_allowed_response_for_invalid_method()
    {
        // Hit the endpoint with an invalid method (POST instead of GET)
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('POST', '/api/v1/mahasiswa/data-mahasiswa');

        // Assert the response indicates that the method is not allowed
        $response->assertStatus(405);
    }

    /** @test */
    public function test_it_returns_invalid_token_response_when_invalid_token_provided()
    {
        // Hit the endpoint with an invalid token
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->json('GET', '/api/v1/mahasiswa/data-mahasiswa');

        // Assert the response indicates that the token is invalid
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Token is Invalid'
            ]);
    }

    /** @test */
    public function test_it_returns_token_not_found_response_when_no_token_provided()
    {
        // Hit the endpoint without providing a token
        $response = $this->json('GET', '/api/v1/mahasiswa/data-mahasiswa');

        // Assert the response indicates that the token is not found
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Authorization Token not found'
            ]);
    }

}
