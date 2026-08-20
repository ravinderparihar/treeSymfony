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
    public ?string $temperatureRange = null;
    public ?string $rainfallRequirement = null;
    public ?string $waterRequirement = null;
    public ?string $humidity = null;
    public ?string $altitudeRange = null;
    public ?bool $sandySoil = null;
    public ?bool $claySoil = null;
    public ?bool $loamySoil = null;
    public ?string $soilPh = null;
    public ?string $leafType = null;
    public ?string $floweringSeason = null;
    public ?string $harvestTime = null;
    public ?string $productionPerTree = null;
    public ?string $seedTreatment = null;
    public ?string $nurseryMethod = null;
    public ?string $plantingDistance = null;
    public ?string $fertilizerSchedule = null;
    public ?string $irrigationSchedule = null;
    public ?string $pruningGuide = null;
    public ?string $commonDiseases = null;
    public ?string $commonInsects = null;
    public ?string $symptoms = null;
    public ?string $treatment = null;

    /**
     * @var LocalNameInput[]
     */
    public array $localNames = [];

    /**
     * @var String[]
     */
    public array $images = [];
    
    /**
     * @var UsesInput[]
     */
    public array $uses = [];
    
    public array $categories = [];
}
