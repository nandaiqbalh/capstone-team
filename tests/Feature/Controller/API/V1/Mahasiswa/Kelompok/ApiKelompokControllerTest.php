<?php

namespace Tests\Feature\API\V1\Mahasiswa\Kelompok;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Api\Mahasiswa\Kelompok\ApiKelompokModel;
use App\Models\User;

class ApiKelompokControllerTest extends TestCase
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

    /** @test */
    public function test_it_returns_success_response_when_fetching_kelompok_data()
    {
        // Mengirimkan permintaan API untuk mendapatkan data kelompok
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/kelompok/');

        // Memastikan respons adalah sukses dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data' => [
                    'kelompok',
                    'getAkun',
                    'rs_mahasiswa',
                    'rs_dosbing',
                    'rs_dospeng',
                    'rs_dospeng_ta',
                ],
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_not_found()
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->get('/api/v1/mahasiswa/kelompok/');

        // Memastikan respons menunjukkan kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_inactive()
    {
        // Menonaktifkan pengguna yang sedang diuji
        $user = User::where('nomor_induk', '21120120130058')->first();
        $user->update(['user_active' => "0"], ['timestamps' => false]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/kelompok/');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);

        // Mengaktifkan kembali pengguna setelah pengujian selesai
        $user->update(['user_active' => "1"], ['timestamps' => false]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_invalid_token()
    {
        // Mengirimkan permintaan API untuk mendapatkan jadwal kelompok dengan token yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->get('/api/v1/mahasiswa/kelompok/');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Token is Invalid'
            ]);
    }

    /** @test */
    public function test_it_returns_not_found_response_when_token_not_found()
    {
        // Mengirimkan permintaan API untuk mendapatkan jadwal kelompok tanpa menyertakan token
        $response = $this->get('/api/v1/mahasiswa/kelompok/');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Authorization Token not found'
            ]);
    }

    /** @test */
    public function test_it_returns_not_found_response_for_invalid_url()
    {
        // Mengirimkan permintaan API dengan URL yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/kelompok/invalid-url');

        // Memastikan respons menunjukkan bahwa URL tidak ditemukan
        $response->assertStatus(404);
    }

    /** @test */
    public function test_it_returns_method_not_allowed_response_for_invalid_method()
    {
        // Mengirimkan permintaan API dengan metode yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post('/api/v1/mahasiswa/kelompok/');

        // Memastikan respons menunjukkan bahwa metode tidak diizinkan
        $response->assertStatus(405);
    }

    /** @test */
    public function test_it_returns_success_response_when_adding_kelompok()
    {
        // Menjalankan request API untuk menambah kelompok
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('POST', '/api/v1/mahasiswa/kelompok/add-kelompok-process', [
                // Masukkan data sesuai kebutuhan untuk testing
            ]);

        // Memastikan respons adalah sukses dan berisi struktur data yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_not_found_adding_kelompok()
    {
        // Menjalankan request API untuk menambah kelompok dengan token yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->json('POST', '/api/v1/mahasiswa/kelompok/add-kelompok-process', [
                // Masukkan data sesuai kebutuhan untuk testing
            ]);

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_not_found_response_for_invalid_url_adding_kelompok()
    {
        // Mengirimkan permintaan API dengan URL yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('POST', '/api/v1/mahasiswa/kelompok/add-kelompok-process/invalid-url', [
                // Masukkan data sesuai kebutuhan untuk testing
            ]);

        // Memastikan respons menunjukkan bahwa URL tidak ditemukan
        $response->assertStatus(404);
    }

    /** @test */
    public function test_it_returns_method_not_allowed_response_for_invalid_method_adding_kelompok()
    {
        // Menjalankan request API dengan metode yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/kelompok/add-kelompok-process');

        // Memastikan respons menunjukkan bahwa metode tidak diizinkan
        $response->assertStatus(405);
    }

    /** @test */
    public function test_it_returns_invalid_token_response_when_invalid_token_provided_adding_kelompok()
    {
        // Menjalankan request API dengan token yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->json('POST', '/api/v1/mahasiswa/kelompok/add-kelompok-process', [
                // Masukkan data sesuai kebutuhan untuk testing
            ]);

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Token is Invalid'
            ]);
    }

    /** @test */
    public function test_it_returns_token_not_found_response_when_no_token_provided_adding_kelompok()
    {
        // Mengirimkan permintaan API tanpa menyertakan token
        $response = $this->json('POST', '/api/v1/mahasiswa/kelompok/add-kelompok-process', [
            // Masukkan data sesuai kebutuhan untuk testing
        ]);

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Authorization Token not found'
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_inactive_adding_kelompok()
    {
        // Menonaktifkan pengguna yang sedang diuji
        $user = User::where('nomor_induk', '21120120130058')->first();
        $user->update(['user_active' => "0"], ['timestamps' => false]);

        // Mengirimkan permintaan API untuk menambah kelompok
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('POST', '/api/v1/mahasiswa/kelompok/add-kelompok-process', [
                // Masukkan data sesuai kebutuhan untuk testing
            ]);

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);

        // Mengaktifkan kembali pengguna setelah pengujian selesai
        $user->update(['user_active' => "1"], ['timestamps' => false]);
    }

    // PUNYA KELOMPOK PROCESS
        /** @test */
    public function test_it_returns_success_response_when_adding_punya_kelompok()
    {
        // Menjalankan request API untuk menambah kelompok
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('POST', '/api/v1/mahasiswa/kelompok/add-punya-kelompok-process', [
                // Masukkan data sesuai kebutuhan untuk testing
            ]);

        // Memastikan respons adalah sukses dan berisi struktur data yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_not_found_adding_punya_kelompok()
    {
        // Menjalankan request API untuk menambah kelompok dengan token yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->json('POST', '/api/v1/mahasiswa/kelompok/add-punya-kelompok-process', [
                // Masukkan data sesuai kebutuhan untuk testing
            ]);

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_not_found_response_for_invalid_url_adding_punya_kelompok()
    {
        // Mengirimkan permintaan API dengan URL yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('POST', '/api/v1/mahasiswa/kelompok/add-punya-kelompok-process/invalid-url', [
                // Masukkan data sesuai kebutuhan untuk testing
            ]);

        // Memastikan respons menunjukkan bahwa URL tidak ditemukan
        $response->assertStatus(404);
    }

    /** @test */
    public function test_it_returns_method_not_allowed_response_for_invalid_method_adding_punya_kelompok()
    {
        // Menjalankan request API dengan metode yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/kelompok/add-punya-kelompok-process');

        // Memastikan respons menunjukkan bahwa metode tidak diizinkan
        $response->assertStatus(405);
    }

    /** @test */
    public function test_it_returns_invalid_token_response_when_invalid_token_provided_adding_punya_kelompok()
    {
        // Menjalankan request API dengan token yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->json('POST', '/api/v1/mahasiswa/kelompok/add-punya-kelompok-process', [
                // Masukkan data sesuai kebutuhan untuk testing
            ]);

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Token is Invalid'
            ]);
    }

    /** @test */
    public function test_it_returns_token_not_found_response_when_no_token_provided_adding_punya_kelompok()
    {
        // Mengirimkan permintaan API tanpa menyertakan token
        $response = $this->json('POST', '/api/v1/mahasiswa/kelompok/add-punya-kelompok-process', [
            // Masukkan data sesuai kebutuhan untuk testing
        ]);

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Authorization Token not found'
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_inactive_adding_punya_kelompok()
    {
        // Menonaktifkan pengguna yang sedang diuji
        $user = User::where('nomor_induk', '21120120130058')->first();
        $user->update(['user_active' => "0"], ['timestamps' => false]);

        // Mengirimkan permintaan API untuk menambah kelompok
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('POST', '/api/v1/mahasiswa/kelompok/add-punya-kelompok-process', [
                // Masukkan data sesuai kebutuhan untuk testing
            ]);

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);

        // Mengaktifkan kembali pengguna setelah pengujian selesai
        $user->update(['user_active' => "1"], ['timestamps' => false]);
    }
}
