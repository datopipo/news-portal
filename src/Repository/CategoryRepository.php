<?php

declare(strict_types=1);

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

    private function createBaseQueryBuilder(): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->select('c.id', 'c.title')
            ->leftJoin('c.news', 'n')
            ->addSelect('n');
    }

    public function findAllWithRecentNews(): array
    {
        return $this->createBaseQueryBuilder()
            ->orderBy('c.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByIdWithNews(int $id): ?Category
    {
        return $this->createBaseQueryBuilder()
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->orderBy('n.insertDate', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
