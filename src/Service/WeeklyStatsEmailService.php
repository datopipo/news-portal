<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\News;
use App\Repository\NewsRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class WeeklyStatsEmailService
{
    public function __construct(
        private readonly NewsRepository $newsRepository,
        private readonly MailerInterface $mailer,
        private readonly string $statsEmail,
        private readonly string $fromEmail
    ) {
    }

    public function sendWeeklyStats(): bool
    {
        $topNews = $this->newsRepository->findTopByViews(10);
        
        if (empty($topNews)) {
            return false;
        }

        $htmlContent = $this->generateHtmlContent($topNews);
        
        $email = (new Email())
            ->from($this->fromEmail)
            ->to($this->statsEmail)
            ->subject('Weekly News Statistics - Top 10 Articles')
            ->html($htmlContent);

        $this->mailer->send($email);
        
        return true;
    }

    /**
     * @param News[] $topNews
     */
    private function generateHtmlContent(array $topNews): string
    {
        $html = '<h2>Weekly News Statistics</h2>';
        $html .= '<p>Top 10 most viewed news articles:</p>';
        $html .= '<table border="1" cellpadding="10" style="border-collapse: collapse;">';
        $html .= '<tr><th>Rank</th><th>Title</th><th>Views</th><th>Comments</th></tr>';
        
        foreach ($topNews as $index => $news) {
            $rank = $index + 1;
            $html .= sprintf(
                '<tr><td>%d</td><td>%s</td><td>%d</td><td>%d</td></tr>',
                $rank,
                htmlspecialchars($news->getTitle()),
                $news->getViewCount(),
                $news->getComments()->count()
            );
        }
        
        $html .= '</table>';
        $html .= '<p><small>Generated on ' . date('Y-m-d H:i:s') . '</small></p>';
        
        return $html;
    }
} 