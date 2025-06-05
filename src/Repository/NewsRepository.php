<?php

namespace App\Repository;

use App\Entity\News;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<News>
 */
class NewsRepository extends ServiceEntityRepository implements NewsRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    private function createBaseQueryBuilder(): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('n')
            ->leftJoin('n.categories', 'c')
            ->addSelect('c');
    }

    private function addOrdering(\Doctrine\ORM\QueryBuilder $qb, string $orderBy = 'insertDate', string $direction = 'DESC'): \Doctrine\ORM\QueryBuilder
    {
        return $qb->orderBy('n.' . $orderBy, $direction);
    }

    public function findRecentByCategory(Category $category, int $limit = 3): array
    {
        return $this->createBaseQueryBuilder()
            ->where('c.id = :categoryId')
            ->setParameter('categoryId', $category->getId())
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findPaginatedByCategory(Category $category, int $page = 1, int $limit = 10): Paginator
    {
        $query = $this->createBaseQueryBuilder()
            ->leftJoin('n.comments', 'comments')
            ->addSelect('comments')
            ->where('c.id = :categoryId')
            ->setParameter('categoryId', $category->getId())
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query);
    }

    public function findWithDetails(int $id): ?News
    {
        return $this->createBaseQueryBuilder()
            ->leftJoin('n.comments', 'comments')
            ->addSelect('comments')
            ->where('n.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findTopByViews(int $limit = 10): array
    {
        return $this->createBaseQueryBuilder()
            ->addOrderBy('n.viewCount', 'DESC')
            ->addOrderBy('n.insertDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findAll(): array
    {
        return $this->createBaseQueryBuilder()
            ->getQuery()
            ->getResult();
    }

    public function findLatest(int $limit = 10): array
    {
        return $this->createBaseQueryBuilder()
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
 