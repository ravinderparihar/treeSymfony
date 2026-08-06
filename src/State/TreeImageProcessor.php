<?php
namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\TreeImageInput;
use App\Entity\Tree;
use App\Entity\Images;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TreeImageProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ParameterBagInterface $params
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $tree = $this->em->getRepository(Tree::class)->find($data->tree);

        if (!$tree) {
            throw new \RuntimeException('Tree not found.');
        }

        /** @var UploadedFile $file */
        $file = $data->file;

        $filename = uniqid().'.'.$file->guessExtension();

        $file->move(
            $this->params->get('tree_images_directory'),
            $filename
        );

        $image = new Images();
        $image->setTree($tree);
        $image->setImageType($data->imageType);
        $image->setImageUrl('/uploads/tree/'.$filename);

        $this->em->persist($image);
        $this->em->flush();

        return $image;
    }
}