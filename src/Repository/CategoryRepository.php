<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    /**
     * Get categories with their latest news (fixes N+1 query problem)
     */
    public function findCategoriesWithLatestNews(int $newsLimit = 3): array
    {
        $categories = $this->findAll();
        $result = [];

        if (empty($categories)) {
            return $result;
        }

        // Single query to get latest news for all categories
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('n, c')
           ->from('App\Entity\News', 'n')
           ->join('n.categories', 'c')
           ->where('c.id IN (:categoryIds)')
           ->setParameter('categoryIds', array_map(fn($cat) => $cat->getId(), $categories))
           ->orderBy('c.id, n.insertDate', 'DESC');

        $allNews = $qb->getQuery()->getResult();

        // Group news by category
        $newsByCategory = [];
        foreach ($allNews as $news) {
            foreach ($news->getCategories() as $category) {
                if (!isset($newsByCategory[$category->getId()])) {
                    $newsByCategory[$category->getId()] = [];
                }
                if (count($newsByCategory[$category->getId()]) < $newsLimit) {
                    $newsByCategory[$category->getId()][] = $news;
                }
            }
        }

        // Build result array
        foreach ($categories as $category) {
            $categoryNews = $newsByCategory[$category->getId()] ?? [];
            if (!empty($categoryNews)) {
                $result[] = [
                    'category' => $category,
                    'news' => $categoryNews,
                ];
            }
        }

        return $result;
    }
}
