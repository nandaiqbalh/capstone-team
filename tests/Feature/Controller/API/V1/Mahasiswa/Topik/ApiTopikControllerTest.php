<?php

namespace Tests\Unit\Controllers\Api\V1\Mahasiswa\Topik;

use Tests\TestCase;
use App\Http\Controllers\Api\V1\Mahasiswa\Topik\ApiTopikController;
use Illuminate\Http\Request;
use App\Models\Api\Mahasiswa\Topik\ApiTopikModel;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiTopikControllerTest extends TestCase
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


       /**
     * Test case for successful data retrieval.
     *
     * @return void
     */
    public function test_it_returns_success_response_when_fetching_topik_data()
    {
        // Mengirimkan permintaan API untuk mendapatkan data topik
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/topik/');

        // Memastikan respons adalah sukses dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data',
            ]);
    }

    /**
     * Test case for failure due to inactive user.
     *
     * @return void
     */
    public function test_it_returns_failure_response_when_user_inactive()
    {
        // Mock JWTAuth::parseToken()->authenticate() to return a user with inactive status
        JWTAuth::shouldReceive('parseToken->authenticate')
            ->andReturn((object)['user_active' => "0"]);

        $controller = new ApiTopikController();

        $response = $controller->index(new Request());

        $this->assertFalse($response->getData()->success);
        $this->assertEquals('Gagal mendapatkan data topik!', $response->getData()->status);
        $this->assertNull($response->getData()->data);
    }

    public function test_it_returns_failure_response_when_user_not_found()
    {
        // Mengirimkan permintaan API untuk mengunggah makalah tanpa token
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->get('/api/v1/mahasiswa/topik');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /**
     * Test case for failure due to invalid token.
     *
     * @return void
     */
    public function test_it_returns_failure_response_when_invalid_token()
    {
        // Mock JWTAuth::parseToken()->authenticate() to throw JWTException
        JWTAuth::shouldReceive('parseToken->authenticate')
            ->andThrow(new \Tymon\JWTAuth\Exceptions\JWTException('Token is Invalid'));

        $controller = new ApiTopikController();

        $response = $controller->index(new Request());

        $this->assertFalse($response->getData()->success);
        $this->assertEquals('Token is Invalid', $response->getData()->status);
        $this->assertNull($response->getData()->data);
    }

    public function test_it_returns_not_found_response_for_missing_token()
    {
        // Mengirimkan permintaan API tanpa menyertakan token
        $response = $this->get('/api/v1/mahasiswa/topik/');

        // Memastikan respons adalah "Not Found" dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
                 ->assertJson([
                    'status' => 'Authorization Token not found'
                 ]);
    }

    /**
     * Test case for invalid URL.
     *
     * @return void
     */
    public function test_it_returns_not_found_response_for_invalid_url()
    {
        // Mengirimkan permintaan API untuk URL yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/topik-invalid-url/');

        // Memastikan respons adalah "Not Found"
        $response->assertStatus(404);
    }

    /**
     * Test case for method not allowed.
     *
     * @return void
     */
    public function test_it_returns_method_not_allowed_response()
    {
        // Mengirimkan permintaan API dengan metode yang tidak diizinkan
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post('/api/v1/mahasiswa/topik/');

        // Memastikan respons adalah "Method Not Allowed"
        $response->assertStatus(405);
    }
}
