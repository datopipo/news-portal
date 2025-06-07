<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\News;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<News>
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    /**
     * @return News[]
     */
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

    /**
     * @return News[]
     */
    public function findTopByViews(int $limit = 10): array
    {
        return $this->createQueryBuilder('n')
            ->orderBy('n.viewCount', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function createByCategoryQuery(Category $category): QueryBuilder
    {
        return $this->createQueryBuilder('n')
            ->join('n.categories', 'c')
            ->where('c.id = :categoryId')
            ->setParameter('categoryId', $category->getId())
            ->orderBy('n.insertDate', 'DESC');
    }

    /**
     * Get paginated news by category with efficient database-level pagination
     * @return array{news: News[], totalCount: int, totalPages: int, currentPage: int, hasNextPage: bool, hasPrevPage: bool}
     */
    public function findByCategoryPaginated(Category $category, int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;
        
        $qb = $this->createQueryBuilder('n')
            ->join('n.categories', 'c')
            ->where('c.id = :categoryId')
            ->setParameter('categoryId', $category->getId())
            ->orderBy('n.insertDate', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $news = $qb->getQuery()->getResult();

        // Get total count for pagination
        $countQb = $this->createQueryBuilder('n')
            ->select('COUNT(DISTINCT n.id)')
            ->join('n.categories', 'c')
            ->where('c.id = :categoryId')
            ->setParameter('categoryId', $category->getId());

        $totalCount = (int) $countQb->getQuery()->getSingleScalarResult();

        return [
            'news' => $news,
            'totalCount' => $totalCount,
            'totalPages' => (int) ceil($totalCount / $limit),
            'currentPage' => $page,
            'hasNextPage' => $page < ceil($totalCount / $limit),
            'hasPrevPage' => $page > 1,
        ];
    }

    public function createAllNewsQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('n')
            ->orderBy('n.insertDate', 'DESC');
    }
}
 