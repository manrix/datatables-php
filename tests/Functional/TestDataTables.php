<?php

namespace Tests\Functional;

use Manrix\DataTables\Adapter\DatabaseAdapter;
use Manrix\DataTables\DataTables;
use Manrix\DataTables\Request;
use Manrix\DataTables\ResultCollection;
use PDO;
use PHPUnit\Framework\TestCase;

class TestDataTables extends TestCase
{
    protected $pdo;
    protected $datatables;
    protected $columns = [
        [
            'name' => 'id',
            'searchable' => false,
            'orderable' => true,
        ],
        [
            'name' => 'name',
            'searchable' => true,
            'orderable' => true,
        ],
    ];

    protected function setUp()
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $this->pdo->query("CREATE TABLE `users` (`id` int(11) NOT NULL, `name` varchar(255) NOT NULL)");
        $this->pdo->query("DELETE FROM users");
        $this->pdo->query("INSERT INTO users (id, name) VALUES (1, 'admin')");
        $this->pdo->query("INSERT INTO users (id, name) VALUES (2, 'moderator')");
        $this->pdo->query("INSERT INTO users (id, name) VALUES (3, 'user')");

        $adapter = new DatabaseAdapter($this->pdo);
        $this->datatables = new DataTables($adapter);
    }

    public function test_it_returns_not_empty_result()
    {
        $request = new Request(1, $this->columns, 0, 10);

        $result = $this->datatables->getData($request, ['id', 'name'], ['table' => 'users']);

        $this->assertInstanceOf(ResultCollection::class, $result);
        $this->assertEquals(3, count($result->toArray()['data']));
    }

    public function test_order_is_applied()
    {
        $request = new Request(1, $this->columns, 0, 1, [
            [
                'column' => 0,
                'dir' => 'desc'
            ]
        ]);

        $result = $this->datatables->getData($request, ['id', 'name'], ['table' => 'users']);

        $this->assertInstanceOf(ResultCollection::class, $result);
        $this->assertEquals('user', $result->toArray()['data'][0]['name']);
    }

    public function test_pagination_is_applied()
    {
        $request = new Request(1, $this->columns, 0, 2);

        $result = $this->datatables->getData($request, ['id', 'name'], ['table' => 'users']);

        $this->assertInstanceOf(ResultCollection::class, $result);
        $this->assertEquals(2, count($result->toArray()['data']));
    }

    public function test_global_filter_is_applied()
    {
        $request = new Request(1, $this->columns, 0, 10, [], 'admin');

        $result = $this->datatables->getData($request, ['id', 'name'], ['table' => 'users']);

        $this->assertInstanceOf(ResultCollection::class, $result);
        $this->assertEquals(1, $result->toArray()['recordsFiltered']);
    }

    public function test_local_filter_is_applied()
    {
        $columns = $this->columns;
        $columns[1]['search'] = [
            'value' => 'admin'
        ];

        $request = new Request(1, $columns, 0, 10, []);

        $result = $this->datatables->getData($request, ['id', 'name'], ['table' => 'users']);

        $this->assertInstanceOf(ResultCollection::class, $result);
        $this->assertEquals(1, $result->toArray()['recordsFiltered']);
    }
}