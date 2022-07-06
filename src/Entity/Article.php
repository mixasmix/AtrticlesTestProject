<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article implements JsonSerializable
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
     * @var Author
     */
    #[ORM\ManyToOne(targetEntity: Author::class, inversedBy: 'articles')]
    private Author $author;

    /**
     * @var DateTimeImmutable
     */
    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $publishedAt;

    /**
     * @var Collection<Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'articles')]
    private Collection $categories;

    /**
     * @var Collection<Vote>
     */
    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Vote::class)]
    private Collection $votes;

    /**
     * @param string $title
     * @param Author $author
     * @param array  $categories
     * @param array  $votes
     */
    public function __construct(
        string $title,
        Author $author,
        array $categories = [],
        array $votes = [],
    ) {
        $this->title = $title;
        $this->author = $author;
        $this->publishedAt = new DateTimeImmutable();
        $this->categories = new ArrayCollection(array_unique($categories, SORT_REGULAR));
        $this->votes = new ArrayCollection(array_unique($votes, SORT_REGULAR));
    }

    /**
     * @param Category $category
     *
     * @return Article
     */
    public function addCategory(Category $category): Article
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    /**
     * @param Vote $vote
     *
     * @return Article
     */
    public function addVote(Vote $vote): Article
    {
        if (!$this->votes->contains($vote)) {
            $this->votes->add($vote);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection<Vote>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    /**
     * Возможно имеет сделать рейтинг не просто лайк/дизлайк, а по пятибальной системе
     *
     * @return int
     */
    public function getRating(): int
    {
        return $this->getVotes()->count();
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return Author
     */
    public function getAuthor(): Author
    {
        return $this->author;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getPublishedAt(): DateTimeImmutable
    {
        return $this->publishedAt;
    }

    /**
     * @return Collection<Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'author' => $this->getAuthor(),
            'published_at' => $this->getPublishedAt()->format(DATE_ATOM),
            'categories' => $this->getCategories()->toArray(),
            'rating' => $this->getRating(),
            'votes' => $this->getVotes()->map(
                fn (Vote $vote): array => ['vote_id' => $vote->getId(), 'user' => $vote->getUser()],
            )->toArray(),
        ];
    }
}
