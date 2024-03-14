<?php

namespace Tests\Unit\Api\Mahasiswa\Broadcast;

use Tests\TestCase;
use App\Models\Api\Mahasiswa\Broadcast\ApiBroadcastModel;

class ApiBroadcastModelTest extends TestCase
{
    public function testGetData()
    {
        // Perform any necessary setup for the test

        $result = ApiBroadcastModel::getData();

        $this->assertNotNull($result);
        $this->assertIsIterable($result);

    }

    public function testGetDataWithPagination()
    {

        $result = ApiBroadcastModel::getDataWithPagination();

        $this->assertNotNull($result);
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);

    }

    public function testGetDataWithHomePagination()
    {

        $result = ApiBroadcastModel::getDataWithHomePagination();

        $this->assertNotNull($result);
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);

    }

    public function testGetDataById()
    {

        $broadcastId = 1; // Replace with a valid broadcast ID for testing
        $result = ApiBroadcastModel::getDataById($broadcastId);

        $this->assertNotNull($result);

    }
}
