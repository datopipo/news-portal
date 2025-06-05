<?php

namespace App\Service;

use App\Repository\CategoryRepository;
use App\Repository\NewsRepository;

class HomePageService
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly NewsRepository $newsRepository
    ) {
    }

    /**
     * Get categories with their recent news
     */
    public function getCategoriesWithNews(): array
    {
        $categories = $this->categoryRepository->findAllWithRecentNews();
        $result = [];

        foreach ($categories as $category) {
            $result[] = [
                'category' => $category,
                'news' => $this->newsRepository->findRecentByCategory($category, 5)
            ];
        }

        return $result;
    }

    /**
     * Get recent news for homepage
     */
    public function getRecentNews(): array
    {
        return $this->newsRepository->findRecentNews(5);
    }

    /**
     * Get top viewed news
     */
    public function getTopViewedNews(): array
    {
        return $this->newsRepository->findTopByViews(5);
    }
} 