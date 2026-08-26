<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Favorite;
use App\Entity\Like;
use App\Entity\Tree;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

final class TreeInteractionController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security,
    ) {
    }

    #[Route('/api/trees/{id}/like', methods: ['POST'])]
    public function like(int $id): JsonResponse
    {
        $user = $this->authenticatedUser();
        $tree = $this->tree($id);
        $repository = $this->entityManager->getRepository(Like::class);
        $like = $repository->findOneBy(['tree' => $tree, 'user' => $user]);

        if ($like) {
            $this->entityManager->remove($like);
            $liked = false;
        } else {
            $like = (new Like())->setTree($tree)->setUser($user);
            $this->entityManager->persist($like);
            $liked = true;
        }

        $this->entityManager->flush();

        return new JsonResponse(['treeId' => $id, 'liked' => $liked]);
    }

    #[Route('/api/trees/{id}/favorite', methods: ['POST'])]
    public function addFavorite(int $id): JsonResponse
    {
        $user = $this->authenticatedUser();
        $tree = $this->tree($id);
        $repository = $this->entityManager->getRepository(Favorite::class);
        $favorite = $repository->findOneBy(['user' => $user]) ?? (new Favorite())->setUser($user);
        $favorite->addTree($tree);
        $this->entityManager->persist($favorite);
        $this->entityManager->flush();

        return new JsonResponse(['treeId' => $id, 'favorite' => true]);
    }

    #[Route('/api/trees/{id}/favorite', methods: ['DELETE'])]
    public function removeFavorite(int $id): JsonResponse
    {
        $user = $this->authenticatedUser();
        $tree = $this->tree($id);
        $favorite = $this->entityManager->getRepository(Favorite::class)->findOneBy(['user' => $user]);

        if ($favorite) {
            $favorite->removeTree($tree);
            $this->entityManager->flush();
        }

        return new JsonResponse(['treeId' => $id, 'favorite' => false]);
    }

    #[Route('/api/trees/{id}/comments', methods: ['POST'])]
    public function addComment(int $id, Request $request): JsonResponse
    {
        $user = $this->authenticatedUser();
        $tree = $this->tree($id);
        $payload = json_decode($request->getContent(), true);
        $text = is_array($payload) ? trim((string) ($payload['comment'] ?? '')) : '';

        if ($text === '') {
            return new JsonResponse(['message' => 'The comment is required.'], Response::HTTP_BAD_REQUEST);
        }

        $comment = (new Comment())->setComment($text)->setTree($tree)->setUser($user);
        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return new JsonResponse($this->serializeComment($comment), Response::HTTP_CREATED);
    }

    #[Route('/api/trees/{id}/comments', methods: ['GET'])]
    public function listComments(int $id): JsonResponse
    {
        $tree = $this->tree($id);
        $comments = $this->entityManager->getRepository(Comment::class)->findBy(
            ['tree' => $tree],
            ['createdAt' => 'DESC'],
        );

        return new JsonResponse(array_map(fn (Comment $comment) => $this->serializeComment($comment), $comments));
    }

    #[Route('/api/comments/{id}', methods: ['DELETE'])]
    public function removeComment(int $id): JsonResponse
    {
        $user = $this->authenticatedUser();
        $comment = $this->entityManager->getRepository(Comment::class)->find($id);

        if (!$comment || $comment->getUser()->getId() !== $user->getId()) {
            return new JsonResponse(['message' => 'Comment not found.'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($comment);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    private function authenticatedUser(): User
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('Authentication is required.');
        }

        return $user;
    }

    private function tree(int $id): Tree
    {
        $tree = $this->entityManager->getRepository(Tree::class)->find($id);
        if (!$tree) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Tree not found.');
        }

        return $tree;
    }

    private function serializeComment(Comment $comment): array
    {
        return [
            'id' => $comment->getId(),
            'comment' => $comment->getComment(),
            'createdAt' => $comment->getCreatedAt()->format(\DateTimeInterface::ATOM),
            'user' => [
                'id' => $comment->getUser()->getId(),
                'name' => $comment->getUser()->getName(),
            ],
        ];
    }
}