<?php

namespace App\Facade;

use App\DTO\CreateCategoryData;
use App\Entity\Category;
use App\Service\CategoryService;
use Throwable;

class CategoryFacade
{
    /**
     * @param CategoryService $categoryService
     */
    public function __construct(private CategoryService $categoryService)
    {
    }

    /**
     * @param CreateCategoryData $categoryData
     *
     * @return Category
     *
     * @throws Throwable
     */
    public function create(CreateCategoryData $categoryData): Category
    {
        return $this->categoryService->create($categoryData->getTitle());
    }
}
