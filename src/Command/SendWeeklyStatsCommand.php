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
    description: 'Sends weekly Top 10 news statistics via email'
)]
class SendWeeklyStatsCommand extends Command
{
    public function __construct(
        private readonly WeeklyStatsEmailService $weeklyStatsService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('📊 Weekly Stats Sender');

        try {
            $success = $this->weeklyStatsService->sendWeeklyStats();

            if ($success) {
                $io->success('Weekly statistics email sent successfully!');
                return Command::SUCCESS;
            } else {
                $io->warning('No news found to send in statistics.');
                return Command::SUCCESS;
            }
        } catch (\Exception $e) {
            $io->error('Failed to send weekly statistics: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
} 