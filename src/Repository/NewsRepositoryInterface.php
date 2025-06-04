<?php

namespace App\Repository;

use App\Entity\News;
use App\Entity\Category;
use Doctrine\ORM\Tools\Pagination\Paginator;

interface NewsRepositoryInterface
{
    public function findRecentByCategory(Category $category, int $limit = 3): array;
    public function findPaginatedByCategory(Category $category, int $page = 1, int $limit = 10): Paginator;
    public function findWithDetails(int $id): ?News;
    public function findTopByViews(int $limit = 10): array;
    public function findAll(): array;
    public function findLatest(int $limit = 10): array;
} 