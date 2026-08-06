<?php

namespace Mahadev\UtilityBundle\Traits;

use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;


trait RepositoryTrait
{


    protected function initRepository(ManagerRegistry $registry, string $className): void {
        parent::__construct($registry, $className);
    }

    public function findOneById($id)
    {
        return $this->findOneBy(['id' => $id]);
    }
    /**
     * {@inheritdoc}
     */
    public function add($resource): void
    {
        $this->_em->persist($resource);
        $this->_em->flush();
    }

    public function persist($resource): void
    {
        $this->add($resource);
    }

    public function getReference(string $className, $id)
    {
        return $this->_em->getReference($className, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function flush(): void
    {
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function clearFlushRepo(): void
    {
        $this->flush();
        $this->clearAll();
    }

    /**
     * {@inheritdoc}
     */
    public function clearAll(): void
    {
        $this->_em->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($resource): void
    {
        $this->_em->remove($resource);
        $this->_em->flush();
    }

    //    /**
    //     * {@inheritdoc}
    //     */
    //    public function createPaginator(array $criteria = [], array $sorting = []): iterable
    //    {
    //        $queryBuilder = $this->createQueryBuilder('o');
    //
    //        $this->applyCriteria($queryBuilder, $criteria);
    //        $this->applySorting($queryBuilder, $sorting);
    //
    //        return $this->getPaginator($queryBuilder);
    //    }
    //
    //    protected function getPaginator(QueryBuilder $queryBuilder): Pagerfanta
    //    {
    //        // Use output walkers option in DoctrineORMAdapter should be false as it affects performance greatly (see #3775)
    //        return new Pagerfanta(new DoctrineORMAdapter($queryBuilder, false, false));
    //    }
    //
    //    /**
    //     * @param array $objects
    //     */
    //    protected function getArrayPaginator($objects): Pagerfanta
    //    {
    //        return new Pagerfanta(new ArrayAdapter($objects));
    //    }

    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = []): void
    {
        foreach ($criteria as $property => $value) {
            if (!in_array($property, array_merge($this->_class->getAssociationNames(), $this->_class->getFieldNames()), true)) {
                continue;
            }

            $name = $this->getPropertyName($property);

            if (null === $value) {
                $queryBuilder->andWhere($queryBuilder->expr()->isNull($name));
            } elseif (is_array($value)) {
                $queryBuilder->andWhere($queryBuilder->expr()->in($name, $value));
            } elseif ('' !== $value) {
                $parameter = str_replace('.', '_', $property);
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->eq($name, ':' . $parameter))
                    ->setParameter($parameter, $value)
                ;
            }
        }
    }

    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = []): void
    {
        foreach ($sorting as $property => $order) {
            if (!in_array($property, array_merge($this->_class->getAssociationNames(), $this->_class->getFieldNames()), true)) {
                continue;
            }

            if (!empty($order)) {
                $queryBuilder->addOrderBy($this->getPropertyName($property), $order);
            }
        }
    }

    protected function getPropertyName(string $name): string
    {
        if (false === strpos($name, '.')) {
            return 'o' . '.' . $name;
        }

        return $name;
    }

    public function getAll($limit = false): Query
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder->select();
        if ($limit) $queryBuilder->setMaxResults($limit);
        return $queryBuilder->getQuery();
    }
}
