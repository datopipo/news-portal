<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\NewsRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class WeeklyStatsEmailService
{
    public function __construct(
        private readonly NewsRepository $newsRepository,
        private readonly MailerInterface $mailer,
        private readonly WeeklyStatsTemplateService $templateService,
        private readonly string $statsEmail,
        private readonly string $fromEmail
    ) {
    }

    public function sendWeeklyStats(): bool
    {
        try {
            $topNews = $this->newsRepository->findTopByViews(10);
            
            if (empty($topNews)) {
                return false; // No data to send
            }

            $emailContent = $this->templateService->generateHtmlTemplate($topNews);
            
            $email = (new Email())
                ->from($this->fromEmail)
                ->to($this->statsEmail)
                ->subject('Weekly News Statistics - Top 10 Articles')
                ->html($emailContent);

            $this->mailer->send($email);
            
            return true;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to send weekly statistics: ' . $e->getMessage(), 0, $e);
        }
    }
} 