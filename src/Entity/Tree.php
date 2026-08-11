<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\LocalNames;
use App\Entity\Images;
use App\Entity\Category;


class Tree
{
    public ?int $id = null;
    public ?string $scientificName = null;
    public ?string $description = null;
    public ?string $lifespanMin = null;
    public ?string $lifespanMax = null;
    public ?string $heightMin = null;
    public ?string $heightMax = null;
    public ?string $growthRate = null;
    public ?bool $status = null;
    public ?string $familyName = null;
    public ?string $genus = null;
    public ?string $species = null;
    public Collection $images;
    public Collection $localNames;
    public Collection $uses;
    /**
     * @var Collection|Category[]
     */
    private $categories;



    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->localNames = new ArrayCollection();
        $this->uses = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScientificName(): ?string
    {
        return $this->scientificName;
    }

    public function setScientificName(string $scientificName): static
    {
        $this->scientificName = $scientificName;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLifespanMin(): ?string
    {
        return $this->lifespanMin;
    }

    public function setLifespanMin(string $lifespanMin): static
    {
        $this->lifespanMin = $lifespanMin;

        return $this;
    }

    public function getLifespanMax(): ?string
    {
        return $this->lifespanMax;
    }

    public function setLifespanMax(string $lifespanMax): static
    {
        $this->lifespanMax = $lifespanMax;

        return $this;
    }

    public function getHeightMin(): ?string
    {
        return $this->heightMin;
    }

    public function setHeightMin(?string $heightMin): static
    {
        $this->heightMin = $heightMin;

        return $this;
    }

    public function getHeightMax(): ?string
    {
        return $this->heightMax;
    }

    public function setHeightMax(?string $heightMax): static
    {
        $this->heightMax = $heightMax;
        return $this;
    }

    public function getGrowthRate(): ?string
    {
        return $this->growthRate;
    }

    public function setGrowthRate(?string $growthRate): static
    {
        $this->growthRate = $growthRate;
        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getFamilyName(): ?string
    {
        return $this->familyName;
    }

    public function setFamilyName(?string $familyName): static
    {
        $this->familyName = $familyName;

        return $this;
    }

    public function getGenus(): ?string
    {
        return $this->genus;
    }

    public function setGenus(?string $genus): static
    {
        $this->genus = $genus;

        return $this;
    }

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function setSpecies(?string $species): static
    {
        $this->species = $species;

        return $this;
    }

    /**
     * @return Collection<int, LocalNames>
     */
    public function getLocalNames(): Collection
    {
        return $this->localNames;
    }

    public function addLocalName(LocalNames $localName): static
    {
        if (!$this->localNames->contains($localName)) {
            $this->localNames->add($localName);
            $localName->setTree($this);
        }

        return $this;
    }

    public function removeLocalName(LocalNames $localName): static
    {
        if ($this->localNames->removeElement($localName)) {
            if ($localName->getTree() === $this) {
                $localName->setTree(null);
            }
        }
        return $this;
    }


    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setTree($this);
        }

        return $this;
    }

    public function removeImage(Images $image): static
    {
        if ($this->images->removeElement($image)) {
            if ($image->getTree() === $this) {
                $image->setTree(null);
            }
        }
        return $this;
    }


    /**
     * @return Collection<int, Uses>
     */
    public function getUses(): Collection
    {
        return $this->uses;
    }

    public function addUses(Uses $uses): static
    {
        if (!$this->uses->contains($uses)) {
            $this->uses->add($uses);
            $uses->setTree($this);
        }

        return $this;
    }

    public function removeUses(Uses $uses): static
    {
        if ($this->images->removeElement($uses)) {
            if ($uses->getTree() === $this) {
                $uses->setTree(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addTree($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            $category->removeTree($this);
        }

        return $this;
    }
}
