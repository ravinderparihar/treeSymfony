<?php

namespace Mahadev\UtilityBundle\Entity;

use Doctrine\Common\Collections\Collection;

interface FilesAwareInterface
{
    /**
     * @return Collection|ImageInterface.php[]
     *
     * @psalm-return Collection<array-key, ImageInterface.php>
     */
    public function getFiles(): Collection;

    /**
     * @return Collection|ImageInterface.php[]
     *
     * @psalm-return Collection<array-key, ImageInterface.php>
     */
    public function getFilesByType(string $type): Collection;

    public function hasFiles(): bool;

    public function hasFile(ImageInterface $image): bool;

    public function addFile(ImageInterface $image): void;

    public function removeFile(ImageInterface $image): void;
}