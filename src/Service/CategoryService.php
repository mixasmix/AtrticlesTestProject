<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

class CategoryService
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param Category $category
     * @param Article  $article
     *
     * @return Category
     */
    public function addArticle(Category $category, Article $article): Category
    {
        $category->addArticle($article);

        $this->entityManager->flush();

        return $category;
    }

    /**
     * @param string $title
     *
     * @return Category
     *
     * @throws Throwable
     */
    public function create(string $title): Category
    {
        $this->entityManager->beginTransaction();

        try {
            $category = new Category($title);

            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Throwable $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }

        return $category;
    }
}

