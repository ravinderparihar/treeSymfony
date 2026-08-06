<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\TreeInput;
use App\Dto\ImageInput;
use App\Dto\LocalNameInput;
use App\Dto\UsesInput;
use App\Entity\Tree;
use App\Entity\Images;
use App\Entity\Uses;
use App\Entity\LocalNames;
use Doctrine\ORM\EntityManagerInterface;

class TreeProcessor implements ProcessorInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    /** @var TreeInput $data */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): mixed {

        $tree = new Tree();
        $tree->setScientificName($data->scientificName);
        $tree->setDescription($data->description);
        $tree->setLifespanMin($data->lifespanMin);
        $tree->setLifespanMax($data->lifespanMax);
        $tree->setHeightMin($data->heightMin);
        $tree->setHeightMax($data->heightMax);
        $tree->setFamilyName($data->familyName);
        $tree->setGenus($data->genus);
        $tree->setSpecies($data->species);
        $tree->setStatus($data->status);
        $tree->setGrowthRate($data->growthRate);

        $this->em->persist($tree);


        /** @var LocalNameInput $item */
        foreach ($data->localNames as $item) {
            $name = new LocalNames();
            $name->setTree($tree);
            $name->setLocalName($item->localName);
            $name->setLanguage($item->language);
            $tree->addLocalName($name);
        }


        /** @var ImageInput $item */
        foreach ($data->images as $item) {
            $image = new Images();
            $image->setTree($tree);
            $image->setImageUrl($item->imageUrl);
            $image->setImageType($item->imageType);
            $tree->addImage($image);
        }

       
        /** @var UsesInput $item */
        foreach ($data->uses as $item) {
            $use = new Uses();
            $use->setTree($tree);
            $use->setTitle($item->title);
            $use->setDescription($item->description);
            $use->setStatus(true);
            $tree->addUses($use);
        }

        $this->em->flush();
        return $tree;
    }
}
