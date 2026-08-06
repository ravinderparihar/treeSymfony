<?php

namespace App\Dto;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class TreeImageInput
{
    public ?int $tree = null;

    public ?string $imageType = null;

    public ?UploadedFile $file = null;
}