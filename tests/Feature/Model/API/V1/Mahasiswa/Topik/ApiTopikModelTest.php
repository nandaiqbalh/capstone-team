<?php

namespace Tests\Unit\Models\Api\Mahasiswa\Topik;

use Tests\TestCase;
use App\Models\Api\Mahasiswa\Topik\ApiTopikModel;

class ApiTopikModelTest extends TestCase
{
    /** @test */
    public function test_it_can_get_topik()
    {
        // Panggil method getTopik dari ApiTopikModel
        $data = ApiTopikModel::getTopik();

        // Pastikan data tidak kosong
        $this->assertNotEmpty($data);

        // Pastikan data adalah instance dari koleksi (collection)
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $data);
    }
}
