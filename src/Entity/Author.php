<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class Author extends User
{
    /**
     * @var Collection
     */
    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Article::class)]
    private Collection $articles;

    /**
     * @param int    $id
     * @param string $name
     * @param array  $articles
     * @param array  $votes
     */
    public function __construct(
        int $id,
        string $name,
        array $articles = [],
        array $votes = [],
    ) {
        parent::__construct(
            name: $name,
            votes: $votes,
            userId: $id,
        );

        $this->articles = new ArrayCollection(array_unique($articles, SORT_REGULAR));
    }

    /**
     * @return Collection<Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }
}
