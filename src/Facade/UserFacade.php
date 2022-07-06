<?php

namespace App\Facade;

use App\DTO\CreateUserData;
use App\Entity\Author;
use App\Entity\User;
use App\Service\UserService;
use Throwable;

class UserFacade
{
    /**
     * @param UserService $userService
     */
    public function __construct(private UserService $userService)
    {
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
        return $this->userService->createAuthor($user);
    }

    /**
     * @param CreateUserData $userData
     *
     * @return User
     *
     * @throws Throwable
     */
    public function createUser(CreateUserData $userData): User
    {
        return $this->userService->create($userData->getName());
    }
}
