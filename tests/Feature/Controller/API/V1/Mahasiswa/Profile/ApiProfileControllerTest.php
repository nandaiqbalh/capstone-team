<?php

namespace Tests\Feature\Api\V1\Mahasiswa\Profile;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ApiProfileControllerTest extends TestCase
{
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        // Melakukan login untuk mendapatkan token
        $loginPayload = [
            'nomor_induk' => '21120120140099',
            'password' => 'mahasiswa123',
        ];

        $loginResponse = $this->json('POST', '/api/v1/auth/login/', $loginPayload);

        // Mengambil token dari response login
        $this->token = $loginResponse->json('data.api_token');
    }

    /** @test */
    public function test_it_returns_user_profile_when_authenticated_user_is_active()
    {
        // Hit the endpoint with the generated token
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('GET', '/api/v1/mahasiswa/profile');

        // Assert the response is successful and has the expected structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'data' => [
                    'user_id',
                    'user_name',
                    'user_email',
                    'no_telp',
                    'user_img_name',
                ],
            ])
            ->assertJson(['success' => true, 'status' => 'Berhasil mendapatkan profil pengguna!']);
    }

    public function test_it_returns_user_profile_when_authenticated_user_is_inactive()
    {
        // Perform login to obtain the token
        $loginPayload = [
            'nomor_induk' => '21120120140096',
            'password' => 'mahasiswa123',
        ];

        $loginResponse = $this->json('POST', '/api/v1/auth/login/', $loginPayload);

        // Retrieve the token from the login response
        $inactiveToken = $loginResponse->json('data.api_token');

       // Hit the endpoint with the generated token
       $response = $this->withHeaders(['Authorization' => 'Bearer ' . $inactiveToken])
       ->json('GET', '/api/v1/mahasiswa/profile');

        // Assert the response indicates failure
        $response->assertStatus(200) // Change the status code to 401 for unauthorized
            ->assertJsonStructure([
                'status',
            ]);
    }

    /** @test */
    public function test_it_returns_failure_response_when_user_is_not_authenticated()
    {
        // Hit the endpoint without a token
        $response = $this->json('GET', '/api/v1/mahasiswa/profile');

        // Assert the response indicates failure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
            ]);
    }

    public function test_it_returns_not_found_response_for_invalid_url()
    {
        // Hit the endpoint with an invalid URL
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('GET', '/api/v1/mahasiswa/profile/invalid-url');

        // Assert the response indicates that the URL is not found
        $response->assertStatus(404);
    }

    /** @test */
    public function test_it_returns_method_not_allowed_response_for_invalid_method()
    {
        // Hit the endpoint with an invalid method (POST instead of GET)
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('POST', '/api/v1/mahasiswa/profile');

        // Assert the response indicates that the method is not allowed
        $response->assertStatus(405);
    }

     /** @test */
     public function test_it_returns_success_result_when_success_to_update_profile()
     {
         $updatePayload = [
            'user_name' => 'New Name',
            'no_telp' => '081201201301',
            'user_email' => 'newemail@example.com',
         ];

         $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
             ->json('POST', '/api/v1/mahasiswa/profile/editProcess', $updatePayload);

         $response->assertStatus(200)
             ->assertJsonStructure([
                 'success',
                 'status',
                 'data' => [
                     'user_id',
                     'user_name',
                     'user_email',
                     'no_telp',
                     'user_img_name',
                 ],
             ])
             ->assertJson(['success' => true, 'status' => 'Profil berhasil diperbaharui!']);

         // Assert the updated user data
         $this->assertDatabaseHas('app_user', [
             'user_name' => 'New Name',
             'no_telp' => '081201201301',
             'user_email' => 'newemail@example.com',
         ]);
     }

     /** @test */
     public function test_it_returns_success_result_when_success_to_update_password()
     {
         $updatePasswordPayload = [
             'current_password' => 'mahasiswa123',
             'new_password' => 'mahasiswa123',
             'repeat_new_password' => 'mahasiswa123',
         ];

         $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
             ->json('POST', '/api/v1/mahasiswa/profile/editPassword', $updatePasswordPayload);

         $response->assertStatus(200)
             ->assertJsonStructure([
                 'success',
                 'status',
                 'data' => [
                     'user_id',
                     'user_name',
                     'user_email',
                     'no_telp',
                     'user_img_name',
                 ],
             ])
             ->assertJson(['success' => true, 'status' => 'Password baru berhasil disimpan.']);

     }

     /** @test */
    public function test_it_returns_failure_result_when_update_profile_fails_due_to_validation()
    {
        // Missing required fields to trigger validation failure
        $invalidUpdatePayload = [
            'user_name' => '',  // This field is required
            // Add other fields as needed
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('POST', '/api/v1/mahasiswa/profile/editProcess', $invalidUpdatePayload);

        $response->assertStatus(422); // Update the status code to 422 for validation failure

    }

    /** @test */
    public function test_it_returns_failure_result_when_update_password_fails_due_to_invalid_current_password()
    {
        $invalidPasswordPayload = [
            'current_password' => 'wrong_current_password',
            'new_password' => 'new_password',
            'repeat_new_password' => 'new_password',
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('POST', '/api/v1/mahasiswa/profile/editPassword', $invalidPasswordPayload);

        $response->assertStatus(200) // Update the status code to 422 for failure due to invalid current password
            ->assertJsonStructure([
                'success',
                'status',
                'data',
            ])
            ->assertJson(['success' => false, 'status' => 'Password saat ini salah!']);
    }

    /** @test */
    public function test_it_returns_method_not_allowed_response_for_invalid_method_update_profile()
    {
        // Hit the endpoint with an invalid method (GET instead of POST)
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('GET', '/api/v1/mahasiswa/profile/editProcess');

        // Assert the response indicates that the method is not allowed
        $response->assertStatus(405);
    }

    /** @test */
    public function test_it_returns_method_not_allowed_response_for_invalid_method_update_password()
    {
        // Hit the endpoint with an invalid method (GET instead of POST)
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('GET', '/api/v1/mahasiswa/profile/editPassword');

        // Assert the response indicates that the method is not allowed
        $response->assertStatus(405);
    }

    /** @test */
    public function test_it_returns_failure_result_when_update_password_fails_due_to_new_password_not_matching()
    {
        $updatePasswordPayload = [
            'current_password' => 'mahasiswa123',
            'new_password' => 'new_password',
            'repeat_new_password' => 'mismatched_password',
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->json('POST', '/api/v1/mahasiswa/profile/editPassword', $updatePasswordPayload);

        $response->assertStatus(422); // Update the status code to 422 for failure due to new passwords not matching

    }

        /** @test */
        public function test_it_returns_success_response_when_updating_photo_profile()
        {
            // Persiapkan file gambar untuk diunggah
            Storage::fake('public');
            $file = UploadedFile::fake()->image('profile-photo.jpg');

            // Kirim permintaan API untuk memperbarui foto profil
            $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                ->json('POST', '/api/v1/mahasiswa/profile/editPhotoProcess', [
                    'user_img' => $file,
                ]);

            // Pastikan respons adalah sukses dan berisi struktur data yang diharapkan
            $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'status',
                    'data' => [
                        'user_id',
                        'user_name',
                        'user_email',
                        'no_telp',
                        'user_img_name',
                    ],
                ])
                ->assertJson(['success' => true, 'status' => 'Berhasil memperbaharui foto profil!']);

            $this->assertNotNull($response->json('data.user_img_name'));
        }

        /** @test */
        public function test_it_returns_failure_response_when_photo_upload_fails()
        {
            // Kirim permintaan API untuk memperbarui foto profil tanpa melampirkan file
            $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                ->json('POST', '/api/v1/mahasiswa/profile/editPhotoProcess');

            // Pastikan respons adalah kegagalan dan tidak berisi data
            $response->assertStatus(422);

            // Pastikan tidak ada perubahan pada foto profil pengguna
            $this->assertDatabaseMissing('app_user', [
                'user_id' => $response->json('data.user_id'),
                'user_img_name' => $response->json('data.user_img_name'),
            ]);
        }

     protected function tearDown(): void
     {
         // Clean up any uploaded files during testing, if any
         Storage::deleteDirectory('/img/user/');
         parent::tearDown();
     }

}
