<?php

namespace Manrix\DataTables;

use Manrix\DataTables\Adapter\AbstractAdapter;

class DataTables
{
    /**
     * @var AbstractAdapter $adapter
     */
    protected $adapter;

    public function __construct(AbstractAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Call adapter getData
     *
     * @param Request $request
     * @param array $columns
     * @param array $options
     * @return ResultCollection
     */
    public function getData(Request $request, array $columns, array $options = []): ResultCollection
    {
        return $this->adapter->getData($request, $columns, $options);
    }
}
