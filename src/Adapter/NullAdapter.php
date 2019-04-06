<?php

namespace Manrix\DataTables\Adapter;

use Manrix\DataTables\Request;
use Manrix\DataTables\ResultCollection;

class NullAdapter extends AbstractAdapter
{
    /**
     * @inheritDoc
     */
    public function getData(Request $request, array $columns, array $options = []): ResultCollection
    {
        return new ResultCollection(0, 0, 0, []);
    }

    /**
     * @inheritDoc
     */
    protected function filter(Request $request, array $columns)
    {
        //
    }

    /**
     * @inheritDoc
     */
    protected function order(Request $request, array $columns)
    {
        //
    }

    /**
     * @inheritDoc
     */
    protected function paginate(Request $request)
    {
        //
    }
}