<?php

namespace App\Dto;
use ApiPlatform\Metadata\ApiProperty;

class TreeInput
{
    public ?string $scientificName = null;
    public ?string $description = null;
    public ?string $lifespanMin = null;
    public ?string $lifespanMax = null;
    public ?string $heightMin = null;
    public ?string $heightMax = null;
    public ?string $familyName = null;
    public ?string $genus = null;
    public ?string $species = null;
    public ?string $growthRate = null;
    public ?bool $status = null;

    /**
     * @var LocalNameInput[]
     */
    public array $localNames = [];

    /**
     * @var ImageInput[]
     */
    public array $images = [];
    
    /**
     * @var UsesInput[]
     */
    public array $uses = [];
    
    /**
     * Ye ab plain IRI strings ka array hai, Category objects nahi.
     * e.g. ["/api/categories/2", "/api/categories/5"]
     *
     * Processor mein IriConverter se manually resolve karenge.
     *
     * @var string[]
     */
    public array $categories = [];
}
