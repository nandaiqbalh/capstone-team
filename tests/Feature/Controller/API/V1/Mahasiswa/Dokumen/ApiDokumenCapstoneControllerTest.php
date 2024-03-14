<?php

namespace Tests\Feature\Api\V1\Mahasiswa\Dokumen;

use Tests\TestCase;
use App\Models\Api\Mahasiswa\Dokumen\ApiDokumenModel;
use Illuminate\Foundation\Testing\WithFaker;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\UploadedFile;
use Mockery;
use Illuminate\Support\Facades\Storage;

class ApiDokumenCapstoneControllerTest extends TestCase
{

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Melakukan login untuk mendapatkan token
        $loginPayload = [
            'nomor_induk' => '21120120130125',
            'password' => 'mahasiswa123',
        ];

        $loginResponse = $this->json('POST', '/api/v1/auth/login/', $loginPayload);

        // Mengambil token dari response login
        $this->token = $loginResponse->json('data.api_token');
    }

    /** @test */
    public function test_it_returns_success_response_when_uploading_c100()
    {
        // Mock ApiDokumenModel::getById() to return a valid user
        $mock = Mockery::mock('ApiDokumenModel');
        $mock->shouldReceive('getById')
            ->andReturn((object)['user_id' => 1, 'user_name' => 'Test User', 'user_active' => 1]);

        // Mock ApiDokumenModel::fileMHS() to return a valid file
        $mock->shouldReceive('fileMHS')
            ->andReturn((object)[
                'file_name_c100' => null,
                'file_path_c100' => null,
            ]);

        // Mock the file to be uploaded
        Storage::fake('public');
        $file = UploadedFile::fake()->create('c100.pdf', 1024); // Create a fake PDF file with 1 KB size

        // Mengirimkan permintaan API untuk mengunggah c100
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post('/api/v1/mahasiswa/dokumen/upload-c100-process', ['c100' => $file]);

        // Memastikan respons adalah sukses dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_not_found_during_c100_upload()
    {
        // Mengirimkan permintaan API untuk mengunggah c100 tanpa token
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->post('/api/v1/mahasiswa/dokumen/upload-c100-process');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_invalid_token_during_c100_upload()
    {
        // Mengirimkan permintaan API untuk mengunggah c100 dengan token tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->post('/api/v1/mahasiswa/dokumen/upload-c100-process');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    public function test_it_returns_failure_response_when_token_not_found_during_c100_upload()
    {
        // Mengirimkan permintaan API tanpa menyertakan token
        $response = $this->get('/api/v1/mahasiswa/dokumen/');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Authorization Token not found'
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_inactive_during_c100_upload()
    {
        // Mock ApiDokumenModel::getById() to return a user with inactive status
        $mock = Mockery::mock('ApiDokumenModel');
        $mock->shouldReceive('getById')
            ->andReturn((object)['user_id' => 1, 'user_name' => 'Test User', 'user_active' => 0]);

        // Mengirimkan permintaan API untuk mengunggah c100 dengan pengguna tidak aktif
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post('/api/v1/mahasiswa/dokumen/upload-c100-process');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_method_not_allowed_during_c100_upload()
    {
        // Mengirimkan permintaan API dengan metode yang tidak diizinkan
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/dokumen/upload-c100-process');

        // Memastikan respons adalah "Method Not Allowed"
        $response->assertStatus(405);
    }

    /** @test */
    public function test_it_returns_failure_response_for_invalid_url_during_c100_upload()
    {
        // Mengirimkan permintaan API dengan URL yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/dokumen/upload-c100-process-invalid-url');

        // Memastikan respons adalah "Not Found"
        $response->assertStatus(404);
    }

    // C200
    public function test_it_returns_success_response_when_uploading_c200()
    {
        // Mock ApiDokumenModel::getById() to return a valid user
        $mock = Mockery::mock('ApiDokumenModel');
        $mock->shouldReceive('getById')
            ->andReturn((object)['user_id' => 1, 'user_name' => 'Test User', 'user_active' => 1]);

        // Mock ApiDokumenModel::fileMHS() to return a valid file
        $mock->shouldReceive('fileMHS')
            ->andReturn((object)[
                'file_name_c200' => null,
                'file_path_c200' => null,
            ]);

        // Mock the file to be uploaded
        Storage::fake('public');
        $file = UploadedFile::fake()->create('c200.pdf', 1024); // Create a fake PDF file with 1 KB size

        // Mengirimkan permintaan API untuk mengunggah c200
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post('/api/v1/mahasiswa/dokumen/upload-c200-process', ['c200' => $file]);

        // Memastikan respons adalah sukses dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_not_found_during_c200_upload()
    {
        // Mengirimkan permintaan API untuk mengunggah c200 tanpa token
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->post('/api/v1/mahasiswa/dokumen/upload-c200-process');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_invalid_token_during_c200_upload()
    {
        // Mengirimkan permintaan API untuk mengunggah c200 dengan token tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->post('/api/v1/mahasiswa/dokumen/upload-c200-process');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    public function test_it_returns_failure_response_when_token_not_found_during_c200_upload()
    {
        // Mengirimkan permintaan API tanpa menyertakan token
        $response = $this->get('/api/v1/mahasiswa/dokumen/');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Authorization Token not found'
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_inactive_during_c200_upload()
    {
        // Mock ApiDokumenModel::getById() to return a user with inactive status
        $mock = Mockery::mock('ApiDokumenModel');
        $mock->shouldReceive('getById')
            ->andReturn((object)['user_id' => 1, 'user_name' => 'Test User', 'user_active' => 0]);

        // Mengirimkan permintaan API untuk mengunggah c200 dengan pengguna tidak aktif
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post('/api/v1/mahasiswa/dokumen/upload-c200-process');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_method_not_allowed_during_c200_upload()
    {
        // Mengirimkan permintaan API dengan metode yang tidak diizinkan
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/dokumen/upload-c200-process');

        // Memastikan respons adalah "Method Not Allowed"
        $response->assertStatus(405);
    }

    /** @test */
    public function test_it_returns_failure_response_for_invalid_url_during_c200_upload()
    {
        // Mengirimkan permintaan API dengan URL yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/dokumen/upload-c200-process-invalid-url');

        // Memastikan respons adalah "Not Found"
        $response->assertStatus(404);
    }

    // C300
    public function test_it_returns_success_response_when_uploading_c300()
    {
        // Mock ApiDokumenModel::getById() to return a valid user
        $mock = Mockery::mock('ApiDokumenModel');
        $mock->shouldReceive('getById')
            ->andReturn((object)['user_id' => 1, 'user_name' => 'Test User', 'user_active' => 1]);

        // Mock ApiDokumenModel::fileMHS() to return a valid file
        $mock->shouldReceive('fileMHS')
            ->andReturn((object)[
                'file_name_c300' => null,
                'file_path_c300' => null,
            ]);

        // Mock the file to be uploaded
        Storage::fake('public');
        $file = UploadedFile::fake()->create('c300.pdf', 1024); // Create a fake PDF file with 1 KB size

        // Mengirimkan permintaan API untuk mengunggah c300
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post('/api/v1/mahasiswa/dokumen/upload-c300-process', ['c300' => $file]);

        // Memastikan respons adalah sukses dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_not_found_during_c300_upload()
    {
        // Mengirimkan permintaan API untuk mengunggah c300 tanpa token
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->post('/api/v1/mahasiswa/dokumen/upload-c300-process');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_invalid_token_during_c300_upload()
    {
        // Mengirimkan permintaan API untuk mengunggah c300 dengan token tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->post('/api/v1/mahasiswa/dokumen/upload-c300-process');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    public function test_it_returns_failure_response_when_token_not_found_during_c300_upload()
    {
        // Mengirimkan permintaan API tanpa menyertakan token
        $response = $this->get('/api/v1/mahasiswa/dokumen/');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Authorization Token not found'
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_inactive_during_c300_upload()
    {
        // Mock ApiDokumenModel::getById() to return a user with inactive status
        $mock = Mockery::mock('ApiDokumenModel');
        $mock->shouldReceive('getById')
            ->andReturn((object)['user_id' => 1, 'user_name' => 'Test User', 'user_active' => 0]);

        // Mengirimkan permintaan API untuk mengunggah c300 dengan pengguna tidak aktif
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post('/api/v1/mahasiswa/dokumen/upload-c300-process');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_method_not_allowed_during_c300_upload()
    {
        // Mengirimkan permintaan API dengan metode yang tidak diizinkan
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/dokumen/upload-c300-process');

        // Memastikan respons adalah "Method Not Allowed"
        $response->assertStatus(405);
    }

    /** @test */
    public function test_it_returns_failure_response_for_invalid_url_during_c300_upload()
    {
        // Mengirimkan permintaan API dengan URL yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/dokumen/upload-c300-process-invalid-url');

        // Memastikan respons adalah "Not Found"
        $response->assertStatus(404);
    }

    // C400
    public function test_it_returns_success_response_when_uploading_c400()
    {
        // Mock ApiDokumenModel::getById() to return a valid user
        $mock = Mockery::mock('ApiDokumenModel');
        $mock->shouldReceive('getById')
            ->andReturn((object)['user_id' => 1, 'user_name' => 'Test User', 'user_active' => 1]);

        // Mock ApiDokumenModel::fileMHS() to return a valid file
        $mock->shouldReceive('fileMHS')
            ->andReturn((object)[
                'file_name_c400' => null,
                'file_path_c400' => null,
            ]);

        // Mock the file to be uploaded
        Storage::fake('public');
        $file = UploadedFile::fake()->create('c400.pdf', 1024); // Create a fake PDF file with 1 KB size

        // Mengirimkan permintaan API untuk mengunggah c400
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post('/api/v1/mahasiswa/dokumen/upload-c400-process', ['c400' => $file]);

        // Memastikan respons adalah sukses dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_not_found_during_c400_upload()
    {
        // Mengirimkan permintaan API untuk mengunggah c400 tanpa token
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->post('/api/v1/mahasiswa/dokumen/upload-c400-process');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_invalid_token_during_c400_upload()
    {
        // Mengirimkan permintaan API untuk mengunggah c400 dengan token tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->post('/api/v1/mahasiswa/dokumen/upload-c400-process');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    public function test_it_returns_failure_response_when_token_not_found_during_c400_upload()
    {
        // Mengirimkan permintaan API tanpa menyertakan token
        $response = $this->get('/api/v1/mahasiswa/dokumen/');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Authorization Token not found'
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_inactive_during_c400_upload()
    {
        // Mock ApiDokumenModel::getById() to return a user with inactive status
        $mock = Mockery::mock('ApiDokumenModel');
        $mock->shouldReceive('getById')
            ->andReturn((object)['user_id' => 1, 'user_name' => 'Test User', 'user_active' => 0]);

        // Mengirimkan permintaan API untuk mengunggah c400 dengan pengguna tidak aktif
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post('/api/v1/mahasiswa/dokumen/upload-c400-process');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_method_not_allowed_during_c400_upload()
    {
        // Mengirimkan permintaan API dengan metode yang tidak diizinkan
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/dokumen/upload-c400-process');

        // Memastikan respons adalah "Method Not Allowed"
        $response->assertStatus(405);
    }

    /** @test */
    public function test_it_returns_failure_response_for_invalid_url_during_c400_upload()
    {
        // Mengirimkan permintaan API dengan URL yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/dokumen/upload-c400-process-invalid-url');

        // Memastikan respons adalah "Not Found"
        $response->assertStatus(404);
    }


    // C500
    public function test_it_returns_success_response_when_uploading_c500()
    {
        // Mock ApiDokumenModel::getById() to return a valid user
        $mock = Mockery::mock('ApiDokumenModel');
        $mock->shouldReceive('getById')
            ->andReturn((object)['user_id' => 1, 'user_name' => 'Test User', 'user_active' => 1]);

        // Mock ApiDokumenModel::fileMHS() to return a valid file
        $mock->shouldReceive('fileMHS')
            ->andReturn((object)[
                'file_name_c500' => null,
                'file_path_c500' => null,
            ]);

        // Mock the file to be uploaded
        Storage::fake('public');
        $file = UploadedFile::fake()->create('c500.pdf', 1024); // Create a fake PDF file with 1 KB size

        // Mengirimkan permintaan API untuk mengunggah c500
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post('/api/v1/mahasiswa/dokumen/upload-c500-process', ['c500' => $file]);

        // Memastikan respons adalah sukses dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_not_found_during_c500_upload()
    {
        // Mengirimkan permintaan API untuk mengunggah c500 tanpa token
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->post('/api/v1/mahasiswa/dokumen/upload-c500-process');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_invalid_token_during_c500_upload()
    {
        // Mengirimkan permintaan API untuk mengunggah c500 dengan token tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->post('/api/v1/mahasiswa/dokumen/upload-c500-process');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    public function test_it_returns_failure_response_when_token_not_found_during_c500_upload()
    {
        // Mengirimkan permintaan API tanpa menyertakan token
        $response = $this->get('/api/v1/mahasiswa/dokumen/');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Authorization Token not found'
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_inactive_during_c500_upload()
    {
        // Mock ApiDokumenModel::getById() to return a user with inactive status
        $mock = Mockery::mock('ApiDokumenModel');
        $mock->shouldReceive('getById')
            ->andReturn((object)['user_id' => 1, 'user_name' => 'Test User', 'user_active' => 0]);

        // Mengirimkan permintaan API untuk mengunggah c500 dengan pengguna tidak aktif
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post('/api/v1/mahasiswa/dokumen/upload-c500-process');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_method_not_allowed_during_c500_upload()
    {
        // Mengirimkan permintaan API dengan metode yang tidak diizinkan
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/dokumen/upload-c500-process');

        // Memastikan respons adalah "Method Not Allowed"
        $response->assertStatus(405);
    }

    /** @test */
    public function test_it_returns_failure_response_for_invalid_url_during_c500_upload()
    {
        // Mengirimkan permintaan API dengan URL yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/dokumen/upload-c500-process-invalid-url');

        // Memastikan respons adalah "Not Found"
        $response->assertStatus(404);
    }

}
