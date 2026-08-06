<?php

namespace Mahadev\UtilityBundle\Traits;

use Symfony\Component\HttpFoundation\File\File;

trait FileAwareTrait
{

    /** @var string|null */
    protected $type;

    /** @var File|null */
    protected ?File $file = null;

    /** @var string|null  */
    private ?string $fileUrl;

    /** @var string|null */
    protected $path;

    /** @var object|null */
    protected $owner;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): void
    {
        $this->file = $file;
    }

    public function hasFile(): bool
    {
        return null !== $this->file;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    public function hasPath(): bool
    {
        return null !== $this->path;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function setOwner($owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return string|null
     */
    public function getFileUrl(): ?string
    {
        return $this->fileUrl;
    }

    /**
     * @param string|null $fileUrl
     */
    public function setFileUrl(?string $fileUrl): void
    {
        $this->fileUrl = $fileUrl;
    }

}
