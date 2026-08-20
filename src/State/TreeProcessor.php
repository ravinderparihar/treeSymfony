<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\TreeInput;
use App\Dto\LocalNameInput;
use App\Dto\UsesInput;
use App\Dto\TreeUpdateInput;
use App\Entity\Tree;
use App\Entity\Images;
use App\Entity\Uses;
use App\Entity\LocalNames;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Metadata\IriConverterInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TreeProcessor implements ProcessorInterface
{
    public function __construct(private EntityManagerInterface $em,  private IriConverterInterface $iriConverter) {}

    /** @var TreeInput $data */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): mixed {

        // Update path: TreeUpdateInput aata hai PATCH request se
        if ($data instanceof TreeUpdateInput) {
            return $this->updateTree($data, $uriVariables);
        }
        return $this->addTree($data);
    }

    public function addTree(mixed $data)
    {
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


        if ($data->images !== null) {
            $this->assignImages($tree, $data->images);
        }


        // $data->categories abhi bhi IRI strings hain,
        // isliye har ek ko manually resolve karna hai actual Category entity mein.
        foreach ($data->categories as $categoryIri) {
            /** @var Category $category */
            $category = $this->iriConverter->getResourceFromIri($categoryIri);
            $tree->addCategory($category);
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

    private function updateTree(TreeUpdateInput $data, array $uriVariables): Tree
    {
        $tree = $this->em
            ->getRepository(Tree::class)
            ->find($uriVariables['id']);

        if (!$tree) {
            throw new NotFoundHttpException('Tree not found.');
        }

        if ($data->scientificName !== null) {
            $tree->setScientificName($data->scientificName);
        }

        if ($data->description !== null) {
            $tree->setDescription($data->description);
        }

        if ($data->lifespanMin !== null) {
            $tree->setLifespanMin($data->lifespanMin);
        }

        if ($data->lifespanMax !== null) {
            $tree->setLifespanMax($data->lifespanMax);
        }

        if ($data->heightMin !== null) {
            $tree->setHeightMin($data->heightMin);
        }

        if ($data->heightMax !== null) {
            $tree->setHeightMax($data->heightMax);
        }

        if ($data->familyName !== null) {
            $tree->setFamilyName($data->familyName);
        }

        if ($data->genus !== null) {
            $tree->setGenus($data->genus);
        }

        if ($data->species !== null) {
            $tree->setSpecies($data->species);
        }

        if ($data->growthRate !== null) {
            $tree->setGrowthRate($data->growthRate);
        }

        if ($data->status !== null) {
            $tree->setStatus($data->status);
        }

        if ($data->categories !== null) {
            $this->assignCategories($tree, $data->categories);
        }

        /** @var UsesInput $item */
        foreach ($data->uses as $item) {
            if ($this->treeHasUse($tree, $item)) {
                continue;
            }

            $use = new Uses();
            $use->setTree($tree);
            $use->setTitle($item->title);
            $use->setDescription($item->description);
            $use->setStatus(true);
            $tree->addUses($use);
        }

        /** @var LocalNameInput $item */
        foreach ($data->localNames as $item) {
            if ($this->treeHasLocalName($tree, $item)) {
                continue;
            }

            $name = new LocalNames();
            $name->setTree($tree);
            $name->setLocalName($item->localName);
            $name->setLanguage($item->language);
            $tree->addLocalName($name);
        }

        if ($data->images !== null) {
            $this->assignImages($tree, $data->images);
        }
        $this->em->flush();
        return $tree;
    }

    private function treeHasUse(Tree $tree, UsesInput $input): bool
    {
        foreach ($tree->getUses() as $use) {
            if (
                $use->getTitle() === $input->title
                && $use->getDescription() === $input->description
            ) {
                return true;
            }
        }

        return false;
    }

    private function treeHasLocalName(Tree $tree, LocalNameInput $input): bool
    {
        foreach ($tree->getLocalNames() as $localName) {
            if (
                $localName->getLocalName() === $input->localName
                && $localName->getLanguage() === $input->language
            ) {
                return true;
            }
        }

        return false;
    }

    private function assignImages(Tree $tree, array $imageIds): void
    {
        foreach ($imageIds as $imageId) {
            $image = $this->em->getRepository(Images::class)->find($imageId);

            if (!$image) {
                throw new \InvalidArgumentException(
                    sprintf('Image with ID "%s" not found.', $imageId)
                );
            }

            $image->setTree($tree);
            $tree->addImage($image);
        }
    }

    /**
     * IRI strings (e.g. "/api/categories/2") se Category entities resolve
     * karke Tree mein assign karta hai.
     */
    private function assignCategories(Tree $tree, array $categoryIris): void
    {
        foreach ($categoryIris as $categoryIri) {
            $categoryId = $this->extractIdFromIri($categoryIri);

            $category = $this->em
                ->getRepository(Category::class)
                ->find($categoryId);

            if (!$category) {
                throw new \InvalidArgumentException(
                    sprintf('Category with IRI "%s" not found.', $categoryIri)
                );
            }

            $tree->addCategory($category);
        }
    }

    /**
     * "/api/categories/2" se sirf "2" nikal deta hai.
     */
    private function extractIdFromIri(string $iri): string
    {
        $parts = explode('/', rtrim($iri, '/'));

        return end($parts);
    }
}
