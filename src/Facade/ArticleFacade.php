<?php

namespace App\Facade;

use App\DTO\CreateArticleData;
use App\Entity\Article;
use App\Entity\Author;
use App\Entity\Category;
use App\Entity\User;
use App\Entity\Vote;
use App\Repository\CategoryRepository;
use App\Service\ArticleService;
use App\Service\CategoryService;
use App\Service\VoteService;
use Throwable;

class ArticleFacade
{
    /**
     * @param CategoryRepository $categoryRepository
     * @param ArticleService     $articleService
     * @param CategoryService    $categoryService
     * @param VoteService        $voteService
     */
    public function __construct(
        private CategoryRepository $categoryRepository,
        private ArticleService $articleService,
        private CategoryService $categoryService,
        private VoteService $voteService
    ) {
    }

    /**
     * @param Author            $author
     * @param CreateArticleData $articleData
     *
     * @return Article
     *
     * @throws Throwable
     */
    public function createArticle(
        Author $author,
        CreateArticleData $articleData
    ): Article {
        $categories = $this->categoryRepository->getByIds(
            $articleData->getCategoryIds(),
        );

        $article = $this->articleService->create(
            $author,
            $articleData->getTitle(),
            $categories,
        );

        //добавляем каждую статью в категорию и каждую категорию в статью
        array_map(
            function (Category $category) use ($article): Category {
                $this->categoryService->addArticle(
                    $category,
                    $article,
                );

                $this->articleService->addCategory(
                    $article,
                    $category,
                );

                return $category;
            },
            $categories,
        );

        return $article;
    }

    /**
     * @param User    $user
     * @param Article $article
     *
     * @return Vote
     *
     * @throws Throwable
     */
    public function createVote(User $user, Article $article): Article
    {
        $vote = $this->voteService->create(
            $user,
            $article,
        );

        return $this->articleService->addVote(
            $article,
            $vote,
        );
    }

    /**
     * @param Vote $vote
     *
     * @return void
     */
    public function deleteVote(
        Vote $vote
    ): void {
        $this->voteService->delete($vote);
    }
}
