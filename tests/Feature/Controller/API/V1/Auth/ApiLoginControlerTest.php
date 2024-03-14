<?php

namespace Tests\Feature\Controller\API\V1\Auth;

use Tests\TestCase;
use App\Models\User;

class ApiLoginControllerTest extends TestCase
{
    public function testAuthenticationWithValidCredentials()
    {
        $payload = [
            'nomor_induk' => '21120120130125',
            'password' => 'mahasiswa123',
        ];

        $response = $this->json('POST', '/api/v1/auth/login/', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data' => [
                    'user_id',
                    'role_id',
                    'user_name',
                    'user_email',
                    'user_active',
                    'user_img_path',
                    'user_img_name',
                    'nomor_induk',
                    'no_telp',
                    'angkatan',
                    'ipk',
                    'sks',
                    'jenis_kelamin',
                    'alamat',
                    'created_by',
                    'created_date',
                    'modified_by',
                    'modified_date',
                    'api_token',
                    'user_img_url',
                ],
            ])
            ->assertJson([
                'success' => true,
                'status' => 'Authentikasi berhasil.',
            ]);
    }


    public function testAuthenticationWithInvalidCredentials()
    {
        $payload = [
            'nomor_induk' => '12123123',
            'password' => 'invalid_password',
        ];

        $response = $this->json('POST', '/api/v1/auth/login/', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data',
            ])
            ->assertJson([
                'success' => false,
                'status' => 'Nomor Induk atau Password tidak valid.',
                'data' => null,
            ]);
    }

    public function testInvalidUrl()
    {
        $response = $this->json('POST', '/api/v1/auth/invalid_url');

        $response->assertStatus(404); // Adjust based on your actual error status code

    }
}
