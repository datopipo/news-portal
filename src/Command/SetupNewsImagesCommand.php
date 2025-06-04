<?php

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
    description: 'Sets up images for news articles',
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
        $this->originalDir = $this->uploadDir . '/original';
        $this->filesystem = new Filesystem();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Create directories if they don't exist
        if (!$this->filesystem->exists($this->uploadDir)) {
            $this->filesystem->mkdir($this->uploadDir);
        }
        if (!$this->filesystem->exists($this->originalDir)) {
            $this->filesystem->mkdir($this->originalDir);
        }

        // Map of fixture image names to original images
        $imageMap = [
            'ai-breakthrough.jpg' => 'majorana1-1260x708-v2-1024x575-684034d960c9e.jpg',
            'world-cup.jpg' => '2025-Oscars-1030x580-68407d2a59259.jpg',
            'climate-summit.jpg' => '5ZD3FGEX2JJU7FZSN2FDIIXFQ4-68407c60b12a6.jpg',
            'tech-product.jpg' => 'images-68407cc6004a8.jpg',
            'economy.jpg' => 'stock-market-data-with-uptrend-vector-68407cab284d6.jpg'
        ];

        foreach ($imageMap as $targetName => $sourceName) {
            $sourcePath = $this->originalDir . '/' . $sourceName;
            $targetPath = $this->uploadDir . '/' . $targetName;

            if ($this->filesystem->exists($sourcePath)) {
                $this->filesystem->copy($sourcePath, $targetPath, true);
                $io->success(sprintf('Copied %s to %s', $sourceName, $targetName));
            } else {
                $io->error(sprintf('Source image %s not found in %s', $sourceName, $this->originalDir));
                $io->note('Please make sure all original images are placed in the original directory.');
            }
        }

        $io->success('News images setup completed successfully!');

        return Command::SUCCESS;
    }
} 