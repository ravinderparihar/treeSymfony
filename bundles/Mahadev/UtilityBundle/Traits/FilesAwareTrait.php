<?php

namespace Mahadev\UtilityBundle\Traits;

use Doctrine\Common\Collections\Collection;
use Mahadev\UtilityBundle\Entity\ImageInterface;

trait FilesAwareTrait
{

    protected Collection $files;

    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function getFilesByType(string $type): Collection
    {
        return $this->files->filter(function (ImageInterface $file) use ($type): bool {
            return $type === $file->getType();
        });
    }

    public function hasFiles(): bool
    {
        return !$this->files->isEmpty();
    }

    public function hasFile(ImageInterface $file): bool
    {
        return $this->files->contains($file);
    }

    public function addFile(ImageInterface $file): void
    {
        $file->setOwner($this);
        $this->files->add($file);
    }

    public function removeFile(ImageInterface $file): void
    {
        if ($this->hasFile($file)) {
            $file->setOwner(null);
            $this->files->removeElement($file);
        }
    }
}