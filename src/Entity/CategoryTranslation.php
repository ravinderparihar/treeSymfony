<?php

namespace App\Entity;

class CategoryTranslation
{
    public ?int $id = null;
    public ?Category $category = null;
    public ?string $locale = null;
    public ?string $name = null;
}
