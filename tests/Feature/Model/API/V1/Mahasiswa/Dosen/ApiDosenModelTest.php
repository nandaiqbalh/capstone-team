<?php

namespace Tests\Unit\Models\Api\Mahasiswa\Dosen;

use Tests\TestCase;
use App\Models\Api\Mahasiswa\Dosen\ApiDosenModel;

class ApiDosenModelTest extends TestCase
{
    /** @test */
    public function test_it_can_get_data()
    {
        // Panggil method getData dari ApiDosenModel
        $data = ApiDosenModel::getDataDosbing1();

        // Pastikan data tidak kosong
        $this->assertNotEmpty($data);

        // Pastikan data adalah instance dari koleksi (collection)
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $data);

    }
    public function test_it_can_get_data_dosbing2()
    {
        // Panggil method getData dari ApiDosenModel
        $data = ApiDosenModel::getDataDosbing2();

        // Pastikan data tidak kosong
        $this->assertNotEmpty($data);

        // Pastikan data adalah instance dari koleksi (collection)
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $data);

    }

}
