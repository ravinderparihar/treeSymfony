<?php

namespace Mahadev\UtilityBundle\Entity;

use Symfony\Component\HttpFoundation\File\File;

interface ImageInterface
{
    /**
     * @return string
     */
    public function getType(): ?string;

    public function setType(?string $type): void;

    public function getFile(): ?File;

    public function setFile(?File $file): void;

    public function hasFile(): bool;

    public function getPath(): ?string;

    public function setPath(?string $path): void;

    /**
     * @return object
     */
    public function getOwner();

    /**
     * @param object|null $owner
     */
    public function setOwner($owner): void;
}