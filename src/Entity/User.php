<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(
    name: 'discr',
    type: Types::STRING,
)]
#[ORM\DiscriminatorMap([
    'user' => User::class,
    'author' => Author::class,
])]
class User implements JsonSerializable
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
    private string $name;

    /**
     * @var DateTimeImmutable
     */
    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    /**
     * @var Collection<Vote>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Vote::class)]
    private Collection $votes;

    /**
     * @var int | null
     */
    #[ORM\Column(type: 'integer')]
    private ?int $userId;

    /**
     * @param string   $name
     * @param array    $votes
     * @param int|null $userId
     */
    public function __construct(string $name, array $votes = [], ?int $userId = null)
    {
        $this->name = $name;
        $this->createdAt = new DateTimeImmutable();
        $this->votes = new ArrayCollection(array_unique($votes, SORT_REGULAR));
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $userId
     *
     * @return User
     */
    public function updateUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return Collection<Vote>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getUserId(),
            'name' => $this->getName(),
            'created_at' => $this->getCreatedAt()->format(DATE_ATOM),
        ];
    }
}
