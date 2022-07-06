<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\Author;
use App\Entity\Category;
use App\Entity\Vote;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

class ArticleService
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param Article  $article
     * @param Category $category
     *
     * @return Article
     */
    public function addCategory(Article $article, Category $category): Article
    {
        $article->addCategory($category);

        $this->entityManager->flush();

        return $article;
    }

    /**
     * @param Article $article
     * @param Vote    $vote
     *
     * @return Article
     */
    public function addVote(Article $article, Vote $vote): Article
    {
        $article->addVote($vote);

        $this->entityManager->flush();

        return $article;
    }

    /**
     * @param Author $author
     * @param string $title
     * @param array  $categories
     *
     * @return Article
     *
     * @throws Throwable
     */
    public function create(Author $author, string $title, array $categories): Article
    {
        $this->entityManager->beginTransaction();

        try {
            $article = new Article(
                title: $title,
                author: $author,
                categories: $categories,
            );

            $this->entityManager->persist($article);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Throwable $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }

        return $article;
    }
}
