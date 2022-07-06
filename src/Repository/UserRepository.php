<?php

namespace App\Repository;

use App\Entity\Author;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param int $id
     *
     * @return User
     *
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     */
    public function getById(int $id): User
    {
        $user = $this->getEntityManager()->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.userId = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if (is_null($user)) {
            throw new EntityNotFoundException(
                sprintf('Пользователь с id %d не найден', $id),
            );
        }

        return $user;
    }

    /**
     * @param int $id
     *
     * @return Author
     *
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     */
    public function getAuthorById(int $id): Author
    {
        $author =  $this->getEntityManager()->createQueryBuilder()
            ->select('a')
            ->from(Author::class, 'a')
            ->where('a.userId = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if (is_null($author)) {
            throw new EntityNotFoundException(
                sprintf('Автор с id %d не найден', $id),
            );
        }

        return $author;
    }
}
