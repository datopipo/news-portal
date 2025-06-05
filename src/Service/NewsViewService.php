<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\News;
use Doctrine\ORM\EntityManagerInterface;

class NewsViewService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function incrementViewCount(News $news): void
    {
        $news->incrementViewCount();
        $this->entityManager->flush();
    }

    public function getTopViewedNews(int $limit = 10): array
    {
        return $this->entityManager->getRepository(News::class)
            ->findTopByViews($limit);
    }
} 