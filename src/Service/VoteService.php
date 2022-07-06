<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\User;
use App\Entity\Vote;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

class VoteService
{
    /**
    * @param EntityManagerInterface $entityManager
    */
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param User    $user
     * @param Article $article
     *
     * @return Vote
     *
     * @throws Throwable
     */
    public function create(User $user, Article $article): Vote
    {
        $this->entityManager->beginTransaction();

        try {
            $vote = new Vote(
                user: $user,
                article: $article,
            );

            $this->entityManager->persist($vote);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Throwable $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }

        return $vote;
    }

    /**
     * @param Vote $vote
     */
    public function delete(Vote $vote): void
    {
        $this->entityManager->remove($vote);

        $this->entityManager->flush();
    }
}
