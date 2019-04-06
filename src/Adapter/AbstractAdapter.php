<?php

namespace Manrix\DataTables\Adapter;

use Manrix\DataTables\Request;
use Manrix\DataTables\ResultCollection;

abstract class AbstractAdapter
{
    /**
     * Retrieve data from source
     *
     * @param Request $request
     * @param array $columns
     * @param array $options
     * @return ResultCollection
     */
    abstract public function getData(Request $request, array $columns, array $options): ResultCollection;

    /**
     * Apply filter
     *
     * @param Request $request
     * @param array $columns
     * @return mixed
     */
    abstract protected function filter(Request $request, array $columns);

    /**
     * Apply order
     *
     * @param Request $request
     * @param array $columns
     * @return mixed
     */
    abstract protected function order(Request $request, array $columns);

    /**
     * Apply pagination
     *
     * @param Request $request
     * @return mixed
     */
    abstract protected function paginate(Request $request);

    /**
     * Format output
     *
     * @param array $columns
     * @param array $data
     * @return array
     */
    protected function normalizeData(array $columns, array $data): array
    {
        $result = [];
        for ($i = 0; $i < count($data); $i++) {
            $row = [];
            for ($h = 0; $h < count($columns); $h++) {
                $row[$columns[$h]] = $data[$i][$columns[$h]];
            }

            $result[] = $row;
        }

        return $result;
    }
}