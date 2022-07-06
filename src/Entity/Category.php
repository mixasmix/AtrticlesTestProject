<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category implements JsonSerializable
{
    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    /**
     * @var DateTimeImmutable
     */
    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable$createdAt;

    /**
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: Article::class, inversedBy: 'categories')]
    #[ORM\JoinTable(name: 'category_article')]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'article_id', referencedColumnName: 'id')]
    private Collection $articles;

    /**
     * @param string $title
     * @param array  $articles
     */
    public function __construct(string $title, array $articles = [])
    {
        $this->title = $title;
        $this->createdAt = new DateTimeImmutable();
        $this->articles = new ArrayCollection(array_unique($articles, SORT_REGULAR));
    }

    /**
     * @param Article $article
     *
     * @return Category
     */
    public function addArticle(Article $article): Category
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection<Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'created_at' => $this->getCreatedAt()->format(DATE_ATOM),
        ];
    }
}
