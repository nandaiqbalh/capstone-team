<?php

namespace Tests\Feature\Controller\API\V1\Mahasiswa\Broadcast;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiBroadcastControllerTest extends TestCase
{
    /** @test */
    public function test_it_returns_broadcast_data_with_pagination()
    {
        $response = $this->json('GET', '/api/v1/mahasiswa/broadcast/');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'status' => 'Berhasil mendapatkan data.',
                'data' => ['rs_broadcast' => []], // Adjust based on your expected data structure
            ]);
    }

    /** @test */
    public function test_it_returns_broadcast_home_data_with_pagination()
    {
        $response = $this->json('GET', '/api/v1/mahasiswa/broadcast-home/');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'status' => 'Berhasil mendapatkan data.',
                'data' => ['rs_broadcast' => []],
            ]);
    }

    /** @test */
    public function test_it_returns_detail_broadcast_data()
    {
        // Assuming you have a broadcast ID in your database
        $response = $this->json('POST', "/api/v1/mahasiswa/detail-broadcast/", ['id' => 1]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'status' => 'Berhasil mendapatkan data.',
                'data' => ['broadcast' => []], // Adjust based on your expected data structure
            ]);
    }

    /** @test */
    public function test_it_fails_to_return_broadcast_data()
    {
        $response = $this->json('GET', '/api/v1/mahasiswa/broadcast-failure/');

        $response->assertStatus(404); // Adjust based on your actual error status code

    }

    /** @test */
    public function test_it_fails_to_return_broadcast_home_data()
    {
        $response = $this->json('GET', '/api/v1/mahasiswa/broadcast-home-failure/');

        $response->assertStatus(404); // Adjust based on your actual error status code
    }

    /** @test */
    public function test_it_fails_to_return_detail_broadcast_data()
    {
        $response = $this->json('GET', "/api/v1/mahasiswa/detail-broadcast-failure/", ['id' => 999]);

        $response->assertStatus(404); // Adjust based on your actual error status code
    }

    /** @test */
    public function test_it_fails_to_return_broadcast_data_with_method_not_allowed_error()
    {
        $response = $this->json('POST', '/api/v1/mahasiswa/broadcast/');

        $response->assertStatus(405); // Method Not Allowed error
    }

    /** @test */
    public function test_it_fails_to_return_broadcast_home_data_with_method_not_allowed_error()
    {
        $response = $this->json('POST', '/api/v1/mahasiswa/broadcast-home/');

        $response->assertStatus(405); // Method Not Allowed error
    }

    /** @test */
    public function test_it_fails_to_return_detail_broadcast_data_with_method_not_allowed_error()
    {
        $response = $this->json('GET', "/api/v1/mahasiswa/detail-broadcast/", ['id' => 999]);

        $response->assertStatus(405); // Method Not Allowed error
    }
}
