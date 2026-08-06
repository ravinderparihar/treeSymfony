<?php

namespace Mahadev\UtilityBundle\Entity;

use Doctrine\Common\Collections\Collection;

interface ImagesAwareInterface
{
    /**
     * @return Collection|ImageInterface[]
     *
     * @psalm-return Collection<array-key, ImageInterface>
     */
    public function getImages(): Collection;

    /**
     * @return Collection|ImageInterface[]
     *
     * @psalm-return Collection<array-key, ImageInterface>
     */
    public function getImagesByType(string $type): Collection;

    public function hasImages(): bool;

    public function hasImage(ImageInterface $image): bool;

    public function addImage(ImageInterface $image): void;

    public function removeImage(ImageInterface $image): void;
}