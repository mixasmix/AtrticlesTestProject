<?php

namespace App\Controller;

use App\DTO\CreateArticleData;
use App\DTO\CreateCategoryData;
use App\DTO\CreateUserData;
use App\DTO\GetArticlesFilterData;
use App\DTO\PaginationData;
use App\Entity\Article;
use App\Entity\Author;
use App\Entity\User;
use App\Entity\Vote;
use App\Facade\ArticleFacade;
use App\Facade\CategoryFacade;
use App\Facade\UserFacade;
use App\Repository\ArticleRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route(path: '/api/v1')]
class ApiController extends AbstractController
{
    /**
     * @param ArticleFacade     $articleFacade
     * @param UserFacade        $userFacade
     * @param CategoryFacade    $categoryFacade
     * @param ArticleRepository $articleRepository
     */
    public function __construct(
        private ArticleFacade $articleFacade,
        private UserFacade $userFacade,
        private CategoryFacade $categoryFacade,
        private ArticleRepository $articleRepository
    ) {
    }

    /**
     * @param CreateUserData $userData
     *
     * @return JsonResponse
     *
     * @throws Throwable
     */
    #[Route(path: '/user', methods: ['POST'])]
    public function createUser(CreateUserData $userData): JsonResponse
    {
        return $this->json([
            'data' => $this->userFacade->createUser($userData),
        ], Response::HTTP_CREATED);
    }

    /**
     * @param User $user
     *
     * @return JsonResponse
     *
     * @throws Throwable
     */
    #[Route(path: '/user/{user_id}/author', methods: ['POST'])]
    #[Entity(
        data: 'user',
        expr: 'repository.getById(user_id)',
    )]
    public function createAuthor(User $user): JsonResponse
    {
        return $this->json([
            'data' => $this->userFacade->createAuthor($user),
        ], Response::HTTP_CREATED);
    }

    /**
     * @param CreateCategoryData $categoryData
     *
     * @return JsonResponse
     *
     * @throws Throwable
     */
    #[Route(path: '/category', methods: ['POST'])]
    public function createCategory(
        CreateCategoryData $categoryData
    ): JsonResponse {
        return $this->json([
            'data' => $this->categoryFacade->create($categoryData),
        ], Response::HTTP_CREATED);
    }

    /**
     * @param Author            $author
     * @param CreateArticleData $articleData
     *
     * @return JsonResponse
     *
     * @throws Throwable
     */
    //надо тут айдишник юзера отвалидировать, но лень...
    #[Route(path: '/user/{user_id}/article', methods: ['POST'])]
    #[Entity(
        data: 'author',
        expr: 'repository.getAuthorById(user_id)',
    )]
    public function createArticle(
        Author $author,
        CreateArticleData $articleData
    ): JsonResponse {
        return $this->json([
            'data' => $this->articleFacade->createArticle(
                $author,
                $articleData,
            ),
        ], Response::HTTP_CREATED);
    }

    /**
     * @param User    $user
     * @param Article $article
     *
     * @return JsonResponse
     *
     * @throws Throwable
     */
    #[Route(path: '/user/{user_id}/article/{article_id}/vote', methods: ['POST'])]
    #[Entity(
        data: 'user',
        expr: 'repository.getById(user_id)',
    )]
    #[Entity(
        data: 'article',
        expr: 'repository.getById(article_id)',
    )]
    public function createVote(
        User $user,
        Article $article,
    ): JsonResponse {
        return $this->json([
            //в фасаде статей потому что под голосовалку отдельный фасад заводить жирно,
            // а голосовалка в контексте статьи
            'data' => $this->articleFacade->createVote(
                $user,
                $article,
            ),
        ], Response::HTTP_CREATED);
    }

    /**
     * @param User    $user
     * @param Article $article
     * @param Vote    $vote
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    #[Route(path: '/user/{user_id}/article/{article_id}/vote/{vote_id}', methods: ['DELETE'])]
    #[Entity(
        data: 'user',
        expr: 'repository.getById(user_id)',
    )]
    #[Entity(
        data: 'article',
        expr: 'repository.getById(article_id)',
    )]
    #[Entity(
        data: 'vote',
        expr: 'repository.getById(vote_id)',
    )]
    public function deleteVote(
        User $user,
        Article $article,
        Vote $vote
    ): JsonResponse {
        if ($vote->getUser()->getId() !== $user->getId()) {
            throw new Exception('Лайк не принадлежит пользователю');
        }

        if ($vote->getArticle()->getId() !== $article->getId()) {
            throw new Exception('Лайк не принадлежит статье');
        }

        $this->articleFacade->deleteVote($vote);

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/article/{article_id}/vote', methods: ['GET'])]
    #[Entity(
        data: 'article',
        expr: 'repository.getById(user_id)',
    )]
    public function getArticleVotes(
        Article $article
    ): JsonResponse {
        return $this->json([
            'data' => $article->getVotes()->map(
                fn (Vote $vote): User => $vote->getUser(),
            )->toArray(),
        ]);
    }

    /**
     * @param GetArticlesFilterData $filterData
     * @param PaginationData        $paginationData
     *
     * @return JsonResponse
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    #[Route(path: '/article', methods: ['GET'])]
    public function getArticlesByFilter(
        GetArticlesFilterData $filterData,
        PaginationData $paginationData
    ): JsonResponse {
        return $this->json([
            'data' => [
                'items' => $this->articleRepository->getByFilter(
                    $filterData->getCategoryIds(),
                    $paginationData->getLimit(),
                    $paginationData->getOffset(),
                ),
                'items_count' => $this->articleRepository->getByFilterCount(
                    $filterData->getCategoryIds(),
                ),
            ],
        ]);
    }
}
