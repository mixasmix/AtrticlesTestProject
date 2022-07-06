<?php

namespace App\DTO;

class PaginationData
{
    /**
     * @var int|null
     */
    private ?int $limit;

    /**
     * @var int|null
     */
    private ?int $offset;

    /**
     * @param int|null $limit
     * @param int|null $offset
     */
    public function __construct(?int $limit, ?int $offset)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @return int|null
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }
}
