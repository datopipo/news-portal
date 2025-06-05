<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\News;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Filesystem\Filesystem;

class NewsFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;
    private array $imageUrls = [
        'https://picsum.photos/800/400',
        'https://picsum.photos/800/401',
        'https://picsum.photos/800/402',
        'https://picsum.photos/800/403',
        'https://picsum.photos/800/404',
    ];

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $filesystem = new Filesystem();
        $uploadDir = 'public/uploads/fixtures';

        // Create upload directory if it doesn't exist
        if (!$filesystem->exists($uploadDir)) {
            $filesystem->mkdir($uploadDir);
        }

        // Download and save sample images
        $savedImages = [];
        foreach ($this->imageUrls as $index => $url) {
            $imageContent = file_get_contents($url);
            if ($imageContent !== false) {
                $filename = sprintf('news_%d.jpg', $index + 1);
                $filepath = $uploadDir . '/' . $filename;
                file_put_contents($filepath, $imageContent);
                $savedImages[] = $filename;
            }
        }

        // Create news articles
        for ($i = 0; $i < 50; $i++) {
            $news = new News();
            $news->setTitle($this->faker->sentence(3, true));
            $news->setShortDescription($this->faker->paragraph(2));
            $news->setContent($this->faker->paragraphs(3, true));
            $news->setInsertDate($this->faker->dateTimeBetween('-1 year', 'now'));
            $news->setViewCount($this->faker->numberBetween(0, 1000));

            // Set a random image from our saved images
            if (!empty($savedImages)) {
                $randomImage = $savedImages[array_rand($savedImages)];
                $news->setPicture($randomImage);
            }

            // Add 1-3 random categories
            $categories = $this->getRandomCategories();
            foreach ($categories as $category) {
                $news->addCategory($category);
            }

            $manager->persist($news);
            $this->addReference('news_' . $i, $news);
        }

        $manager->flush();
    }

    private function getRandomCategories(): array
    {
        $categories = [];
        $numCategories = rand(1, 3);
        
        for ($i = 0; $i < $numCategories; $i++) {
            $categoryRef = 'category_' . rand(0, 4); // We have 5 categories
            if ($this->hasReference($categoryRef)) {
                $categories[] = $this->getReference($categoryRef);
            }
        }

        return array_unique($categories);
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
} 