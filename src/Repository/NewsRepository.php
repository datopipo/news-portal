<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\News;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<News>
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    public function findRecentByCategory(Category $category, int $limit = 3): array
    {
        return $this->createBaseQuery()
            ->leftJoin('n.categories', 'c')
            ->where('c.id = :categoryId')
            ->setParameter('categoryId', $category->getId())
            ->orderBy('n.insertDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findTopByViews(int $limit = 10): array
    {
        return $this->createBaseQuery()
            ->orderBy('n.viewCount', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findLatest(int $limit = 10): array
    {
        return $this->createBaseQuery()
            ->orderBy('n.insertDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findAllPaginated(int $page = 1, int $limit = 10): Paginator
    {
        $query = $this->createBaseQuery()
            ->orderBy('n.insertDate', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query);
    }

    public function findById(int $id): ?News
    {
        return $this->createBaseQuery()
            ->where('n.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function createBaseQuery()
    {
        return $this->createQueryBuilder('n')
            ->addSelect('categories', 'comments')
            ->leftJoin('n.categories', 'categories')
            ->leftJoin('n.comments', 'comments');
    }
}
 