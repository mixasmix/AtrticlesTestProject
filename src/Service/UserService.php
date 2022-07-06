<?php

namespace App\Service;

use App\Entity\Author;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

class UserService
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param string $userName
     *
     * @return User
     *
     * @throws Throwable
     */
    public function create(string $userName): User
    {
        $this->entityManager->beginTransaction();

        try {
            $user = new User($userName);

            $this->entityManager->persist($user);

            $user->updateUserId($user->getId());

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Throwable $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }

        return $user;
    }

    /**
     * @param User $user
     *
     * @return Author
     *
     * @throws Throwable
     */
    public function createAuthor(User $user): Author
    {
        $this->entityManager->beginTransaction();

        try {
            $author = new Author(
                id: $user->getId(),
                name: $user->getName(),
                votes: $user->getVotes()->toArray(),
            );

            $this->entityManager->persist($author);

            $this->entityManager->remove($user);
            $this->entityManager->flush();

            $this->entityManager->commit();
        } catch (Throwable $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }

        return $author;
    }
}
