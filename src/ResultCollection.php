<?php

namespace Manrix\DataTables;

class ResultCollection
{
    /**
     * @var int $draw
     */
    protected $draw;

    /**
     * @var int $recordsTotal
     */
    protected $recordsTotal;

    /**
     * @var int $recordsFiltered
     */
    protected $recordsFiltered;

    /**
     * @var array $data
     */
    protected $data;

    public function __construct(
        int $draw,
        int $recordsTotal,
        int $recordsFiltered,
        array $data = []
    )
    {
        $this->draw = $draw;
        $this->recordsTotal = $recordsTotal;
        $this->recordsFiltered = $recordsFiltered;
        $this->data = $data;
    }

    public function toArray()
    {
        return [
            'draw' => $this->draw,
            'recordsTotal' => $this->recordsTotal,
            'recordsFiltered' => $this->recordsFiltered,
            'data' => $this->data,
        ];
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function __toString()
    {
        return $this->toJson();
    }
}
