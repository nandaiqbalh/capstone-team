<?php

namespace Tests\Feature\Api\V1\Mahasiswa\Expo;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ApiExpoControllerTest extends TestCase
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
    public function test_it_returns_success_response_when_fetching_expo_schedule()
    {
        // Mengirimkan permintaan API untuk mendapatkan jadwal expo
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/expo/');

        // Memastikan respons adalah sukses dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_not_found()
    {
        // Mengirimkan permintaan API untuk mendapatkan jadwal expo dengan token yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->get('/api/v1/mahasiswa/expo/');

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
        $user = User::where('nomor_induk', '21120120130125')->first();
        $user->update(['user_active' => 0], ['timestamps' => false]);

        // Mengirimkan permintaan API untuk mendapatkan jadwal expo
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/expo/');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);

        // Mengaktifkan kembali pengguna setelah pengujian selesai
        $user->update(['user_active' => 1], ['timestamps' => false]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_invalid_token()
    {
        // Mengirimkan permintaan API untuk mendapatkan jadwal expo dengan token yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->get('/api/v1/mahasiswa/expo/');

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Token is Invalid'
            ]);
    }

    /** @test */
    public function test_it_returns_not_found_response_when_token_not_found()
    {
        // Mengirimkan permintaan API untuk mendapatkan jadwal expo tanpa menyertakan token
        $response = $this->get('/api/v1/mahasiswa/expo/');

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
            ->get('/api/v1/mahasiswa/expo/invalid-url');

        // Memastikan respons menunjukkan bahwa URL tidak ditemukan
        $response->assertStatus(404);
    }

    /** @test */
    public function test_it_returns_method_not_allowed_response_for_invalid_method_expo_available()
    {
        // Mengirimkan permintaan API dengan metode yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post('/api/v1/mahasiswa/expo/');

        // Memastikan respons menunjukkan bahwa metode tidak diizinkan
        $response->assertStatus(405);
    }

    /** @test */
    public function test_it_returns_success_response_when_registering_for_expo()
    {
        // Menjalankan request API untuk mendaftar expo
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('POST', '/api/v1/mahasiswa/expo-daftar/', [
                'id_expo' => 8,
                'link_berkas_expo' => 'https://example.com/berkas_expo',
                'judul_ta_mhs' => 'Judul Tugas Akhir Mahasiswa',
            ]);

        // Memastikan respons adalah sukses dan berisi struktur data yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data' => [],
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_has_no_group()
    {
        // Melakukan login untuk mendapatkan token
        $loginPayload = [
            'nomor_induk' => '21120120120015',
            'password' => 'mahasiswa123',
        ];

        $loginResponse = $this->json('POST', '/api/v1/auth/login/', $loginPayload);

        // Mengambil token dari response login
        $token = $loginResponse->json('data.api_token');

        // Menjalankan request API untuk mendaftar expo
        $response = $this->withHeaders(['Authorization' => 'Bearer ' .  $token])
            ->json('POST', '/api/v1/mahasiswa/expo-daftar/', [
                'id_expo' => 8,
                'link_berkas_expo' => 'https://example.com/berkas_expo',
                'judul_ta_mhs' => 'Judul Tugas Akhir Mahasiswa',
            ]);

        // Memastikan respons adalah kegagalan dan tidak berisi data
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data',
            ]);
    }

    /** @test */
    public function test_it_returns_method_not_allowed_response_for_invalid_method_daftar_expo()
    {
        // Mengirimkan permintaan API dengan metode yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/expo-daftar/');

        // Memastikan respons menunjukkan bahwa metode tidak diizinkan
        $response->assertStatus(405);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_inactive_register_expo()
    {
        // Menonaktifkan pengguna yang sedang diuji
        $user = User::where('nomor_induk', '21120120130125')->first();
        $user->update(['user_active' => 0], ['timestamps' => false]);

        // Mengirimkan permintaan API untuk mendaftar expo
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('POST', '/api/v1/mahasiswa/expo-daftar/', [
                'id_expo' => 8,
                'link_berkas_expo' => 'https://example.com/berkas_expo',
                'judul_ta_mhs' => 'Judul Tugas Akhir Mahasiswa',
            ]);

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);

        // Mengaktifkan kembali pengguna setelah pengujian selesai
        $user->update(['user_active' => 1], ['timestamps' => false]);
    }

    /** @test */
    public function test_it_returns_invalid_token_response_when_invalid_token_provided()
    {
        // Mengirimkan permintaan API dengan token yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])
            ->json('POST', '/api/v1/mahasiswa/expo-daftar/', [
                'id_expo' => 8,
                'link_berkas_expo' => 'https://example.com/berkas_expo',
                'judul_ta_mhs' => 'Judul Tugas Akhir Mahasiswa',
            ]);

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Token is Invalid'
            ]);
    }

    /** @test */
    public function test_it_returns_token_not_found_response_when_no_token_provided()
    {
        // Mengirimkan permintaan API tanpa menyertakan token
        $response = $this->json('POST', '/api/v1/mahasiswa/expo-daftar/', [
            'id_expo' => 8,
            'link_berkas_expo' => 'https://example.com/berkas_expo',
            'judul_ta_mhs' => 'Judul Tugas Akhir Mahasiswa',
        ]);

        // Memastikan respons adalah kegagalan dan memiliki struktur JSON yang diharapkan
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Authorization Token not found'
            ]);
    }

    /** @test */
    public function test_it_returns_not_found_response_for_invalid_url_register_expo()
    {
        // Mengirimkan permintaan API dengan URL yang tidak valid
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get('/api/v1/mahasiswa/expo/invalid-url');

        // Memastikan respons menunjukkan bahwa URL tidak ditemukan
        $response->assertStatus(404);
    }


}
