<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\WeeklyStatsEmailService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:send-weekly-stats',
    description: 'Send weekly top 10 news statistics to configured email',
)]
class SendWeeklyStatsCommand extends Command
{
    public function __construct(
        private readonly WeeklyStatsEmailService $weeklyStatsEmailService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $success = $this->weeklyStatsEmailService->sendWeeklyStats();

            if (!$success) {
                $io->warning('No news articles found to send statistics.');
                return Command::SUCCESS;
            }

            $io->success('Weekly statistics sent successfully!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Failed to send weekly statistics: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
