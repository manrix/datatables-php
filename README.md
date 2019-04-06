# PHP DataTables wrapper

A simple php wrapper for the DataTables plugin with support for multiple source adapters.

## Usage

Example with the database adapter:

    $pdo = new \PDO('sqlite::memory:');
    $adapter = new Manrix\DataTables\Adapter\DatabaseAdapter($pdo);
    $datatables = new Manrix\DataTables\DataTables($adapter);
    
    $columns = [
        [
            'name' => 'id',
            'searchable' => false,
            'orderable' => true,
        ],
        [
            'name' => 'name',
            'searchable' => true,
            'orderable' => true,
        ]
    ];
    $draw = 0;
    $start = 0;
    $length = 10;
    $request = new Request($draw, $columns, $start, $length);
    $result = $datatables->getData($request, ['id', 'name'], ['table' => 'users']);
