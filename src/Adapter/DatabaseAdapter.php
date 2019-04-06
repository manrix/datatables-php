<?php

namespace Manrix\DataTables\Adapter;

use Manrix\DataTables\Request;
use Manrix\DataTables\ResultCollection;

class DatabaseAdapter extends AbstractAdapter
{
    /**
     * @var \PDO $pdo
     */
    protected $pdo;

    /**
     * @var array $bindings
     */
    protected $bindings;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @inheritDoc
     */
    public function getData(Request $request, array $columns, array $options = []): ResultCollection
    {
        if (!isset($options['table'])) {
            throw new \InvalidArgumentException('No table specified');
        }

        $this->bindings = [];
        $filter = $this->filter($request, $columns);
        $order = $this->order($request, $columns);
        $paginate = $this->paginate($request);

        $query = $this->pdo->prepare(sprintf("SELECT COUNT(*) FROM `%s`", $options['table']));
        $query->execute();
        $totalRecords = (int)$query->fetchColumn();

        $query = [];
        $query[] = sprintf("SELECT COUNT(*) FROM `%s`", $options['table']);
        $query[] = $filter;

        $query = $this->pdo->prepare(implode(' ', $query));
        $query->execute($this->bindings);
        $totalRecordsFiltered = (int)$query->fetchColumn();

        $query = [];
        $query[] = sprintf("SELECT `%s` FROM `%s`", implode('`, `', $columns), $options['table']);
        $query[] = $filter;
        $query[] = $order;
        $query[] = $paginate;

        $query = $this->pdo->prepare(implode(' ', $query));
        $query->execute($this->bindings);

        $data = $query->fetchAll(\PDO::FETCH_ASSOC);
        $data = $this->normalizeData($columns, $data);

        return new ResultCollection($request->getDraw(), $totalRecords, $totalRecordsFiltered, $data);
    }

    /**
     * @inheritDoc
     */
    protected function filter(Request $request, array $columns)
    {
        $globalSearch = [];
        $columnSearch = [];
        $searchValue = $request->getSearchValue();
        if ($searchValue) {
            for ($i = 0; $i < count($request->getColumns()); $i++) {
                $requestColumn = $request->getColumns()[$i];
                if ($requestColumn['searchable'] == 'true' && in_array($requestColumn['name'], $columns)) {
                    $binding = '%' . $searchValue . '%';
                    $this->bindings[] = $binding;
                    $globalSearch[] = "`" . $requestColumn['name'] . "` LIKE ?";
                }
            }
        }

        // Individual column filtering
        if ($request->getColumns()) {
            for ($i = 0; $i < count($request->getColumns()); $i++) {
                $requestColumn = $request->getColumns()[$i];
                if ($requestColumn['searchable'] == 'true' &&
                    in_array($requestColumn['name'], $columns) &&
                    isset($requestColumn['search'])) {
                    $binding = '%' . $requestColumn['search']['value'] . '%';
                    $this->bindings[] = $binding;
                    $columnSearch[] = "`" . $requestColumn['name'] . "` LIKE ?";
                }
            }
        }

        // Combine the filters into a single string
        $where = '';
        if (count($globalSearch)) {
            $where = implode(' OR ', $globalSearch);
        }

        if (count($columnSearch)) {
            $where = $where === '' ?
                implode(' AND ', $columnSearch) :
                $where . ' AND ' . implode(' AND ', $columnSearch);
        }

        if ($where) {
            $where = 'WHERE ' . $where;
        }

        return $where;
    }

    /**
     * @inheritDoc
     */
    protected function order(Request $request, array $columns)
    {
        $order = '';

        if (is_array($request->getOrder()) && count($request->getOrder())) {
            $orderBy = [];
            for ($i = 0; $i < count($request->getOrder()); $i++) {
                if (isset($request->getOrder()[$i])) {
                    $columnIdx = (int)$request->getOrder()[$i]['column'];
                    $requestColumn = $request->getColumns()[$columnIdx];
                    if ($requestColumn['orderable'] == 'true' && in_array($requestColumn['name'], $columns)) {
                        $dir = $request->getOrder()[$i]['dir'] === 'asc' ? 'ASC' : 'DESC';
                        $orderBy[] = '`' . $requestColumn['name'] . '`' . $dir;
                    }
                }
            }

            if (count($orderBy)) {
                $order = 'ORDER BY ' . implode(', ', $orderBy);
            }
        }

        return $order;
    }

    /**
     * @inheritDoc
     */
    protected function paginate(Request $request)
    {
        $limit = '';
        if ($request->getStart() >= 0 && $request->getLength() !== -1) {
            $limit = sprintf("LIMIT %d, %d", $request->getStart(), $request->getLength());
        }

        return $limit;
    }
}