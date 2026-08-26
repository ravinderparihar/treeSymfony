<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Favorite
{
    private ?int $id = null;
    private User $user;
    private Collection $trees;

    public function __construct()
    {
        $this->trees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getTrees(): Collection
    {
        return $this->trees;
    }

    public function addTree(Tree $tree): static
    {
        if (!$this->trees->contains($tree)) {
            $this->trees->add($tree);
        }
        return $this;
    }

    public function removeTree(Tree $tree): static
    {
        $this->trees->removeElement($tree);
        return $this;
    }
}