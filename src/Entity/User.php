<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $userName = null;

    #[ORM\Column(length: 255)]
    private ?string $fullName = null;

    #[ORM\Column(length: 10)]
    private ?string $role = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'addedBy', targetEntity: Bucket::class)]
    private Collection $buckets;

    #[ORM\OneToMany(mappedBy: 'addedBy', targetEntity: Wishlist::class, orphanRemoval: true)]
    private Collection $wishlists;

    public function __construct()
    {
        $this->buckets = new ArrayCollection();
        $this->wishlists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;
        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return Collection<int, Bucket>
     */
    public function getBuckets(): Collection
    {
        return $this->buckets;
    }

    public function addBucket(Bucket $bucket): self
    {
        if (!$this->buckets->contains($bucket)) {
            $this->buckets->add($bucket);
            $bucket->setAddedBy($this);
        }

        return $this;
    }

    public function removeBucket(Bucket $bucket): self
    {
        if ($this->buckets->removeElement($bucket)) {
            // set the owning side to null (unless already changed)
            if ($bucket->getAddedBy() === $this) {
                $bucket->setAddedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Wishlist>
     */
    public function getWishlists(): Collection
    {
        return $this->wishlists;
    }

    public function addWishlist(Wishlist $wishlist): self
    {
        if (!$this->wishlists->contains($wishlist)) {
            $this->wishlists->add($wishlist);
            $wishlist->setAddedBy($this);
        }

        return $this;
    }

    public function removeWishlist(Wishlist $wishlist): self
    {
        if ($this->wishlists->removeElement($wishlist)) {
            // set the owning side to null (unless already changed)
            if ($wishlist->getAddedBy() === $this) {
                $wishlist->setAddedBy(null);
            }
        }

        return $this;
    }
}
