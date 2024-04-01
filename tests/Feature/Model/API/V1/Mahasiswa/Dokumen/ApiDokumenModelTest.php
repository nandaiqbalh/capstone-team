<?php

namespace Tests\Unit\Models\Api\Mahasiswa\Dokumen;

use Tests\TestCase;
use App\Models\Api\Mahasiswa\Dokumen\ApiDokumenModel;
use App\Models\Api\Mahasiswa\TugasAkhir\ApiTugasAkhirModel;
use Illuminate\Support\Facades\DB;

class ApiDokumenModelTest extends TestCase
{

    protected $token;
    protected $user_id;

    protected function setUp(): void
    {
        parent::setUp();

        // Login untuk mendapatkan token
        $loginPayload = [
            'nomor_induk' => '21120120130124',
            'password' => 'mahasiswa123',
        ];

        $loginResponse = $this->json('POST', '/api/v1/auth/login/', $loginPayload);

        // Ambil token dari respons login
        $this->token = $loginResponse->json('data.api_token');
        $this->user_id = $loginResponse->json('data.user_id');
    }

    /** @test */
    public function test_it_can_get_user_by_id()
    {

        // Panggil method getById dari ApiDokumenModel
        $result = ApiDokumenModel::getById($this->user_id);

        // Pastikan hasilnya adalah instance dari stdClass
        $this->assertInstanceOf(\stdClass::class, $result);

        // Pastikan hasilnya sesuai dengan user yang dibuat
        $this->assertEquals($this->user_id, $result->user_id);
    }

    /** @test */
    public function test_it_can_get_kelompok_file_by_id()
    {
        // Menyiapkan data kelompok mahasiswa
        $kelompok = ApiTugasAkhirModel::pengecekan_kelompok_mahasiswa($this->user_id);

        // Panggil method getKelompokFile dari ApiDokumenModel
        $result = ApiDokumenModel::getKelompokFile($kelompok->id_kelompok);

        // Pastikan hasilnya adalah instance dari stdClass
        $this->assertInstanceOf(\stdClass::class, $result);

        // Pastikan hasilnya sesuai dengan data kelompok yang dibuat
        $this->assertEquals($kelompok->judul_capstone, $result->judul_capstone);
    }

    /** @test */
    public function test_it_can_get_file_by_user_id()
    {
        // Panggil method getById dari ApiDokumenModel
        $mahasiswa = ApiDokumenModel::getById($this->user_id);

        // Panggil method fileMHS dari ApiDokumenModel
        $result = ApiDokumenModel::fileMHS($mahasiswa->user_id);

        // Pastikan hasilnya adalah instance dari stdClass
        $this->assertInstanceOf(\stdClass::class, $result);

        // Pastikan hasilnya sesuai dengan data mahasiswa yang dibuat
        $this->assertEquals($mahasiswa->user_id, $result->id_mahasiswa);
    }

    /** @test */
    public function test_it_can_upload_file_for_mahasiswa()
    {
         // Panggil method getById dari ApiDokumenModel
         $mahasiswa = ApiDokumenModel::getById($this->user_id);

        // Parameter untuk diupdate
        $params = [
            'file_name_makalah' => 'test_makalah.pdf',
            'file_path_makalah' => '/path/to/makalah.pdf',
            'file_name_laporan_ta' => 'test_laporan_ta.pdf',
            'file_path_laporan_ta' => '/path/to/laporan_ta.pdf',
        ];

        // Panggil method uploadFileMHS dari ApiDokumenModel
        $result = ApiDokumenModel::uploadFileMHS($mahasiswa->user_id, $params);

        // Pastikan hasilnya adalah true atau tidak false
        $this->assertTrue($result !== false);
    }

    /** @test */
    public function test_it_can_upload_file_for_kelompok()
    {
        // Menyiapkan data kelompok mahasiswa
        $kelompok = ApiTugasAkhirModel::pengecekan_kelompok_mahasiswa($this->user_id);

        // Parameter untuk diupdate
        $params = [
            'file_name_c100' => 'test_c100.pdf',
            'file_path_c100' => '/path/to/c100.pdf',
            'file_name_c200' => 'test_c200.pdf',
            'file_path_c200' => '/path/to/c200.pdf',
            'file_name_c300' => 'test_c300.pdf',
            'file_path_c300' => '/path/to/c300.pdf',
            'file_name_c400' => 'test_c400.pdf',
            'file_path_c400' => '/path/to/c400.pdf',
            'file_name_c500' => 'test_c500.pdf',
            'file_path_c500' => '/path/to/c500.pdf',
        ];

        // Panggil method uploadFileKel dari ApiDokumenModel
        $result = ApiDokumenModel::uploadFileKel($kelompok->id, $params);

        // Pastikan hasilnya adalah true atau tidak false
        $this->assertTrue($result !== false);
    }
}
