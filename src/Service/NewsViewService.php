<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\News;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;

class NewsViewService
{
    public function __construct(
        private readonly NewsRepository $newsRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function incrementViewCount(News $news): void
    {
        $news->incrementViewCount();
        $this->entityManager->flush();
    }
} 