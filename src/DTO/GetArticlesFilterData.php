<?php

namespace App\DTO;

class GetArticlesFilterData
{
    /**
     * @param array $categoryIds
     */
    public function __construct(private array $categoryIds)
    {
    }

    /**
     * @return array
     */
    public function getCategoryIds(): array
    {
        return $this->categoryIds;
    }
}
