<?php

namespace App\Entity;

use App\Repository\BucketRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BucketRepository::class)]
class Bucket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'buckets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Books $bookId = null;

    #[ORM\ManyToOne(inversedBy: 'buckets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $addedBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookId(): ?Books
    {
        return $this->bookId;
    }

    public function setBookId(?Books $bookId): self
    {
        $this->bookId = $bookId;

        return $this;
    }

    public function getAddedBy(): ?User
    {
        return $this->addedBy;
    }

    public function setAddedBy(?User $addedBy): self
    {
        $this->addedBy = $addedBy;

        return $this;
    }
}
