<?php

namespace App\Entity;

class Images
{

    public ?int $id = null;
    public ?string $imageType = null;
    public ?string $imageUrl = null;
    public ?Tree $tree = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageType(): ?string
    {
        return $this->imageType;
    }

    public function setImageType(string $imageType): static
    {
        $this->imageType = $imageType;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getTree(): ?Tree
    {
        return $this->tree;
    }

    public function setTree(?Tree $tree): static
    {
        $this->tree = $tree;

        return $this;
    }
}
