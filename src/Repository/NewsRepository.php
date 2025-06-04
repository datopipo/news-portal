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

    public function findRecentByCategory(Category $category, int $limit = 3): array
    {
        return $this->createQueryBuilder('n')
            ->leftJoin('n.categories', 'c')
            ->where('c.id = :categoryId')
            ->setParameter('categoryId', $category->getId())
            ->orderBy('n.insertDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findPaginatedByCategory(Category $category, int $page = 1, int $limit = 10): Paginator
    {
        $query = $this->createQueryBuilder('n')
            ->leftJoin('n.categories', 'c')
            ->leftJoin('n.comments', 'comments')
            ->addSelect('c', 'comments')
            ->where('c.id = :categoryId')
            ->setParameter('categoryId', $category->getId())
            ->orderBy('n.insertDate', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query);
    }

    public function findWithDetails(int $id): ?News
    {
        return $this->createQueryBuilder('n')
            ->leftJoin('n.categories', 'c')
            ->leftJoin('n.comments', 'comments')
            ->addSelect('c', 'comments')
            ->where('n.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findTopByViews(int $limit = 10): array
    {
        return $this->createQueryBuilder('n')
            ->leftJoin('n.categories', 'c')
            ->addSelect('c')
            ->orderBy('n.viewCount', 'DESC')
            ->addOrderBy('n.insertDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('n')
            ->leftJoin('n.categories', 'c')
            ->addSelect('c')
            ->orderBy('n.insertDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findLatest(int $limit = 10): array
    {
        return $this->createQueryBuilder('n')
            ->leftJoin('n.categories', 'c')
            ->addSelect('c')
            ->orderBy('n.insertDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
 