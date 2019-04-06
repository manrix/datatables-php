<?php

namespace Manrix\DataTables;

class Request
{
    /**
     * @var int $draw
     */
    protected $draw;

    /**
     * @var int $draw
     */
    protected $start;

    /**
     * @var int $draw
     */
    protected $length;

    /**
     * @var array $columns
     */
    protected $columns;

    /**
     * @var array $order
     */
    protected $order;

    /**
     * @var string $searchValue
     */
    protected $searchValue;

    /**
     * @var bool $regex
     */
    protected $regex;

    public function __construct(
        int $draw,
        array $columns,
        int $start = null,
        int $length = null,
        array $order = [],
        string $searchValue = null,
        bool $regex = false
    )
    {
        $this->draw = $draw;
        $this->start = $start;
        $this->length = $length;
        $this->columns = $columns;
        $this->order = $order;
        $this->searchValue = $searchValue;
        $this->regex = $regex;
    }

    public function getDraw(): int
    {
        return (int)$this->draw;
    }

    public function getStart(): int
    {
        return (int)$this->start;
    }

    public function getLength(): int
    {
        return (int)$this->length;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getOrder(): array
    {
        return $this->order;
    }

    public function getSearchValue(): string
    {
        return (string)$this->searchValue;
    }

    public function getRegex(): bool
    {
        return (bool)$this->regex;
    }

    public static function fromArray(array $data)
    {
        if (!isset($data['draw'])) {
            throw new \InvalidArgumentException("Missing draw parameter");
        }

        if (!isset($data['columns'])) {
            throw new \InvalidArgumentException("Missing columns parameter");
        }

        $draw = $data['draw'];
        $column = $data['columns'];
        $start = isset($data['start']) ? $data['start'] : null;
        $length = isset($data['length']) ? $data['length'] : null;
        $order = isset($data['order']) ? $data['order'] : [];
        $searchValue = isset($data['searchValue']) ? $data['searchValue'] : null;
        $regex = isset($data['regex']) ? $data['regex'] : false;

        return new self($draw, $column, $start, $length, $order, $searchValue, $regex);
    }
}
