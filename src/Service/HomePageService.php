<?php

declare(strict_types=1);

namespace App\Service;

use App\Constants\NewsConstants;
use App\Repository\CategoryRepository;
use App\Repository\NewsRepository;

class HomePageService
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly NewsRepository $newsRepository,
        private readonly NewsConstants $newsConstants
    ) {
    }

    public function getCategoriesWithNews(): array
    {
        $categories = $this->categoryRepository->findAllWithRecentNews();
        $result = [];

        foreach ($categories as $category) {
            $result[] = [
                'category' => $category,
                'news' => $this->newsRepository->findRecentByCategory(
                    $category,
                    $this->newsConstants->getRecentNewsLimit()
                )
            ];
        }

        return $result;
    }

    public function getTopNews(): array
    {
        return $this->newsRepository->findTopByViews($this->newsConstants->getTopNewsLimit());
    }
} 