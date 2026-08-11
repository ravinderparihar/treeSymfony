<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


class Category
{

    private ?int $id = null;

    private ?string $name = null;

    /**
     * @var Collection|Tree[]
     */
    private $trees;

    public function __construct()
    {
        $this->trees = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Tree[]
     */
    public function getTrees(): Collection
    {
        return $this->trees;
    }

    public function addTree(Tree $tree): self
    {
        if (!$this->trees->contains($tree)) {
            $this->trees[] = $tree;
        }

        return $this;
    }

    public function removeTree(Tree $tree): self
    {
        if ($this->trees->contains($tree)) {
            $this->trees->removeElement($tree);
        }

        return $this;
    }
}
