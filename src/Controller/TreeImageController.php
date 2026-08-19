<?php

namespace App\Controller;

use App\Entity\Images;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class TreeImageController extends AbstractController
{

    public function __invoke(Request $request, EntityManagerInterface $entityManager, ParameterBagInterface $params): JsonResponse
    {
        $imageType = $request->request->get('imageType');
        $file = $request->files->get('file');

        if (!$file) {
            return $this->json([
                'message' => 'Image file is required.'
            ], 400);
        }

       

        $filename = uniqid() . '.' . $file->guessExtension();

        $file->move(
            $params->get('tree_images_directory'),
            $filename
        );

        $treeImage = new Images();
        $treeImage->setImageType($imageType);
        $treeImage->setImageUrl('/uploads/tree/' . $filename);

        $entityManager->persist($treeImage);
        $entityManager->flush();

        return $this->json([
            'id' => $treeImage->getId(),
            'imageUrl' => $treeImage->getImageUrl(),
            'message' => 'Image uploaded successfully.'
        ], 201);
    }
}
