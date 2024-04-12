<?php

namespace Tests\Unit\Models\Api\Mahasiswa\Kelompok;

use Tests\TestCase;
use App\Models\Api\Mahasiswa\Kelompok\ApiKelompokModel;

class ApiKelompokModelTest extends TestCase
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
    public function test_it_can_do_pengecekan_kelompok_mahasiswa()
    {
        $kelompok = ApiKelompokModel::pengecekan_kelompok_mahasiswa($this->user_id);

        $this->assertNotNull($kelompok);
    }

    /** @test */
    public function test_it_can_list_kelompok_mahasiswa()
    {
        $kelompok = ApiKelompokModel::pengecekan_kelompok_mahasiswa($this->user_id);

        $id_kelompok = $kelompok->id;
        $result = ApiKelompokModel::listKelompokMahasiswa($id_kelompok);

        $this->assertNotNull($result);
    }

    /** @test */
    public function test_it_can_get_akun_by_id()
    {
        $result = ApiKelompokModel::getAkunByID($this->user_id);

        $this->assertNotNull($result);
    }

    /** @test */
    public function test_it_can_check_if_account_exists()
    {
        $result = ApiKelompokModel::isAccountExist($this->user_id);

        $this->assertNotNull($result);
    }

    /** @test */
    public function test_it_can_get_akun_dosbing_kelompok()
    {
        $id_kelompok = 1;

        $result = ApiKelompokModel::getAkunDosbingKelompok($id_kelompok);

        $this->assertNotNull($result);
    }

    /** @test */
    public function test_it_can_get_akun_dospeng_kelompok()
    {
        $id_kelompok = 1;

        $result = ApiKelompokModel::getAkunDospengKelompok($id_kelompok);

        $this->assertNotNull($result);
    }

    /** @test */
    public function test_it_can_get_akun_dospeng_ta()
    {
        $result = ApiKelompokModel::getAkunDospengTa($this->user_id);

        $this->assertNotNull($result);
    }

    /** @test */
    public function test_it_can_get_topik()
    {
        $result = ApiKelompokModel::getTopik();

        $this->assertNotNull($result);
    }

    /** @test */
    public function test_it_can_get_topik_by_id()
    {
        $id = 1;

        $result = ApiKelompokModel::getTopikById($id);

        $this->assertNotNull($result);
    }

    /** @test */
    public function test_it_can_get_peminatan_by_id()
    {
        $id = 1;

        $result = ApiKelompokModel::getPeminatanById($id);

        $this->assertNotNull($result);
    }

    /** @test */
    public function test_it_can_check_if_siklus_still_active()
    {
        $kelompok = ApiKelompokModel::pengecekan_kelompok_mahasiswa($this->user_id);

        $id_siklus = $kelompok->id_siklus;

        $result = ApiKelompokModel::checkApakahSiklusMasihAktif($id_siklus);

        $this->assertNotNull($result);
    }

    /** @test */
    public function test_it_can_insert_kelompok()
    {
        $params = [];

        $result = ApiKelompokModel::insertKelompok($params);

        $this->assertTrue($result);
    }

    /** @test */
    public function test_it_can_insert_kelompok_mhs()
    {
        $params = [];

        $result = ApiKelompokModel::insertKelompokMHS($params);

        $this->assertTrue($result);
    }

    /** @test */
    public function test_it_can_update_mahasiswa()
    {
        $params = [
            'user_name' => 'Iwan Test',
        ];

        $result = ApiKelompokModel::updateMahasiswa($this->user_id, $params);

         // Pastikan hasilnya adalah true atau tidak false
         $this->assertTrue($result !== false);
    }
}
