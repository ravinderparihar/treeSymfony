<?php

namespace App\Entity;


class LocalNames
{
    public ?int $id = null;
    public ?string $language = null;
    public ?string $localName = null;
    public ?Tree $tree = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getLocalName(): ?string
    {
        return $this->localName;
    }

    public function setLocalName(string $localName): static
    {
        $this->localName = $localName;

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
