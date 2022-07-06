<?php

namespace App\DTO;

class CreateArticleData
{
    /**
     * @param string $title
     * @param array  $categoryIds
     */
    public function __construct(private string $title, private array $categoryIds)
    {
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public function getCategoryIds(): array
    {
        return $this->categoryIds;
    }
}
