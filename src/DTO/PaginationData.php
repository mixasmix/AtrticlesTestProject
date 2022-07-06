<?php

namespace App\DTO;

class PaginationData
{
    /**
     * @param int|null $limit
     * @param int|null $offset
     */
    public function __construct(private ?int $limit, private ?int $offset)
    {
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
