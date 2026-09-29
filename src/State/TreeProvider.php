<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Favorite;
use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\Tree;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tree collection supports:
 *   ?scientificName=  / ?localName=   partial, case-insensitive (matches either)
 *   ?growthRate=Fast                  exact, case-insensitive
 *   ?categories=3 | categories=1,2 | categories[]=1&categories[]=2 | categories=/api/categories/3
 *   ?favorited=true                   only trees the current user has favorited
 *   ?page= / ?itemsPerPage=           standard API Platform pagination
 */
final class TreeProvider implements ProviderInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security,
        private Pagination $pagination,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $repository = $this->entityManager->getRepository(Tree::class);
        if ($operation instanceof GetCollection) {
            $request = $context['request'] ?? null;
            $queryBuilder = $this->collectionQuery($request instanceof Request ? $request : null);

            if (!$this->pagination->isEnabled($operation, $context)) {
                $trees = $queryBuilder->getQuery()->getResult();
                $this->enrich($trees);

                return $trees;
            }

            [$page, $offset, $limit] = $this->pagination->getPagination($operation, $context);
            $queryBuilder->setFirstResult($offset)->setMaxResults($limit);

            // fetchJoinCollection: the local-name / category joins can match a tree more than once.
            $paginator = new DoctrinePaginator($queryBuilder, fetchJoinCollection: true);
            $trees = iterator_to_array($paginator, false);
            $this->enrich($trees);

            return new TraversablePaginator(new \ArrayIterator($trees), $page, $limit, \count($paginator));
        }

        $tree = $repository->find($uriVariables['id'] ?? null);
        if ($tree) {
            $this->enrich([$tree]);
        }

        return $tree;
    }

    private function collectionQuery(?Request $request): QueryBuilder
    {
        $query = $request?->query;
        $scientificName = trim((string) ($query?->get('scientificName', '') ?? ''));
        $localName = trim((string) ($query?->get('localName', $query->get('localNames', '')) ?? ''));
        $growthRate = trim((string) ($query?->get('growthRate', '') ?? ''));
        $categoryIds = $this->categoryIds($request);
        $favoritedOnly = filter_var($query?->get('favorited'), FILTER_VALIDATE_BOOLEAN);

        $queryBuilder = $this->entityManager->getRepository(Tree::class)
            ->createQueryBuilder('tree')
            // Stable order so pages never overlap or skip trees.
            ->orderBy('tree.id', 'ASC');

        $searchConditions = [];
        if ($scientificName !== '') {
            $searchConditions[] = 'LOWER(tree.scientificName) LIKE LOWER(:scientificName)';
            $queryBuilder->setParameter('scientificName', '%'.$scientificName.'%');
        }
        if ($localName !== '') {
            $queryBuilder
                ->leftJoin('tree.localNames', 'localName')
                ->setParameter('localName', '%'.$localName.'%');
            $searchConditions[] = 'LOWER(localName.localName) LIKE LOWER(:localName)';
        }
        if ($searchConditions !== []) {
            $queryBuilder->andWhere(implode(' OR ', $searchConditions));
        }

        if ($growthRate !== '') {
            $queryBuilder
                ->andWhere('LOWER(tree.growthRate) = LOWER(:growthRate)')
                ->setParameter('growthRate', $growthRate);
        }

        if ($categoryIds !== []) {
            $queryBuilder
                ->innerJoin('tree.categories', 'category')
                ->andWhere('category.id IN (:categoryIds)')
                ->setParameter('categoryIds', $categoryIds);
        }

        if ($favoritedOnly) {
            $user = $this->security->getUser();
            if (!$user instanceof User) {
                // Anonymous users have no favorites.
                $queryBuilder->andWhere('1 = 0');
            } else {
                $queryBuilder
                    ->andWhere(sprintf(
                        'EXISTS (SELECT favorite.id FROM %s favorite JOIN favorite.trees favoriteTree WHERE favorite.user = :currentUser AND favoriteTree = tree)',
                        Favorite::class,
                    ))
                    ->setParameter('currentUser', $user);
            }
        }

        return $queryBuilder;
    }

    /** @return int[] accepts ids or IRIs, as a single value, comma-separated list or array */
    private function categoryIds(?Request $request): array
    {
        if (!$request || !$request->query->has('categories')) {
            return [];
        }
        $ids = [];
        foreach ((array) $request->query->all()['categories'] as $value) {
            foreach (explode(',', (string) $value) as $part) {
                if (preg_match('~(\d+)$~', trim($part), $match)) {
                    $ids[] = (int) $match[1];
                }
            }
        }

        return array_values(array_unique($ids));
    }

    /**
     * Fills the computed fields with one query each for the whole page, instead of 3-4 queries per tree.
     *
     * @param Tree[] $trees
     */
    private function enrich(array $trees): void
    {
        if ($trees === []) {
            return;
        }
        $ids = array_map(fn (Tree $tree) => $tree->getId(), $trees);

        $likesCount = $this->countsByTree(Like::class, $ids);
        $commentsCount = $this->countsByTree(Comment::class, $ids);

        $likedIds = [];
        $favoritedIds = [];
        $user = $this->security->getUser();
        if ($user instanceof User) {
            $likedIds = array_flip($this->entityManager->createQueryBuilder()
                ->select('IDENTITY(interactionLike.tree)')
                ->from(Like::class, 'interactionLike')
                ->where('interactionLike.user = :user')
                ->andWhere('interactionLike.tree IN (:ids)')
                ->setParameter('user', $user)
                ->setParameter('ids', $ids)
                ->getQuery()
                ->getSingleColumnResult());

            $favoritedIds = array_flip($this->entityManager->createQueryBuilder()
                ->select('favoriteTree.id')
                ->from(Favorite::class, 'favorite')
                ->join('favorite.trees', 'favoriteTree')
                ->where('favorite.user = :user')
                ->andWhere('favoriteTree.id IN (:ids)')
                ->setParameter('user', $user)
                ->setParameter('ids', $ids)
                ->getQuery()
                ->getSingleColumnResult());
        }

        foreach ($trees as $tree) {
            $id = $tree->getId();
            $tree->likesCount = $likesCount[$id] ?? 0;
            $tree->commentsCount = $commentsCount[$id] ?? 0;
            if ($user instanceof User) {
                $tree->likedByCurrentUser = isset($likedIds[$id]);
                $tree->favoritedByCurrentUser = isset($favoritedIds[$id]);
            }
        }
    }

    /** @return array<int, int> tree id => row count */
    private function countsByTree(string $entityClass, array $treeIds): array
    {
        $rows = $this->entityManager->createQueryBuilder()
            ->select('IDENTITY(row.tree) AS treeId, COUNT(row.id) AS total')
            ->from($entityClass, 'row')
            ->where('row.tree IN (:ids)')
            ->groupBy('row.tree')
            ->setParameter('ids', $treeIds)
            ->getQuery()
            ->getArrayResult();

        $counts = [];
        foreach ($rows as $row) {
            $counts[(int) $row['treeId']] = (int) $row['total'];
        }

        return $counts;
    }
}
