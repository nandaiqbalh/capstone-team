<?php

namespace Tests\Feature\Controller\API\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiLogoutControlerTest extends TestCase
{

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Melakukan login untuk mendapatkan token
        $loginPayload = [
            'nomor_induk' => '21120120130058',
            'password' => 'mahasiswa123',
        ];

        $loginResponse = $this->json('POST', '/api/v1/auth/login/', $loginPayload);

        // Mengambil token dari response login
        $this->token = $loginResponse->json('data.api_token');
    }

    public function testLogoutWithValidToken()
    {
        // Assume you have a valid token for testing purposes

        // Mock the request with the valid token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->json('GET', '/api/v1/auth/logout');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'status' => 'Berhasil keluar!',
                ]);
    }

    public function testLogoutWithInvalidToken()
    {
        // Assume you have an invalid token for testing purposes
        $invalidToken = 'your_invalid_token_here';

        // Mock the request with the invalid token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $invalidToken,
        ])->json('GET', '/api/v1/auth/logout');

        $response->assertStatus(200)
        ->assertJson([
            'status' => 'Token is Invalid',
        ]);
    }
}
