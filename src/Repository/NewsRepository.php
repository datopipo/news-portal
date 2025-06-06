<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\News;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    public function findLatestByCategory(Category $category, int $limit = 3): array
    {
        return $this->createQueryBuilder('n')
            ->join('n.categories', 'c')
            ->where('c.id = :categoryId')
            ->setParameter('categoryId', $category->getId())
            ->orderBy('n.insertDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findTopByViews(int $limit = 10): array
    {
        return $this->createQueryBuilder('n')
            ->orderBy('n.viewCount', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function createByCategoryQuery(Category $category)
    {
        return $this->createQueryBuilder('n')
            ->join('n.categories', 'c')
            ->where('c.id = :categoryId')
            ->setParameter('categoryId', $category->getId())
            ->orderBy('n.insertDate', 'DESC');
    }

    public function createAllNewsQuery()
    {
        return $this->createQueryBuilder('n')
            ->orderBy('n.insertDate', 'DESC');
    }
}
 