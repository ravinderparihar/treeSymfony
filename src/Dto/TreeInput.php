<?php

namespace App\Dto;

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
}
