<?php

namespace Tests\Feature\Api\V1\Mahasiswa\TugasAkhir;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Api\Mahasiswa\TugasAkhir\ApiTugasAkhirModel;

class ApiTugasAkhirControllerTest extends TestCase
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
    public function test_it_returns_success_response_when_getting_sidang_tugas_akhir_by_mahasiswa()
    {
         // Menyiapkan data kelompok mahasiswa
         $kelompok = ApiTugasAkhirModel::pengecekan_kelompok_mahasiswa($this->user_id);

        // Kirim permintaan API untuk mendapatkan jadwal sidang tugas akhir
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('GET', '/api/v1/mahasiswa/sidang-tugas-akhir-mahasiswa');

        // Pastikan respons adalah sukses dan berisi struktur data yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data' => [
                    'kelompok',
                    'rsSidang',
                    'periode',
                    'status_pendaftaran',
                ],
            ])
            ->assertJson(['success' => true, 'status' => 'Berhasil mendapatkan jadwal sidang Tugas Akhir!']);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_not_found()
    {
        // Kirim permintaan API dengan token tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->json('GET', '/api/v1/mahasiswa/sidang-tugas-akhir-mahasiswa');

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
            ->json('GET', '/api/v1/mahasiswa/sidang-tugas-akhir-mahasiswa');

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
        $response = $this->json('GET', '/api/v1/mahasiswa/sidang-tugas-akhir-mahasiswa');

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
            ->json('GET', '/api/v1/mahasiswa/sidang-tugas-akhir-mahasiswa');

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
            ->json('POST', '/api/v1/mahasiswa/sidang-tugas-akhir-mahasiswa');

        // Pastikan respons adalah kegagalan
        $response->assertStatus(405);
    }

    /** @test */
    public function test_it_returns_failure_response_when_invalid_url()
    {
        // Kirim permintaan API dengan URL yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('GET', '/api/v1/mahasiswa/sidang-tugas-akhir-mahasiswa/invalid-url');

        // Pastikan respons adalah kegagalan karena URL tidak ditemukan
        $response->assertStatus(404);
    }

    // daftar sidang\
    /** @test */
    public function test_it_returns_success_response_when_daftar_sidang_tugas_akhir()
    {
         // Menyiapkan data kelompok mahasiswa
         $kelompok = ApiTugasAkhirModel::pengecekan_kelompok_mahasiswa($this->user_id);

         $params = [
            'file_status_lta' => "Laporan TA Telah Disetujui!", 'file_status_mta' => "Makalah TA Telah Disetujui!"
         ];
         ApiTugasAkhirModel::updateKelompokMHS($this->user_id, $params);

        // Kirim permintaan API untuk mendaftarkan sidang tugas akhir
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('POST', '/api/v1/mahasiswa/sidang-tugas-akhir-daftar', [
                'link_upload' => 'https://example.com/upload',
                'judul_ta_mhs' => 'Judul Tugas Akhir',
            ]);

        // Pastikan respons adalah sukses dan berisi struktur data yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data',
            ])
            ->assertJson(['success' => true]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_not_found_daftar_sidang()
    {
        // Kirim permintaan API dengan token tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->json('POST', '/api/v1/mahasiswa/sidang-tugas-akhir-daftar', [
                'link_upload' => 'https://example.com/upload',
                'judul_ta_mhs' => 'Judul Tugas Akhir',
            ]);

        // Pastikan respons adalah kegagalan dan tidak berisi data
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_invalid_token_daftar_sidang()
    {
        // Kirim permintaan API dengan token tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->json('POST', '/api/v1/mahasiswa/sidang-tugas-akhir-daftar', [
                'link_upload' => 'https://example.com/upload',
                'judul_ta_mhs' => 'Judul Tugas Akhir',
            ]);

        // Pastikan respons adalah kegagalan dan tidak berisi data
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_token_not_found_daftar_sidang()
    {
        // Kirim permintaan API tanpa token
        $response = $this->json('POST', '/api/v1/mahasiswa/sidang-tugas-akhir-daftar', [
            'link_upload' => 'https://example.com/upload',
            'judul_ta_mhs' => 'Judul Tugas Akhir',
        ]);

        // Pastikan respons adalah kegagalan dan tidak berisi data
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_inactive_daftar_sidang()
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
            ->json('POST', '/api/v1/mahasiswa/sidang-tugas-akhir-daftar', [
                'link_upload' => 'https://example.com/upload',
                'judul_ta_mhs' => 'Judul Tugas Akhir',
            ]);

        // Pastikan respons adalah kegagalan dan tidak berisi data
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_method_not_allowed_daftar_sidang()
    {
        // Kirim permintaan API dengan metode yang tidak diizinkan (GET)
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('GET', '/api/v1/mahasiswa/sidang-tugas-akhir-daftar');

        // Pastikan respons adalah kegagalan
        $response->assertStatus(405);
    }

    /** @test */
    public function test_it_returns_failure_response_when_invalid_url_daftar_sidang()
    {
        // Kirim permintaan API dengan URL yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('POST', '/api/v1/mahasiswa/sidang-tugas-akhir-daftar/invalid-url');

        // Pastikan respons adalah kegagalan karena URL tidak ditemukan
        $response->assertStatus(404);
    }
}
