<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\News;

class WeeklyStatsTemplateService
{
    public function generateHtmlTemplate(array $topNews): string
    {
        $html = $this->getHeader();
        $html .= $this->getTableStart();
        $html .= $this->generateTableRows($topNews);
        $html .= $this->getTableEnd();
        $html .= $this->getFooter();

        return $html;
    }

    private function getHeader(): string
    {
        return '<h2>📊 Weekly News Statistics</h2>' .
               '<p>Here are the top 10 most viewed news articles from the past week:</p>';
    }

    private function getTableStart(): string
    {
        return '<table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%;">' .
               '<thead><tr style="background-color: #f8f9fa;">' .
               '<th>Rank</th><th>Title</th><th>Views</th><th>Comments</th><th>Categories</th>' .
               '</tr></thead><tbody>';
    }

    private function generateTableRows(array $topNews): string
    {
        $rows = '';
        foreach ($topNews as $index => $news) {
            $rows .= $this->generateTableRow($news, $index + 1);
        }
        return $rows;
    }

    private function generateTableRow(News $news, int $rank): string
    {
        $categories = [];
        foreach ($news->getCategories() as $category) {
            $categories[] = $category->getTitle();
        }
        $categoriesStr = implode(', ', $categories);

        return sprintf(
            '<tr><td>%d</td><td>%s</td><td>%d</td><td>%d</td><td>%s</td></tr>',
            $rank,
            htmlspecialchars($news->getTitle()),
            $news->getViewCount(),
            $news->getComments()->count(),
            htmlspecialchars($categoriesStr)
        );
    }

    private function getTableEnd(): string
    {
        return '</tbody></table>';
    }

    private function getFooter(): string
    {
        return '<p><small>Generated on ' . date('Y-m-d H:i:s') . '</small></p>';
    }
} 