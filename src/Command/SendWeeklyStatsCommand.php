<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\NewsRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsCommand(
    name: 'app:send-weekly-stats',
    description: 'Send weekly top 10 news statistics to configured email',
)]
class SendWeeklyStatsCommand extends Command
{
    public function __construct(
        private readonly NewsRepository $newsRepository,
        private readonly string $statsEmail
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            // Get top 10 news by views
            $topNews = $this->newsRepository->findTopByViews(10);

            if (empty($topNews)) {
                $io->warning('No news articles found to send statistics.');
                return Command::SUCCESS;
            }

            // Prepare email content
            $emailContent = $this->generateEmailContent($topNews);

            // Get mailer service lazily
            $mailer = $this->getApplication()->getKernel()->getContainer()->get('mailer.mailer');

            // Send email
            $email = (new Email())
                ->from('noreply@newsportal.com')
                ->to($this->statsEmail)
                ->subject('Weekly News Statistics - Top 10 Articles')
                ->html($emailContent);

            $mailer->send($email);

            $io->success(sprintf('Weekly statistics sent successfully to %s', $this->statsEmail));
        } catch (\Exception $e) {
            $io->error('Failed to send weekly statistics: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function generateEmailContent(array $topNews): string
    {
        $html = '<h2>📊 Weekly News Statistics</h2>';
        $html .= '<p>Here are the top 10 most viewed news articles from the past week:</p>';
        $html .= '<table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%;">';
        $html .= '<thead><tr style="background-color: #f8f9fa;">';
        $html .= '<th>Rank</th><th>Title</th><th>Views</th><th>Comments</th><th>Categories</th>';
        $html .= '</tr></thead>';
        $html .= '<tbody>';

        foreach ($topNews as $index => $news) {
            $rank = $index + 1;
            $categories = [];
            foreach ($news->getCategories() as $category) {
                $categories[] = $category->getTitle();
            }
            $categoriesStr = implode(', ', $categories);

            $html .= sprintf(
                '<tr><td>%d</td><td>%s</td><td>%d</td><td>%d</td><td>%s</td></tr>',
                $rank,
                htmlspecialchars($news->getTitle()),
                $news->getViewCount(),
                $news->getComments()->count(),
                htmlspecialchars($categoriesStr)
            );
        }

        $html .= '</tbody></table>';
        $html .= '<p><small>Generated on ' . date('Y-m-d H:i:s') . '</small></p>';

        return $html;
    }
}
