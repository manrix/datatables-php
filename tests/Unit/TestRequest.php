<?php

namespace Tests\Unit;

use Manrix\DataTables\Request;
use PHPUnit\Framework\TestCase;

class TestRequest extends TestCase
{
    public function test_request_class_is_instantiated()
    {
        $request = new Request(1, []);

        $this->assertInstanceOf(Request::class, $request);
    }

    public function test_request_is_instantiated_from_array()
    {
        $request = Request::fromArray([
            'draw' => 0,
            'columns' => [
                [
                    'name' => 'id',
                    'searchable' => false,
                    'orderable' => true,
                ],
            ],
        ]);

        $this->assertInstanceOf(Request::class, $request);
    }
}