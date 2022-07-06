<?php

namespace App\DTO;

class GetArticlesFilterData
{
    /**
     * @var array
     */
    private array $categoryIds;

    /**
     * @param array $categoryIds
     */
    public function __construct(array $categoryIds)
    {
        $this->categoryIds = $categoryIds;
    }

    /**
     * @return array
     */
    public function getCategoryIds(): array
    {
        return $this->categoryIds;
    }
}
