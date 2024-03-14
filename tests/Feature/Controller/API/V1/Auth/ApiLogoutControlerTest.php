<?php

namespace Tests\Feature\Controller\API\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiLogoutControlerTest extends TestCase
{
    public function testLogoutWithValidToken()
    {
        // Assume you have a valid token for testing purposes
        $validToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0L2NhcHN0b25lX3RlYW0vcHVibGljL2FwaS92MS9hdXRoL2xvZ2luIiwiaWF0IjoxNzEwMDA2MjAxLCJleHAiOjE3MTA2MTEwMDEsIm5iZiI6MTcxMDAwNjIwMSwianRpIjoiUHlFdncxaFNpUUJHVUZISCIsInN1YiI6IjE3MDY5MjUyMzgzMjk2IiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.P7IVoq_OCmq-7Uyxp8fsN7-rWd7HVDLncF3zf4f1epM';

        // Mock the request with the valid token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $validToken,
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
