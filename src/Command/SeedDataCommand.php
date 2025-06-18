<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'app:seed-data',
    description: 'Seeds the database with fake data for development'
)]
class SeedDataCommand extends Command
{
    protected function configure(): void
    {
        $this->addOption(
            'clear',
            'c',
            InputOption::VALUE_NONE,
            'Clear existing data before seeding'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('🌱 News Portal Data Seeder');

        if ($input->getOption('clear')) {
            $io->section('Clearing existing data...');
            
            // Remove SQLite database file directly (cleaner approach)
            $dbFile = __DIR__ . '/../../var/data_dev.db';
            if (file_exists($dbFile)) {
                unlink($dbFile);
            }
            
            // Recreate database and schema quietly
            $process = new Process(['php', 'bin/console', 'doctrine:database:create']);
            $process->run();
            
            $process = new Process(['php', 'bin/console', 'doctrine:schema:create', '--quiet']);
            $process->run();
            
            $io->success('Database cleared and recreated');
        }

        $io->section('Loading fixtures...');

        // Load fixtures
        $process = new Process(['php', 'bin/console', 'doctrine:fixtures:load', '--no-interaction']);
        $process->run();

        if ($process->isSuccessful()) {
            $io->success('Fixtures loaded successfully!');
            
            $io->section('📈 Generated Data Summary:');
            $io->listing([
                '10 Categories (Technology, Sports, Business, etc.)',
                '50 News Articles with realistic content and images',
                '200 Comments from various users',
                'Random view counts, dates, and image assignments',
                'Articles are properly categorized',
                'Comments are distributed across articles'
            ]);

            $io->note([
                'You can now:',
                '• Visit the public site to see articles',
                '• Login to admin panel with: admin / admin123',
                '• Create, edit, and manage content'
            ]);

            return Command::SUCCESS;
        } else {
            $io->error('Failed to load fixtures: ' . $process->getErrorOutput());
            return Command::FAILURE;
        }
    }
} 