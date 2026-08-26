<?php

namespace App\Entity;

class Comment
{
    private ?int $id = null;
    private string $comment;
    private \DateTimeImmutable $createdAt;
    private Tree $tree;
    private User $user;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;
        return $this;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
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