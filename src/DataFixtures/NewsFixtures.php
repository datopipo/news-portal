<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\News;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class NewsFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $categories = $manager->getRepository(Category::class)->findAll();

        if (empty($categories)) {
            return;
        }

        // Ensure uploads directory exists
        $uploadsDir = __DIR__ . '/../../public/uploads/pictures';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0755, true);
        }

        // Generate sample images if they don't exist
        $sampleImages = $this->generateSampleImages($uploadsDir);

        for ($i = 1; $i <= 25; $i++) {
            $news = new News();
            $news->setTitle($this->faker->sentence());
            $news->setShortDescription($this->faker->paragraph(2));
            $news->setContent($this->faker->paragraphs(5, true));
            $news->setInsertDate($this->faker->dateTimeBetween('-6 months'));
            $news->setViewCount($this->faker->numberBetween(0, 1000));

            // Randomly assign an image to 70% of articles
            if (!empty($sampleImages)) {
                $news->setPicture($this->faker->randomElement($sampleImages));
            }

            $categoryCount = $this->faker->numberBetween(1, 3);
            $selectedCategories = $this->faker->randomElements($categories, $categoryCount);

            foreach ($selectedCategories as $category) {
                if ($category instanceof Category) {
                    $news->addCategory($category);
                }
            }

            $manager->persist($news);
        }

        $manager->flush();
    }

    /**
     * @return string[]
     */
    private function generateSampleImages(string $uploadsDir): array
    {
        $images = [];

        // Check for existing images first
        $existingImages = glob($uploadsDir . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        if (!empty($existingImages)) {
            return array_map('basename', $existingImages);
        }

        // Generate placeholder images using simple colored rectangles
        $colors = [
            ['r' => 59, 'g' => 130, 'b' => 246],   // Blue
            ['r' => 16, 'g' => 185, 'b' => 129],   // Green
            ['r' => 245, 'g' => 101, 'b' => 101],  // Red
            ['r' => 168, 'g' => 85, 'b' => 247],   // Purple
            ['r' => 251, 'g' => 146, 'b' => 60],   // Orange
        ];

        for ($i = 0; $i < 5; $i++) {
            $filename = 'sample-' . ($i + 1) . '-' . uniqid() . '.jpg';
            $filepath = $uploadsDir . '/' . $filename;

            if ($this->createPlaceholderImage($filepath, $colors[$i])) {
                $images[] = $filename;
            }
        }

        return $images;
    }

    /**
     * @param array{r: int, g: int, b: int} $color
     */
    private function createPlaceholderImage(string $filepath, array $color): bool
    {
        if (!extension_loaded('gd')) {
            return false;
        }

        $width = 800;
        $height = 600;

        $image = imagecreatetruecolor($width, $height);
        $bgColor = imagecolorallocate($image, $color['r'], $color['g'], $color['b']);
        $textColor = imagecolorallocate($image, 255, 255, 255);

        imagefill($image, 0, 0, $bgColor);

        // Add some text
        $text = 'Sample News Image';
        $fontSize = 5;
        $textWidth = imagefontwidth($fontSize) * strlen($text);
        $textHeight = imagefontheight($fontSize);
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2;

        imagestring($image, $fontSize, (int)$x, (int)$y, $text, $textColor);

        $result = imagejpeg($image, $filepath, 85);
        imagedestroy($image);

        return $result;
    }
}
