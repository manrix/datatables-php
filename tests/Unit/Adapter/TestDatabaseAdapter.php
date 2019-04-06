<?php

namespace Tests\Unit\Adapter;

use Manrix\DataTables\Adapter\DatabaseAdapter;
use Manrix\DataTables\Request;
use PDO;
use PHPUnit\Framework\TestCase;

class TestDatabaseAdapter extends TestCase
{
    protected $pdo;

    protected function setUp()
    {
        $this->pdo = new PDO('sqlite::memory:');
    }

    public function test_database_adapter_instance_is_created()
    {
        $adapter = new DatabaseAdapter($this->pdo);

        $this->assertInstanceOf(DatabaseAdapter::class, $adapter);

        return $adapter;
    }

    /**
     * @depends test_database_adapter_instance_is_created
     * @param DatabaseAdapter $adapter
     */
    public function test_exception_if_no_table_specified($adapter)
    {
        $this->expectException(\InvalidArgumentException::class);

        $request = new Request(1, []);

        $adapter->getData($request, []);
    }
}