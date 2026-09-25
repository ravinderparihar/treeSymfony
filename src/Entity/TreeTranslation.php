<?php

namespace App\Entity;

// Localized text for a tree. Numeric and code-like fields (lifespan, growthRate, soilPh...) stay on Tree.
class TreeTranslation
{
    public const FIELDS = [
        'description', 'temperatureRange', 'rainfallRequirement', 'altitudeRange', 'leafType', 'floweringSeason',
        'harvestTime', 'productionPerTree', 'seedTreatment', 'nurseryMethod', 'plantingDistance', 'fertilizerSchedule',
        'irrigationSchedule', 'pruningGuide', 'commonDiseases', 'commonInsects', 'symptoms', 'treatment',
    ];

    public ?int $id = null;
    public ?Tree $tree = null;
    public ?string $locale = null;
    public ?string $description = null;
    public ?string $temperatureRange = null;
    public ?string $rainfallRequirement = null;
    public ?string $altitudeRange = null;
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
}
