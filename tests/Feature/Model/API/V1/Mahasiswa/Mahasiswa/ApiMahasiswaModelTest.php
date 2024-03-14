<?php

namespace Tests\Feature\Model\API\V1\Mahasiswa\Mahasiswa;

use Tests\TestCase;
use App\Models\Api\Mahasiswa\Mahasiswa\ApiMahasiswaModel;

class ApiMahasiswaModelTest extends TestCase
{
    /** @test */
    public function test_it_can_get_data_mahasiswa_available()
    {
        // Panggil method getDataMahasiswaAvailable dari ApiMahasiswaModel
        $data = ApiMahasiswaModel::getDataMahasiswaAvailable();

        // Pastikan data tidak kosong
        $this->assertNotEmpty($data);

        // Pastikan data adalah instance dari koleksi (collection)
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $data);
    }
}
