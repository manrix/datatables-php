<?php

namespace Tests\Unit\Adapter;

use Manrix\DataTables\Adapter\NullAdapter;
use Manrix\DataTables\Request;
use Manrix\DataTables\ResultCollection;
use PHPUnit\Framework\TestCase;

class TestNullAdapter extends TestCase
{
    public function test_null_adapter_instance_is_created()
    {
        $adapter = new NullAdapter();

        $this->assertInstanceOf(NullAdapter::class, $adapter);

        return $adapter;
    }

    /**
     * @depends test_null_adapter_instance_is_created
     * @param NullAdapter $adapter
     */
    public function test_null_adapter_returns_an_empty_result($adapter)
    {
        $request = new Request(1, []);

        $result = $adapter->getData($request, []);
        $resultArray = $result->toArray();

        $this->assertInstanceOf(ResultCollection::class, $result);
        $this->assertArrayHasKey('data', $resultArray);
        $this->assertEmpty($resultArray['data']);
    }
}