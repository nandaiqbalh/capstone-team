<?php

namespace Tests\Feature\Api\V1\Mahasiswa\Dokumen;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class ApiDokumenControllerTest extends TestCase
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
    public function test_it_returns_success_response_when_fetching_documents()
    {
        // Mengirimkan permintaan API untuk mendapatkan dokumen
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/dokumen/');

        // Memastikan respons adalah sukses dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_token_invalid()
    {
        // Mengirimkan permintaan API dengan token yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->get('/api/v1/mahasiswa/dokumen/');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Token is Invalid',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_token_not_found()
    {
        // Mengirimkan permintaan API tanpa menyertakan token
        $response = $this->get('/api/v1/mahasiswa/dokumen/');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Authorization Token not found',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_has_no_group()
    {
        // Melakukan login untuk mendapatkan token
        $loginPayload = [
            'nomor_induk' => '21120119130103',
            'password' => 'mahasiswa123',
        ];

        $loginResponse = $this->json('POST', '/api/v1/auth/login/', $loginPayload);

        // Mengambil token dari response login
        $token = $loginResponse->json('data.api_token');

        // Mengirimkan permintaan API dengan token yang memiliki user tanpa kelompok
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->get('/api/v1/mahasiswa/dokumen/');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJson([
                'success' => false,
                'status' => 'Anda belum mendaftar capstone!',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_inactive()
    {

        $loginPayload = [
            'nomor_induk' => '21120120140096',
            'password' => 'mahasiswa123',
        ];

        $loginResponse = $this->json('POST', '/api/v1/auth/login/', $loginPayload);

        // Retrieve the token from the login response
        $inactiveToken = $loginResponse->json('data.api_token');

        // Mengirimkan permintaan API dengan token yang memiliki user tidak aktif
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $inactiveToken])
            ->get('/api/v1/mahasiswa/dokumen/');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_method_not_allowed_response()
    {
        // Mengirimkan permintaan API dengan metode yang tidak diizinkan
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post('/api/v1/mahasiswa/dokumen/');

        // Memastikan respons adalah "Method Not Allowed"
        $response->assertStatus(405);
    }

    /** @test */
    public function test_it_returns_not_found_response_for_invalid_url()
    {
        // Mengirimkan permintaan API dengan URL yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/dokumen-invalid-url/');

        // Memastikan respons adalah "Not Found"
        $response->assertStatus(404);
    }

/** @test */
    public function test_it_returns_success_response_when_uploading_makalah()
    {
        // Mock ApiDokumenModel::getById() to return a valid user
        $mock = Mockery::mock('ApiDokumenModel');
        $mock->shouldReceive('getById')
            ->andReturn((object) ['user_id' => 1, 'user_name' => 'Test User', 'user_active' => "1"]);

        // Mock ApiDokumenModel::fileMHS() to return a valid file
        $mock->shouldReceive('fileMHS')
            ->andReturn((object) [
                'file_name_makalah' => null,
                'file_path_makalah' => null,
            ]);

        // Mock the file to be uploaded
        Storage::fake('public');
        $file = UploadedFile::fake()->create('makalah.pdf', 1024); // Create a fake PDF file with 1 KB size

        // Mengirimkan permintaan API untuk mengunggah makalah
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post('/api/v1/mahasiswa/dokumen/upload-makalah-process', ['makalah' => $file]);

        // Memastikan respons adalah sukses dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_not_found_during_makalah_upload()
    {
        // Mengirimkan permintaan API untuk mengunggah makalah tanpa token
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->post('/api/v1/mahasiswa/dokumen/upload-makalah-process');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_invalid_token_during_makalah_upload()
    {
        // Mengirimkan permintaan API untuk mengunggah makalah dengan token tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->post('/api/v1/mahasiswa/dokumen/upload-makalah-process');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    public function test_it_returns_failure_response_when_token_not_found_during_makalah_upload()
    {
        // Mengirimkan permintaan API tanpa menyertakan token
        $response = $this->get('/api/v1/mahasiswa/dokumen/');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Authorization Token not found',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_inactive_during_makalah_upload()
    {
        // Mock ApiDokumenModel::getById() to return a user with inactive status
        $mock = Mockery::mock('ApiDokumenModel');
        $mock->shouldReceive('getById')
            ->andReturn((object) ['user_id' => 1, 'user_name' => 'Test User', 'user_active' => "0"]);

        // Mengirimkan permintaan API untuk mengunggah makalah dengan pengguna tidak aktif
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post('/api/v1/mahasiswa/dokumen/upload-makalah-process');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_method_not_allowed_during_makalah_upload()
    {
        // Mengirimkan permintaan API dengan metode yang tidak diizinkan
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/dokumen/upload-makalah-process');

        // Memastikan respons adalah "Method Not Allowed"
        $response->assertStatus(405);
    }

    /** @test */
    public function test_it_returns_failure_response_for_invalid_url_during_makalah_upload()
    {
        // Mengirimkan permintaan API dengan URL yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/dokumen/upload-makalah-process-invalid-url');

        // Memastikan respons adalah "Not Found"
        $response->assertStatus(404);
    }

    /** @test */
    public function test_it_returns_success_response_when_uploading_laporan_ta()
    {
        // Mock ApiDokumenModel::getById() to return a valid user
        $mock = Mockery::mock('ApiDokumenModel');
        $mock->shouldReceive('getById')
            ->andReturn((object) ['user_id' => 1, 'user_name' => 'Test User', 'user_active' => "1"]);

        // Mock ApiDokumenModel::fileMHS() to return a valid file
        $mock = Mockery::mock('ApiDokumenModel');
        $mock->shouldReceive('fileMHS')
            ->andReturn((object) [
                'file_name_laporan_ta' => null,
                'file_path_laporan_ta' => null,
            ]);

        // Mock the file to be uploaded
        Storage::fake('public');
        $file = UploadedFile::fake()->create('laporan_ta.pdf', 1024); // Create a fake PDF file with 1 KB size

        // Mengirimkan permintaan API untuk mengunggah laporan TA
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post('/api/v1/mahasiswa/dokumen/upload-laporan-process', ['laporan_ta' => $file]);

        // Memastikan respons adalah sukses dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_not_found_during_laporan_upload()
    {
        // Mengirimkan permintaan API untuk mengunggah laporan TA tanpa token
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->post('/api/v1/mahasiswa/dokumen/upload-laporan-process');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_invalid_token_during_laporan_upload()
    {
        // Mengirimkan permintaan API untuk mengunggah laporan TA dengan token tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->post('/api/v1/mahasiswa/dokumen/upload-laporan-process');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    public function test_it_returns_failure_response_when_token_not_found_during_laporan_upload()
    {
        // Mengirimkan permintaan API tanpa menyertakan token
        $response = $this->get('/api/v1/mahasiswa/dokumen/');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Authorization Token not found',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_inactive_during_laporan_upload()
    {
        // Mock ApiDokumenModel::getById() to return a user with inactive status
        $mock = Mockery::mock('ApiDokumenModel');
        $mock->shouldReceive('getById')
            ->andReturn((object) ['user_id' => 1, 'user_name' => 'Test User', 'user_active' => "0"]);

        // Mengirimkan permintaan API untuk mengunggah laporan TA dengan pengguna tidak aktif
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post('/api/v1/mahasiswa/dokumen/upload-laporan-process');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_method_not_allowed_during_laporan_upload()
    {
        // Mengirimkan permintaan API dengan metode yang tidak diizinkan
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/dokumen/upload-laporan-process');

        // Memastikan respons adalah "Method Not Allowed"
        $response->assertStatus(405);
    }

    /** @test */
    public function test_it_returns_failure_response_for_invalid_url_during_laporan_upload()
    {
        // Mengirimkan permintaan API dengan URL yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/dokumen/upload-laporan-process-invalid-url');

        // Memastikan respons adalah "Not Found"
        $response->assertStatus(404);
    }
}
