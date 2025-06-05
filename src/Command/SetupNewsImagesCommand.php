<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'app:setup-news-images',
    description: 'Set up news images by copying them from original directory to uploads directory',
)]
class SetupNewsImagesCommand extends Command
{
    private string $uploadDir;
    private string $originalDir;
    private Filesystem $filesystem;

    public function __construct(KernelInterface $kernel)
    {
        parent::__construct();
        $this->uploadDir = $kernel->getProjectDir() . '/public/uploads/pictures';
        $this->originalDir = $kernel->getProjectDir() . '/public/uploads/pictures';
        $this->filesystem = new Filesystem();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Create directories if they don't exist
        $this->filesystem->mkdir([$this->uploadDir, $this->originalDir]);

        // Map of fixture images to original images
        $imageMap = [
            'ai-breakthrough.jpg' => 'majorana1-1260x708-v2-1024x575-684034d960c9e.jpg',
            'world-cup.jpg' => '5ZD3FGEX2JJU7FZSN2FDIIXFQ4-68407c60b12a6.jpg',
            'climate-summit.jpg' => 'images-68407cc6004a8.jpg',
            'economy.jpg' => 'stock-market-data-with-uptrend-vector-68407cab284d6.jpg',
        ];

        foreach ($imageMap as $fixtureImage => $originalImage) {
            $sourcePath = $this->originalDir . '/' . $originalImage;
            $targetPath = $this->uploadDir . '/' . $fixtureImage;

            if (!$this->filesystem->exists($sourcePath)) {
                $io->error(sprintf('Source image not found: %s', $sourcePath));
                continue;
            }

            try {
                $this->filesystem->copy($sourcePath, $targetPath);
                $io->success(sprintf('Copied %s to %s', $originalImage, $fixtureImage));
            } catch (\Exception $e) {
                $io->error(sprintf('Failed to copy %s: %s', $originalImage, $e->getMessage()));
            }
        }

        return Command::SUCCESS;
    }
} 