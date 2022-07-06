<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @param array      $categoryIds
     * @param int | null $limit
     * @param int | null $offset
     *
     * @return array
     */
    public function getByFilter(array $categoryIds, ?int $limit, ?int $offset = 0): array
    {
        $builder = $this->getEntityManager()->createQueryBuilder()
            ->select('a')
            ->from(Article::class, 'a');

        $builder = $this->getArticlesFilterBuilder(
            $builder,
            $categoryIds
        );

        if (!is_null($limit)) {
            $builder->setMaxResults($limit);
        }
        return $builder->setFirstResult($offset)->getQuery()->getResult();
    }

    /**
     * @param array $categoryIds
     *
     * @return int
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getByFilterCount(array $categoryIds): int
    {
        $builder = $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(a)')
            ->from(Article::class, 'a');

        $builder = $this->getArticlesFilterBuilder(
            $builder,
            $categoryIds
        );

        return $builder->getQuery()->getSingleScalarResult();
    }

    /**
     * @param QueryBuilder $builder
     * @param array        $categoryIds
     *
     * @return QueryBuilder
     */
    private function getArticlesFilterBuilder(
        QueryBuilder $builder,
        array $categoryIds,
    ): QueryBuilder {
        if (!empty($categoryIds)) {
            $builder->innerJoin('a.categories', 'ac')
                ->andWhere('ac.id IN (:categoryIds)')
                ->setParameter('categoryIds', $categoryIds);
        }

        return $builder;
    }

    /**
     * @param int $id
     *
     * @return Article
     *
     * @throws EntityNotFoundException
     */
    public function getById(int $id): Article
    {
        $article = $this->find($id);

        if (is_null($article)) {
            throw new EntityNotFoundException(
                sprintf('Статья с id %d не найдена', $id),
            );
        }

        return $article;
    }
}
