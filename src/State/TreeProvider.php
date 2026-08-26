<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Favorite;
use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\Tree;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class TreeProvider implements ProviderInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $repository = $this->entityManager->getRepository(Tree::class);
        if ($operation instanceof GetCollection) {
            return array_map(fn (Tree $tree) => $this->enrich($tree), $repository->findAll());
        }

        $tree = $repository->find($uriVariables['id'] ?? null);
        return $tree ? $this->enrich($tree) : null;
    }

    private function enrich(Tree $tree): Tree
    {
        $likeRepository = $this->entityManager->getRepository(Like::class);
        $tree->likesCount = (int) $likeRepository->createQueryBuilder('interactionLike')
            ->select('COUNT(interactionLike.id)')
            ->where('interactionLike.tree = :tree')
            ->setParameter('tree', $tree)
            ->getQuery()
            ->getSingleScalarResult();

        $tree->commentsCount = (int) $this->entityManager->getRepository(Comment::class)
            ->createQueryBuilder('treeComment')
            ->select('COUNT(treeComment.id)')
            ->where('treeComment.tree = :tree')
            ->setParameter('tree', $tree)
            ->getQuery()
            ->getSingleScalarResult();

        $user = $this->security->getUser();
        if ($user instanceof User) {
            $tree->likedByCurrentUser = null !== $likeRepository->findOneBy(['tree' => $tree, 'user' => $user]);
            $favorite = $this->entityManager->getRepository(Favorite::class)->findOneBy(['user' => $user]);
            $tree->favoritedByCurrentUser = $favorite?->getTrees()->contains($tree) ?? false;
        }

        return $tree;
    }
}
