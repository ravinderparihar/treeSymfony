<?php

namespace App\Entity;

class Like
{
    private ?int $id = null;
    private int $type = 1;
    private Tree $tree;
    private User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTree(Tree $tree): static
    {
        $this->tree = $tree;
        return $this;
    }

    public function getTree(): Tree
    {
        return $this->tree;
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
}