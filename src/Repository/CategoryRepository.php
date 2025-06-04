<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findAllWithRecentNews(): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.news', 'n')
            ->addSelect('n')
            ->orderBy('c.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByIdWithNews(int $id): ?Category
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.news', 'n')
            ->addSelect('n')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->orderBy('n.insertDate', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
